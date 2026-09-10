<?php

namespace App\Models\CustomModels;

class t_salary_borongan extends \App\Models\BasicModels\t_salary_borongan
{    
    private $helper; 
    public function __construct()
    {
        $this->helper = getCore('Helper');
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function custom_post($req)
    {
        $validator = \Validator::make($req->all(),[
            'label'     => 'nullable',
            'date'      => 'required|date|date_format:Y-m-d',
            'jml_kary'  => 'required',
            'pic_id'    => 'required',
            'keterangan'=> 'nullable',
        ]);

        if($validator->fails()) return $this->helper->responseValidate($validator);

        try{
            \DB::beginTransaction();
                $h = t_salary_borongan::where('date', $req->date)
                ->whereRaw("lower(label) = ?", [strtolower($req->label)])
                ->lockForUpdate()
                ->first();
                if(@$h->status == 'POST') $this->helper->customResponse('Sudah terdapat transaksi yg menjadi perhitungan gaji, proses edit ditolak', 422);

                if($h){
                    // t_salary_borongan::where('id', @$h->id)->delete();
                    t_salary_borongan_det::where('t_salary_borongan_id', @$h->id)->delete();
                    t_salary_borongan_det_kary::where('t_salary_borongan_id', @$h->id)->delete();
                }

                $label = isset($req->label) && $req->label !== '' ? $req->label : $this->helper->generateNomor('KODE BORONGAN');


                $data = $this->updateOrCreate(
                    [
                        'label' => $label, 
                        'date'  => $req->date
                    ], 
                    [
                        'jml_kary'         => $req->jml_kary,
                        'pic_id'           => $req->pic_id,
                        'total_pendapatan' => (float)$req->total_pendapatan,
                        'keterangan'       => $req->keterangan
                    ] 
                );


                $total_pendapatan = 0;
                foreach($req->t_salary_borongan_det ?? [] as $d)
                {
                    t_salary_borongan_det::create([
                        't_salary_borongan_id'  => $data->id,
                        'm_tarif_group_id'      => $d['m_tarif_group_id'],
                        'm_tarif_id'            => $d['m_tarif_id'],
                        'tarif'                 => $d['tarif'],
                        'tarif_desc'            => $d['tarif_desc'],
                        'qty'                   => $d['qty'],
                        'subtotal'              => (float)$d['tarif'] * (float)$d['qty'],
                        'keterangan'            => $d['keterangan']

                    ]);
                    $total_pendapatan += (float)$d['tarif'] * (float)$d['qty'];
                }
                
                foreach($req->t_salary_borongan_det_kary ?? [] as $d)
                {
                    t_salary_borongan_det_kary::create([
                        't_salary_borongan_id'  => $data->id,
                        'm_kary_id'             => $d['m_kary_id'],
                        'diterima'              => $total_pendapatan / count($req->t_salary_borongan_det_kary),
                        'is_pic'                => (int)$d['m_kary_id'] == (int)$req->pic_id ? true : false
                    ]);
                }
            \DB::commit();
            return $this->helper->customResponse('Data hasil borongan berhasil disimpan');
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_get_by_date($req){
        $validator = \Validator::make($req->all(),[
            'date'      => 'required',
            'label'     => 'required'
        ]);

        if($validator->fails()) return $this->helper->responseValidate($validator);
        
        $data = [];
        $data = $this->where('t_salary_borongan.date',$req->date)->whereRaw("lower(t_salary_borongan.label) = ?", [strtolower($req->label)])
            ->with('t_salary_borongan_det.m_tarif','t_salary_borongan_det_kary.m_kary.m_dept')->first();

        return response()->json($data);
    }

    public function custom_get_label_by_date($req){
        $validator = \Validator::make($req->all(),[
            'date'      => 'required',
        ]);

        if($validator->fails()) return $this->helper->responseValidate($validator);
        
        $data = [];
        $data = $this->select('label')->where('date', $req->date)->get();

        return response()->json($data);
    }


    public function custom_get_kary_by_group($req){
        $validator = \Validator::make($req->all(),[
            'm_kary_group_id'   => 'required',
        ]);

        if($validator->fails()) return $this->helper->responseValidate($validator);
        
        $data = [];
        $data = m_kary_group_det::where('m_kary_group_id', $req->m_kary_group_id)->with('m_kary.m_dept')->get();

        return response()->json($data);
    }

    public function custom_generate_gaji_borongan(){
        return $this->generateSalaryBorongan();
    }

    public function generateSalaryBorongan()
    {
        try{
            $req = app()->request;
            $startDate = $req->periode_awal;
            $endDate = $req->periode_akhir;

            $otherFilterSql = "";

            if($req->m_divisi_id){
                $otherFilterSql .= " and d.id = $req->m_divisi_id";
            }
            if($req->m_dept_id){
                $otherFilterSql .= " and dp.id = $req->m_dept_id";
            }

            $groupedData = \DB::select("
                select k.id,k.nama_lengkap, k.kode, k.m_dir_id, k.m_divisi_id, k.m_dept_id, dir.nama dir,d.nama divisi, dp.nama dept, sum(diterima) gaji, k.grading_id,
                (select count(1) from t_salary_borongan_det_kary a join t_salary_borongan b on b.id = a.t_salary_borongan_id 
                    where a.m_kary_id = k.id and b.date >= ? and b.date <= ?) jml_hari
                from t_salary_borongan_det_kary t join t_salary_borongan h on h.id = t.t_salary_borongan_id
                left join m_kary k on k.id = t.m_kary_id 
                left join m_dir dir on dir.id = k.m_dir_id
                left join m_divisi d on d.id = k.m_divisi_id 
                left join m_dept dp on dp.id = k.m_dept_id 
                where h.date >= ? and h.date <= ? $otherFilterSql
                group by k.id,k.nama_lengkap,k.kode, k.m_divisi_id,k.m_dir_id, k.m_dept_id, dir.nama, d.nama, dp.nama;
            ", [$startDate, $endDate, $startDate, $endDate]);
            
            $formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $startDate);
            $formattedDate2 = \Carbon\Carbon::createFromFormat('Y-m-d', $endDate);
            $getPeriode = m_general::where('group','PERIODE GAJI')->where('key','PRDGJ02')->first();
            
            $data = [];
            foreach($groupedData as $d){
                if(@$d->id){
                    $gaji = $this->salaryOfKaryBorongan($getPeriode,$d,$d->jml_hari,$formattedDate->format('Y-m-d'),$formattedDate2->format('Y-m-d'),$req->is_tunjangan);
                    // trigger_error(json_encode($gaji)); //debug disini rawan error
                    $filteredItem = [
                        'm_kary_id'         => $d->id,
                        'm_kary.nik'        => @$d->kode,
                        'm_kary_dir_id'     => @$d->m_dir_id,
                        'm_kary_dir.nama'   => @$d->dir,
                        'm_kary_divisi_id'  => @$d->m_divisi_id,
                        'm_kary_divisi.nama'=> @$d->divisi,
                        'm_kary_dept_id'    => @$d->m_dept_id,
                        'm_kary_dept.nama'  => @$d->dept,
                        'nik'               => @$d->kode,
                        'nama_lengkap'      => @$d->nama_lengkap,
                        'periode'           => $formattedDate->format('d-m-Y').' - '.$formattedDate2->format('d-m-Y'),
                        'periode_in_date'   => $formattedDate->format('Y-m-d'),
                        'periode_id'        => @$getPeriode->id ?? 0,
                        'periode_text'      => @$getPeriode->value ?? '-',
                        'total_tax'         => 0,
                        'total_gaji'        => $gaji['total_gaji'],
                        'netto'             => $gaji['netto'],
                        'detail_gaji'       => $gaji['detail']
                    ];

                    $data[] = $filteredItem;
                }
            }

            $data = [
                "message" => "success",
                "error" => false,
                "data" => $data
            ];

            return $data;
        }catch(\Exception $e){
            return $this->helper->responseCatch($e);

        }
        
    }

    private function summarySubSalary($arrConfig) 
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

    private function salaryOfKaryBorongan($periode,$kary,$lembur,$date_from,$date_to, $isTunjangan = false){
         try{
            $m_kary_id = @$kary->id ?? 0;
            if(!@$kary->gaji) return [
                'm_kary_id'  => $m_kary_id,
                'total_gaji' => 0,
                'total_tax'  => 0,
                'netto'      => 0,
                'detail'     => []
            ];
          
            $lemburData = [];
            $grade = m_grade_d::where('m_grade_id',@$kary->grading_id)->whereNot('is_month',true)->where('is_active', true)->get();
            $bulanan = m_grade_d::where('m_grade_id',@$kary->grading_id)->where('is_month',true)->where('is_active', true)->get();
            if($isTunjangan){
                foreach($bulanan as $single){
                    $data = [
                        'name' => str_replace(' ','_', strtolower($single['keterangan'])),
                        'type' => "MINGGUAN",
                        'label' => $single['keterangan'],
                        'factor' => $single['factor'],
                        'value' => (int) $single['value'],
                        'can_adjust' => 1 
                    ];
                    $lemburData[] = $data;
                }
            }
            if($grade){
                $presensi = $this->salaryPresensi(@$kary->id,$date_from,$date_to);
                foreach($grade as $single){
                    if($single['full_week']){
                        $getFullWeek = collect($presensi['getFullWeek']);
                        $countGetFullWeek = $getFullWeek->where('hasThursdayToThursday', true)->count();
                        if($countGetFullWeek > 0){
                            $bonusFullweek = [
                                'name' => str_replace(' ','_', strtolower($single['keterangan'])),
                                'type' => "MINGGUAN",
                                'label' => $single['keterangan'],
                                'factor' => $single['factor'],
                                'value' => (int) $single['value'] * $countGetFullWeek,
                                'can_adjust' => 1 
                            ];
                            $lemburData[] = $bonusFullweek;
                        }
                    }
                    if($single['need_overtime']){
                        $diff = 0;
                        $needOvertimeCount = 0;

                        //cek data lembur
                        $getOvertime = t_lembur::where('m_kary_id',@$kary->id)
                        ->whereRaw("tanggal >= ? and tanggal <= ?",[$date_from, $date_to])
                        ->join('m_kary as mk','m_kary_id','mk.id')
                        ->select('t_lembur.*')
                        ->where('status', 'APPROVED')->get();

                            if($getOvertime->isNotEmpty()){
                                foreach($getOvertime as $singleOvertime){
                                $startOvertime = \Carbon::parse($singleOvertime['tanggal'].''.$singleOvertime['jam_mulai']);
                                $endOvertime = \Carbon::parse($singleOvertime['tanggal'].''.$singleOvertime['jam_selesai']);
                                if($singleOvertime['jam_mulai'] >= $singleOvertime['jam_selesai']) $endOvertime->addDay();

                                $diff += $startOvertime->diffInHours($endOvertime);
                                $overtimeTunjangan = $startOvertime->diffInHours($endOvertime);
                                if($overtimeTunjangan >= $single['value_overtime']){
                                    $needOvertimeCount = $needOvertimeCount + 1; 
                                }
                            }
                        }

                        if($needOvertimeCount > 0){
                            $bonusOvertime = [
                                'name' => str_replace(' ','_', strtolower($single['keterangan'])),
                                'type' => "MINGGUAN",
                                'label' => $single['keterangan'],
                                'factor' => $single['factor'],
                                'value' => (float) $single['value'] * $needOvertimeCount,
                                'can_adjust' => 1 
                            ];
                            $lemburData[] = $bonusOvertime;
                        }
                    }
                }
            }

            $getBasicSalary = [];

            $penghasilan_borongan = \DB::select("select to_char(h.date, 'dd-mm-yyyy') tgl, h.label, dk.m_kary_id, dk.diterima
                ,REPLACE(dt.keterangan, ', ', E', \n') AS keterangan
                from t_salary_borongan_det_kary dk 
                join t_salary_borongan h on h.id= dk.t_salary_borongan_id 
                LEFT JOIN 
                    (SELECT 
                        t_salary_borongan_id, 
                        STRING_AGG(keterangan || ' : ' || subtotal, ', ') AS keterangan  -- Concatenates keterangan : subtotal
                    FROM t_salary_borongan_det
                    GROUP BY t_salary_borongan_id) dt
                    ON h.id = dt.t_salary_borongan_id
                where dk.m_kary_id = ? and h.date >= ? and h.date <= ? order by h.date", [$m_kary_id, $date_from, $date_to]);
            
            foreach($penghasilan_borongan as $d){
                $getBasicSalary[] = 
                    [
                        'name' => "gaji_borongan",
                        'type' => $periode['value'],
                        'label' => "Gaji Borongan ($d->label)". ($d->keterangan ? " ($d->keterangan)" : "") ." / $d->tgl",
                        'factor' => "+",
                        'value' => (float)@$d->diterima ?? 0,
                        'can_adjust' => 1
                    ];
            }

            // Merge lemburData into getBasicSalary without modifying getBasicSalary directly
            $mergedSalaries = array_merge($getBasicSalary, $lemburData);

            // Calculate net salary
            $netto = $this->summarySubSalary($mergedSalaries);

            // Create final detail array with total salary item
            $detail = array_merge($mergedSalaries, [
                [
                    'label'  => 'Total Gaji',
                    'factor' => '=',
                    'value'  => $netto,
                    'type'   => '-'
                ]
            ]);

            // Calculate final net salary based on the detail array
            $nettoFinish = $this->summarySubSalary($detail);

            return [
                'm_kary_id'  => $m_kary_id,
                'total_gaji' => $netto,
                'netto'      => $nettoFinish,
                'detail'     => $detail
            ];
        } catch (\Exception $e) {
            return $this->helper->responseCatch($e);
        }
    }

    private function salaryPresensi($karyId, $date_from, $date_to){
        $getUserId = default_users::where('m_kary_id',$karyId)->first()->id ?? 0;
        $getLiburNasional = m_libur_nasional::whereBetween('tanggal', [$date_from, $date_to])->where('is_active', true)->get('tanggal')->toArray();
        $getPresensi = presensi_absensi::where('default_user_id',$getUserId)
        ->where('presensi_absensi.status','ATTEND')
        ->whereRaw("tanggal >= ? and tanggal <= ?",[$date_from, $date_to]);


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
       
         return[
            "user_id" => $getUserId,
            "count" => $count + $shift1Sat,
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
    
        $thursdayIndices = [];
        foreach ($dates as $index => $date) {
            $carbonDate = \Carbon::parse($date);
            if ($carbonDate->isThursday()) {
                $thursdayIndices[] = $index;
            }
        }
    
        $results = [];
        foreach ($thursdayIndices as $thursdayIndex) {
            $startDate = \Carbon::parse($dates[$thursdayIndex]);
            $foundDays = [];
    
            for ($i = 0; $i < 7; $i++) {
                $dayToCheck = $startDate->copy()->addDays($i);
                foreach ($dates as $date) {
                    if ($dayToCheck->isSameDay(\Carbon::parse($date))) {
                        $foundDays[] = $dayToCheck->format('Y-m-d');
                        break;
                    }
                }
            }
    
            $isComplete = count($foundDays) === 7;
            $results[] = [
                'thursdayIndex' => $thursdayIndex,
                'hasThursdayToThursday' => $isComplete,
                'foundDays' => $foundDays
            ];
        }
    
        return $results;
    }    
    

    private function saturday_7_5_fullweek($fullweek, $get75, $saturday){
        $get75Day = array_column($get75,'date');
        $fullweek = $fullweek->pluck('foundDays')->flatten()->toArray();
        $date = array_intersect($get75Day, $fullweek);
        if(!empty($date) && !empty($get75Day)){
            return $saturday;
        }else{
            return 0;
        }
    }
    
}