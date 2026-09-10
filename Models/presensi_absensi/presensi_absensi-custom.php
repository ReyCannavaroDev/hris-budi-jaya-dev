<?php

namespace App\Models\CustomModels;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;

class presensi_absensi extends \App\Models\BasicModels\presensi_absensi
{
    private $helper;
    private $hour;

    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore('Helper');
        $this->hour = 14;
    }

    public $fileColumns = [];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function onRetrieved($model)
    {
        if (!preg_match('/^https?:\/\//i', $model->checkout_foto)) {
        $model->checkout_foto = url('') . '/' . $model->checkout_foto;
        }

        if (!preg_match('/^https?:\/\//i', $model->checkin_foto)) {
            $model->checkin_foto = url('') . '/' . $model->checkin_foto;
        }
    }

    public function custom_get_by_daily_old($req)
    {
        $req->month = $req->month.'-01';
        $weeks = $req->weeks;
        $start_date = '';
        $end_date = '';

        $weeksArr = explode('/', $weeks);
        if(count($weeksArr) > 1){
            $start_date = $weeksArr[0];
            $end_date = $weeksArr[1];
            $data = \DB::select("
                SELECT json_agg(json_build_object(
                    'all_days_of_month', all_days_of_month,
                    'date_to_idn', date_to_idn,
                    'day_name_idn', day_name_idn,
                    'type', type,
                    'presentase', presentase,
                    'attend', attend,
                    'cuti', cuti,
                    'alpha', alpha,
                    'total_kary', total_kary
                )) AS monthly_report
                FROM generate_weekly_report(?,?,?,?)",[$start_date, $end_date,$req->divisi_id,$req->dept_id]);
        }else{
            $data = \DB::select("
                SELECT json_agg(json_build_object(
                    'all_days_of_month', all_days_of_month,
                    'date_to_idn', date_to_idn,
                    'day_name_idn', day_name_idn,
                    'type', type,
                    'presentase', presentase,
                    'attend', attend,
                    'cuti', cuti,
                    'alpha', alpha,
                    'total_kary', total_kary
                )) AS monthly_report
                FROM generate_monthly_report(?,?,?)",[$req->month,$req->divisi_id,$req->dept_id]);
        }

        
        if(count($data)){   
            return $this->helper->customResponse('OK',200,json_decode($data[0]->monthly_report));
        }else{
            return $this->helper->customResponse('OK',200,[]);
        }
    }

    public function custom_get_by_daily($req)
    {
        if ($req->weeks) {
            [$startDate, $endDate] = explode("/", $req->weeks);
        } else {
            $startDate = Carbon::parse($req->month . "-01");
            $endDate = Carbon::parse($req->month . "-03");
        }

        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current->lte($end)) {
            $dates[] = $current->format("Y-m-d");
            $current->addDay();
        }

        $hariLibur = m_general::where("group", "HARI LIBUR")
            ->pluck("value")
            ->toArray();

        $cutiBersama = m_libur_nasional::pluck("tanggal")
            ->map(fn($t) => Carbon::parse($t)->format("Y-m-d"))
            ->toArray();

        $totalKary = default_users::whereHas("m_kary", function ($q) use (
            $req
        ) {
            $q->where("is_active", true);
            if ($req->divisi_id) {
                $q->where("m_divisi_id", $req->divisi_id);
            }
            if ($req->dept_id) {
                $q->where("m_dept_id", $req->dept_id);
            }
        })->count();

        $result = [];

        foreach ($dates as $index => $date) {
            $carbonDate = Carbon::parse($date);
            $dayNameIdn = $carbonDate->translatedFormat("l");
            $dayNameIdn = ucfirst($dayNameIdn);

            if (in_array($dayNameIdn, $hariLibur)) {
                $type = "Hari Libur";
            } elseif (in_array($carbonDate->format("Y-m-d"), $cutiBersama)) {
                $type = "Cuti Bersama";
            } else {
                $type = "Hari Kerja";
            }

            $hadir = default_users::whereHas("m_kary", function ($q1) use (
                $req
            ) {
                $q1->where("is_active", true);
                if ($req->divisi_id) {
                    $q1->where("m_divisi_id", $req->divisi_id);
                }
                if ($req->dept_id) {
                    $q1->where("m_dept_id", $req->dept_id);
                }
            })
                ->whereHas("presensi_absensi", function ($q) use ($date) {
                    $q->where("tanggal", $date);
                })
                ->count();

            $cuti = t_cuti::where("status", "APPROVED")
                ->whereDate("date_from", ">=", $date)
                ->whereDate("date_to", "<=", $date)
                ->count();

            $alpha = $totalKary - $hadir - $cuti;

            $presentase = $totalKary > 0 ? ($hadir / $totalKary) * 100 : 0;

            $result[] = [
                "all_days_of_month" => $carbonDate->format("Y-m-d"),
                "date_to_idn" => $carbonDate->format("d-m-Y"),
                "day_name_idn" => $dayNameIdn,
                "type" => $type,
                "presentase" => $presentase,
                "attend" => $hadir,
                "cuti" => $cuti,
                "alpha" => $alpha,
                "total_kary" => $totalKary,
            ];
        }

        return $this->helper->customResponse("OK", 200, $result);
    }

    public function custom_get_by_date($req)
    {
        $data = \DB::select(
            "
        SELECT json_agg(json_build_object(
            'm_kary_id', m_kary_id,
            'default_user_id', default_user_id,
            'kode', kode,
            'nama_lengkap', nama_lengkap,
            'dept', dept,
            'absensi', absensi
        )) AS att_report
        FROM get_employee_attendance_report(?,?,?)",
            [$req->date, $req->divisi_id, $req->dept_id]
        );

        if (!count($data)) {
            return $this->helper->customResponse("OK", 200, []);
        }

        $att_report = json_decode($data[0]->att_report);

        $cutiAll = \DB::table("t_cuti")
            ->select("m_kary_id", "date_from", "date_to")
            ->where("status", "APPROVED")
            ->whereDate("date_from", "<=", $req->date)
            ->whereDate("date_to", ">=", $req->date)
            ->get();

        foreach ($att_report as $report) {
            if ($report->absensi->status === "NOT ATTEND") {
                $report->absensi->created_at = "-";
                $report->absensi->updated_at = "-";

                $hasCuti = $cutiAll->contains(function ($c) use ($report) {
                    return $c->m_kary_id == $report->m_kary_id;
                });

                if ($hasCuti) {
                    $report->absensi->status = "CUTI";
                }
            }
        }

        return $this->helper->customResponse("OK", 200, $att_report);
    }

    public function custom_get_by_date_old($req)
    {
        $data = \DB::select("
            SELECT json_agg(json_build_object(
                'm_kary_id', m_kary_id,
                'default_user_id', default_user_id,
                'kode', kode,
                'nama_lengkap', nama_lengkap,
                'dept', dept,
                'absensi', absensi
            )) AS att_report
            FROM get_employee_attendance_report(?,?,?)",[$req->date,$req->divisi_id,$req->dept_id]);

        
        if(count($data)){   
            return $this->helper->customResponse('OK',200,json_decode($data[0]->att_report));
        }else{
            return $this->helper->customResponse('OK',200,[]);
        }
    }

    public function custom_checkin($req)
    {

        $validator = Validator::make($req->all(), [
            "foto" => "required",
            "lat" => "required",
            "long" => "required",
            "address" => "required",
        ]);
        if ($validator->fails()) 
            return $this->helper->responseValidate($validator);

        DB::beginTransaction();
        try {
            $distance = $this->distance($req->lat, $req->long);
            if ($distance) {
                $data["on_scope"] = true;
                $data["region"] = $distance->nama;
                $data["checkin_lat"] = $req->lat;
                $data["checkin_long"] = $req->long;
                $data["checkin_address"] = $req->address;
                $data["catatan_in"] = null;
            } else {
                $data["on_scope"] = false;
                $data["region"] = "Out Scope";
                $data["checkin_lat"] = $req->lat;
                $data["checkin_long"] = $req->long;
                $data["checkin_address"] = $req->address;
                $data["catatan_in"] = $req->catatan_in ?? null;
            }

            //new
            $isShift = $this->custom_check_shift();
            $now = Carbon::now();
            $createdAt = $isShift['created_at'] ?? null;
            $endTime = Carbon::parse($createdAt)->addHours($this->hour)->toDateTimeString();
            if($now->between($createdAt,$endTime) && $isShift){
                $check_exists_absen = $this->where('created_at', '>=', $isShift['created_at'])  
                    ->where('created_at', '<=', $endTime)
                    ->where('default_user_id', auth()->user()->id ?? 0)
                    ->exists();
                if ($check_exists_absen == true) 
                    return $this->helper->customResponse("Anda sudah checkin hari ini", 422);         
            } else {
                $check_exists_absen = $this->where("tanggal", date("Y-m-d"))
                        ->where("default_user_id", auth()->user()->id)
                        ->exists();
                if ($check_exists_absen == true) 
                    return $this->helper->customResponse("Anda sudah checkin hari ini", 422);         
            }
            
            if ($req->hasFile("foto")) {                 
                try {
                    $file = $req->file("foto");
                    $fileName =
                    auth()->user()->username .
                    ":::" .
                    md5(time()) .
                    "." .
                    $file->getClientOriginalExtension();

                    $s3Path = "presensi/$fileName";
                    $uploadSuccess = \Storage::disk("s3")->put($s3Path, file_get_contents($file), "public");

                    if ($uploadSuccess) {
                        $path = env('AWS_ASSET_URL').'/'.$s3Path;
                    } else {
                        throw new \Exception("S3 upload failed");
                    }
                } catch (\Exception $e) {
                    \Log::error("S3 Upload Failed: " . $e->getMessage());

                    $file->move(public_path("uploads/presensi"), $fileName);
                    $path = "uploads/presensi/$fileName";
                }
            } else {
                trigger_error("IMAGE NOT VALID");
            }

            $this->create([
                "tanggal" => date("Y-m-d"),
                "checkin_time" => date("H:i:s"),
                "checkin_foto" => $path,
                "checkin_lat" => $data["checkin_lat"],
                "checkin_long" => $data["checkin_long"],
                "checkin_address" => $data["checkin_address"],
                "checkin_region" => $data["region"],
                "checkin_on_scope" => $data["on_scope"],
                "catatan_in" => $data["catatan_in"],
                "default_user_id" => auth()->user()->id,
                "creator_id" => auth()->user()->id
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->helper->customResponse("Checkin gagal, coba kembali nanti - ".$e->getMessage(), 400);
        }
        return $this->helper->customResponse("Checkin berhasil", 200, $data);
    }

    public function custom_checkout($req)
    {
        $validator = Validator::make($req->all(), [
            "foto" => "required",
            "lat" => "required",
            "long" => "required",
            "address" => "required",
        ]);
        if ($validator->fails()) return $this->helper->responseValidate($validator);

        DB::beginTransaction();
        try {
            $distance = $this->distance($req->lat, $req->long);
            if ($distance) {
                $data["on_scope"] = true;
                $data["region"] = $distance->nama;
                $data["checkout_lat"] = $req->lat;
                $data["checkout_long"] = $req->long;
                $data["checkout_address"] = $req->address;
                $data["catatan_out"] = null;
            } else {
                $data["on_scope"] = false;
                $data["region"] = "Out Scope";
                $data["checkout_lat"] = $req->lat;
                $data["checkout_long"] = $req->long;
                $data["checkout_address"] = $req->address;
                $data["catatan_out"] = $req->catatan_out ?? null;
            }


            //new 
            $isShift = $this->custom_check_shift();
            $now = Carbon::now();
            $createdAt = $isShift['created_at'] ?? null;
            $endTime = Carbon::parse($createdAt)->addHours($this->hour)->toDateTimeString();
            if($now->between($createdAt,$endTime) && $isShift){
                $check_exists_absen = $this->where('created_at', '>=', $isShift['created_at'])  
                    ->where('created_at', '<=', $endTime)
                    ->where("default_user_id", auth()->user()->id)
                    ->where("status", "ATTEND")
                    ->exists();
                if ($check_exists_absen) 
                    return $this->helper->customResponse("Anda sudah checkout hari ini", 422);
                
                $check_not_exists_checkin = $this->where('created_at', '>=', $isShift['created_at'])  
                    ->where('created_at', '<=', $endTime)
                    ->where("default_user_id", auth()->user()->id)
                    ->where("status", "WORKING")->exists();
                if ($check_exists_absen) 
                    return $this->helper->customResponse("Anda belum checkin hari ini", 422);
            } else {
                $check_exists_absen = $this->where("tanggal", date("Y-m-d"))
                    ->where("default_user_id", auth()->user()->id)
                    ->where("status", "ATTEND")
                    ->exists();
                if ($check_exists_absen) 
                    return $this->helper->customResponse("Anda sudah checkout hari ini", 422);
                
                $check_not_exists_checkin = $this->where("tanggal", date("Y-m-d"))
                    ->where("default_user_id", auth()->user()->id)
                    ->where("status", "WORKING")->exists();
                if ($check_exists_absen) 
                    return $this->helper->customResponse("Anda belum checkin hari ini", 422);
            }

            if ($req->hasFile("foto")) {                 
                try {
                    $file = $req->file("foto");
                    $fileName =
                    auth()->user()->username .
                    ":::" .
                    md5(time()) .
                    "." .
                    $file->getClientOriginalExtension();

                    $s3Path = "presensi/$fileName";
                    $uploadSuccess = \Storage::disk("s3")->put($s3Path, file_get_contents($file), "public");

                    if ($uploadSuccess) {
                        $path = env('AWS_ASSET_URL').'/'.$s3Path;
                    } else {
                        throw new \Exception("S3 upload failed");
                    }
                } catch (\Exception $e) {
                    \Log::error("S3 Upload Failed: " . $e->getMessage());

                    $file->move(public_path("uploads/presensi"), $fileName);
                    $path = "uploads/presensi/$fileName";
                }
            } else {
                trigger_error("IMAGE NOT VALID");
            }

            //new
            if($now->between($createdAt,$endTime) && $isShift){
                $getJadwal = $this->getJadwal($isShift['created_at']);

                $isOvertime = $this->isOvertime($isShift['created_at']);

                $this->where('created_at', '>=', $isShift['created_at'])  
                ->where('created_at', '<=', $endTime)
                ->where("default_user_id", auth()->user()->id)
                ->where("status", "WORKING")
                ->update([
                    "checkout_time" => date("H:i:s"),
                    "checkout_foto" => $path,
                    "checkout_lat" => $data["checkout_lat"],
                    "checkout_long" => $data["checkout_long"],
                    "checkout_address" => $data["checkout_address"],
                    "checkout_region" => $data["region"],
                    "checkout_on_scope" => $data["on_scope"],
                    "catatan_out" => $data["catatan_out"],
                    "is_lembur" => $isOvertime['is_lembur'] ?? false,
                    "jam_lembur" => $isOvertime['jam_lembur'] ?? 0,
                    "shift" => $getJadwal['value'] ?? null,
                    "jadwal_kerja_id" => $getJadwal['id'] ?? null,
                    "status" => "ATTEND",
                ]);
            } else {
                $getCheckIn = $this->where("tanggal", date("Y-m-d"))
                ->where("default_user_id", auth()->user()->id)
                ->where("status", "WORKING")->first();

                $getJadwal = $this->getJadwal($getCheckIn['created_at']);

                $isOvertime = $this->isOvertime($getCheckIn['created_at']);

                $this->where("tanggal", date("Y-m-d"))
                ->where("default_user_id", auth()->user()->id)
                ->where("status", "WORKING")
                ->update([
                    "checkout_time" => date("H:i:s"),
                    "checkout_foto" => $path,
                    "checkout_lat" => $data["checkout_lat"],
                    "checkout_long" => $data["checkout_long"],
                    "checkout_address" => $data["checkout_address"],
                    "checkout_region" => $data["region"],
                    "checkout_on_scope" => $data["on_scope"],
                    "catatan_out" => $data["catatan_out"],
                    "is_lembur" => $isOvertime['is_lembur'] ?? false,
                    "jam_lembur" => $isOvertime['jam_lembur'] ?? 0,
                    "shift" => $getJadwal['value'] ?? null,
                    "jadwal_kerja_id" => $getJadwal['id'] ?? null,
                    "status" => "ATTEND",
                ]);
            }

            DB::commit();
       } catch (\Exception $e) {
            DB::rollback();
            return $this->helper->customResponse("Checkout gagal, coba kembali nanti - ".$e->getMessage(), 422);
        }
        return $this->helper->customResponse("Checkout berhasil", 200, $data);
    }

    private function distance($lat, $long)
    {
        $distance = DB::select("select distance_location(?,?)", [$lat, $long]);
        if (count($distance)) {
            $location = json_decode($distance[0]->distance_location);
            return @$location[0] ?? false;
        } else {
            return false;
        }
    }

    public function custom_distance_check($req)
    {
        $distance = $this->distance($req->lat, $req->long);
        if ($distance) {
            $data["on_scope"] = true;
            $data["region"] = $distance->nama;
            $data["lat"] = $distance->lat;
            $data["long"] = $distance->long;
            $data["address"] = $req->address;
            $data["office"] = $distance->nama;
        } else {
            $data["on_scope"] = false;
            $data["region"] = "Out Scope";
            $data["lat"] = $req->lat;
            $data["long"] = $req->long;
            $data["address"] = $req->address;
            $data["office"] = null;
        }
        return $this->helper->customResponse("OK", 200, $data);
    }

    public function custom_status($model)
    {
        
        $isShift = $this->custom_check_shift();
        $now = Carbon::now();
        $createdAt = $isShift['created_at'] ?? null;
        $endTime = Carbon::parse($createdAt)->addHours($this->hour)->toDateTimeString();
        if($now->between($createdAt,$endTime) && $isShift){
            $data = [
                'status' => $this->where('created_at', '>=', $isShift['created_at'])  
                ->where('created_at', '<=', $endTime)
                ->where('default_user_id', auth()->user()->id ?? 0)
                ->pluck('status')
                ->first() ?? 'NOT ATTEND'
            ];
        } else {
            $data = [
                    'status' => $this->where("tanggal", date("Y-m-d"))->where('default_user_id', auth()->user()->id ?? 0)->pluck('status')->first() ?? 'NOT ATTEND'
                ]; 
        }       
        return $this->helper->customResponse("OK", 200, $data);
    }

    public function custom_status_shift()
    {
        $isShift = $this->custom_check_shift();
        $now = Carbon::now();
        $createdAt = $isShift['created_at'] ?? null;
        $endTime = Carbon::parse($createdAt)->addHours($this->hour)->toDateTimeString();
        if($now->between($createdAt,$endTime) && $isShift){
            $data = [
                'status' => $this->where('created_at', '>=', $isShift['created_at'])  
                ->where('created_at', '<=', $endTime)
                ->where('default_user_id', auth()->user()->id ?? 0)
                ->pluck('status')
                ->first() ?? 'NOT ATTEND'
            ];
        } else {
            $data = [
                    'status' => $this->where("tanggal", date("Y-m-d"))->where('default_user_id', auth()->user()->id ?? 0)->pluck('status')->first() ?? 'NOT ATTEND'
                ]; 
        }       
        return $this->helper->customResponse("OK", 200, $data);
    }

    //jika terikat dengan jadwal
    public function custom_status_jadwal_kerja(){
        $karyId = auth()->user()->m_kary_id;
        $getTodayNum = Carbon::today()->dayOfWeek;
        $startOfWeek = Carbon::today()->startOfWeek();
        $endOfweek = Carbon::today()->endOfweek();
        $now = Carbon::now();

        $t_jadwal_kerja_det = t_jadwal_kerja_det::where('m_kary_id',$karyId)->whereHas('t_jadwal_kerja', function($query){
            $query->where('status','POSTED');
        })->whereHas('t_jadwal_kerja_det_hari',function($query) {
            $query->whereIn('day_num',range(1,7));
        })->with(['t_jadwal_kerja_det_hari.m_jam_kerja' => function($select){
            $select->select('id','m_jam_kerja.is_hari_berikutnya');
        }])->with(['t_jadwal_kerja' => function($select){
            $select->select('id','keterangan');
        }])->get([
            'id','m_kary_id','t_jadwal_kerja_det_hari_id','t_jadwal_kerja_id'
        ]);

        if($t_jadwal_kerja_det->isEmpty()){
            trigger_error('Jadwal Belum Diset');
        }

        $t_jadwal_kerja_det = $t_jadwal_kerja_det->transform(function ($item){
            $tanggal = Carbon::today()->startOfWeek()->addDays($item->t_jadwal_kerja_det_hari->day_num - 1)->toDateString();
            $item->t_jadwal_kerja_det_hari->tanggal = $tanggal;
            $waktu_mulai = $item->t_jadwal_kerja_det_hari->waktu_mulai;
            $waktu_akhir = $item->t_jadwal_kerja_det_hari->waktu_akhir;
            $item->start_work = Carbon::parse("$tanggal $waktu_mulai")->subHours(2)->toDateTimeString();
             if (isset($item->t_jadwal_kerja_det_hari->m_jam_kerja) && $item->t_jadwal_kerja_det_hari->m_jam_kerja->is_hari_berikutnya) {
                    $item->end_work = Carbon::parse("$tanggal $waktu_akhir")->addDay()->addHours(2)->toDateTimeString();
                } else {
                    $item->end_work = Carbon::parse("$tanggal $waktu_akhir")->toDateTimeString();
                }
            return $item;
        });

        $count = $t_jadwal_kerja_det->count() - 1;

        $startDayBeforeWeekend = $t_jadwal_kerja_det[$count]['start_work'];
        $EndDayBeforeWeekend = $t_jadwal_kerja_det[$count]['end_work'];

        $day_before = [
        "start_work" => Carbon::parse($startDayBeforeWeekend)->subWeek()->toDateTimeString(),
        "end_work" => Carbon::parse($EndDayBeforeWeekend)->subWeek()->toDateTimeString(),
        ];

        $t_jadwal_kerja_det->prepend($day_before);

        $getData = $t_jadwal_kerja_det->filter(function ($item) use ($now) {
                return $now->between(Carbon::parse($item['start_work']), Carbon::parse($item['end_work']));
        })->first();
        
        if($getData){
            $data = [
                'status' => "WORKING HOURS",
                'start_work' => $getData['start_work'],
                'end_work' => $getData['end_work'],
                'day' => $getData['t_jadwal_kerja_det_hari']['day'],
                'jadwal_kerja' => $getData['t_jadwal_kerja'],
            ];
        }else{
            $data = [
                'status' => "NOT WORKING HOURS" 
            ];
        }
        return $data;
    }


    public function scopeFilter($model)
    {
        if(req('date_from') && req('date_to')){
            return $model->whereBetween('tanggal',[req('date_from'),req('date_to')])->where('default_user_id', auth()->user()->id ?? 0);
        }
    }

    public function custom_get_absen($req)
    {
        $periode = ($req->periode ?? date('Y-m')).'-1';
        $m_kary_id = auth()->user()->m_kary_id;

        $data = \DB::select("
            select * from employee_attendance_detail(?, ?);
        ", [$periode,$m_kary_id ?? 0]);

        // transform object for mobile
        foreach($data as $dt){
            $dt->status = @json_decode($dt->absensi)->status ?? null;
            $dt->tanggal = @json_decode($dt->absensi)->tanggal ?? null;
            $dt->catatan_in = @json_decode($dt->absensi)->catatan_in ?? null;
            $dt->catatan_out = @json_decode($dt->absensi)->catatan_out ?? null;
            $dt->checkin_lat = @json_decode($dt->absensi)->checkin_lat ?? null;
            $dt->checkin_foto = ($inPic=@json_decode($dt->absensi)->checkin_foto) ? (str_contains($inPic,'http')?$inPic:url($inPic)) : null;
            $dt->checkin_long = @json_decode($dt->absensi)->checkin_long ?? null;
            $dt->checkin_time = @json_decode($dt->absensi)->checkin_time ?? null;
            $dt->checkout_lat = @json_decode($dt->absensi)->checkout_lat ?? null;
            $dt->checkout_foto = ($outPic=@json_decode($dt->absensi)->checkout_foto) ? (str_contains($outPic,'http')?$outPic:url($outPic)) : null;
            $dt->checkout_long = @json_decode($dt->absensi)->checkout_long ?? null;
            $dt->checkout_time = @json_decode($dt->absensi)->checkout_time ?? null;
            $dt->checkin_region = @json_decode($dt->absensi)->checkin_region ?? null;
            $dt->checkin_address = @json_decode($dt->absensi)->checkin_address ?? null;
            $dt->checkout_region = @json_decode($dt->absensi)->checkout_region ?? null;
            $dt->checkin_on_scope = @json_decode($dt->absensi)->checkin_on_scope ?? null;
            $dt->checkout_address = @json_decode($dt->absensi)->checkout_address ?? null;
            $dt->checkout_on_scope = @json_decode($dt->absensi)->checkout_on_scope ?? null;
            $dt->presensi_absensi_id = @json_decode($dt->absensi)->presensi_absensi_id ?? null;
        }

        return $this->helper->customResponse("OK", 200, $data);
    }


    public function scopeGetOvertime($model){
        try{
            $id = @request('id_lembur');
            if($id){
                $data =  $model->where('is_lembur', true)->where('presensi_absensi.id', $id);
            }else{
                $data =  $model->where('is_lembur', true);
            }
            return $data;
        }catch(\Exception $e){
            trigger_error($e->getMessage());
        }
    }

    // public function scopeGetOvertimeDetail(){
    //     try{
    //         $id = request('id');
    //         $data =  $model->where('is_lembur', true)->where('id',$id);
    //         return $data;
    //     }catch(\Exception $e){
    //         trigger_error($e->getMessage());
    //     }
    // }

    public function getJadwal($data){
        $checkIn = $data;
        
        Carbon::setLocale('id');
        $auth = auth()->user()->m_kary_id;
        $value = m_kary::where('m_kary.id',$auth)
        ->join('m_general as tj', 'tj.id', 'm_kary.tipe_jam_kerja_id')
        ->select('m_kary.*','tj.value')
        ->first()->value ?? "null";

        $checkInTime = Carbon::parse($checkIn);
        $startRange = $checkInTime->copy()->subHours(3)->toDateTimeString();
        $jadwalKerja = t_jadwal_kerja::where('status','POSTED')
        ->join('t_jadwal_kerja_det_hari as hari','t_jadwal_kerja.id','hari.t_jadwal_kerja_id')
        ->join('m_general','tipe_jam_kerja_id','m_general.id')
        ->select('t_jadwal_kerja.id','m_general.value','hari.day_num','hari.waktu_mulai','hari.id as detail_id','hari.day')
        ->where('value','like','%'.$value.'%');
        
        //sementara
        // if(strpos($value,"OFFICE") !== false){
        //     $jadwalKerja->where('value','like','%ecurity%');
        // } else if (strpos($value, "PROD 1") !== false) {
        //     $jadwalKerja->where('value','like','%Shift 1%')->whereNot('value','like','%ecurity%');
        // }else {
        //     $jadwalKerja->whereNot('value','like','%ecurity%')->whereNot('value','like','%Shift 1%');
        // }

        $jadwalKerja = $jadwalKerja->get();

        $jadwalKerjaTransform = $jadwalKerja->transform(function($item) use($checkInTime){
            $time = $item['waktu_mulai'];
            $tanggal = Carbon::today()->startOfWeek()->addDays($item['day_num'] - 1)->toDateString();
            if($time == "00:00:00"){
                $item['waktu_mulai_parse'] = Carbon::parse($tanggal.''.$time)->subHour()->toDateTimeString();
            } else {
                $item['waktu_mulai_parse'] = Carbon::parse($tanggal.''.$time)->toDateTimeString();
            }
            return $item;
        });
        
        $jadwalKerjaFilter = $jadwalKerjaTransform->filter(function($item) use($startRange){
            $startTime = Carbon::parse($startRange);
            $endTime = $startTime->copy()->addHours(8);
            $parseTimeBetween = Carbon::parse($item['waktu_mulai_parse']);
            return $parseTimeBetween->between($startRange,$endTime);
        });

        return $jadwalKerjaFilter->values()[0] ?? null;
    }

    public function isOvertime($data) { 
        
        $checkIn = \Carbon::parse($data);
        $getData = presensi_absensi::where('default_user_id',auth()->user()->id)->where('tanggal', $checkIn)->first();
        
        if(!$getData){
            return null;
        }
        $startTime = Carbon::parse($getData['tanggal'].' '.$getData['checkin_time']);
        $endTime = \Carbon::now();
        
        //hari minggu
        Carbon::setLocale('id');
        $hariLibur = m_general::where('group','HARI LIBUR')->where('code',"0001")->first()->value ;
        $day = Carbon::createFromFormat("Y-m-d", $getData['tanggal'])->translatedFormat('l');
        if($day == $hariLibur){
            $interval = $endTime->diff($startTime);
            $intervalInt = $interval->format('%h');
            return [
                "is_lembur" => true,
                "jam_lembur" => $intervalInt
            ];
        }

        //hari libur nasional
        $startDate = $startTime->toDateString();
        $getHariLibur = m_libur_nasional::where('tanggal',$startDate)->first();
        if($getHariLibur){
            $interval = $endTime->diff($startTime);
            $intervalInt = $interval->format('%h');
            return [
                "is_lembur" => true,
                "jam_lembur" => $intervalInt
            ];
        }

        //jam kerja lebih dari 7 jam
        $interval = $endTime->copy()->diff($startTime);
        $intervalIntJam = $interval->format('%h');
        $jamLembur = m_general::where('group','JAM_LEMBUR')->where('key',"1")->first()->value ?? 0;
        if($intervalIntJam > $jamLembur){
            $intervalInt = $intervalIntJam - $jamLembur;
            return [
                "is_lembur" => true,
                "jam_lembur" => $intervalInt
            ];
        }

        return [
                "is_lembur" => false,
                "jam_lembur" => 0
            ];
    }


    // public function custom_getJadwal(){
    //     $checkIn = "2024-11-04 07:56:36";
        
    //     Carbon::setLocale('id');
    //     $auth = auth()->user()->m_kary_id;
    //     $value = m_kary::where('m_kary.id',$auth)
    //     ->join('m_general as tj', 'tj.id', 'm_kary.tipe_jam_kerja_id')
    //     ->select('m_kary.*','tj.value')
    //     ->first()->value ?? "null";

    //     $checkInTime = Carbon::parse($checkIn);
    //     $startRange = $checkInTime->copy()->subHours(3)->toDateTimeString();
    //     $jadwalKerja = t_jadwal_kerja::where('status','POSTED')
    //     ->join('t_jadwal_kerja_det_hari as hari','t_jadwal_kerja.id','hari.t_jadwal_kerja_id')
    //     ->join('m_general','tipe_jam_kerja_id','m_general.id')
    //     ->select('t_jadwal_kerja.id','m_general.value','hari.day_num','hari.waktu_mulai','hari.id as detail_id','hari.day')
    //     ->where('value','like','%'.$value.'%');
        
    //     //sementara
    //     // if(strpos($value,"OFFICE") !== false){
    //     //     $jadwalKerja->where('value','like','%ecurity%');
    //     // } else if (strpos($value, "PROD 1") !== false) {
    //     //     $jadwalKerja->where('value','like','%Shift 1%')->whereNot('value','like','%ecurity%');
    //     // }else {
    //     //     $jadwalKerja->whereNot('value','like','%ecurity%')->whereNot('value','like','%Shift 1%');
    //     // }

    //     $jadwalKerja = $jadwalKerja->get();

    //     $jadwalKerjaTransform = $jadwalKerja->transform(function($item) use($checkInTime){
    //         $time = $item['waktu_mulai'];
    //         $tanggal = Carbon::today()->startOfWeek()->addDays($item['day_num'] - 1)->toDateString();
    //         if($time == "00:00:00"){
    //             $item['waktu_mulai_parse'] = Carbon::parse($tanggal.''.$time)->subHour()->toDateTimeString();
    //         } else {
    //             $item['waktu_mulai_parse'] = Carbon::parse($tanggal.''.$time)->toDateTimeString();
    //         }
    //         return $item;
    //     });
        
    //     $jadwalKerjaFilter = $jadwalKerjaTransform->filter(function($item) use($startRange){
    //         $startTime = Carbon::parse($startRange);
    //         $endTime = $startTime->copy()->addHours(8);
    //         $parseTimeBetween = Carbon::parse($item['waktu_mulai_parse']);
    //         return $parseTimeBetween->between($startRange,$endTime);
    //     });

    //     return $jadwalKerjaFilter->values()[0] ?? null;
    // }

    private function checkYesterdayPresence($shift){
        $now = Carbon::now();
        $yesterday = $now->copy()->subDay()->toDateString();
        foreach($shift as $index => $single){
            $shiftParse = [];
            if($single[0] > $single[1]) {
                $shiftParse[] = Carbon::parse($yesterday.' '.$single[0])->toDateTimeString();
                $shiftParse[] = Carbon::parse($now->copy()->toDateString().''.$single[1])->toDateTimeString();
            } else {
                foreach($single as $time){             
                    $shiftParse[] = Carbon::parse($yesterday.' '.$time)->toDateTimeString();                
                }
            }
            
            $checkPresence = $this->whereBetween('created_at', $shiftParse)->where('default_user_id', auth()->user()->id ?? 0)->orderBy('tanggal', 'desc')->whereIn('status', ['WORKING','ATTEND'])->first() ?? null;
            if($checkPresence){
                return $checkPresence;
            }
        }
        if(!$checkPresence) return null;
    }

    public function custom_check_shift() {

        //set digeneral
        $karyId = auth()->user()->m_kary_id;
        $now = \Carbon::now();
        $setRangeCheckInTime = [
            'shift2' => ["17:00:00", "19:00:00"],
        ];
        
        $checkYesterdayPresence = $this->checkYesterdayPresence($setRangeCheckInTime);
        if($checkYesterdayPresence) return $checkYesterdayPresence;
        return null;

    }

    public function custom_salaryPresensi($req){
        $getUserId = default_users::where('m_kary_id',$req['kary_id'])->first()->id ?? 0;
        $getLiburNasional = m_libur_nasional::whereBetween('tanggal', [$req['date_from'], $req['date_to']])->where('is_active', true)->get('tanggal')->toArray();
        $getPresensi = presensi_absensi::where('default_user_id',$getUserId)
        ->where('presensi_absensi.status','ATTEND')
        ->whereRaw("tanggal >= ? and tanggal <= ?",[$req['date_from'], $req['date_to']]);

        $liburNasional = clone $getPresensi;
        $collectLiburNasional = collect($getLiburNasional)->pluck('tanggal')->map(fn ($date) => \Carbon::parse($date)->toDateString());
        $liburNasional = collect($liburNasional->get('tanggal'))->pluck('tanggal')->map(fn ($date)=> \Carbon::parse($date)->toDateString());
        $totalLiburNasional = $collectLiburNasional->intersect($liburNasional);
        $countLiburNasionalCheckIn = $totalLiburNasional->count();
        $liburNasionalCount = $collectLiburNasional->count();
        $countLiburNasionalNotCheckin = $liburNasionalCount - $countLiburNasionalCheckIn;

        // $shift = clone $getPresensi;
        // $shift = $shift->whereRaw("shift ~* ?", ['shift [23]'])->count();
        
        $count = clone $getPresensi;
        $count = $count->whereRaw("EXTRACT(DOW FROM tanggal) != 0")->count();
        
        // $night = clone $getPresensi;
        // $night = $night->where('checkin_time','<=','12:00:00')->where(function($query) {
        //         $query->whereRaw("checkout_time >= ?", ["19:00:00"])
        //             ->orWhereRaw("checkout_time <= ?", ["05:00:00"]);
        //         })->count();

        // $shift1Sat = clone $getPresensi;
        // $shift1Sat = $shift1Sat->whereRaw("EXTRACT(DOW FROM tanggal) = 6")->whereRaw("shift ~* ?", ['shift [1]'])->count();

        $startDate = \Carbon::parse($req['date_from']);
        $endDate = \Carbon::parse($req['date_to']);
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
        $getFullWeekThursday = $this->isFullWeekThursday($checkInArray);


        $saturday = clone $getPresensi;
        $saturday = $saturday->whereRaw("EXTRACT(DOW FROM tanggal) = 6")->count();

        $saturday75 = clone $getPresensi;
        $saturday75 = $saturday75->whereRaw("EXTRACT(DOW FROM tanggal) = 6")->get();
        $get75 = $this->is75($saturday75);

        $collectFullweek = collect($getFullWeek)->where('hasMondayToSaturday', true);
        $collectFullweek = collect($getFullWeekThursday)->where('hasThursdayToThursday', true);

        $saturday_7_5_fullweek = $this->saturday_7_5_fullweek($collectFullweek, $get75, $saturday);
       
         return[
            "user_id" => $getUserId,
            "count" => $count,
            // "night" => $night,
            // "shift" => $shift,
            // "shift1_sat" => $shift1Sat,
            "sunday" => $sunday,
            "sunday_not_checkin" => $sundayNotCheckin,
            "saturday" => $saturday,
            "libur_nasional_checkin" => $countLiburNasionalCheckIn,
            "libur_nasional_not_checkin" => $countLiburNasionalNotCheckin,
            "saturday_7_5" => $get75,
            "getFullWeekMondayToSaturday" => $getFullWeek,
            "getFullWeekThursday" => $getFullWeekThursday,
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

        $mondayIndices = [];
        foreach ($dates as $index => $date) {
            $carbonDate = Carbon::parse($date);
            if ($carbonDate->isMonday()) {
                $mondayIndices[] = $index;
            }
        }


        $results = [];
        foreach ($mondayIndices as $mondayIndex) {
            $startDate = Carbon::parse($dates[$mondayIndex]);
            $foundDays = [];

            for ($i = 0; $i < 6; $i++) {
                $dayToCheck = $startDate->copy()->addDays($i); 
                foreach ($dates as $date) {
                    if ($dayToCheck->isSameDay(Carbon::parse($date))) {
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

    private function saturday_7_5_fullweek($fullweek, $get75, $saturday){
        $get75Day = array_column($get75, 'date');
        $fullweek = $fullweek->pluck('foundDays')->flatten()->toArray();
        $date = array_intersect($get75Day, $fullweek);
        if (!empty($date)) {
            return $saturday;
        }
        return 0;
    }

    // public function custom_s3(){
    //     try {
    //         $files = \Storage::disk('s3')->files();
    //         return response()->json(['message' => 'Connected to S3', 'files' => $files]);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'S3 connection failed', 'message' => $e->getMessage()], 500);
    //     }
    // }

    // public function custom_distance($req)
    // {
    //     $distance = DB::select("select distance_location(?,?)", [$req['lat'], $req['long']]);
    //     if (count($distance)) {
    //         $location = json_decode($distance[0]->distance_location);
    //         @$location[0] ?? false;
    //         return response()->json($location);            
    //     } else {
    //         return false;
    //     }
    // }

    private function isFullWeekThursday($dates){
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

    
}
