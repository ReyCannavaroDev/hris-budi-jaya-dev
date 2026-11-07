<?php

namespace App\Models\CustomModels;

class generate_approval extends \App\Models\BasicModels\generate_approval
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

    public function generate_approval_det(): HasMany
    {
        return $this->hasMany(generate_approval_det::class);
    }

    public function custom_send_approval()
    {
        $conf = [
            'app_name'          => 'Contoh Approval',
            'trx_id'            => 1,
            'trx_table'         => $this->getTable(),
            'trx_name'          => 'Pengajuan Lembur', 
            'form_name'         => 't_lembur', 
            'trx_nomor'         => '123',           // isi dengan nomor transaksi, ini wajib!
            'trx_date'          => Date('Y-m-d'),   // isi dengan tanggal transaksi, ini wajib!
            'trx_creator_id'    => 1                // isi dengan user creator transaksi, ini wajib!
        ];

        $app = $this->helper->approvalCreateTicket($conf);
        if($app) {
            // disini update status transaksi
        }
        return $this->helper->customResponse('Permintaan approval berhasil dibuat');
    }   

    public function custom_progress()
    {
        $conf = [
            'app_id' => 1,
            'app_type' => 'APPROVED', // APPROVED, REVISED, REJECTED,
            'app_note' => 'Contoh Approve by sistem' // alasan approve
        ];

        $app = $this->helper->approvalProgress($conf);
        if($app->status) {
            if(!$app->finish){
                // approval berhasil -> namun masih ada approval lainnya

            }else{
                // approval berkahir -> waktunya update status header transaksi
            
            }
        }
        return $this->helper->customResponse('Proses approval berhasil');
    }   

    public function custom_detail()
    {
        $id = 1;
        $data = $this->helper->approvalDetail($id);
        return $this->helper->customResponse('OK', 200, $data);
    }

    public function custom_outstanding()
    {
        $data = $this->helper->approvalOustanding();
        return response($data);
    }

    public function custom_outstandingDetail($request){
        $data = $this->helper->approvalDetail($request->id);
         if (isset($data->trx)) {
            $mappedTrx = new \stdClass();


            if($data->approval->trx_table === 't_spd'){
                $mappedTrx->nomor = $data->trx->nomor;
                $mappedTrx->tanggal = $data->trx->tanggal;
                $mappedTrx->tgl_acara_awal = $data->trx->tgl_acara_awal;
                $mappedTrx->tgl_acara_akhir = $data->trx->tgl_acara_akhir;
                $mappedTrx->jenis_spd_id = $data->trx->jenis_spd_id;
                $mappedTrx->jenis_spd = m_general::where('id', $data->trx->jenis_spd_id)->value('value');
                $mappedTrx->m_zona_asal_id = $data->trx->m_zona_asal_id;
                $mappedTrx->zona_asal = m_zona::where('id', $data->trx->m_zona_asal_id)->value('nama');
                $mappedTrx->m_zona_tujuan_id = $data->trx->m_zona_tujuan_id;
                $mappedTrx->zona_tujuan = m_zona::where('id', $data->trx->m_zona_tujuan_id)->value('nama');
                $mappedTrx->m_lokasi_tujuan_id = $data->trx->m_lokasi_tujuan_id;
                $mappedTrx->lokasi = m_lokasi::where('id', $data->trx->m_lokasi_tujuan_id)->value('nama');
                $mappedTrx->m_kary_id = $data->trx->m_kary_id;
                $mappedTrx->nama_kary = m_kary::where('id', $data->trx->m_kary_id)->value('nama_lengkap');
                $mappedTrx->pic_id = $data->trx->pic_id;
                $mappedTrx->nama_pic = default_users::where('id', $data->trx->pic_id)->value('name');
                $mappedTrx->total_biaya = $data->trx->total_biaya;
                $mappedTrx->kegiatan = $data->trx->kegiatan;
                $mappedTrx->keterangan = $data->trx->keterangan;
                $mappedTrx->status = $data->trx->status;
                $mappedTrx->interval = t_spd::where('id', $data->approval->trx_id)->value('interval');
                $mappedTrx->catatan_kend = $data->trx->catatan_kend;
            }
            elseif($data->approval->trx_table === 't_cuti')
            {
                $datas = \DB::select("select public.employee_attendance(?,?)",[Date('Y-m-d'),$data->trx->m_kary_id ??0]);
                $datas = json_decode($datas[0]->employee_attendance);
                $mappedTrx->nomor = $data->trx->nomor;
                $mappedTrx->alasan_id = $data->trx->alasan_id;
                $mappedTrx->alasan = m_general::where('id', $data->trx->alasan_id)->value('value');
                $mappedTrx->tipe_cuti_id = $data->trx->tipe_cuti_id;
                $mappedTrx->tipe_cuti = m_general::where('id', $data->trx->tipe_cuti_id)->value('value');
                $mappedTrx->date_from = $data->trx->date_from;
                $mappedTrx->date_to = $data->trx->date_to;
                $mappedTrx->time_from = $data->trx->time_from;
                $mappedTrx->time_to = $data->trx->time_to;
                $mappedTrx->keterangan = $data->trx->keterangan;
                $mappedTrx->status = $data->trx->status;
                $mappedTrx->interval = t_cuti::where('id', $data->approval->trx_id)->value('interval');
                $mappedTrx->interval_min = t_cuti::where('id', $data->approval->trx_id)->value('interval_min');
                $mappedTrx->attachment = $data->trx->attachment;
                $mappedTrx->cuti_sisa_panjang = $datas->sisa_cuti_reguler ?? 0;
                $mappedTrx->cuti_sisa_reguler = $datas->sisa_cuti_masa_kerja ?? 0;
                $mappedTrx->cuti_sisa_p24 = $datas->sisa_cuti_p24 ?? 0;
                $mappedTrx->info_cuti = $datas ?? [];

            }
            elseif($data->approval->trx_table === 't_rpd')
            {
                $t_spd = t_spd::leftJoin('m_divisi', 't_spd.m_divisi_id', '=', 'm_divisi.id')
                    ->leftJoin('m_dept', 't_spd.m_dept_id', '=', 'm_dept.id')
                    ->leftJoin('m_lokasi', 't_spd.m_lokasi_tujuan_id', '=', 'm_lokasi.id')
                    ->leftJoin('default_users', 't_spd.m_kary_id', '=', 'default_users.m_kary_id')
                    ->select('t_spd.*', 'm_divisi.nama as nama_divisi', 'm_dept.nama as nama_dept', 'm_lokasi.nama as lokasi_tujuan', 'default_users.name')
                    ->where('t_spd.id', $data->trx->t_spd_id)
                    ->first();
                $pic = m_kary::where('id', $t_spd->m_kary_id)->value('nama_lengkap');
                $mappedTrx->nomor = $data->trx->nomor;
                $mappedTrx->total_biaya_spd = $data->trx->total_biaya_spd;
                $mappedTrx->total_biaya_selisih = $data->trx->total_biaya_selisih;
                $mappedTrx->keterangan = $data->trx->keterangan;
                $mappedTrx->status = $data->trx->status;
                $mappedTrx->tgl_acara_awal = @$t_spd->tgl_acara_awal ?? null;
                $mappedTrx->tgl_acara_akhir = @$t_spd->tgl_acara_akhir ?? null; 
                $mappedTrx->nama_divisi = @$t_spd->nama_divisi ?? null;
                $mappedTrx->nama_dept = @$t_spd->nama_dept ?? null;
                $mappedTrx->lokasi_tujuan = @$t_spd->lokasi_tujuan ?? null;
                $mappedTrx->pic = @$pic ?? @$t_spd->name ?? null;
                $mappedTrx->interval = @$t_spd->interval ?? 0;
                $mappedTrx->kegiatan = @$t_spd->kegiatan ?? null;
                $mappedTrx->catatan_kend = @$t_spd->catatan_kend ?? null;
                $mappedTrx->t_rpd_det = t_rpd_det::leftJoin('m_general', 't_rpd_det.tipe_spd_id', '=', 'm_general.id')
                                        ->select('t_rpd_det.*', 'm_general.value as tipe_spd')
                                        ->where('t_rpd_det.t_rpd_id', $data->approval->trx_id)
                                        ->get() ?? [];
            }
            else{
                $mappedTrx->nomor = $data->trx->nomor;
                $mappedTrx->tanggal = $data->trx->tanggal;
                $mappedTrx->jam_mulai = $data->trx->jam_mulai;
                $mappedTrx->jam_selesai = $data->trx->jam_selesai;
                $mappedTrx->no_doc = $data->trx->no_doc;
                $mappedTrx->doc = $data->trx->doc;
                $mappedTrx->keterangan = $data->trx->keterangan;
                $mappedTrx->status = $data->trx->status;
                $mappedTrx->nama_pic = default_users::where('id', $data->trx->pic_id)->value('name');
                $mappedTrx->interval_min = t_lembur::where('id', $data->approval->trx_id)->value('interval_min');
            }
            $data->trx = $mappedTrx;
        }
        return $this->helper->customResponse("OK", 200, $data);
    }

    public function custom_log()
    {
        $conf = [
            'trx_id' => 1,
            'trx_table' => $this->getTable()
        ];
        $data = $this->helper->approvalLog($conf);
        return response($data);
    }

    public function custom_progressing($req)
    {
        \DB::beginTransaction();

        try {
            $conf = [
                "app_id" => $req->id,
                "app_type" => $req->type, // APPROVED, REVISED, REJECTED,
                "app_note" => $req->note, // alasan approve
            ];
            $datas = generate_approval::where('id', $req->id)->first();

            if (!$datas) {
                return $this->helper->customResponse('errors', 404, "Data not found");
            }

            $cek = \DB::table($datas['trx_table'])->where('id', $datas['trx_id'])->first();
            if($cek->status === 'REJECTED' || $cek->status === 'REVISED'){
                return $this->helper->customResponse('Data Sudah Dalam Status Rejected atau Revised , Harap Ulangi atau Perbaiki Pengajuan', 422);
            }

            $app = $this->helper->approvalProgress($conf);
            if ($app->status) {
                $data = \DB::table($datas['trx_table'])->where('id',$app->trx_id);
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

    public function public_tes_object()
    {
        return $this->helper->customResponse('OK',200,[
            'nomor' => 'CT-231213-00000136',
            'tanggal' => '13/12/2023',
            'alasan' => 'Sakit',
            'tipe' => 'Cuti Tahunan',
            'status' => 'IN APPROVAL',
            'detail' => [
                [
                    'no' => 1,
                    'tipe' => 'Makan',
                    'biaya' => 50000,
                    'ket' => 'tes'
                ],
                [
                    'no' => 2,
                    'tipe' => 'Lain-lain',
                    'biaya' => 40000,
                    'ket' => 'aaa'
                ],
                [
                    'no' => 3,
                    'tipe' => 'Makan',
                    'biaya' => 20000,
                    'ket' => 'aaa'
                ]
            ]
        ]);
    }
}