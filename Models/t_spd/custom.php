<?php

namespace App\Models\CustomModels;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class t_spd extends \App\Models\BasicModels\t_spd
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function transformRowData( array $row )
    {
        $approval = generate_approval_log::where('trx_table', 't_spd')->where('trx_id', $row['id']) ->orderBy('created_at', 'desc')->value('action_note');
        $det_spd_biaya = \DB::table('m_spd_det_biaya')
            ->leftJoin('m_general', 'm_spd_det_biaya.tipe_id', '=', 'm_general.id')
            ->where('m_spd_det_biaya.m_spd_id', $row['m_spd_id'])
            ->select('m_spd_det_biaya.total_biaya', 'm_spd_det_biaya.tipe_id', 'm_spd_det_biaya.keterangan', 'm_general.value as tipe_value')
            ->get();

        if (app()->request->header("Source") == "mobile" || app()->request->detail) {
            $row['pic.nama_lengkap'] = m_kary::where('id',$row['pic.m_kary_id'])->pluck('nama_lengkap')->first();
        }

        return array_merge( $row, [
            'approval_note' => $approval ?? '',
            'nama_pic' => default_users::where('id', $row['pic_id'])->value('name') ?? '',
            'det_biaya' => @$det_spd_biaya ?? []
        ] );
    }

    private function hitungHari($from , $to) {
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
    

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newArrayData = array_merge($arrayData, [
            "m_dir_id" => m_divisi::where('id', @$arrayData['m_divisi_id'] ?? 0)->pluck('m_dir_id')->first(),
            "nomor" => $this->helper->generateNomor("KODE SPD"),
            "interval" => ($arrayData['tgl_acara_awal'] !== null && $arrayData['tgl_acara_akhir'] !== null ) ? $this->hitungHari($arrayData['tgl_acara_awal'], $arrayData['tgl_acara_akhir']) : null,
        ]);
        if (app()->request->header("Source") == "mobile") {
            $newArrayData = array_merge($newArrayData, [
                "nomor" => $this->helper->generateNomor("KODE SPD"),
                "interval" => ($arrayData['tgl_acara_awal'] !== null && $arrayData['tgl_acara_akhir'] !== null ) ? $this->hitungHari($arrayData['tgl_acara_awal'], $arrayData['tgl_acara_akhir']) : null,
                "status" => "IN APPROVAL",
            ]);
        }
        $newArrayData = array_merge($newArrayData, [
            "pic_id" => app()->request->pic_id ?? auth()->user()->id,
        ]);

        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {

        if (isset($arrayData['tgl_acara_awal']) && isset($arrayData['tgl_acara_akhir'])) {
            $interval = $this->hitungHari($arrayData['tgl_acara_awal'], $arrayData['tgl_acara_akhir']);
        }

        $data = t_spd::where('id', $id)->first();
        if($data["status"] === 'REVISED'){
            $status = 'IN APPROVAL';
        }
        
        $newArrayData  = array_merge( $arrayData,[
            'status' => $status ?? @$arrayData['status'],
            'interval' => @$interval ?? $data['interval'],
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
            $spd = t_spd::find(req("id"));
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
        if ($trx->is_kend_dinas) {
            $app_name = "APPROVAL SPPD DENGAN KENDARAAN DINAS";
        }else{
            $app_name = "APPROVAL SPPD";
        }
        // pengajuan lembur untuk bawahan 
        // dengan deteksi kolom pic_id terisi dan pic_id bukan user nya sendiri
        if(@$trx->pic_id != auth()->user()->id){
            $app_name .= " UNTUK BAWAHAN";
        }

        $conf = [
            "app_name" => $app_name ,
            "trx_id" => $trx->id,
            "trx_table" => $this->getTable(),
            "trx_name" => "Pengajuan Surat Perjalanan Dinas",
            "form_name" => "t_rencana_perjalanan_dinas",
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

    public function custom_post($request)
    {
        $data = t_cuti::find($request->$id);
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

    public function scopeForREPD($model)
    {
        return $model->whereRaw("
            upper(t_spd.status) = 'APPROVED' and t_spd.id not in(select r.t_spd_id from t_rpd r where upper(r.status) = 'APPROVED')
        ");
    }
}
