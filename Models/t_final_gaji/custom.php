<?php

namespace App\Models\CustomModels;

class t_final_gaji extends \App\Models\BasicModels\t_final_gaji
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

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $req = app()->request;
        $newArrayData  = array_merge( $arrayData,[
            'nomor' => $this->helper->generateNomor('KODE FINAL GAJI')
        ]);
       
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function custom_tes()
    {
        $det = t_final_gaji_det::with(['t_final_gaji_det_rincian' => function ($query) {
        $query->where('t_potongan_id', '!=', null);
        }])
        ->where('t_final_gaji_id', 1)
        ->get();


        return response()->json($det);
    }
    
    public function custom_postData($request)
    {
        \DB::beginTransaction();
        $data = t_final_gaji::find($request->id);

        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        try {

            $update = $data->update([
                'status' => "POSTED"
            ]);
            $create = true;
            $det = t_final_gaji_det::with(['t_final_gaji_det_rincian' => function ($query) {
            $query->where('t_potongan_id', '!=', null);
            }])
            ->where('t_final_gaji_id', $request->id)
            ->get();
            foreach($det as $dt){
                foreach($dt['t_final_gaji_det_rincian'] as $dt1){
                    $potongan = t_potongan::where('id', $dt1['t_potongan_id'])->first();
                    $create = t_potongan_det_bayar::create([
                        'm_potongan_id' => $dt1['t_potongan_id'],
                        't_final_gaji_id' => $request->id,
                        'percentage' =>  $potongan['percentage'],
                        'nilai' => $dt1['value'],
                        'paid_at' => \Carbon::now()
                    ]) && $create;
                }
                // if($create){
                //     $cekAngsuran = t_potongan_det_bayar::where('t_potongan_id')
                // }
            }

            if ($update && $create) {
                 \DB::commit(); 
                return response()->json(['message' => 'Data berhasil diposting.']);
            } else {
                \DB::rollback();
                return response()->json(['error' => 'Gagal memperbarui status.'], 500);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            // Handle exception, log error messages, etc.
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function custom_save($req) {
        \Validator::make($req->all(),[
            "periode_awal" => 'required',
            "periode_akhir" => 'required',
            "total_pengeluaran_gaji" => 'required',
            "desc" => 'required',
            "status" => 'required',
            "type_perhitungan" => 'required',
        ]);
        $data = $req->all();

        \DB::beginTransaction();
        try {
            $IdFinalGaji = t_final_gaji::create([
                'nomor' => $this->helper->generateNomor('KODE FINAL GAJI'),
                'periode_awal' => $data['periode_awal'],
                'periode_akhir' => $data['periode_akhir'],
                'total_pengeluaran_gaji' => $data['total_pengeluaran_gaji'],
                'desc' => $data['desc'],
                'status' => $data['status'] ?? "DRAFT",
                'type_perhitungan' => $data['type_perhitungan']
            ]);

            $finalGajiDetData = [];
            $finalGajiDetRincianData = [];

            foreach ($data['t_final_gaji_det'] as $single) {
                $finalGajiDetData[] = [
                    't_final_gaji_id' => $IdFinalGaji->id,
                    't_perhitungan_gaji_id' => $single['t_perhitungan_gaji_id'],
                    'm_kary_id' => $single['m_kary_id'],
                    'm_kary_dir_id' => $single['m_kary_dir_id'],
                    'm_kary_divisi_id' => $single['m_kary_divisi_id'],
                    'm_kary_dept_id' => $single['m_kary_dept_id'],
                    'periode' => $single['periode'],
                    'periode_in_date' => \Carbon::createFromFormat('d/m/Y', $single['periode_in_date'])->format('Y-m-d'),
                    'total_gaji' => $single['total_gaji'],
                    'total_tax' => $single['total_tax'],
                    'netto' => $single['netto'],
                    'periode_id' => $single['periode_id'],
                    'deskripsi' => $single['deskripsi'],
                    'status' => $single['status'] ?? "DRAFT",
                ];
            }

            t_final_gaji_det::insert($finalGajiDetData);

            $finalGajiDetIds = t_final_gaji_det::where('t_final_gaji_id', $IdFinalGaji->id)
                ->pluck('id')
                ->toArray();

            foreach ($data['t_final_gaji_det'] as $index => $single) {
                foreach ($single['t_final_gaji_det_rincian'] as $subSingle) {
                    $finalGajiDetRincianData[] = [
                        't_final_gaji_det_id' => $finalGajiDetIds[$index],
                        'seq' => $subSingle['seq'],
                        'name' => $subSingle['name'] ?? null,
                        'type' => $subSingle['type'],
                        'factor' => $subSingle['factor'],
                        'value_ref' => $subSingle['value_ref'] ?? null,
                        'value' => $subSingle['value'],
                        'can_adjust' => $subSingle['can_adjust'] ?? null,
                        'detail' => $subSingle['detail'] ?? null,
                        'status' => $subSingle['status'] ?? 'DRAFT',
                        't_potongan_id' => $subSingle['t_potongan_id'] ?? null,
                        't_cuti_id' => $subSingle['t_cuti_id'] ?? null,
                        'label' => $subSingle['label'],
                    ];
                }
            }

            t_final_gaji_det_rincian::insert($finalGajiDetRincianData);

            \DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan.'], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function custom_update($req){
        \Validator::make($req->all(),[
            "id" => 'required',
            "periode_awal" => 'required',
            "periode_akhir" => 'required',
            "total_pengeluaran_gaji" => 'required',
            "desc" => 'required',
            "status" => 'required',
            "type_perhitungan" => 'required',
        ]);
        $data = $req->all();

        \DB::beginTransaction();
        try {
            $id = $data['id'];
            $IdFinalGaji = t_final_gaji::findOrFail($id);
            $IdFinalGaji->update([
                'periode_awal' => $data['periode_awal'],
                'periode_akhir' => $data['periode_akhir'],
                'total_pengeluaran_gaji' => $data['total_pengeluaran_gaji'],
                'desc' => $data['desc'],
                'status' => $data['status'] ?? "DRAFT",
                'type_perhitungan' => $data['type_perhitungan']
            ]);

            $finalGajiDetData = [];
            $finalGajiDetRincianData = [];

            t_final_gaji_det::where('t_final_gaji_id', $id)->delete();

            foreach ($data['t_final_gaji_det'] as $single) {
                $finalGajiDetData[] = [
                    't_final_gaji_id' => $id,
                    't_perhitungan_gaji_id' => $single['t_perhitungan_gaji_id'],
                    'm_kary_id' => $single['m_kary_id'],
                    'm_kary_dir_id' => $single['m_kary_dir_id'],
                    'm_kary_divisi_id' => $single['m_kary_divisi_id'],
                    'm_kary_dept_id' => $single['m_kary_dept_id'],
                    'periode' => $single['periode'],
                    'periode_in_date' => \Carbon::createFromFormat('d/m/Y', $single['periode_in_date'])->format('Y-m-d'),
                    'total_gaji' => $single['total_gaji'],
                    'total_tax' => $single['total_tax'],
                    'netto' => $single['netto'],
                    'periode_id' => $single['periode_id'],
                    'deskripsi' => $single['deskripsi'],
                    'status' => $single['status'] ?? "DRAFT",
                ];
            }

            t_final_gaji_det::insert($finalGajiDetData);

            $finalGajiDetIds = t_final_gaji_det::where('t_final_gaji_id', $IdFinalGaji->id)
                ->pluck('id')
                ->toArray();
                         
                         
            t_final_gaji_det_rincian::where('t_final_gaji_det_id', $finalGajiDetIds)->delete();

            foreach ($data['t_final_gaji_det'] as $index => $single) {
                foreach ($single['t_final_gaji_det_rincian'] as $subSingle) {
                    $finalGajiDetRincianData[] = [
                        't_final_gaji_det_id' => $finalGajiDetIds[$index],
                        'seq' => $subSingle['seq'],
                        'name' => $subSingle['name'] ?? null,
                        'type' => $subSingle['type'],
                        'factor' => $subSingle['factor'],
                        'value_ref' => $subSingle['value_ref'] ?? null,
                        'value' => $subSingle['value'],
                        'can_adjust' => $subSingle['can_adjust'] ?? null,
                        'detail' => $subSingle['detail'] ?? null,
                        'status' => $subSingle['status'] ?? 'DRAFT',
                        't_potongan_id' => $subSingle['t_potongan_id'] ?? null,
                        't_cuti_id' => $subSingle['t_cuti_id'] ?? null,
                        'label' => $subSingle['label'],
                    ];
                }
            }

            t_final_gaji_det_rincian::insert($finalGajiDetRincianData);


            \DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan.'], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage() .' '. $e->getLine()], 500);
        }
    }

    // public function custom_slip_gaji($req){
    //     if(app()->request->header('Source') !== 'mobile'){
    //        return response()->json(["message" => 'Unauthorized'],401);
    //     }
    //     $date = $req->date;
    //     $idFinalGaji = $req->id_final_gaji;
    //     $startPeriod = \Carbon::parse($date)->startOfMonth();
    //     $endPeriod = \Carbon::parse($date)->endOfMonth();
    //     $karyID = $req->kary_id;
    //     $query = \DB::select("select f.periode_awal, k.kode, f.nomor nomor_gaji, f.periode_akhir, f.desc,k.nama_lengkap, k.nik,kd.nama dir, kdi.nama divisi, kde.nama dept, r.*, r.label as name from t_final_gaji_det_rincian r
    //     join t_final_gaji_det d on d.id = r.t_final_gaji_det_id 
    //     join m_kary k on k.id = d.m_kary_id 
    //     join t_final_gaji f on f.id = d.t_final_gaji_id 
    //     left join m_dir kd on kd.id = k.m_dir_id 
    //     left join m_divisi kdi on kdi.id = k.m_divisi_id 
    //     left join m_dept kde on kde.id = k.m_dept_id
    //     where d.m_kary_id = coalesce(?,k.id)
    //     AND (
    //         (f.periode_awal BETWEEN COALESCE(?, CURRENT_DATE) AND COALESCE(?, CURRENT_DATE)) 
    //         OR (f.periode_akhir BETWEEN COALESCE(?, CURRENT_DATE) AND COALESCE(?, CURRENT_DATE)) 
    //         OR (f.periode_awal <= COALESCE(?, CURRENT_DATE) AND f.periode_akhir >= COALESCE(?, CURRENT_DATE))
    //     )
    //     order by r.seq",[$karyID, $startPeriod, $endPeriod, $startPeriod, $endPeriod, $startPeriod, $endPeriod]);
    //     return response()->json(['message' => 'success', 'data' => $query],200);
    // }

    // public function custom_slip_gaji($req){
    //     if(app()->request->header('Source') !== 'mobile'){
    //        return response()->json(["message" => 'Unauthorized'],401);
    //     }
    //     $date = $req->date;
    //     $idFinalGaji = $req->id_final_gaji;
    //     $startPeriod = \Carbon::parse($date)->startOfMonth();
    //     $endPeriod = \Carbon::parse($date)->endOfMonth();
    //     $karyID = $req->kary_id;
    //     $query = \DB::select("select f.periode_awal, k.kode, f.nomor nomor_gaji, f.periode_akhir, f.desc,k.nama_lengkap, k.nik,kd.nama dir, kdi.nama divisi, kde.nama dept, r.*, r.label as name from t_final_gaji_det_rincian r
    //     join t_final_gaji_det d on d.id = r.t_final_gaji_det_id 
    //     join m_kary k on k.id = d.m_kary_id 
    //     join t_final_gaji f on f.id = d.t_final_gaji_id 
    //     left join m_dir kd on kd.id = k.m_dir_id 
    //     left join m_divisi kdi on kdi.id = k.m_divisi_id 
    //     left join m_dept kde on kde.id = k.m_dept_id
    //     where d.m_kary_id = coalesce(?,k.id)
    //     AND (
    //         (f.periode_awal BETWEEN COALESCE(?, CURRENT_DATE) AND COALESCE(?, CURRENT_DATE)) 
    //         OR (f.periode_akhir BETWEEN COALESCE(?, CURRENT_DATE) AND COALESCE(?, CURRENT_DATE)) 
    //         OR (f.periode_awal <= COALESCE(?, CURRENT_DATE) AND f.periode_akhir >= COALESCE(?, CURRENT_DATE))
    //     )
    //     order by r.seq",[$karyID, $startPeriod, $endPeriod, $startPeriod, $endPeriod, $startPeriod, $endPeriod]);
    //     return response()->json(['message' => 'success', 'data' => $query],200);
    // }

    // public function custom_tes_slip_gaji($req)
    // {
    //     $date = $req->date;
    //     $karyID = $req->kary_id;
    //     $res = t_final_gaji_det_rincian::whereHas('t_final_gaji_det', function($q) use ($karyID){
    //         $q->where('m_kary_id', $karyID);
    //     })
    //     ->with('t_final_gaji_det.t_final_gaji')
    //     ->get();
    //     return response()->json(['message' => 'success', 'data' => $res],200);
    // }

    public function custom_slip_gaji($req)
    {
        $karyID = $req->kary_id;

        $res = t_final_gaji_det_rincian::with([
                't_final_gaji_det.t_final_gaji', 
                't_final_gaji_det.m_kary.m_dir', 
                't_final_gaji_det.m_kary.m_divisi', 
                't_final_gaji_det.m_kary.m_dept'
            ])
            ->whereHas('t_final_gaji_det', function($q) use ($karyID) {
                $q->where('m_kary_id', $karyID);
                $q->whereHas('t_final_gaji', function($q2){
                    $q2->where('status', 'POSTED');
                });
            })
            ->get();

        $data = $res->map(function ($item) {
            $det = $item->t_final_gaji_det;
            $header = $det->t_final_gaji ?? null;
            $karyawan = $det->m_kary ?? null;

            if($item->label == 'Total Gaji' && $item->value != $det->total_gaji){
                // dd($det->total_gaji);
                if($det->total_gaji != 0)
                {
                    $value = $det->total_gaji;
                }else{
                    $value = $item->value;
                }

            }else{
                $value = $item->value;
            }

            return [
                "periode_awal"   => $header->periode_awal ?? null,
                "kode"           => $karyawan->kode ?? null,
                "nomor_gaji"     => $header->nomor ?? null,
                "periode_akhir"  => $header->periode_akhir ?? null,
                "desc"           => $header->desc ?? null,
                
                "nama_lengkap"   => $karyawan->nama_lengkap ?? null,
                "nik"            => $karyawan->nik ?? null,
                "dir"            => $karyawan->m_dir->nama ?? null,
                "divisi"         => $karyawan->m_divisi->nama ?? null,
                "dept"           => $karyawan->m_dept->nama ?? null,

                "id"                  => $item->id,
                "t_final_gaji_det_id" => $item->t_final_gaji_det_id,
                "seq"                 => number_format($item->seq, 2, '.', ''), 
                "name"                => $item->label, 
                "label"               => $item->label,
                "type"                => $item->type,
                "factor"              => $item->factor,
                "value_ref"           => $item->value_ref,
                "value"               => number_format($value, 2, '.', ''),
                "can_adjust"          => $item->can_adjust,
                "detail"              => $item->detail,
                "deskripsi"           => $item->deskripsi,
                "status"              => $item->status,
                "creator_id"          => $item->creator_id,
                "last_editor_id"      => $item->last_editor_id,
                "created_at"          => $item->created_at,
                "updated_at"          => $item->updated_at,
                "t_potongan_id"       => $item->t_potongan_id,
                "t_cuti_id"           => $item->t_cuti_id,
            ];
        });

        return response()->json(['message' => 'success', 'data' => $data], 200);
    }

    public function scopeFinalGajiKaryawan($model){
        $karyId = request('kary_id',0);
        return $model->whereHas('t_final_gaji_det', function($query) use($karyId){
            $query->where('m_kary_id', $karyId);
        });
    }
}