<?php

namespace App\Models\CustomModels;

class t_kary_salary extends \App\Models\BasicModels\t_kary_salary
{    
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createAfter( $model, $arrayData, $metaData, $id=null )
    {        
        $this->where('m_kary_id', $arrayData['m_kary_id'])->where('id', '!=', $id)->update([
            'is_active' => false
        ]);
    }
    

    public function scopeWithDetail(){
        return $this->with('t_kary_salary_det');
    }

    public function custom_kary($req)
    {
        $data = $this->where('m_kary_id', $req->m_kary_id)->first();
        if($data) {
            $data->t_kary_salary_det = t_kary_salary_det::where('t_kary_salary_id', $data->id)->orderBy('id','asc')->get();
        }
        return $this->helper->customResponse('OK', 200, $data);
    }

    public function custom_get_grading_treatment($grade){
        $grade = isset($grade['grade']) ? $grade['grade'] : $grade;

        $treatment = [
                "1A" => [
                    [
                        "name" => "uang makan",
                        "type" => "field",
                        "value" => 0,
                        "factor" => "+",
                        "day" => null,
                        "big_event" => false,
                        "full_week" => false,
                        "is_7_5" => false,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => false,
                        "not_checkin" => false,
                        "is_active" => true
                    ],
                    [
                        "name" => "uang lembur",
                        "type" => "field",
                        "value" => 0,
                        "factor" => "+",
                        "day" => null,
                        "big_event" => false,
                        "full_week" => false,
                        "is_7_5" => false,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => false,
                        "not_checkin" => false,
                        "is_active" => true
                    ],
                    [
                        "name" => "uang transport",
                        "type" => "field",
                        "value" => 0,
                        "factor" => "+",
                        "day" => null,
                        "big_event" => false,
                        "full_week" => false,
                        "is_7_5" => false,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => false,
                        "not_checkin" => false,
                        "is_active" => true
                    ],
                    [
                        "name" => "uang bulanan",
                        "type" => "field",
                        "value" => 0,
                        "factor" => "+",
                        "day" => null,
                        "big_event" => false,
                        "full_week" => false,
                        "is_7_5" => false,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => true,
                        "not_checkin" => false,
                        "is_active" => true
                    ],
                    [
                        "name" => "tanggal merah masuk (hari besar) gaji 3x",
                        "type" => "multiplication",
                        "value" => 3,
                        "factor" => "+",
                        "day" => null,
                        "big_event" => true,
                        "full_week" => false,
                        "is_7_5" => false,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => false,
                        "not_checkin" => false,
                        "is_active" => true
                    ],
                    [
                        "name" => "tanggal merah masuk tidak masuk gaji 1x",
                        "type" => "multiplication",
                        "value" => 1,
                        "factor" => "+",
                        "day" => null,
                        "big_event" => true,
                        "full_week" => false,
                        "is_7_5" => false,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => false,
                        "not_checkin" => true,
                        "is_active" => true
                    ],
                    [
                        "name" => "minggu masuk gaji 2x",
                        "type" => "multiplication",
                        "value" => 2,
                        "factor" => "+",
                        "day" => 0,
                        "big_event" => false,
                        "full_week" => false,
                        "is_7_5" => false,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => false,
                        "not_checkin" => false,
                        "is_active" => true
                    ],
                    [
                        "name" => "Sabtu Pulang 7-5, tambahan 1.5x, 1 minggu full",
                        "type" => "multiplication",
                        "value" => 1.5,
                        "factor" => "+",
                        "day" => 6,
                        "big_event" => false,
                        "full_week" => true,
                        "is_7_5" => true,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => false,
                        "not_checkin" => false,
                        "is_active" => true
                    ],
                    [
                        "name" => "Sabtu pulang 7-5, tambahan 1x, 1 minggu gak full",
                        "type" => "multiplication",
                        "value" => 1,
                        "factor" => "+",
                        "day" => 6,
                        "big_event" => false,
                        "full_week" => false,
                        "is_7_5" => true,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => false,
                        "not_checkin" => false,
                        "is_active" => true
                    ],
                    [
                        "name" => "potongan bpjs ketenagakerjaan",
                        "type" => "percentage",
                        "value" => 2,
                        "factor" => "-",
                        "day" => null,
                        "big_event" => false,
                        "full_week" => false,
                        "is_7_5" => false,
                        "need_overtime" => false,
                        "value_overtime" => 0,
                        "is_month" => true,
                        "not_checkin" => false,
                        "is_active" => true
                    ],
                    // [
                    //     "name" => "potongan bpjs kesehatan",
                    //     "type" => "field",
                    //     "value" => 0,
                    //     "factor" => "-",
                    //     "day" => null,
                    //     "big_event" => false,
                    //     "full_week" => false,
                    //     "is_7_5" => false,
                    //     "need_overtime" => false,
                    //     "value_overtime" => 0,
                    //     "is_month" => true,
                    //     "not_checkin" => false,
                    //     "is_active" => true
                    // ]
                ],
                "1B" => [
                    ["name" => "uang makan", "type" => "field", "value" => null],
                    ["name" => "uang lembur", "type" => "percentage", "value" => 20],
                    ["name" => "uang transport", "type" => "field", "value" => null],
                    ["name" => "uang bulanan", "type" => "field", "value" => null],
                    ["name" => "tanggal merah masuk (Hari Besar) gaji 2x", "type" => "percentage", "value" => 200],
                    ["name" => "minggu masuk gaji 2x", "type" => "percentage", "value" => 150],
                    ["name" => "sabtu pulang 7-5, tambahan 1.5x, 1 minggu full", "type" => "percentage", "value" => 120],
                    ["name" => "sabtu pulang 7-5, tambahan 1x, 1 minggu gak full", "type" => "percentage", "value" => 90],
                    ["name" => "potongan bpjs ketenagakerjaan", "type" => "percentage", "value" => 10],
                    ["name" => "potongan bpjs kesehatan", "type" => "percentage", "value" => 5]
                ],
                "1C" => [
                    ["name" => "uang makan", "type" => "value", "value" => 1],
                    ["name" => "uang lembur", "type" => "percentage", "value" => 10],
                    ["name" => "uang transport", "type" => "value", "value" => 1],
                    ["name" => "uang bulanan", "type" => "value", "value" => 1],
                    ["name" => "tanggal merah masuk (Hari Besar) gaji 1.5x", "type" => "percentage", "value" => 150],
                    ["name" => "minggu masuk gaji 1.5x", "type" => "percentage", "value" => 100],
                    ["name" => "sabtu pulang 7-5, tambahan 1.5x, 1 minggu full", "type" => "percentage", "value" => 100],
                    ["name" => "sabtu pulang 7-5, tambahan 1x, 1 minggu gak full", "type" => "percentage", "value" => 75],
                    ["name" => "potongan bpjs ketenagakerjaan", "type" => "percentage", "value" => 10],
                    ["name" => "potongan bpjs kesehatan", "type" => "percentage", "value" => 5]
                ],
                "1D" => [
                    ["name" => "uang makan", "type" => "value", "value" => 1],
                    ["name" => "uang lembur", "type" => "percentage", "value" => 5],
                    ["name" => "uang transport", "type" => "value", "value" => 1],
                    ["name" => "uang bulanan", "type" => "value", "value" => 1],
                    ["name" => "tanggal merah masuk (Hari Besar) gaji 1x", "type" => "percentage", "value" => 100],
                    ["name" => "minggu masuk gaji 1x", "type" => "percentage", "value" => 50],
                    ["name" => "sabtu pulang 7-5, tambahan 1.5x, 1 minggu full", "type" => "percentage", "value" => 80],
                    ["name" => "sabtu pulang 7-5, tambahan 1x, 1 minggu gak full", "type" => "percentage", "value" => 60]
                ],
                "2A" => [
                    ["name" => "uang makan, kalau lembur 4 jam", "type" => "value", "value" => 1],
                    ["name" => "uang kehadiran, kalau masuk 7 hari full", "type" => "value", "value" => 1],
                    ["name" => "uang transport", "type" => "value", "value" => 1],
                    ["name" => "uang bulanan", "type" => "value", "value" => 1],
                    ["name" => "potongan bpjs ketenagakerjaan", "type" => "percentage", "value" => 10],
                    ["name" => "potongan bpjs kesehatan", "type" => "percentage", "value" => 5]
                ],
                "2B" => [
                    ["name" => "uang makan, kalau lembur 4 jam", "type" => "value", "value" => 1],
                    ["name" => "uang kehadiran, kalau masuk 7 hari full", "type" => "value", "value" => 1],
                    ["name" => "uang transport", "type" => "value", "value" => 1],
                    ["name" => "potongan bpjs ketenagakerjaan", "type" => "percentage", "value" => 10],
                    ["name" => "potongan bpjs kesehatan", "type" => "percentage", "value" => 5]
                ],
                "2C" => [
                    ["name" => "uang makan, kalau lembur 4 jam", "type" => "value", "value" => 1],
                    ["name" => "uang kehadiran, kalau masuk 7 hari full", "type" => "value", "value" => 1],
                    ["name" => "uang transport", "type" => "value", "value" => 1]
                ],
                "3A" => [
                    ["name" => "uang makan", "type" => "value", "value" => 1],
                    ["name" => "uang kehadiran", "type" => "value", "value" => 1],
                    ["name" => "uang bulanan", "type" => "value", "value" => 1],
                    ["name" => "potongan bpjs ketenagakerjaan", "type" => "percentage", "value" => 10],
                    ["name" => "potongan bpjs kesehatan", "type" => "percentage", "value" => 5]
                ],
                "3B" => [
                    ["name" => "uang makan, kalau lembur 4 jam", "type" => "value", "value" => 1],
                    ["name" => "uang kehadiran, kalau masuk 7 hari full", "type" => "value", "value" => 1],
                    ["name" => "potongan bpjs ketenagakerjaan", "type" => "percentage", "value" => 10],
                    ["name" => "potongan bpjs kesehatan", "type" => "percentage", "value" => 5]
                ],
                "3C" => [
                    ["name" => "uang makan, kalau lembur 4 jam", "type" => "value", "value" => 1],
                    ["name" => "uang kehadiran, kalau masuk 7 hari full", "type" => "value", "value" => 1]
                ],
        ];

        if (array_key_exists((string)$grade, $treatment)) {
            return $data =[
                "status" => "success",
                "grade" => $grade,
                "treatments" => $treatment[$grade],
            ];
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Grade Belum Ditambahkan",
            ], 404);
        }
        // $listTreatment = collect($data['treatments']);
        // return $listTreatment->where('full_week', true)->values();
    }

    public function custom_employee_attendance_detail($req){
        $periode = $req->periode;
        $id =$req->kary_id;
        $data = \DB::select("
        select * from employee_attendance_detail(?,?)
        ",[$periode, $id]);

        return $kary_id = @json_encode(@$data[0]) ?? 0;
    }

    public function custom_employee_attendance($req){
        $kary_id = $req->kary_id;
        $periode = $req->periode;

        $rekap = \DB::select("
    select 
      employee_attendance(?,k.id, ?) absen,
      (select   
        TO_CHAR(INTERVAL '1 second' * AVG(EXTRACT(EPOCH FROM pa.checkin_time::TIME)), 'HH24:MI:SS')
        from presensi_absensi pa where pa.default_user_id = u.id and pa.checkin_time is not null and to_char(pa.tanggal,'mm') = '11')  checkin_avg,
      (select   
        TO_CHAR(INTERVAL '1 second' * AVG(EXTRACT(EPOCH FROM pa.checkout_time::TIME)), 'HH24:MI:SS')
        from presensi_absensi pa where pa.default_user_id = u.id and pa.checkout_time is not null and to_char(pa.tanggal,'mm') = '11') checkout_avg,
      k.id, kode, nama_lengkap, d.nama dept 
    from m_kary k
    join default_users u on u.m_kary_id = k.id
    join m_dept d on d.id = k.m_dept_id
        where k.is_active = true 
        and k.m_dept_id IS NOT NULL and k.m_dept_id != 0
        and k.id = COALESCE(?, k.id)
        ",[ $periode, date('Y-m-d'), $kary_id ]);

        return json_encode($rekap);
    }

    public function custom_save($req){
        try{
            \DB::beginTransaction();

            $header = $this->where('m_kary_id',$req->m_kary_id)->with('t_kary_salary_det')->get();
            foreach($header as $singleHeader){
                foreach($singleHeader->t_kary_salary_det as $single){
                    $single->delete();
                }
                $singleHeader->delete();
            }

            $id = $this->create([
                'tipe' => $req['tipe'],
                'is_active' => $req['is_active'],
                'direktorat' => $req['direktorat'],
                'total' => $req['total'],
                'm_kary_id' => $req['m_kary_id'],
                'keterangan' => $req['keterangan'],
            ]);

            $getData = t_kary_salary_det::where('t_kary_salary_id',$id->id)->delete();

            foreach($req->t_kary_salary_det as $singleDetail){
                t_kary_salary_det::create([
                    't_kary_salary_id' => $id->id,
                    'nominal' => $singleDetail['nominal'],
                    'keterangan' => $singleDetail['keterangan']
                ]);
            }

            \DB::commit();

            return response()->json([
                'message' => "success",
                'error' => false
            ]);

        }catch (Exception $e){
            \DB::rollBack();
            
            return response()->json([
                'message' => $e->getMessage(),
                'error' => true

            ]);
        }
    }
}