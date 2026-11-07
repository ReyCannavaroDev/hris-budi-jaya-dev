<?php

namespace App\Models\CustomModels;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class t_cuti extends \App\Models\BasicModels\t_cuti
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = [
        'attachment'
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function transformRowData( array $row )
    {
        $approval = generate_approval_log::where('trx_table', 't_cuti')->where('trx_id', $row['id']) ->orderBy('created_at', 'desc')->value('action_note');
        return array_merge( $row, [
            'approval_note' => $approval ?? '-'
        ] );
    }

    

    private function hitungHari($from , $to) {
        $dateFrom = Carbon::parse($from);
        $dateTo = Carbon::parse($to);

        $period = CarbonPeriod::create($dateFrom, $dateTo);
        $tanggalMerah = \DB::table('m_libur_nasional')->pluck('tanggal')->toArray();
        $businessDays = 0;

        foreach ($period as $date) {
            if ($date->dayOfWeek != Carbon::SUNDAY && !in_array($date->format('Y-m-d'), $tanggalMerah)) {
                $businessDays++;
            }
        }
        return $businessDays;
    }

    public function custom_hitungHari($req) {
        $from = $req->date_from;
        $to = $req->date_to;
        if(app()->request->header("Source") == "mobile"){
            $dateFrom = Carbon::parse($from);
            $dateTo = Carbon::parse($to);
        }else{
             $dateFrom = Carbon::createFromFormat('d/m/Y', $from);
            $dateTo = Carbon::createFromFormat('d/m/Y', $to);
        }

        // Membuat rentang tanggal antara date_from dan date_to
        $period = CarbonPeriod::create($dateFrom, $dateTo);

        // Menghitung jumlah hari kerja
        $tanggalMerah = \DB::table('m_libur_nasional')->pluck('tanggal')->toArray();
        $businessDays = 0;

        foreach ($period as $date) {
            if ($date->dayOfWeek != Carbon::SUNDAY && !in_array($date->format('Y-m-d'), $tanggalMerah)) {
                $businessDays++;
            }
        }
        
        return $businessDays;
    }

    private function hitungMenit($from, $to) {
        $dateFrom = Carbon::parse($from);
        $dateTo = Carbon::parse($to);

        // Calculate the difference in minutes for datetime range
        $minutesDifference = $dateFrom->diffInMinutes($dateTo);

        return $minutesDifference;
    }

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {   
        if(!isset($arrayData['m_kary_id'])){
            return $this->helper->customResponse(
                "Akun ini tidak Tersambung dengan data karyawan manapun !",
                422
            );
        }

        if($arrayData['alasan_id'] == ""){
            return [
                "errors" => ['Alasan Harus Diisi']
            ];
        }

        $interval = null;
        $interval_min = null;

        if (isset($arrayData['date_from']) && isset($arrayData['date_to']) && (!isset($arrayData['time_from']) || $arrayData['time_from'] === null || $arrayData['time_from'] === '') && (!isset($arrayData['time_to']) || $arrayData['time_to'] === null || $arrayData['time_to'] === '')) {
            $interval = @$this->hitungHari($arrayData['date_from'], $arrayData['date_to']) ?? 1;
        }

        if (isset($arrayData['time_from']) && isset($arrayData['time_to'])) {
            $interval_min = @$this->hitungMenit($arrayData['time_from'], $arrayData['time_to']) ?? 1;
        }

        
        $newArrayData = array_merge($arrayData, [
            "nomor" => $this->helper->generateNomor("KODE CUTI"),
            "interval" => $interval,
            "interval_min" => $interval_min
        ]);
        if (app()->request->header("Source") == "mobile") {
            $newArrayData = array_merge($newArrayData, [
                "status" => "IN APPROVAL",
                "interval" => @$interval,
                "interval_min" => @$interval_min
            ]);
        }

        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {

        $interval = null;
        $interval_min = null;

        if (isset($arrayData['date_from']) && isset($arrayData['date_to']) && (!isset($arrayData['time_from']) || $arrayData['time_from'] === null || $arrayData['time_from'] === '') && (!isset($arrayData['time_to']) || $arrayData['time_to'] === null || $arrayData['time_to'] === '')) {
            $interval = @$this->hitungHari($arrayData['date_from'], $arrayData['date_to']) ?? 1;
        }

        if (isset($arrayData['time_from']) && isset($arrayData['time_to'])) {
            $interval_min = @$this->hitungMenit($arrayData['time_from'], $arrayData['time_to']) ?? 1;
        }

        if (app()->request->header("Source") == "mobile") {
            $data = t_cuti::where('id', $id)->first();
            if($data["status"] === 'REVISED'){
                $status = 'IN APPROVAL';
            }
        }
        $newArrayData  = array_merge( $arrayData,[
            'status' => @$status ?? @$arrayData['status'],
            "interval" =>@ $interval ?? @$arrayData['interval'],
            "interval_min" => @$interval_min ?? @$arrayData['interval_min'] 
        ]);


        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }

 
    

    public function createAfter($model, $arrayData, $metaData, $id = null)
    {
        if (app()->request->header("Source") == "mobile") {
            $app = $this->createAppTicket($model->id);
        }
    }

    public function updateAfterTransaction( $newdata, $olddata, $data, $meta )
    {
        if (app()->request->header("Source") == "mobile") {
            $app = $this->createAppTicket($newdata['id']);  
        }
    }

    public function custom_send_approval()
    {
        $app = $this->createAppTicket(req("id"));
        if (!$app) {
            return $this->helper->customResponse(
                "Terjadi kesalahan, coba kembali nanti",
                400
            );
        }

        if (app()->request->header("Source") != "mobile") {
            $spd = t_cuti::find(req("id"));
            if ($spd) {
                $spd->update([
                    "status" => "IN APPROVAL",
                ]);
            }
        }

        return $this->helper->customResponse(
            "Permintaan approval berhasil dibuat"
        );
    }

    public function public_tes($req)
    {
        $id = "4";
        $data = t_cuti::where('id', (int)$id)->first();
        return $data;
    }

    // public function updateAfter( $model, $arrayData, $metaData, $id=null )
    // {
    //     if (app()->request->header("Source") == "mobile") {
    //         $data = t_cuti::where('id', 4)->first();
    //         if(@$data) { 
    //             dd($data);
    //             }
    //         if(@$data->status === 'REVISED'){
    //             $status = 'IN APPROVAL';
    //             $app = $this->createAppTicket($id);
    //              if (!$app) {
    //                 return $this->helper->customResponse(
    //                     "Approval tidak tersedia untuk atribut user anda",
    //                     400
    //                 );
    //             }
    //         }
    //     }
    //     $newArrayData = $arrayData;
    //     if(@$status){
    //         $newArrayData  = array_merge( $newArrayData,[
    //             'status' => $status 
    //         ]);
    //     }

    //     return [
    //         "model"  => $model,
    //         "data"   => $newArrayData,
    //         // "errors" => ['error1']
    //     ];
    // }
    

    private function createAppTicket($id)
    {
        $tempId = $id;
        $trx = \DB::table('t_cuti')->find($tempId);
        $conf = [
            "app_name" => "APPROVAL CUTI",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Cuti",
            "form_name" => "t_cuti",
            "trx_nomor" => $trx->nomor,
            "trx_date" => Date("Y-m-d"),
            "trx_creator_id" => $trx->creator_id,
        ];

        $app = $this->helper->approvalCreateTicket($conf);
        if ($app) {
            return true;
        } else {
            return false;
        }
    }

    // public function custom_post ($request) {
    //     $data = t_cuti::find($request->$id);
    //     if (!$data) {
    //         return response()->json(['message' => 'Data not found'], 404);
    //     }
    //     if ($data->status === 'DRAFT') {
    //         // Change the status to post
    //         $data->update([
    //             "status" => "POSTED"
    //             ]);
    //         // $data->status = 'POSTED';
    //         // $data->save();
    //         return response()->json(['message' => 'DRAFT status changed to "POSTED"']);
    //     } else {
    //         // If the status is not draft, return a message
    //         return response()->json(['message' => 'POSTED status is not "DRAFT"'], 400);
    //     }
    // }

    

    public function custom_progress($req)
    {
        // Start a database transaction
        \DB::beginTransaction();

        try {
            $conf = [
                "app_id" => $req->id,
                "app_type" => $req->type, // APPROVED, REVISED, REJECTED,
                "app_note" => $req->note, // alasan approve
            ];

            $app = $this->helper->approvalProgress($conf, true);
            if ($app->status) {
                $data = $this->find($app->trx_id);
                if ($app->finish) {
                    $data->update([
                        "status" => $req->type
                    ]);
                   
                } else {
                    $data->update([
                        "status" => "IN APPROVAL",
                    ]);
                }
            }

            \DB::commit();

            return $this->helper->customResponse("Proses approval berhasil");
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }
    }


    public function custom_detail($req)
    {
        $id = $req->id ?? 66;
        $data = $this->helper->approvalDetail($id);
        return $this->helper->customResponse("OK", 200, $data);
    }
    public function custom_log($req)
    {
        $conf = [
            "trx_id" => $req->id ?? 0,
            "trx_table" => $this->getTable(),
        ];
        $data = $this->helper->approvalLog($conf);
        return response($data);
    }
}
