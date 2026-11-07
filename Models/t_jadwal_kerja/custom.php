<?php

namespace App\Models\CustomModels;

class t_jadwal_kerja extends \App\Models\BasicModels\t_jadwal_kerja
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
        // validasi
        $tipe_jam_kerja = m_general::where('id', $arrayData['tipe_jam_kerja_id'])->pluck('code')->first();

        if(strtolower($tipe_jam_kerja) == 'shift'){
            foreach(@app()->request->t_jadwal_kerja_det_hari ?? [] as $idx => $d){
                $no = $idx+1;
                if(!@$d['waktu_mulai']){
                    return [
                        "errors" => ["Detail baris $no, waktu mulai wajib di isi"]
                    ];
                }
                if(!@$d['waktu_akhir']){
                    return [
                        "errors" => ["Detail baris $no, waktu akhir wajib di isi"]
                    ];
                }
            }
        }


        $newArrayData  = array_merge( $arrayData,[
            'nomor' => $this->helper->generateNomor('KODE JADWAL KERJA')
        ]);
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {
         // validasi
        $tipe_jam_kerja = m_general::where('id', $arrayData['tipe_jam_kerja_id'])->pluck('code')->first();

        // khusus edit tambahkan pengecualian untuk setting jadwal detail karyawan
        if(strtolower($tipe_jam_kerja) == 'shift' && app()->request->from != 'setting_jadwal'){
            foreach(@app()->request->t_jadwal_kerja_det_hari ?? [] as $idx => $d){
                $no = $idx+1;
                if(!@$d['waktu_mulai']){
                    return [
                        "errors" => ["Detail baris $no, waktu mulai wajib di isi"]
                    ];
                }
                if(!@$d['waktu_akhir']){
                    return [
                        "errors" => ["Detail baris $no, waktu akhir wajib di isi"]
                    ];
                }
                $check = t_jadwal_kerja_det_hari::join('t_jadwal_kerja as t','t.id','t_jadwal_kerja_det_hari.t_jadwal_kerja_id')
                    ->whereRaw("((waktu_mulai <= ? and waktu_akhir >= ?) or (waktu_mulai <= ? and waktu_akhir >= ?)) and t.status = 'POSTED'", [$d['waktu_mulai'],$d['waktu_mulai'], $d['waktu_akhir'],$d['waktu_akhir']])   
                    ->exists();
            }
        }

        $newArrayData  = array_merge( $arrayData,[] );
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }
    

    public function custom_generate(){
        $validator = \Validator::make(app()->request->all(), [
            'grup_kerja_id' => 'required'
        ]);
        if($validator->fails()) return $this->helper->responseValidate($validator);

        $grup_kerja = t_grup_kerja::find(req('grup_kerja_id') ?? 0);

        if(!$grup_kerja) return $this->helper->customResponse("Grup kerja id tersbut tidak ditemukan", 422);

        $date_from = $grup_kerja->date_from;
        $date_to = $grup_kerja->date_to;

        $date_from = new \DateTime($grup_kerja->date_from);
        $date_to = new \DateTime($grup_kerja->date_to);

        // Menghitung jumlah hari antara tanggal_awal dan tanggal_akhir
        $interval = $date_from->diff($date_to);
        $jumlah_hari = $interval->days;

        $data = [];
        for ($i = 0; $i <= $jumlah_hari; $i++) {
            $date = $date_from->format('Y-m-d');
            // Menambahkan satu hari untuk iterasi berikutnya
            $date_from->add(new \DateInterval('P1D'));

            $kary = m_kary::selectRaw("m_kary.*, m_posisi.desc_kerja posisi, m_jam_kerja.kode jam_kerja")
                    ->where('m_dept_id', $grup_kerja->m_dept_id)
                    ->join('m_posisi', 'm_posisi.id','m_kary.m_posisi_id')
                    ->join('m_jam_kerja', 'm_jam_kerja.id','m_kary.m_jam_kerja_id')
                    ->get();
            foreach($kary as $d){
                $data[] = [
                    'tanggal'   => $date,
                    'm_kary_id' => $d->id,
                    'nik' => $d->nik,
                    'nama_lengkap' => $d->nama_lengkap,
                    'm_posisi_id' => $d->m_posisi_id,
                    'posisi.nama' => $d->m_posisi_id,
                    'm_jam_kerja_id' => $d->m_jam_kerja_id,
                    'status'    => 'DRAFT'
                ];
            }
        }

        return $this->helper->customResponse('OK', 200, $data);
    }

    public function custom_get_jam_kerja_default()
    {
        $data = \DB::table('m_jam_kerja as k')->join('m_general as g','g.id','k.tipe_jam_kerja_id')
            ->whereRaw("lower(g.value) = 'office'")->selectRaw('k.*')->first();
        return $this->helper->customResponse('OK', 200, $data, true);
    }

     public function custom_get_jadwal_office($req)
    {
        $data = \DB::table('t_jadwal_kerja as t')->selectRaw("t.*")->join('m_general as g','g.id','t.tipe_jam_kerja_id')->where('g.value','OFFICE')
                ->where('status','POSTED')->first();
        return $this->helper->customResponse('OK', 200, $data, true);
    }

     public function custom_post($request)
    {
        try {
            // Begin a database transaction
            \DB::beginTransaction();

            $data = $this->find($request->id);
            if (!$data) {
                return response()->json(
                    ["error" => "Data tidak ditemukan."],
                    404
                );
            }

            $tipe_jam_kerja = m_general::where('id',$data->tipe_jam_kerja_id)->pluck('value')->first();

            $update = $data->update([
                "status" => "POSTED",
            ]);

            if(strtolower($tipe_jam_kerja) == 'office'){
                $update = $this->where('id', '!=' ,$request->id)->where('tipe_jam_kerja_id',$data->tipe_jam_kerja_id)->update([
                    "status" => "EXPIRED",
                ]);
            }

            \DB::commit();
        } catch (\Exception $e) {
            // Handle exception, log error messages, etc.

            // Rollback the transaction in case of any exception
            \DB::rollBack();

            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }

    public function custom_expired($request)
    {
        try {
            // Begin a database transaction
            \DB::beginTransaction();

            $data = $this->find($request->id);
            if (!$data) {
                return response()->json(
                    ["error" => "Data tidak ditemukan."],
                    404
                );
            }

            $update = $data->update([
                "status" => "EXPIRED",
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            // Handle exception, log error messages, etc.

            // Rollback the transaction in case of any exception
            \DB::rollBack();

            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }

    public function custom_generate_det_kary($req)
    {
        $data = m_kary::selectRaw("m_kary.id m_kary_id, m_kary.nama_lengkap, d.nama \"m_dept.nama\", dv.nama \"m_divisi.nama\",m_kary.m_dir_id,m_kary.m_divisi_id,m_kary.m_dept_id")
            ->leftJoin('m_dept as d','d.id','m_kary.m_dept_id')
            ->leftJoin('m_divisi as dv','dv.id','m_kary.m_divisi_id')
            ->join('m_general as g','g.id','m_kary.tipe_jam_kerja_id')
            ->where('g.value','!=','OFFICE')
            ->whereRaw("m_kary.id not in(select d.m_kary_id from t_jadwal_kerja_det d 
                join t_jadwal_kerja t on t.id = d.t_jadwal_kerja_id where t.status = 'POSTED')")
            ->where('m_kary.is_active', true)
            ->get();
        return $this->helper->customResponse('OK', 200, $data);
    }

    public function custom_post_det_kary($req)
    {
        try {
            \DB::beginTransaction();
                
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }

    public function custom_save_jadwal($req){
        $data = collect($req);
            foreach($data as $single){
                $check = t_jadwal_kerja_det::firstOrCreate([
                    't_jadwal_kerja_det_hari_id' => $single['t_jadwal_kerja_det_hari_id'],
                    't_jadwal_kerja_id' => $single['t_jadwal_kerja_id'],
                    'm_kary_id' => $single['m_kary_id']
                ],[
                    'm_dir_id' => $single['m_dir_id'],
                    'm_divisi_id' => $single['m_divisi_id'],
                    'm_dept_id' => $single['m_dept_id'],
                ]);
            }
        return $this->helper->customResponse('Success', 201);        
    }

    public function custom_delete_jadwal($req){
        $t_jadwal_det_head = $req;
        $getData = t_jadwal_kerja_det::where('t_jadwal_kerja_det_hari_id',$t_jadwal_det_head['t_jadwal_kerja_det_hari_id'])
        ->where('t_jadwal_kerja_id',$t_jadwal_det_head['t_jadwal_kerja_id'])
        ->where('m_kary_id',$t_jadwal_det_head['m_kary_id'])->first();
        if($getData){
            $getData->delete();
            return $this->helper->customResponse('Success', 200);
        }else{
            return $this->helper->customResponse('Not Found', 404);
        }
    }

    public function custom_delete_jadwal_bulk($req){
        $data = collect($req['items'])->pluck('id')->toArray();
        try{
            $check = t_jadwal_kerja_det::whereIn('id',$data)->delete();
            return $this->helper->customResponse('Success', 200);
        }catch(\Exception $e){
            return $e->getMessage();
        }

    }
}