<?php
namespace App\Cores;

use App\Models\BasicModels\t_kary_salary;
use App\Models\BasicModels\m_standart_gaji;
use App\Models\BasicModels\m_standart_gaji_det;
use App\Models\BasicModels\m_general;
use App\Models\BasicModels\t_cuti;
use App\Models\BasicModels\t_potongan;
use App\Models\BasicModels\m_kary;
use App\Models\BasicModels\default_users;
use App\Models\BasicModels\presensi_absensi;
use App\Models\BasicModels\t_lembur;
use App\Models\CustomModels\m_general as grade;
use App\Models\BasicModels\m_libur_nasional;
use Carbon\Carbon;

class PerhitunganGaji
{
    public function factorSalary($standart_gaji, $kary = null, $periode = null)
    {
        $firstDayOfMonth = "$periode-01";
        $date = new \DateTime($firstDayOfMonth);

        // Set the date to the last day of the month
        $date->modify('last day of this month');

        // Get the last day as a string in 'Y-m-d' format
        $lastDayOfMonth = $date->format('Y-m-d');

        $defaultColumns = [
            [
                'name'  => 'gaji_pokok',
                'type'  => 'gaji_pokok_periode'
            ],
            [
                'name'  => 'uang_saku',
                'type'  => 'uang_saku_periode'
            ],
            [
                'name'  => 'tunjangan_posisi',
                'type'  => 'tunjangan_posisi_periode'
            ],
            [
                'name'  => 'tunjangan_kemahalan_id',
                'table' => 'm_tunj_kemahalan',
                'type'  => 'tunjangan_kemahalan_periode'
            ],
            [
                'name'  => 'uang_makan',
                'type'  => 'uang_makan'
            ],
            [
                'name'  => 'tunjangan_tetap',
                'type'  => 'tunjangan_tetap'
            ]
        ];

        foreach($defaultColumns as $idx => $key ){
            $defaultColumns[$idx]['label']  = getCore('Helper')->snakeCaseToCapitalize($key['name']);
            $defaultColumns[$idx]['factor'] = '+'; // pasti tambah karena default kolom tunjangan

            // dynamic 
            if(@!$key['table']) {
                    $defaultColumns[$idx]['value']  = (float)$standart_gaji[$key['name']] ?? 0;
                    $defaultColumns[$idx]['type']   = $standart_gaji[$key['type']];
              
            }else{
                if($key['table'] == 'm_tunj_kemahalan'){
                    $defaultColumns[$idx]['label']  = "Tunjangan Kemahalan";
                    $defaultColumns[$idx]['value']  = (float)\DB::table($key['table'])->where('id', $standart_gaji[$key['name']])->pluck('besaran')->first() ?? 0;
                    $defaultColumns[$idx]['type']   = $standart_gaji[$key['type']];
                }
            }
            $defaultColumns[$idx]['can_adjust'] = 1;
            if ($defaultColumns[$idx]['value'] == 0) {
                unset($defaultColumns[$idx]);
            }
        }

        // faktor lain dari table m_standart_gaji_det
        $standart_gaji_det = m_standart_gaji_det::where('m_standart_gaji_id', $standart_gaji->id ?? 0)->get();
        foreach($standart_gaji_det as $d){
            $defaultColumns[] = [
                'label'    => $d->komponen,
                'factor'   => $d->faktor,
                'value'    => $d->nilai,
                'type'     => $d->periode,
                'can_adjust' => 1
                
            ];
        }

        if(!$kary) return $defaultColumns;

        // tunjangan masa kerja
        $general_masa_kerja = m_general::where('group', 'TUNJANGAN MASA KERJA')->where('key','01')->pluck('value')->first();
        if($general_masa_kerja && $kary->tgl_masuk) {
            $general_masa_kerja = (float)$general_masa_kerja;
            $date_from = \DateTime::createFromFormat('Y-m-d', $kary->tgl_masuk);
            $date_to = \DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
            $interval = @$date_from->diff($date_to) ?? 0;
            $jumlah_tahun = floor($interval->days / 365);

            $total_tunjangan = $general_masa_kerja * pow(2, $jumlah_tahun);
            if($total_tunjangan > 0){
                $defaultColumns[] = [
                    'label'    => "Tunjangan Masa Kerja ($jumlah_tahun)",
                    'factor'   => '+',
                    'value'    => $total_tunjangan,
                    'type'     => 'Bulanan',
                    'can_adjust' => 1
                ];
            }
        }

        // faktor lain :Potongan
        $t_potongan = t_potongan::where('m_kary_id', @$kary->id ?? 0)->orWhere('is_all_kary', true)->whereRaw("date_from >= ? and date_to <= ?",[$firstDayOfMonth,$lastDayOfMonth])->get();
        if(count($t_potongan)) {
            foreach($t_potongan as $d){
                $nilai_netto = ((float)$d->nilai * (float)$d->percentage)/100;
                $defaultColumns[] = [
                    'label'    => "Potongan - $d->nomor",
                    'factor'   => '-',
                    'value'    => $nilai_netto,
                    'type'     => 'Bulanan',
                    'can_adjust' => 1,
                    't_potongan_id' => $d->id
                ];
            }
        }

        // check kehadiran karyawan
        $attendance = \DB::select("select public.employee_attendance(?,?)",[$firstDayOfMonth,@$kary->id ?? 0]);
        if(count($attendance)){
            $att = $attendance[0]->employee_attendance;
            $att = json_decode($att);

            $jml_hari_sebulan = $att->work_days_in_month;
            $tidak_masuk_kerja = $att->work_not_present;
            $cuti_reguler = @$att->cuti_reguler;
            $sisa_cuti_reguler = @$att->sisa_cuti_reguler;
            $sisa_cuti_masa_kerja = @$att->sisa_cuti_masa_kerja;
            $potongan_cuti = @$att->potongan_cuti;
            $sisa_cuti = @$sisa_cuti_reguler+$sisa_cuti_masa_kerja;

            // gaji perhari
            $gaji_per_hari = 0;
            $makan_per_hari = 0;
            $gaji_pokok = @$standart_gaji['gaji_pokok'] ?? 0;
            if($gaji_pokok){
                $gaji_per_hari = $gaji_pokok / $jml_hari_sebulan;
            }
            $standart_gaji = @$standart_gaji['uang_makan'];
            if($standart_gaji){
                $makan_per_hari = $standart_gaji / $jml_hari_sebulan;
            }

            // potongan tidak hadir dan jatah semua cuti sudah habis
            if(($sisa_cuti-$tidak_masuk_kerja) < 0){
                $sisa_cuti -= $tidak_masuk_kerja;
                $value = $gaji_per_hari*$tidak_masuk_kerja;
                $defaultColumns[] = [
                    'label'    => "Potongan Tidak Masuk Kerja ($tidak_masuk_kerja)",
                    'factor'   => '-',
                    'value'    => $value,
                    'type'     => 'Bulanan',
                    'can_adjust' => 1
                ];
            }
            

            // ketika jatah cuti reguler masih ada -> potong uang makan
            if($sisa_cuti > 0 && $potongan_cuti > 0){
                $value = $makan_per_hari*$potongan_cuti;
                $defaultColumns[] = [
                    'label'    => "Potongan Uang Makan Cuti ($potongan_cuti)",
                    'factor'   => '-',
                    'value'    => $value,
                    'type'     => 'Bulanan',
                    'can_adjust' => 1
                ];
            }

            // ketika jatah cuti reguler tidak ada -> potong gaji 
            if($sisa_cuti <= 0 && $potongan_cuti > 0){
                $value = $gaji_per_hari*$potongan_cuti;
                $defaultColumns[] = [
                    'label'    => "Potongan Cuti ($potongan_cuti)",
                    'factor'   => '-',
                    'value'    => $value,
                    'type'     => 'Bulanan',
                    'can_adjust' => 1
                ];
            }

          
        }
        

        // faktor lain :Cuti
        $t_cuti = t_cuti::where('m_kary_id', @$kary->id ?? 0)->whereRaw("status = 'APPROVED' and date_from >= ? and date_to <= ?",[$firstDayOfMonth,$lastDayOfMonth])->get();
        if(count($t_cuti)) {
            $sisa_cuti = m_kary::where('id', @$kary->id ?? 0)->pluck('cuti_sisa_reguler')->first() ?? 0;
            $count = t_cuti::where('m_kary_id', @$kary->id ?? 0)->whereRaw("attachment is not null and status = 'APPROVED' and date_from >= ? and date_to <= ?",[$firstDayOfMonth,$lastDayOfMonth])->count();
            foreach($t_cuti as $d){
                $date_from = \DateTime::createFromFormat('Y-m-d', $d->date_from);
                $date_to = \DateTime::createFromFormat('Y-m-d', $d->date_to);
                $interval = @$date_from->diff($date_to) ?? 0;
                $jumlah_hari = $interval->days;
                if($sisa_cuti > 0){
                    $jumlah_hari = $jumlah_hari - $sisa_cuti;
                }

                $gaji_per_hari = 0;
                $makan_per_hari = 0;
                $gaji_pokok = @$standart_gaji['gaji_pokok'] ?? 0;
                if($gaji_pokok){
                    $gaji_per_hari = $gaji_pokok / (int)date('t');
                }
                $makan_per_hari = @$standart_gaji['uang_makan'] / (int)date('t');
                $potongan_cuti = $gaji_per_hari*$jumlah_hari;
                $potongan_makan = $makan_per_hari * $jumlah_hari;

                if($count > 7){
                    $defaultColumns[] = [
                        'label'    => "Potongan Cuti ($jumlah_hari)",
                        'factor'   => '-',
                        'value'    => $potongan_cuti,
                        'type'     => 'Bulanan',
                        'can_adjust' => 1,
                        't_cuti_id' => $d->id
                    ];
                    
                }else{
                    $defaultColumns[] = [
                        'label'    => "Potongan Cuti (Uang Makan) ($jumlah_hari)",
                        'factor'   => '-',
                        'value'    => $potongan_cuti,
                        'type'     => 'Bulanan',
                        'can_adjust' => 1,
                        't_cuti_id' => $d->id
                    ];
                }
                
            }
        }

        return $defaultColumns;
    }

    public function factorSalaryManual($kary, $date_from, $date_to, $isTunjangan, $kary_grade)
    {
        //logic kuontol
        $grade = grade::where('id',$kary_grade)->with('treatments')->first();
        $treatments = collect($grade['treatments']);

        $notCheckIn = $treatments->where('not_checkin',true);
        $potonganGrade = $treatments->where('keterangan','like','%potongan%');
        $needOvertime = $treatments->where('need_overtime', true);
        $fullweek75 = $treatments->where('is_7_5',true)->where('full_week',true);
        $sundayCheckIn = $treatments->Where('day', "0")->where('full_week',false)->first();
        $sundayCheckInFullWeek = $treatments->Where('day', "0")->where('full_week',true)->first();

        $pluckKeterangan = $needOvertime->pluck('keterangan')->toArray();

        $faktor = $treatments->where('is_month', true)
                ->pluck('factor', 'keterangan')
                ->mapWithKeys(function ($faktor, $keterangan) {
                        return [strtolower($keterangan) => $faktor];
                })
                ->toArray();
        
        $bulanan = array_keys($faktor);
        
        $defaultColumns = [];

        if(!$kary) return $defaultColumns;
        // $setQuery =  m_general::where('group', 'SET-TUNJANGAN')->get();
        // $setName = $setQuery->firstWhere('key', 'NAMA')->value;

        // $name = strtolower(@$setName) ?? "uang bulanan";

        $t_kary_salary = t_kary_salary::selectRaw("m_kary_id, t_kary_salary.id, t_kary_salary.total, d.*")
                            ->join('t_kary_salary_det as d','d.t_kary_salary_id','t_kary_salary.id')
                            ->where('m_kary_id', @$kary->id ?? 0)
                            ->where('t_kary_salary.is_active', true)
                            ->whereNotIn(\DB::raw('LOWER(d.keterangan)'),  $bulanan)
                            ->whereRaw("d.keterangan NOT ILIKE '%lembur%'")
                            ->whereRaw("LOWER(d.keterangan) NOT ILIKE '%potongan%'");
                            // ->get();               
        if($needOvertime){
            $t_kary_salary = $t_kary_salary->whereNotIn(\DB::raw('LOWER(d.keterangan)'),  $pluckKeterangan);
        }

        // Tanggal awal dan akhir
        // $dateFrom = new \DateTime($date_from);
        // $dateTo = new \DateTime($date_to);

        // // Hitung selisihnya
        // $interval = $dateFrom->diff($dateTo);

        // // Ambil total hari
        // $totalDays = $interval->days;

        //tunjangan
        if($isTunjangan){

            $getTunjangan = t_kary_salary::selectRaw("m_kary_id, t_kary_salary.id, t_kary_salary.total, d.*")
                            ->join('t_kary_salary_det as d','d.t_kary_salary_id','t_kary_salary.id')
                            ->where('m_kary_id', @$kary->id ?? 0)
                            ->whereIn(\DB::raw('LOWER(d.keterangan)'),  $bulanan)
                            ->where('t_kary_salary.is_active', true)
                            ->get();
            
            foreach ($getTunjangan as $single){

                $keteranganLower = strtolower($single['keterangan']);
                $factor = $faktor[$keteranganLower] ?? '+';

                $defaultColumns[] = [
                    'label' => $single['keterangan'] . ' (' . $factor . ')',
                    'factor' => $factor,
                    'value' => (float) $single['nominal'],
                    'type' => 'HARIAN',
                    'can_adjust' => 1,
                ];
            }
        }

        // $dataResults = $t_kary_salary->get();
        $t_kary_salary = $t_kary_salary->get();

        $gaji_karyawan = @$t_kary_salary[0]->total ?? 0;
        // $gaji_karyawan = @$t_kary_salary[0]->total;

        $gaji_hari = $t_kary_salary->firstWhere('keterangan','Gaji Pokok');
        // trigger_error(json_encode($gaji_hari->nominal));
       
        // faktor lain :Potongan
        $t_potongan = t_potongan::where('m_kary_id', @$kary->id ?? 0)->orWhere('is_all_kary', true)->whereRaw("date_from >= ? and date_to <= ?",[$date_from,$date_to])->get();
        if(count($t_potongan)) {
            foreach($t_potongan as $d){
                $nilai_netto = ((float)$d->nilai * (float)$d->percentage)/100;
                $defaultColumns[] = [
                    'label'    => "Potongan - $d->nomor",
                    'factor'   => '-',
                    'value'    => $nilai_netto,
                    'type'     => 'Bulanan',
                    'can_adjust' => 1,
                    't_potongan_id' => $d->id
                ];
            }
        }

        //check data presensi
        $presensi = $this->salaryPresensi(@$kary->id ?? 0, $date_from, $date_to, $kary_grade);

        if($presensi['sunday_not_checkin'] > 0){
            if($notCheckIn){
                foreach($notCheckIn as $single){
                    $presensi['count'] += $single['value'];
                }
            }
        }

        //cek data lembur
        $getOvertime = t_lembur::where('m_kary_id',@$kary->id)
        ->whereRaw("tanggal >= ? and tanggal <= ?",[$date_from, $date_to])
        ->join('m_kary as mk','m_kary_id','mk.id')
        // ->join('m_posisi as mp','mk.m_posisi_id','mp.id')
        ->select('t_lembur.*')
        ->where('status', 'APPROVED')->get();

        $diff = 0;
        $needOvertimeCount = 0;
        $needOvertimeHour = $needOvertime->pluck('value_overtime')->first();
        if($getOvertime->isNotEmpty()){
                foreach($getOvertime as $single){
                $startOvertime = \Carbon::parse($single['tanggal'].''.$single['jam_mulai']);
                $endOvertime = \Carbon::parse($single['tanggal'].''.$single['jam_selesai']);
                if($single['jam_mulai'] >= $single['jam_selesai']) $endOvertime->addDay();
                $diff += $startOvertime->diffInHours($endOvertime);
                $overtimeTunjangan = $startOvertime->diffInHours($endOvertime);
                if($overtimeTunjangan >= $needOvertimeHour){
                    $needOvertimeCount = $needOvertimeCount + 1; 
                }
            }
        }
        

        if($needOvertimeCount > 0){
            if($needOvertime){
                $getNeedOvertime = t_kary_salary::selectRaw("m_kary_id, t_kary_salary.id, t_kary_salary.total, d.*")
                            ->join('t_kary_salary_det as d','d.t_kary_salary_id','t_kary_salary.id')
                            ->where('m_kary_id', @$kary->id ?? 0)
                            ->whereIn(\DB::raw('LOWER(d.keterangan)'),  $pluckKeterangan)
                            ->where('t_kary_salary.is_active', true)
                            ->get();

                if($getNeedOvertime){
                        foreach($getNeedOvertime as $single){
                        $defaultColumns[] = [
                            'label'    => $single['keterangan'],
                            'factor'   => '+',
                            'value'    => $needOvertimeCount * $single['nominal'],
                            'type'     => 'HARIAN',
                            'can_adjust' => 1
                        ];
                    }
                }
            }
        }

        // check kehadiran karyawan
        $attendance = \DB::select("select public.employee_attendance_harian(?,?,?)",[$date_from, $date_to, @$kary->id ?? 0]);
        if(count($attendance)){
            $att = $attendance[0]->employee_attendance_harian;
            $att = json_decode($att);
            //dd($att);
            $jml_hari_sebulan = $att->work_days_in_month;
            $jml_hari_terpilih = $att->work_day_in_week;
            $tidak_masuk_kerja = $att->work_not_present;
            $cuti_reguler = @$att->cuti_reguler;
            $sisa_cuti_reguler = @$att->sisa_cuti_reguler;
            $sisa_cuti_masa_kerja = @$att->sisa_cuti_masa_kerja;
            $potongan_cuti = @$att->potongan_cuti;
            $sisa_cuti = @$sisa_cuti_reguler+$sisa_cuti_masa_kerja;
            $libur_nasional = @$att->libur_nasional;
            $cuti_satu_hari = @$att->cuti_satu_hari;
            
            $total_gaji_libur_nasional = 0;
            $total_7_5_fullweek = 0;
            $totalSundayCheckin = 0;
            $totalSundayFullweek = 0;

            if((float)$libur_nasional > 0){
                $getLiburNasional = $treatments->where('big_event', true)->first();
                if (isset($presensi['libur_nasional']) && $presensi['libur_nasional'] > 0) {
                    $total_gaji_libur_nasional = $presensi['libur_nasional'] * $getLiburNasional['value'];
                }
            }
            
            if($presensi['saturday_7_5_fullweek'] > 0){
                if ($fullweek75 && !$fullweek75->isEmpty()) {
                    foreach ($fullweek75 as $single) {
                        $total_7_5_fullweek += $single['value'] * $presensi['saturday_7_5_fullweek'];
                    }
                }
            }

            $saturday_bonus = 0;
            if($presensi['saturday_bonus_new'] > 0){
                // if ($fullweek75 && !$fullweek75->isEmpty()) {
                //     foreach ($fullweek75 as $single) {
                //         $total_7_5_fullweek += $single['value'] * $presensi['saturday_7_5_fullweek'];
                //     }
                // }
                $saturday_bonus = $presensi['saturday_bonus_new'];
            }

            $sunday_bonus = 0;
            if($presensi['sunday_bonus_new'] > 0){
                // if ($fullweek75 && !$fullweek75->isEmpty()) {
                //     foreach ($fullweek75 as $single) {
                //         $total_7_5_fullweek += $single['value'] * $presensi['saturday_7_5_fullweek'];
                //     }
                // }
                $sunday_bonus = $presensi['sunday_bonus_new'];
            }

             $holiday_bonus = 0;
            if($presensi['holiday_bonus_new'] > 0){
                // if ($fullweek75 && !$fullweek75->isEmpty()) {
                //     foreach ($fullweek75 as $single) {
                //         $total_7_5_fullweek += $single['value'] * $presensi['saturday_7_5_fullweek'];
                //     }
                // }
                $holiday_bonus = $presensi['holiday_bonus_new'];
            }

            if($presensi['sunday'] > 0){
                if($sundayCheckIn){
                        $totalSundayCheckin += $sundayCheckIn['value'] * $presensi['sunday'];
                }
            }

            if($presensi['countFullWeek'] > 0){
                if($sundayCheckInFullWeek){
                    $totalSundayFullweek += $sundayCheckInFullWeek['value'] * $presensi['sunday'];
                }
            }
            
            // $gaji_pokok_hari = (float)$presensi['count'] + (float)$total_gaji_libur_nasional + $saturday_bonus + $totalSundayCheckin + $totalSundayFullweek + $sunday_bonus;
            // ini sebelumny $gaji_pokok_hari = (float)$presensi['hadir'] + (float)$total_gaji_libur_nasional + $saturday_bonus + $totalSundayCheckin + $sunday_bonus;
            
            // $gaji_pokok_hari = (float)$presensi['count'] + (float)$total_gaji_libur_nasional + $saturday_bonus + $totalSundayCheckin + $totalSundayFullweek;
            // $gaji_pokok_hari = (float)$presensi['count'] + (float)$total_gaji_libur_nasional + $total_7_5_fullweek + $totalSundayCheckin + $totalSundayFullweek;
            // dd($gaji_pokok_hari);
            // $gaji_pokok_hari_1 = $gaji_pokok_hari;
            
            $gaji_pokok_hari = (float)$presensi['hadir'] + $holiday_bonus + $saturday_bonus + $totalSundayCheckin + $sunday_bonus;


            foreach($t_kary_salary as $d){
                if(@$d->nominal){

                    $value = (float)($presensi['hadir'] ?? 0);

                    if($d->keterangan === 'Gaji Pokok'){
                        $t_cuti_approved = t_cuti::where('m_kary_id', $kary->id ?? 0)
                            ->where('status', 'APPROVED')
                            ->whereBetween('date_from', [$date_from, $date_to])
                            ->whereBetween('date_to', [$date_from, $date_to])
                            ->count();

                        if ($t_cuti_approved > 0) {
                            $sisa_cuti_tersedia = ($cuti_satu_hari ?? 0) - $t_cuti_approved;
                            
                            if ($sisa_cuti_tersedia >= 0) {
                                $gaji_pokok_hari += $t_cuti_approved;
                            }
                        }

                        $value = (float)$gaji_pokok_hari;
                    }

                    $defaultColumns[] = [
                        //'label'    => $d->keterangan.' - '. $value .' hari kerja' . ' ' . (float)$presensi['hadir'] . ' ' . (float)$total_gaji_libur_nasional . ' ' . $saturday_bonus . ' ' . $sunday_bonus,
                        'label'      => ($d->keterangan ?? 'Gaji Pokok') . " - $value hari kerja [H:{$presensi['hadir']}, Hol:$holiday_bonus, Sat:$saturday_bonus, Sun:$sunday_bonus (" . ($presensi['debug_sunday'] ?? '') . "), SunChk:$totalSundayCheckin]",
                        'factor'     => '+',
                        'value'      => $value * (float)($d->nominal ?? 0),
                        'type'       => 'HARIAN',
                        'can_adjust' => 1
                    ];
                }
            }

            // gaji perhari
            $gaji_per_hari = $gaji_karyawan;
            // dd($gaji_per_hari);
            $makan_per_hari = 0;
           

            // potongan tidak hadir dan jatah semua cuti sudah habis
            // if(($sisa_cuti-$tidak_masuk_kerja) < 0){
            //     $sisa_cuti -= $tidak_masuk_kerja;
            //     $value = $gaji_per_hari*$tidak_masuk_kerja;
            //     $defaultColumns[] = [
            //         'label'    => "Potongan Tidak Masuk Kerja ($tidak_masuk_kerja)",
            //         'factor'   => '-',
            //         'value'    => $value,
            //         'type'     => 'HARIAN',
            //         'can_adjust' => 1
            //     ];
            // }

            // ketika jatah cuti reguler tidak ada -> potong gaji 
            if($sisa_cuti <= 0 && $potongan_cuti > 0){
                $value = $gaji_per_hari*$potongan_cuti;
                $defaultColumns[] = [
                    'label'    => "Potongan Cuti ($potongan_cuti)",
                    'factor'   => '-',
                    'value'    => $value,
                    'type'     => 'HARIAN',
                    'can_adjust' => 1
                ];
            }
        }

        if($diff != 0){
            $getLembur = t_kary_salary::selectRaw("m_kary_id, t_kary_salary.id, t_kary_salary.total, d.*")
                            ->join('t_kary_salary_det as d','d.t_kary_salary_id','t_kary_salary.id')
                            ->where('m_kary_id', @$kary->id ?? 0)
                            ->whereRaw('LOWER(d.keterangan) = LOWER(?)', ['Uang Lembur'])
                            ->where('t_kary_salary.is_active', true)
                            ->first();
            $totalOvertime = $diff * @$getLembur['nominal'] ?? 0;
            $defaultColumns[] = [
                'label' => 'Uang Lembur - '.(float)$diff.' Jam',
                'factor' => '+',
                'value' => (float)$totalOvertime ?? 0,
                'type' => 'HARIAN',
                'can_adjust' => 1, 
            ];
        }

        
        

        // faktor lain :Cuti
        $t_cuti = t_cuti::where('m_kary_id', @$kary->id ?? 0)->whereRaw("status = 'APPROVED' and date_from >= ? and date_to <= ?",[$date_from,$date_to])->get();
        if(count($t_cuti)) {
            $sisa_cuti = m_kary::where('id', @$kary->id ?? 0)->pluck('cuti_sisa_reguler')->first() ?? 0;
            $count = t_cuti::where('m_kary_id', @$kary->id ?? 0)->whereRaw("attachment is not null and status = 'APPROVED' and date_from >= ? and date_to <= ?",[$date_from,$date_to])->count();
            foreach($t_cuti as $d){
                $date_from = \DateTime::createFromFormat('Y-m-d', $d->date_from);
                $date_to = \DateTime::createFromFormat('Y-m-d', $d->date_to);
                $interval = @$date_from->diff($date_to) ?? 0;
                $jumlah_hari = $interval->days;
                if($sisa_cuti > 0){
                    $jumlah_hari = $jumlah_hari - $sisa_cuti;
                }

                $gaji_per_hari = $gaji_karyawan;
                $makan_per_hari = 0;
                $potongan_cuti = $gaji_per_hari*$jumlah_hari;
                $potongan_makan = $makan_per_hari * $jumlah_hari;

                // if($count > 7){
                    $defaultColumns[] = [
                        'label'    => "Potongan Cuti ($jumlah_hari)",
                        'factor'   => '-',
                        'value'    => $potongan_cuti,
                        'type'     => 'Bulanan',
                        'can_adjust' => 1,
                        't_cuti_id' => $d->id
                    ];
                    
                // }else{
                //     $defaultColumns[] = [
                //         'label'    => "Potongan Cuti (Uang Makan) ($jumlah_hari)",
                //         'factor'   => '-',
                //         'value'    => $potongan_cuti,
                //         'type'     => 'Bulanan',
                //         'can_adjust' => 1,
                //         't_cuti_id' => $d->id
                //     ];
                // }
                
            }
        }


        return $defaultColumns;
    }

    public function summarySubSalary($arrConfig) 
    {   
        return array_reduce($arrConfig, function ($carry, $item) {
            if(is_numeric($item['value'])){
                $value = (float)$item['value'];
                if($value != 0){
                    if($item['factor'] == '+'){
                        $carry = $carry + $item['value'];
                    }elseif($item['factor'] == '-'){
                        $carry = $carry - $item['value'];
                    }
                }
            }
            return $carry;
        }, 0);
    }

    public function countPPH21($kary, $netto = 0)
    {
        $getBasicSalary = [];
        // pengurangan dari perhitungan pph21
        // ------------------------- contoh perhitungan ---------------------------
        // Penghasilan Neto dalam setahun Rp9.400.000 x 12	    = Rp112.800.000
        // PTKP Status Lajang	                                = Rp54.000.000 (-)
        // Pendapatan Kena Pajak (PKP):	
        // PKP setahun Rp112.800.000 – Rp54.000.000	            = Rp58.800.000

        $tanggungan = m_general::find($kary->tanggungan_id);
        if($tanggungan){

            // persentase pajak <= Rp50.000.000                 = 5%
            // persentase pajak > Rp50.000.000  – Rp250.000.000 = 15%
            // persentase pajak > Rp250.000.000 – Rp500.000.000 = 25%
            // persentase pajak > Rp250.000.000 – Rp500.000.000 = 30%

            $nilaiTanggungan = @$tanggungan->value_2 ?? 0;
            $nettoYear = $netto*12;
            $nettoPTKP = $nettoYear-$nilaiTanggungan;

            // hentikan fungsi ketika gaji masih dibawah jumlah tanggungan 
            if($nettoPTKP <= 0) return $getBasicSalary;

            $percent   = 0;
            if($nettoPTKP <= 50000000){
                $before_value = 0;
                $before_percent = $percent;
                $percent = 5;
            }elseif(
                $nettoPTKP > 50000000 
                && $nettoPTKP <= 250000000
            ){
                $before_value = 50000000;
                $before_percent = $percent;
                $percent = 15;
        
            }elseif($nettoPTKP > 250000000 && $nettoPTKP <= 500000000){ 
                $before_value = 250000000;
                $before_percent = $percent;
                $percent = 25;
              
            }elseif($nettoPTKP > 500000000){
                $before_value = 500000000;
                $before_percent = $percent;
                $percent = 30;
            }
            $getBasicSalary = $this->countTaxDetail(
                $tanggungan, 
                $nettoPTKP, 
                $before_value, 
                $before_percent, 
                $percent, 
                $getBasicSalary
            );
        }
        return $getBasicSalary;
    }

       private function countTaxDetail(
        $tanggungan, 
        $nettoPTKP, 
        $before_value, 
        $before_percent, 
        $percent, 
        $mergingArr
    )
    {
        $outstanding = $nettoPTKP-$before_value;
        $tax1 = $before_percent*$before_value/100;
        $tax2 = $percent*$outstanding/100;
        $total_tax = $tax1+$tax2;
        // insert dari kondisi gaji sebelumnya sebelumnya
        // ex: 5% x 50.000.000
        // ex: 15% x 800.0000
        $detail = [];
        if($before_percent != 0){
            // jika netto / before value memiliki sisa
            $detail = [
                [
                    'label'    => "$before_percent% x $before_value",
                    'factor'   => '+',
                    'value'    => $tax1,
                    'type'     => 'Tahunan'
                ],
                [
                    'label'    => "$percent% x $outstanding",
                    'factor'   => '+',
                    'value'    => $tax2,
                    'type'     => 'Tahunan'
                ]
            ];
        }else{
            // jika netto / before value tidak memiliki sisa (konidisi pertama)
            $detail = [
                [
                    'label'    => "$percent% x $nettoPTKP",
                    'factor'   => '+',
                    'value'    => $tax2,
                    'type'     => 'Tahunan',
                ]
            ];
        }

        $mergingArr[] = [
            'label'    => "PTKP $tanggungan->value (perbulan)",
            'factor'   => '-',
            'value'    => $total_tax/12,
            'type'     => 'Bulanan',
            'can_adjust' => 0,
            'detail'   => $detail
        ];
        return $mergingArr;
    }

    public function salaryOfKary($id, $periode = null)
    {
        try{
            $m_kary_id = $id;
            $kary = m_kary::find($m_kary_id);

            // check standart gaji karyawan
            if(!@$kary->m_standart_gaji_id) return [
                'm_kary_id'  => $m_kary_id,
                'total_gaji' => 0,
                'total_tax'  => 0,
                'netto'      => 0,
                'detail'     => []
            ];
            $m_standart_gaji = m_standart_gaji::find($kary->m_standart_gaji_id);

            // default summary salary
            $getBasicSalary = $this->factorSalary($m_standart_gaji, $kary, $periode);
            $netto          = $this->summarySubSalary($getBasicSalary);
            $getBasicSalary    = array_merge($getBasicSalary, [
                [
                    'label'    => 'Total Gaji',
                    'factor'   => '=',
                    'value'    => $netto,
                    'type'     => '-'
                ]
            ]);

            $nettoFinish    = $this->summarySubSalary($getBasicSalary);

            // default summary tax
            $arrPPH         = $this->countPPH21($kary, $netto);
            $totalTax = @$arrPPH[0]['value'];
            if(count($arrPPH)){
                $getBasicSalary = array_merge($getBasicSalary, $arrPPH);
                $nettoFinish    = $this->summarySubSalary($getBasicSalary);
                $getBasicSalary    = array_merge($getBasicSalary, [
                    [
                        'label'    => 'Total Gaji (Setelah PPH 21)',
                        'factor'   => '=',
                        'value'    => $nettoFinish,
                        'type'     => '-'
                    ]
                ]);
            }

            return [
                'm_kary_id'  => $m_kary_id,
                'total_gaji' => $netto,
                'total_tax'  => $totalTax,
                'netto'      => $nettoFinish,
                'detail'     => $getBasicSalary
            ];
        } catch (\Exception $e) {
            return $e->getMessage();
            return getCore('Helper')->responseCatch($e);
        }
    }

    // untuk perhitungan gaji manual tanpa standar gaji
    public function salaryOfKaryManual($id, $date_from, $date_to, $isTunjangan)
    {
        try{
            $m_kary_id = $id;
            $kary = m_kary::find($m_kary_id);
            $grade = @$kary->grading_id ?? 20;

            // default summary salary
            $getBasicSalary = $this->factorSalaryManual($kary, $date_from, $date_to, $isTunjangan, $grade);
            $netto          = $this->summarySubSalary($getBasicSalary);
            $getBasicSalary    = array_merge($getBasicSalary, [
                [
                    'label'    => 'Total Gaji',
                    'factor'   => '=',
                    'value'    => $netto,
                    'type'     => '-'
                ]
            ]);

            $nettoFinish    = $this->summarySubSalary($getBasicSalary);

            // default summary tax
            $arrPPH         = $this->countPPH21($kary, $netto);
            $totalTax = @$arrPPH[0]['value'];
            if(count($arrPPH)){
                $getBasicSalary = array_merge($getBasicSalary, $arrPPH);
                $nettoFinish    = $this->summarySubSalary($getBasicSalary);
                $getBasicSalary    = array_merge($getBasicSalary, [
                    [
                        'label'    => 'Total Gaji (Setelah PPH 21)',
                        'factor'   => '=',
                        'value'    => $nettoFinish,
                        'type'     => '-'
                    ]
                ]);
            }

            return [
                'm_kary_id'  => $m_kary_id,
                'total_gaji' => $netto,
                'total_tax'  => $totalTax,
                'netto'      => $nettoFinish,
                'detail'     => $getBasicSalary
            ];
        } catch (\Exception $e) {
            // trigger_error($e->getMessage());
            return $e->getMessage();
            return getCore('Helper')->responseCatch($e);
        }
    }

    //  // gaji manual -> tanpa master standar gaji
    //     $t_kary_salary = t_kary_salary::selectRaw("m_kary_id, t_kary_salary.id, d.*")->join('t_kary_salary_det as d','d.t_kary_salary_id','t_kary_salary.id')
    //                         ->where('m_kary_id', @$kary->id ?? 0)
    //                         ->where('t_kary_salary.is_active', true)
    //                         ->get();

    //      foreach($standart_gaji_det as $d){
    //         $defaultColumns[] = [
    //             'label'    => $d->keterangan,
    //             'factor'   => '+',
    //             'value'    => $d->,
    //             'type'     => $d->periode,
    //             'can_adjust' => 1
    //         ];
    //     }

    private function salaryPresensi($karyId, $date_from, $date_to, $grade){
        $getUserId = default_users::where('m_kary_id',$karyId)->first()->id ?? 0;
        $getLiburNasional = m_libur_nasional::whereBetween('tanggal', [$date_from, $date_to])->where('is_active', true)->get('tanggal')->toArray();
        $getPresensi = presensi_absensi::where('default_user_id',$getUserId)
        ->where('presensi_absensi.status','ATTEND')
        ->whereRaw("tanggal >= ? and tanggal <= ?",[$date_from, $date_to])
        ->distinct('tanggal');


        $liburNasional = clone $getPresensi;
        $collectLiburNasional = collect($getLiburNasional)->pluck('tanggal')->map(fn ($date) => \Carbon::parse($date)->toDateString());
        $liburNasional = collect($liburNasional->get('tanggal'))->pluck('tanggal')->map(fn ($date)=> \Carbon::parse($date)->toDateString());
        $totalLiburNasional = $collectLiburNasional->intersect($liburNasional);
        $countLiburNasionalCheckIn = $totalLiburNasional->count();
        $liburNasionalCount = $collectLiburNasional->count();
        $countLiburNasionalNotCheckin = $liburNasionalCount - $countLiburNasionalCheckIn;

        $shift = clone $getPresensi;
        $shift = $shift->whereRaw("shift ~* ?", ['shift [23]'])->count();

        $count = clone $getPresensi;
        $count = $count->whereRaw("EXTRACT(DOW FROM tanggal) != 0")->count();
        
        $hadir = clone $getPresensi;
        $hadir = $hadir->count();
        
        $night = clone $getPresensi;
        $night = $night->where('checkin_time','<=','12:00:00')->where(function($query) {
                $query->whereRaw("checkout_time >= ?", ["19:00:00"])
                    ->orWhereRaw("checkout_time <= ?", ["05:00:00"]);
                })->count();

        $shift1Sat = clone $getPresensi;
        $shift1Sat = $shift1Sat->whereRaw("EXTRACT(DOW FROM tanggal) = 6")->whereRaw("shift ~* ?", ['shift [1]'])->count();

        $startDate = \Carbon::parse($date_from);
        $endDate = \Carbon::parse($date_to);
        $sundayCount = 0;

        while($startDate->lte($endDate)){
            if($startDate->isSunday()){
                $sundayCount++;
            }
            $startDate->addDay();
        }
        
        $sunday = clone $getPresensi;
        $sunday = $sunday->whereRaw("EXTRACT(DOW FROM tanggal) = 0")->count();

        $sundayNotCheckin =  $sundayCount - $sunday;

        $checkInArray = clone $getPresensi;
        $checkInArray = collect($checkInArray->get('tanggal')->toArray())->pluck('tanggal')->map(fn($date) => \Carbon::parse($date));
        $getFullWeek = $this->isFullWeek($checkInArray);

        $saturday = clone $getPresensi;
        $saturday = $saturday->whereRaw("EXTRACT(DOW FROM tanggal) = 6")->count();

        $saturday75 = clone $getPresensi;
        $saturday75 = $saturday75->whereRaw("EXTRACT(DOW FROM tanggal) = 6")->get();
        $get75 = $this->is75($saturday75);

        $collectFullweek = collect($getFullWeek)->where('hasMondayToSaturday', true);
        $saturday_7_5_fullweek = $this->saturday_7_5_fullweek($collectFullweek, $get75, $saturday);


        // $saturdayNew = $this->saturday_7_5_fullweekNew($getPresensi->get());
        // $sundayNew = $this->sunday_fullweek_bonus($getPresensi->get());

        $grade = strtoupper(m_general::find($grade)?->value ?? '');

        // Ambil data asli dari query builder
        $originalData = collect($getPresensi->get());

        $weeks = $originalData->groupBy(function ($item) {
            return Carbon::parse($item['tanggal'])->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        });

        $completePresensi = collect();
        $firstWeekDate = $weeks->keys()->first(); // Ambil key tanggal Senin pekan pertama

        foreach ($weeks as $mondayDate => $records) {
            // JIKA ini adalah pekan pertama, ambil data Senin-Minggu secara utuh dari DB
            if ($mondayDate === $firstWeekDate) {
                $startOfWeek = Carbon::parse($mondayDate); 
                $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SUNDAY);

                $fullWeekData = presensi_absensi::where('default_user_id', $getUserId)
                    ->where('status', 'ATTEND')
                    ->whereBetween('tanggal', [
                        $startOfWeek->format('Y-m-d'), 
                        $endOfWeek->format('Y-m-d')
                    ])
                    ->get();

                $completePresensi = $completePresensi->merge($fullWeekData);
            } else {
                // JIKA BUKAN pekan pertama (pekan terakhir, dll), gunakan data asli apa adanya
                $completePresensi = $completePresensi->merge($records);
            }
        }

        $completePresensi = $completePresensi->unique('tanggal');

        $saturdayNew = $this->saturday_7_5_fullweekNew($completePresensi, $grade);
        $sundayNew = $this->sunday_fullweek_bonus($completePresensi, $grade);
        $holidayNew = $this->holiday_fullweek_bonus($completePresensi, $grade);

        // $saturdayNew = $this->saturday_7_5_fullweekNew($getPresensi->get(), $grade);
        // $sundayNew   = $this->sunday_fullweek_bonus($getPresensi->get(), $grade);

        // dd($saturdaNew);
       
         return[
            "user_id" => $getUserId,
            // "count" => $count + $shift1Sat,
            "count" => $count,
            "hadir" => $hadir,
            "night" => $night,
            "shift" => $shift,
            "shift1_sat" => $shift1Sat,
            "sunday" => $sunday,
            "sunday_not_checkin" => $sundayNotCheckin,
            "saturday" => $saturday,
            "libur_nasional_checkin" => $countLiburNasionalCheckIn,
            "libur_nasional_not_checkin" => $countLiburNasionalNotCheckin,
            "saturday_7_5" => $get75,
            "getFullWeek" => $getFullWeek,
            "saturday_7_5_fullweek" => $saturday_7_5_fullweek,
            "countFullWeek" => $collectFullweek->count(),
            "saturday_bonus_new" => $saturdayNew,
            "sunday_bonus_new" => $sundayNew['total'] ?? 0,
            "debug_sunday" => $sundayNew['debug'] ?? '',
            "holiday_bonus_new" => $holidayNew,
        ];
    }

    private function is75($data){
        $is75 = [];
        $requiredDiff = 10;
        foreach($data as $single){
            $checkIn = \Carbon::parse($single['checkin_time']);
            $checkOut = \Carbon::parse($single['checkout_time']);
            $durationInHours = $checkOut->diffInHours($checkIn);
            if($durationInHours >= $requiredDiff){
                $is75[]=[
                    'date' => $single['tanggal'],
                ];
            };
        }
        return $is75;
    }

    private function isFullWeek($dates){
        $hasFullWeek = 0;

        $mondayIndices = [];
        foreach ($dates as $index => $date) {
            $carbonDate = \Carbon::parse($date);
            if ($carbonDate->isMonday()) {
                $mondayIndices[] = $index;
            }
        }


        $results = [];
        foreach ($mondayIndices as $mondayIndex) {
            $startDate = \Carbon::parse($dates[$mondayIndex]);
            $foundDays = [];

            for ($i = 0; $i < 6; $i++) {
                $dayToCheck = $startDate->copy()->addDays($i); 
                foreach ($dates as $date) {
                    if ($dayToCheck->isSameDay(\Carbon::parse($date))) {
                        $foundDays[] = $dayToCheck->format('Y-m-d'); 
                        break;
                    }
                }
            }

            $isComplete = count($foundDays) === 6;
            $results[] = [
                'mondayIndex' => $mondayIndex,
                'hasMondayToSaturday' => $isComplete,
                'foundDays' => $foundDays
            ];
        }

        return $results;
    }

    // private function saturday_7_5_fullweek($getPresensi)
    // {
    //     // Ambil semua data hari Sabtu
    //     $saturdays = $getPresensi->filter(function ($item) {
    //         return \Carbon::parse($item['tanggal'])->dayOfWeek === 6; // 6 = Sabtu
    //     });

    //     $bonus = 0;

    //     foreach ($saturdays as $day) {
    //         $checkIn  = \Carbon::parse($day['checkin_time']);
    //         $checkOut = \Carbon::parse($day['checkout_time']);
    //         $duration = $checkOut->diffInHours($checkIn);

    //         // Jika kerja >= 10 jam, tambahkan bonus 0.5
    //         if ($duration >= 10) {
    //             $bonus += 0.5;
    //         }
    //     }

    //     return $bonus;
    // }

    // private function saturday_7_5_fullweekNew($getPresensi)
    // {
    //     // Group data presensi berdasarkan minggu (format: YYYY-WW)
    //     $weeks = collect($getPresensi)->groupBy(function ($item) {
    //         return \Carbon::parse($item['tanggal'])->format('o-W');
    //     });

    //     $totalBonus = 0;

    //     foreach ($weeks as $week => $records) {
    //         // Ambil daftar hari dalam minggu ini (0=Min, 1=Senin, ... 6=Sabtu)
    //         $daysOfWeek = collect($records)->map(function ($r) {
    //             return \Carbon::parse($r['tanggal'])->dayOfWeek;
    //         })->unique()->values();

    //         // Cek apakah hadir dari Senin (1) sampai Sabtu (6)
    //         $hasFullWeek = collect(range(1, 6))->every(fn($d) => $daysOfWeek->contains($d));

    //         if ($hasFullWeek) {
    //             // Cari presensi Sabtu
    //             $saturday = collect($records)->first(function ($r) {
    //                 return \Carbon::parse($r['tanggal'])->dayOfWeek === 6;
    //             });

    //             if ($saturday) {
    //                 $checkIn = \Carbon::parse($saturday['checkin_time']);
    //                 $checkOut = \Carbon::parse($saturday['checkout_time']);
    //                 $duration = $checkOut->diffInHours($checkIn);

    //                 // Jika Sabtu >= 10 jam kerja
    //                 if ($duration >= 10) {
    //                     $totalBonus += 0.5;
    //                 }
    //             }
    //         }
    //     }

    //     return $totalBonus;
    // }

    // private function saturday_7_5_fullweekNew($getPresensi, $grade)
    // {
    //     // Tidak dapat bonus jika borongan
    //     if (str_starts_with($grade, '2') || str_starts_with($grade, '3')) {
    //         return 0;
    //     }

    //     // $bonusFullWeek = 0.5;
    //     $bonusFullWeek = ($grade === '1D') ? 0 : 0.5;
    //     $bonusNotFull = 0;

    //     // Group by week
    //     $weeks = collect($getPresensi)->groupBy(function ($item) {
    //         return \Carbon::parse($item['tanggal'])->startOfWeek(\Carbon::MONDAY)->format('Y-m-d');
    //     });

    //     $total = 0;

    //     foreach ($weeks as $weekStart => $records) {

    //         // List hari dalam minggu itu
    //         $daysPresent = collect($records)->map(fn($r) =>
    //             \Carbon::parse($r['tanggal'])->dayOfWeek
    //         )->unique();

    //         // Cek apakah full Week (Senin–Sabtu)
    //         $hasFullWeek = collect(range(1, 6))->every(fn($d) => $daysPresent->contains($d));

    //         // Jika minggu terpotong oleh periode (misal mulai Rabu), tetap dianggap full
    //         if (!$hasFullWeek) {
    //             $firstInWeek = collect($records)->sortBy('tanggal')->first();
    //             $hariPertama = \Carbon::parse($firstInWeek['tanggal'])->dayOfWeek;

    //             // Jika hari pertama minggu itu bukan Senin (1), berarti minggu terpotong oleh periode
    //             if ($hariPertama > 1) {
    //                 $hasFullWeek = true;
    //             }
    //         }

    //         // Ambil Sabtu
    //         $saturday = collect($records)->first(function ($r) {
    //             return \Carbon::parse($r['tanggal'])->dayOfWeek === 6;
    //         });

    //         if (!$saturday) continue;

    //         // Durasi dalam menit
    //         $checkIn = \Carbon::parse($saturday['checkin_time']);
    //         $checkOut = \Carbon::parse($saturday['checkout_time']);
    //         $durationMinutes = $checkOut->diffInMinutes($checkIn);

    //         // 10 menit toleransi (bukan 2 menit)
    //         $minimalMinutes = 600 - 5; // 10 jam - toleransi

    //         if ($durationMinutes >= $minimalMinutes) {
    //             $total += $hasFullWeek ? $bonusFullWeek : $bonusNotFull;
    //         }
    //     }

    //     return $total;
    // }

    //revisi terbaru masih trial
    private function saturday_7_5_fullweekNew($getPresensi, $grade)
    {
        if (str_starts_with($grade, '2') || str_starts_with($grade, '3')) {
            return 0;
        }

        $bonusFullWeek = ($grade === '1D') ? 0 : 0.5;
        $total = 0;

        // Ambil data presensi yang statusnya benar-benar ATTEND
        $attendPresensi = collect($getPresensi)->filter(function($r) {
            $status = is_array($r) ? ($r['status'] ?? '') : ($r->status ?? '');
            $in = is_array($r) ? ($r['checkin_time'] ?? '') : ($r->checkin_time ?? '');
            $out = is_array($r) ? ($r['checkout_time'] ?? '') : ($r->checkout_time ?? '');
            return $status === 'ATTEND' && !empty($in) && !empty($out) && $in !== '00:00:00' && $out !== '00:00:00';
        });

        // Ambil semua tanggal unik yang ada di data absensi hadir ini
        $allPresenceDates = $attendPresensi->map(fn($r) => \Carbon::parse(is_array($r) ? $r['tanggal'] : $r->tanggal)->format('Y-m-d'))->unique()->toArray();
        
        if (empty($allPresenceDates)) return 0;

        $allDates = collect($allPresenceDates)->map(fn($d) => \Carbon::parse($d));
        $periodStart = $allDates->min();
        $periodEnd = $allDates->max();

        $weeks = $attendPresensi->groupBy(function ($item) {
            $tgl = is_array($item) ? $item['tanggal'] : $item->tanggal;
            return \Carbon::parse($tgl)->startOfWeek(\Carbon::MONDAY)->format('Y-m-d');
        });
        foreach ($weeks as $weekStart => $records) {
            // Cari data Sabtu yang hadir
            $saturday = collect($records)->first(function($r) {
                $tgl = is_array($r) ? $r['tanggal'] : $r->tanggal;
                $status = is_array($r) ? ($r['status'] ?? '') : ($r->status ?? '');
                $in = is_array($r) ? ($r['checkin_time'] ?? '') : ($r->checkin_time ?? '');
                $out = is_array($r) ? ($r['checkout_time'] ?? '') : ($r->checkout_time ?? '');
                return \Carbon::parse($tgl)->dayOfWeek === 6 &&
                       $status === 'ATTEND' &&
                       !empty($in) && !empty($out) &&
                       $in !== '00:00:00' && $out !== '00:00:00' && $in !== $out;
            });

            if (!$saturday) {
                continue;
            }

            $startOfWeek = \Carbon::parse($weekStart);
            $endOfWeek = $startOfWeek->copy()->endOfWeek(\Carbon::SUNDAY);

            // Tentukan batas pengecekan untuk minggu ini saja
            $checkStart = $periodStart->greaterThan($startOfWeek) ? $periodStart->format('Y-m-d') : $startOfWeek->format('Y-m-d');
            $checkEnd = $periodEnd->lessThan($endOfWeek) ? $periodEnd->format('Y-m-d') : $endOfWeek->format('Y-m-d');

            // Buat list tanggal yang SEHARUSNYA ada dalam range periode di minggu ini
            $expectedDates = [];
            $current = \Carbon::parse($checkStart);
            $stop = \Carbon::parse($checkEnd);
            
            while ($current <= $stop) {
                $expectedDates[] = $current->format('Y-m-d');
                $current->addDay();
            }

            // Ambil tanggal yang BENAR-BENAR ada (hadir) di minggu ini
            $actualDates = collect($records)->map(fn($r) => \Carbon::parse(is_array($r) ? $r['tanggal'] : $r->tanggal)->format('Y-m-d'))->unique()->toArray();

            // Cek apakah semua tanggal yang diharapkan ada di data hadir
            $hasFullWeek = collect($expectedDates)->every(fn($date) => in_array($date, $actualDates));

            $satIn = is_array($saturday) ? $saturday['checkin_time'] : $saturday->checkin_time;
            $satOut = is_array($saturday) ? $saturday['checkout_time'] : $saturday->checkout_time;
            $checkIn = \Carbon::parse($satIn);
            $checkOut = \Carbon::parse($satOut);
            
            // Gunakan absolute difference agar tidak minus
            $durationMinutes = $checkOut->diffInMinutes($checkIn);

            // 10 jam = 600 menit. Toleransi 10 menit = 590 menit.
            if ($durationMinutes >= 590) {
                $total += $hasFullWeek ? $bonusFullWeek : 0;
            }
        }
        return $total;
    }

    private function saturday_7_5_fullweekNewOld($getPresensi)
    {
        // Group data presensi berdasarkan awal minggu (Senin)
        $weeks = collect($getPresensi)->groupBy(function ($item) {
            return \Carbon::parse($item['tanggal'])->startOfWeek(\Carbon::MONDAY)->format('Y-m-d');
        });

        $totalBonus = 0;

        foreach ($weeks as $weekStart => $records) {
            $daysOfWeek = collect($records)->map(function ($r) {
                return \Carbon::parse($r['tanggal'])->dayOfWeek;
            })->unique()->values();

            // Cek hadir dari Senin (1) sampai Sabtu (6)
            $hasFullWeek = collect(range(1, 6))->every(fn($d) => $daysOfWeek->contains($d));

            if ($hasFullWeek) {
                // Cari presensi Sabtu
                $saturday = collect($records)->first(function ($r) {
                    return \Carbon::parse($r['tanggal'])->dayOfWeek === 6;
                });

                if ($saturday) {
                    $checkIn = \Carbon::parse($saturday['checkin_time']);
                    $checkOut = \Carbon::parse($saturday['checkout_time']);
                    $duration = $checkOut->diffInHours($checkIn);

                    // Jika Sabtu >= 10 jam kerja maka tambahkan 0.5
                    if ($duration >= 10) {
                        $totalBonus += 0.5;
                    }

                    // Bonus 0.5 tambahan karena full week
                    $totalBonus += 0.5;
                }
            }
        }

        return $totalBonus;
    }

    private function sunday_fullweek_bonus($getPresensi, $grade)
    {
        // Borongan tidak dapat bonus Minggu
        if (str_starts_with($grade, '2') || str_starts_with($grade, '3')) {
            return 0;
        }

        $gradeBonus = [
            '1A' => 1,
            '1B' => 1,
            '1C' => 0.5,
            '1D' => 0,
        ];

        $bonusValue = $gradeBonus[$grade] ?? 0;
        if ($bonusValue <= 0) return 0;

        // Ambil data presensi yang statusnya BENAR-BENAR ATTEND
        $attendPresensi = collect($getPresensi)->filter(function($r) {
            $status = is_array($r) ? ($r['status'] ?? '') : ($r->status ?? '');
            $in = is_array($r) ? ($r['checkin_time'] ?? '') : ($r->checkin_time ?? '');
            $out = is_array($r) ? ($r['checkout_time'] ?? '') : ($r->checkout_time ?? '');
            return $status === 'ATTEND' && !empty($in) && !empty($out) && $in !== '00:00:00' && $out !== '00:00:00';
        });

        // Ambil semua tanggal unik yang hadir di periode ini
        $allPresenceDates = $attendPresensi
            ->map(fn($r) => \Carbon::parse(is_array($r) ? $r['tanggal'] : $r->tanggal)->format('Y-m-d'))
            ->unique();

        if ($allPresenceDates->isEmpty()) return 0;

        $periodStart = \Carbon::parse($allPresenceDates->min());

        // Grouping berdasarkan minggu
        $weeks = $attendPresensi->groupBy(function ($item) {
            $tgl = is_array($item) ? $item['tanggal'] : $item->tanggal;
            return \Carbon::parse($tgl)->startOfWeek(\Carbon::MONDAY)->format('Y-m-d');
        });

        $total = 0;

        foreach ($weeks as $weekStart => $records) {
            // Cari data hari Minggu di minggu ini yang BENAR-BENAR HADIR (status == ATTEND)
            $sunday = collect($records)->first(function($r) {
                $tgl = is_array($r) ? $r['tanggal'] : $r->tanggal;
                $status = is_array($r) ? ($r['status'] ?? '') : ($r->status ?? '');
                $in = is_array($r) ? ($r['checkin_time'] ?? '') : ($r->checkin_time ?? '');
                $out = is_array($r) ? ($r['checkout_time'] ?? '') : ($r->checkout_time ?? '');

                return \Carbon::parse($tgl)->dayOfWeek === 0 &&
                       $status === 'ATTEND' &&
                       !empty($in) && !empty($out) &&
                       $in !== '00:00:00' && $out !== '00:00:00' &&
                       $in !== $out;
            });

            // JIKA HARI MINGGU TIDAK MASUK/LIBUR -> TIDAK DAPAT BONUS MINGGU
            if (!$sunday) {
                continue;
            }

            $sunTgl = is_array($sunday) ? $sunday['tanggal'] : $sunday->tanggal;
            $sundayDate = \Carbon::parse($sunTgl);
            
            // Tentukan range pengecekan: 
            // Dari awal minggu (Senin) sampai hari Sabtu sebelum Minggu
            $startCheck = \Carbon::parse($weekStart);

            // Logika Full Week: Cek apakah dari startCheck s/d hari Sabtu masuk semua
            $hasFullWeek = true;
            $tempDate = $startCheck->copy();
            $saturdayDate = $sundayDate->copy()->subDay();
            while ($tempDate <= $saturdayDate) {
                if (!$allPresenceDates->contains($tempDate->format('Y-m-d'))) {
                    $hasFullWeek = false;
                    break;
                }
                $tempDate->addDay();
            }

            // Jika Full Week terpenuhi, cek durasi/kehadiran hari Minggu tersebut
            if ($hasFullWeek) {
                $sunIn = is_array($sunday) ? $sunday['checkin_time'] : $sunday->checkin_time;
                $sunOut = is_array($sunday) ? $sunday['checkout_time'] : $sunday->checkout_time;
                $checkIn = \Carbon::parse($sunIn);
                $checkOut = \Carbon::parse($sunOut);

                if ($checkOut->gt($checkIn)) {
                    $total += $bonusValue;
                    $debugSun[] = "$sunTgl in:$sunIn out:$sunOut";
                }
            }
        }

        return [
            'total' => $total,
            'debug' => implode('; ', $debugSun ?? [])
        ];
    }

    private function holiday_fullweek_bonus($getPresensi, $grade)
    {
        // 1. Borongan atau Grade tertentu tidak dapat bonus
        if (str_starts_with($grade, '2') || str_starts_with($grade, '3')) {
            return 0;
        }

        $gradeBonus = [
            '1A' => 1,
            '1B' => 1,
            '1C' => 0.5,
            '1D' => 0,
        ];

        $bonusValue = $gradeBonus[$grade] ?? 0;
        if ($bonusValue <= 0) return 0;

        // Ambil data presensi yang statusnya benar-benar ATTEND
        $attendPresensi = collect($getPresensi)->filter(function($r) {
            $status = is_array($r) ? ($r['status'] ?? '') : ($r->status ?? '');
            $in = is_array($r) ? ($r['checkin_time'] ?? '') : ($r->checkin_time ?? '');
            $out = is_array($r) ? ($r['checkout_time'] ?? '') : ($r->checkout_time ?? '');
            return $status === 'ATTEND' && !empty($in) && !empty($out) && $in !== '00:00:00' && $out !== '00:00:00';
        });

        // 2. Ambil data Libur Nasional (filter berdasarkan tanggal hadir dan status is_active)
        $allPresenceDates = $attendPresensi
            ->map(fn($r) => \Carbon::parse(is_array($r) ? $r['tanggal'] : $r->tanggal)->format('Y-m-d'))
            ->unique();

        if ($allPresenceDates->isEmpty()) return 0;

        $periodStart = \Carbon::parse($allPresenceDates->min());
        $periodEnd   = \Carbon::parse($allPresenceDates->max());

        $holidays = \DB::table('m_libur_nasional')
            ->where('is_active', true)
            ->whereBetween('tanggal', [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')])
            ->pluck('tanggal')
            ->toArray();

        if (empty($holidays)) return 0;

        // 3. Grouping presensi berdasarkan minggu (Senin - Minggu)
        $weeks = $attendPresensi->groupBy(function ($item) {
            $tgl = is_array($item) ? $item['tanggal'] : $item->tanggal;
            return \Carbon::parse($tgl)->startOfWeek(\Carbon::MONDAY)->format('Y-m-d');
        });

        $total = 0;

        foreach ($weeks as $weekStart => $records) {
            // Cari apakah ada hari libur nasional di dalam record kehadiran minggu ini
            $presenceInWeek = collect($records);
            
            foreach ($holidays as $holidayDate) {
                // Cek apakah hari libur ini ada di minggu yang sedang di-loop
                $carbonHoliday = \Carbon::parse($holidayDate);
                $startOfWeek = \Carbon::parse($weekStart);
                $endOfWeek = $startOfWeek->copy()->endOfWeek(\Carbon::SUNDAY);

                if ($carbonHoliday->between($startOfWeek, $endOfWeek)) {
                    
                    // Cari data kehadiran user di hari libur tersebut (wajib ada checkin/checkout dan status ATTEND)
                    $holidayPresence = $presenceInWeek->first(function($r) use ($holidayDate) {
                        $tgl = is_array($r) ? $r['tanggal'] : $r->tanggal;
                        $status = is_array($r) ? ($r['status'] ?? '') : ($r->status ?? '');
                        $in = is_array($r) ? ($r['checkin_time'] ?? '') : ($r->checkin_time ?? '');
                        $out = is_array($r) ? ($r['checkout_time'] ?? '') : ($r->checkout_time ?? '');
                        return $tgl == $holidayDate && $status === 'ATTEND' && !empty($in) && !empty($out) && $in !== '00:00:00' && $out !== '00:00:00';
                    });

                    if ($holidayPresence) {
                        // Logika Full Week: Karyawan wajib hadir di seluruh hari kerja aktif di pekan tersebut (Senin s/d Sabtu)
                        $startOfWeek = \Carbon::parse($weekStart); // Senin
                        $saturdayOfWeek = $startOfWeek->copy()->addDays(5); // Sabtu
                        
                        $hasFullWeek = true;
                        $tempDate = $startOfWeek->copy();

                        while ($tempDate <= $saturdayOfWeek) {
                            $dateStr = $tempDate->format('Y-m-d');
                            // Hari libur itu sendiri atau libur nasional lain tidak wajib hadir kerja normal
                            if ($dateStr !== $holidayDate && !in_array($dateStr, $holidays)) {
                                if (!$allPresenceDates->contains($dateStr)) {
                                    $hasFullWeek = false;
                                    break;
                                }
                            }
                            $tempDate->addDay();
                        }

                        // Jika syarat masuk seluruh hari kerja terpenuhi dan ada jam kerja valid di hari libur
                        if ($hasFullWeek) {
                            $holIn = is_array($holidayPresence) ? $holidayPresence['checkin_time'] : $holidayPresence->checkin_time;
                            $holOut = is_array($holidayPresence) ? $holidayPresence['checkout_time'] : $holidayPresence->checkout_time;
                            $checkIn = \Carbon::parse($holIn);
                            $checkOut = \Carbon::parse($holOut);

                            if ($checkOut->gt($checkIn)) {
                                $total += $bonusValue;
                            }
                        }
                    }
                }
            }
        }

        return $total;
    }

    // private function sunday_fullweek_bonus($getPresensi, $grade)
    // {
    //     // Borongan tidak dapat bonus Minggu
    //     if (str_starts_with($grade, '2') || str_starts_with($grade, '3')) {
    //         return 0;
    //     }

    //     // Bonus per grade
    //     $gradeBonus = [
    //         '1A' => 1,
    //         '1B' => 1,
    //         '1C' => 0.5,
    //         '1D' => 0,
    //     ];

    //     $bonus = $gradeBonus[$grade] ?? 0;

    //     $weeks = collect($getPresensi)->groupBy(function ($item) {
    //         return \Carbon::parse($item['tanggal'])->format('o-W');
    //     });

    //     $total = 0;

    //     foreach ($weeks as $records) {
    //         $sunday = collect($records)->first(fn($r) =>
    //             \Carbon::parse($r['tanggal'])->dayOfWeek === 0
    //         );

    //         if ($sunday) {
    //             $checkIn = \Carbon::parse($sunday['checkin_time']);
    //             $checkOut = \Carbon::parse($sunday['checkout_time']);

    //             if ($checkOut->gt($checkIn)) {
    //                 $total += $bonus;
    //             }
    //         }
    //     }

    //     return $total;
    // }


   private function sunday_fullweek_bonusOld($getPresensi)
    {
        $weeks = collect($getPresensi)->groupBy(function ($item) {
            return \Carbon::parse($item['tanggal'])->format('o-W');
        });

        $totalBonus = 0;

        foreach ($weeks as $week => $records) {
            $sunday = collect($records)->first(function ($r) {
                return \Carbon::parse($r['tanggal'])->dayOfWeek === 0;
            });

            if ($sunday) {
                $checkIn = \Carbon::parse($sunday['checkin_time']);
                $checkOut = \Carbon::parse($sunday['checkout_time']);
                $duration = $checkOut->diffInHours($checkIn);

                if ($duration > 0) {
                    $totalBonus += 1;
                }
            }
        }

        return $totalBonus;
    }




    private function saturday_7_5_fullweek($fullweek, $get75, $saturday){
        $get75Day = array_column($get75, 'date');
        $fullweek = $fullweek->pluck('foundDays')->flatten()->toArray();
        $date = array_intersect($get75Day, $fullweek);
        if (!empty($date)) {
            return $saturday;
        }
        return 0;
    }
    
}