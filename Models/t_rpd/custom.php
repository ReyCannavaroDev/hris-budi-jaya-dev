<?php

namespace App\Models\CustomModels;

class t_rpd extends \App\Models\BasicModels\t_rpd
{    
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore('Helper');
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function transformRowData( array $row )
    {
        $approval = generate_approval_log::where('trx_table', 't_rpd')->where('trx_id', $row['id']) ->orderBy('created_at', 'desc')->value('action_note');
        return array_merge( $row, [
            'approval_note' => $approval ?? ''
        ] );
    }

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $newArrayData  = array_merge( $arrayData,[
            'nomor' => $this->helper->generateNomor('KODE REALISASI PERJALANAN DINAS')
        ]);

        if (app()->request->header("Source") == "mobile") {
            $newArrayData = array_merge($newArrayData, [
                "nomor" => $this->helper->generateNomor("KODE REALISASI PERJALANAN DINAS"),
                "status" => "IN APPROVAL",
            ]);
        }
       
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

    public function custom_postData($request)
    {
        $data = t_rpd::find($request->id);

        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        try {
            $update = $data->update([
                'status' => "POSTED"
            ]);

            if ($update) {
                return response()->json(['message' => 'Data berhasil diposting.']);
            } else {
                return response()->json(['error' => 'Gagal memperbarui status.'], 500);
            }
        } catch (\Exception $e) {
            // Handle exception, log error messages, etc.
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

        private function createAppTicket($id)
    {
        $tempId = $id;
        $trx = \DB::table('t_rpd')->find($tempId);
        $conf = [
            "app_name" => "APPROVAL RPD",
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Realisasi Perjalanan Dinas",
            "form_name" => "t_rpd",
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

    public function custom_send_approval()
    {
        if (app()->request->header("Source") == "mobile") {
             $app = $this->createAppTicket(req("id"));
            if (!$app) {
                return $this->helper->customResponse(
                    "Terjadi kesalahan, coba kembali nanti",
                    400
                );
            }

            $spd = t_rpd::find(req("id"));
            if ($spd) {
                $spd->update([
                    "status" => "IN APPROVAL",
                ]);
            }
            return $this->helper->customResponse(
                "Permintaan approval berhasil dibuat"
            );
        }else{
            $spd = t_rpd::find(req("id"));
            if ($spd) {
                $spd->update([
                    "status" => "APPROVED",
                ]);
            }
            return $this->helper->customResponse(
                "Approved !"
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

            $datas = generate_approval::where('id', $req->id)->first();
            $cek = $this->where('id', $datas['trx_id'])->first();
            if($cek['status'] === 'REJECTED' || $cek['status'] === 'REVISED'){
                return $this->helper->customResponse('errors', '422',"Data Sudah Dalam Status Rejected atau Revised , Harap Ulangi atau Perbaiki Pengajuan");
            }

            $app = $this->helper->approvalProgress($conf);
            if ($app->status) {
                $data = $this->find($app->trx_id);
                if ($app->finish) {
                    $data->update([
                        "status" => $req->type,
                    ]);
                } else {
                    if($req->type != 'APPROVED'){                        
                        $data->update([
                            "status" => $req->type,
                        ]);
                    }else{
                        $data->update([
                            "status" => 'IN APPROVAL',
                        ]);
                    }
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