<?php

namespace App\Models\CustomModels;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class t_lembur extends \App\Models\BasicModels\t_lembur
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = ["doc"];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function scopeFilter($model)
    {
     

        if(app()->request->header('Source') === 'mobile') {
            // $model->where
        }
        
        if (req("date_from") && req("date_to")) {
            return $model
                ->whereBetween("tanggal", [req("date_from"), req("date_to")])
                ->whereRaw(
                    "t_lembur.m_dir_id in(select u.m_dir_id from default_users u where u.id = ?)",
                    [auth()->user()->id ?? 0]
                );
        }
    }

    public function transformRowData( array $row )
    {
        $approval = generate_approval_log::where('trx_table', 't_lembur')->where('trx_id', $row['id']) ->orderBy('created_at', 'desc')->value('action_note');
        return array_merge( $row, [
            'approval_note' => $approval ?? ''
        ] );
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
        $newArrayData = array_merge($arrayData, [
            "nomor" => $this->helper->generateNomor("KODE LEMBUR"),
            "interval_min" => ($arrayData['jam_mulai'] !== null && $arrayData['jam_selesai'] !== null ) ? $this->hitungMenit($arrayData['jam_mulai'], $arrayData['jam_selesai']) : null,
        ]);
        if (app()->request->header("Source") == "mobile") {
            $newArrayData = array_merge($newArrayData, [
                "nomor" => $this->helper->generateNomor("KODE LEMBUR"),
                "interval_min" => ($arrayData['jam_mulai'] !== null && $arrayData['jam_selesai'] !== null ) ? $this->hitungMenit($arrayData['jam_mulai'], $arrayData['jam_selesai']) : null,
                "status" => "IN APPROVAL",
            ]);
        }
        $newArrayData = array_merge($newArrayData, [
            "pic_id" => @app()->request->pic_id ?? auth()->user()->id,
        ]);

        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {
        if (app()->request->header("Source") == "mobile") {
            $data = \DB::table('t_lembur')->where('id', $id)->first();
            if($data->status === 'REVISED'){
                $status = 'IN APPROVAL';
            }
        }else{
            $data = \DB::table('t_lembur')->where('id', $id)->first();
            if($data->status === 'REVISED'){
                $status = 'IN APPROVAL';
            }
        }
        if (isset($arrayData['jam_mulai']) && isset($arrayData['jam_selesai']) ) {
            $interval = $this->hitungMenit($arrayData['jam_mulai'], $arrayData['jam_selesai']);
        }

        $newArrayData  = array_merge( $arrayData,[
            'status' => $status ?? $data->status,
            'interval_min' => @$interval ?? $data->interval_min,
            "pic_id" => @app()->request->pic_id ?? auth()->user()->id,
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

    // public function onRetrieved($model){
    //     // inject pic name
    //     if (app()->request->header("Source") == "mobile" || app()->request->detail) {
    //         $model['pic.nama_lengkap'] = m_kary::where('id',$model['pic.m_kary_id'])->pluck('nama_lengkap')->first();
    //     }
    // }

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
            $spd = t_lembur::find(req("id"));
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

    private function createAppTicket($id)
    {
        $trx = $this->find($id);

        // pengajuan lembur untuk bawahan 
        // dengan deteksi kolom pic_id terisi dan pic_id bukan user nya sendiri
        if(@$trx->pic_id != auth()->user()->id){
            $app_name = "APPROVAL LEMBUR UNTUK BAWAHAN";
        }
        $conf = [
            "app_name" => @$app_name ?? "APPROVAL LEMBUR", 
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Lembur",
            "form_name" => "t_lembur",
            "trx_nomor" => $trx->nomor,
            "trx_date" => $trx->tanggal,
            "trx_creator_id" => $trx->creator_id,
        ];

        $app = $this->helper->approvalCreateTicket($conf);
        if ($app) {
            return true;
        } else {
            return false;
        }
    }

    public function custom_post($request)
    {
        $data = $this->find($request->$id);
        if (!$data) {
            return response()->json(["message" => "Data not found"], 404);
        }
        if ($data->status === "DRAFT") {
            // Change the status to post
            $data->update([
                "status" => "POSTED",
            ]);
            // $data->status = 'POSTED';
            // $data->save();
            return response()->json([
                "message" => 'DRAFT status changed to "POSTED"',
            ]);
        } else {
            // If the status is not draft, return a message
            return response()->json(
                ["message" => 'POSTED status is not "DRAFT"'],
                400
            );
        }
    }

    public function custom_progress($req)
    {
        \DB::beginTransaction();

        try {
            $conf = [
                "app_id" => $req->id,
                "app_type" => $req->type, // APPROVED, REVISED, REJECTED,
                "app_note" => $req->note, // alasan approve
            ];

            $app = $this->helper->approvalProgress($conf);
            if ($app->status) {
                $data = $this->find($app->trx_id);
                if ($app->finish) {
                    $data->update([
                        "status" => $req->type,
                    ]);

                    // Additional logic for updating related records or performing other actions
                } else {
                    $data->update([
                        "status" => "IN APPROVAL",
                    ]);
                    // Additional logic for updating related records or performing other actions
                }
            }

            \DB::commit();

            return $this->helper->customResponse("Proses approval berhasil");
        } catch (\Exception $e) {
            \DB::rollback();

            // Log or handle the exception as needed
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
