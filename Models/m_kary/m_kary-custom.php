<?php

namespace App\Models\CustomModels;
use Carbon\Carbon;

class m_kary extends \App\Models\BasicModels\m_kary
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

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newArrayData = array_merge($arrayData, [
            "kode" => @$arrayData['kode'] ?? $this->helper->generateNomor("KODE KARYAWAN"),
            "comp_id" => auth()->user()->m_comp_id ?? 0,
            "nama_belakang" => $arrayData['nama_belakang'] ?? ""
        ]);
        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function updateAfter( $model, $arrayData, $metaData, $id=null )
    {
        if(@$arrayData['m_dir_id']){
            default_users::where('m_kary_id', $id)->update(['m_dir_id' => $arrayData['m_dir_id']]);
        }
    }
    

    public function transformRowData( array $row )
    {
        $object = [];
        if(app()->request->detail){
            $data = \DB::select("select public.employee_attendance_fix_cuti(?,?) as employee_attendance",[Date('Y-m-d'),$row['id'] ??0]);
            //$data = \DB::select("select public.employee_attendance(?,?) as employee_attendance",[Date('Y-m-d'),$row['id'] ??0]);

            $data = json_decode($data[0]->employee_attendance);
            $object['info_cuti'] = $data;

            $jadwal_kerja = \DB::table('t_jadwal_kerja as t')->selectRaw("t.*")->join('m_general as g','g.id','t.tipe_jam_kerja_id')->where('t.tipe_jam_kerja_id', $row['tipe_jam_kerja_id'])
                ->where('status','POSTED')->first();
            $object['jadwal_kerja'] = $jadwal_kerja;
        }
        $object['nomor_ktp'] = \DB::table('m_kary_det_kartu')->where('m_kary_id', $row['id'] ?? 0)->value('ktp_no') ?? null ;

        if(app()->request->view_gaji){
            $gaji = t_kary_salary::where('m_kary_id', $row['id'])->where('is_active', true)->first();
            $object['gaji'] = @$gaji->total;
            $object['gaji_last_updated_at'] = @$gaji->updated_at;
        }

        return array_merge( $row, $object );
    }

    private function generateNik($compId, $dirId, $divisiId, $posisiId)
    {
        $currentDateTime = \Carbon\Carbon::now();;

        $year = $currentDateTime->format("Y");
        $month = $currentDateTime->format("m");
        $day = $currentDateTime->format("d");

        $lastInsertedId = m_kary::max("id");

        $newId = $lastInsertedId + 1;

        // Create a formatted nik
        $formattedNik = sprintf(
            "%s%s%s%s%s%04d",
            $year,
            $month,
            $day,
            $compId,
            $dirId,
            $newId
        );

        return $formattedNik;
    }



    public function custom_resetCuti($request)
    {
        try{
            \DB::beginTransaction();
            $employees = m_kary::where('is_active', true)->get();

            foreach ($employees as $employee) {
                $employmentStartDate = Carbon::parse($employee->tgl_masuk);  

                $yearsOfWork = $employmentStartDate->diffInYears(Carbon::now());

                $baseLeaveDays = 12;
                $additionalLeaveDays = [3, 5, 0, 3, 0, 0, 3, 0, 5];

                $employee->cuti_sisa_reguler = ($yearsOfWork >= 1) ? $baseLeaveDays + $additionalLeaveDays[($yearsOfWork - 1) % count($additionalLeaveDays)] : -4;

                $employee->cuti_sisa_panjang = 16;
                $employee->exp_date_cuti = Carbon::now()->addYear()->format('Y-m-d');

                $employee->save();
            }

            \DB::commit();
        } catch (\Exception $e) {
            return response()->json([
                "errors" => $e->getMessage(),
            ]);
        }
    }

    public function custom_postKaryawan($request)
    {
        try {
            \DB::beginTransaction();

            $idPelamar = $request->id;
            $status = $request->status;
            $data = t_pelamar::where("id", $idPelamar)->first();
            $dataBlacklist = m_blacklist::all();
            if($status === 'TOLAK'){
                $data->update([
                    "status" => "rejected",
                ]);

                return response()->json([
                    "message" => "Akun Ini Terblacklis",
                ]);
            }
            foreach ($dataBlacklist as $blacklist) {
                if ($blacklist->no_ktp === $data->ktp_no) {
                    $data->update([
                        "status" => "blacklist",
                    ]);

                    return response()->json([
                        "message" => "Akun Ini Terblacklis",
                    ]);
                }
            }


            $karyawan = m_kary::create([
                "ref_id" => $data["id"],
                "m_comp_id" => $data["m_comp_id"] ?? null,
                "m_dir_id" => $data["m_dir_id"] ?? null,
                "m_divisi_id" => $data["m_divisi_id"] ?? null,
                "m_dept_id" => $data["m_dept_id"] ?? null,
                "m_zona_id" => $data["m_zona_id"] ?? 0,
                "grading_id" => $data["grading_id"] ?? 0,
                "costcontre_id" => $data["costcontre_id"] ?? 0,
                "kode" => $this->helper->generateNomor("KODE KARYAWAN"),
                "m_posisi_id" => $data["m_posisi_id"] ?? null,
                "m_jam_kerja_id" => $data["m_jam_kerja_id"] ?? 0,
                "kode_presensi" => $data["kode_presensi"] ?? "",
                "nik" =>
                    $this->generateNik(
                        $data["m_comp_id"],
                        $data["m_dir_id"],
                        $data["m_divisi_id"],
                        $data["m_posisi_id"]
                    ) ?? null,
                "nama_depan" => $data["nama_depan"] ?? "",
                "nama_belakang" => $data["nama_belakang"] ?? "",
                "nama_lengkap" => $data["nama_pelamar"] ?? "",
                "nama_panggilan" => $data["nama_pelamar"] ?? "",
                "jk_id" => $data["jk_id"] ?? null,
                "tempat_lahir" => $data["tempat_lahir"] ?? null,
                "tgl_lahir" => $data["tgl_lahir"] ?? null,
                "provinsi_id" => $data["provinsi_id"] ?? 0,
                "kota_id" => $data["kota_id"] ?? 0,
                "kecamatan_id" => $data["kecamatan_id"] ?? 0,
                "kode_pos" => $data["kode_pos"] ?? 0,
                "alamat_asli" => $data["alamat_asli"] ?? "",
                "alamat_domisili" => $data["alamat_domisili"] ?? "",
                "no_tlp" => $data["telp"] ?? "",
                "no_tlp_lainnya" => $data["no_tlp_lainnya"] ?? "",
                "no_darurat" => $data["no_darurat"] ?? "",
                "nama_kontak_darurat" => $data["nama_kontak_darurat"] ?? "",
                "agama_id" => $data["agama_id"] ?? 0,
                "gol_darah_id" => $data["gol_darah_id"] ?? 0,
                "status_nikah_id" => $data["status_nikah_id"] ?? 0,
                "tanggungan_id" => $data["tanggungan_id"] ?? 0,
                "hub_dgn_karyawan" => $data["hub_dgn_karyawan"] ?? "",
                "cuti_jatah_reguler" => $data["cuti_jatah_reguler"] ?? 12,
                "cuti_sisa_reguler" => $data["cuti_sisa_reguler"] ?? 12,
                "cuti_panjang" => $data["cuti_panjang"] ?? 20,
                "cuti_sisa_panjang" => $data["cuti_sisa_panjang"] ?? 20,
                "status_kary_id" => $data["status_kary_id"] ?? null,
                "lama_kontrak_awal" => $data["lama_kontrak_awal"] ?? null,
                "lama_kontrak_akhir" => $data["lama_kontrak_akhir"] ?? null,
                "tgl_masuk" => $data["tgl_masuk"] ?? null,
                "tgl_berhenti" => $data["tgl_berhenti"] ?? null,
                "alasan_berhenti" => $data["alasan_berhenti"] ?? null,
                "uk_baju" => $data["uk_baju"] ?? "",
                "uk_celana" => $data["uk_celana"] ?? "",
                "uk_sepatu" => $data["uk_sepatu"] ?? "",
                "desc" => $data["desc"] ?? null,
                "is_active" => $data["is_active"] ?? true,
                "m_standart_gaji_id" => $data["m_standart_gaji_id"] ?? 0,
                "periode_gaji_id" => $data["periode_gaji_id"] ?? 0,
            ]);

            \DB::commit();

            return response()->json([
                "message" => "Registrasi Karyawan Berhasil",
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                "errors" => $e->getMessage(),
            ]);
        }
    }

    /**
     * collection data diri karyawan 
     * for mobile profile
     */

    private function defaultDataDiri()
    {
        return 
        [
            "id"   => null,
            "m_comp_id"    => null,
            "m_dir_id" => null,
            "m_divisi_id"  => null,
            "m_dept_id"    => null,
            "m_zona_id"    => null,
            "grading_id"   => null,
            "costcontre_id"    => null,
            "kode" => null,
            "m_posisi_id"  => null,
            "m_jam_kerja_id"   => null,
            "kode_presensi"    => null,
            "nik"  => null,
            "nama_depan"   => null,
            "nama_belakang"    => null,
            "nama_lengkap" => null,
            "nama_panggilan"   => null,
            "jk_id"    => null,
            "tempat_lahir" => null,
            "tgl_lahir"    => null,
            "provinsi_id"  => null,
            "kota_id"  => null,
            "kecamatan_id" => null,
            "kode_pos" => null,
            "alamat_asli"  => null,
            "alamat_domisili"  => null,
            "no_tlp"   => null,
            "no_tlp_lainnya"   => null,
            "no_darurat"   => null,
            "nama_kontak_darurat"  => null,
            "agama_id" => null,
            "gol_darah_id" => null,
            "status_nikah_id"  => null,
            "tanggungan_id"    => null,
            "hub_dgn_karyawan" => null,
            "cuti_jatah_reguler"   => null,
            "cuti_sisa_reguler"    => null,
            "cuti_panjang" => null,
            "cuti_sisa_panjang"    => null,
            "status_kary_id"   => null,
            "lama_kontrak_awal"    => null,
            "lama_kontrak_akhir"   => null,
            "tgl_masuk"    => null,
            "tgl_berhenti" => null,
            "alasan_berhenti"  => null,
            "uk_baju"  => null,
            "uk_celana"    => null,
            "uk_sepatu"    => null,
            "desc" => null,
            "is_active"    => null,
            "creator_id"   => null,
            "last_editor_id"   => null,
            "created_at"   => null,
            "updated_at"   => null,
            "m_standart_gaji_id"   => null,
            "periode_gaji_id"  => null,
            "ref_id"   => null,
            "presensi_lokasi_default_id"   => null,
            "exp_date_cuti"    => null,
            "limit_potong" => null,
            "atasan_id"    => null,
            "cuti_p24" => null,
            "cuti_sisa_p24"    => null,
            "dir"  => null,
            "div"  => null,
            "dept" => null,
            "zona" => null,
            "grading"  => null,
            "posisi"   => null,
            "jam_kerja"    => null,
            "jk"   => null,
            "provinsi" => null,
            "kota" => null,
            "kecamatan"    => null,
            "agama"    => null,
            "gol_darah"    => null,
            "tanggungan"   => null,
            "costcontre"   => null,
            "status_nikah" => null,
            "ktp_no"   => null,
            "ktp_foto" => null,
            "pas_foto" => null,
            "kk_no"    => null,
            "kk_foto"  => null,
            "npwp_no"  => null,
            "npwp_foto"    => null,
            "npwp_tgl_berlaku" => null,
            "bpjs_tipe_id" => null,
            "bpjs_no"  => null,
            "bpjs_no_kesehatan"  => null,
            "bpjs_no_ketenagakerjaan"  => null,
            "bpjs_foto"    => null,
            "berkas_lain"  => null,
            "desc_file"    => null
        ];
    }

    public function custom_data_diri($req)
    {
        $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
        $data = [];
        $data = m_kary::selectRaw("
                m_kary.*,
                dir.nama dir,
                d.nama div,
                dp.nama dept,
                z.nama zona,
                g.value grading,
                z.nama zona,
                p.desc_kerja posisi,
                j.desc jam_kerja,
                jk.value jk,
                prov.value provinsi,
                kota.value kota,
                kec.value kecamatan,
                agama.value agama,
                gol_darah.value gol_darah,
                tanggungan.value tanggungan,
                costcontre.value costcontre,
                status_nikah.value status_nikah
            ")
            ->leftJoin('m_dir as dir','dir.id','m_kary.m_dir_id')
            ->leftJoin('m_divisi as d','d.id','m_kary.m_divisi_id')
            ->leftJoin('m_dept as dp','dp.id','m_kary.m_dept_id')
            ->leftJoin('m_zona as z','z.id','m_kary.m_zona_id')
            ->leftJoin('m_general as g','g.id','m_kary.grading_id')
            ->leftJoin('m_general as c','c.id','m_kary.costcontre_id')
            ->leftJoin('m_posisi as p','p.id','m_kary.m_posisi_id')
            ->leftJoin('m_jam_kerja as j','j.id','m_kary.m_jam_kerja_id')
            ->leftJoin('m_general as jk','jk.id','m_kary.jk_id')
            ->leftJoin('m_general as prov','prov.id','m_kary.provinsi_id')
            ->leftJoin('m_general as kota','kota.id','m_kary.kota_id')
            ->leftJoin('m_general as kec','kec.id','m_kary.kecamatan_id')
            ->leftJoin('m_general as agama','agama.id','m_kary.agama_id')
            ->leftJoin('m_general as gol_darah','gol_darah.id','m_kary.gol_darah_id')
            ->leftJoin('m_general as status_nikah','status_nikah.id','m_kary.status_nikah_id')
            ->leftJoin('m_general as tanggungan','tanggungan.id','m_kary.tanggungan_id')
            ->leftJoin('m_general as costcontre','costcontre.id','m_kary.costcontre_id')
            ->where('m_kary.id',$id_kary)->first();
        if(!$data){
            $data = $this->defaultDataDiri();
        }else{
            $det_kartu = m_kary_det_kartu::with(['bpjs_tipe'])->where('m_kary_id', @$id_kary ?? 0)->first();
            $det_pemb = m_kary_det_pemb::with(['periode_gaji', 'metode', 'tipe' , 'bank'])->where('m_kary_id', @$id_kary ?? 0)->first();
            if($det_kartu){
                $data['ktp_no'] = $det_kartu->ktp_no ?? null;
                $data['ktp_foto'] = $det_kartu->ktp_foto ?? null;
                $data['pas_foto'] = $det_kartu->pas_foto ?? null;
                $data['kk_no'] = $det_kartu->kk_no ?? null;
                $data['kk_foto'] = $det_kartu->kk_foto ?? null;
                $data['npwp_no'] = $det_kartu->npwp_no ?? null;
                $data['npwp_foto'] = $det_kartu->npwp_foto ?? null;
                $data['npwp_tgl_berlaku'] = $det_kartu->npwp_tgl_berlaku ?? null;
                $data['bpjs_tipe'] = $det_kartu->bpjs_tipe->value ?? null;
                $data['bpjs_tipe_id'] = $det_kartu->bpjs_tipe_id ?? null;
                $data['bpjs_no'] = $det_kartu->bpjs_no ?? null;
                $data['bpjs_no_kesehatan'] = $det_kartu->bpjs_no_kesehatan ?? null;
                $data['bpjs_no_ketenagakerjaan'] = $det_kartu->bpjs_no_ketenagakerjaan ?? null;
                $data['bpjs_foto'] = $det_kartu->bpjs_foto ?? null;
                $data['berkas_lain'] = $det_kartu->berkas_lain ?? null;
                $data['desc_file'] = $det_kartu->desc_file ?? null;
                $data['periode_gaji_id'] = $det_pemb->periode_gaji->id ?? null;
                $data['periode_gaji'] = $det_pemb->periode_gaji->value ?? null;
                $data['metode'] = $det_pemb->metode->value ?? null;
                $data['metode_id'] = $det_pemb->metode->id ?? null;
                $data['tipe'] = $det_pemb->tipe->value ?? null;
                $data['tipe_id'] = $det_pemb->tipe->id ?? null;
                $data['bank'] = $det_pemb->bank->value ?? null;
                $data['bank_id'] = $det_pemb->bank->id ?? null;
                $data['no_rek'] = $det_pemb->no_rek ?? null;
                $data['atas_nama_rek'] = $det_pemb->atas_nama_rek ?? null;
                
            }
        }
        return $this->helper->customResponse('OK', 200, $data);
    }

    private function uploadFile($file)
    {
        if ($file) {
            $fileName = md5(time()) . ":::" . $file->getClientOriginalName() . "." . $file->getClientOriginalExtension();
            $file->move(public_path("uploads/m_kary_det_kartu"), $fileName);
            return $fileName;
        }
        return null;
    }

    public function custom_data_diri_update($req)
    {
        try{
            \DB::beginTransaction();
            $id_kary = default_users::where('id',auth()->user()->id)->pluck('m_kary_id')->first();
            $kar = m_kary::where('id',$id_kary)->first();
            if(!$kar) {
                // buat karyawan jika tidak ditemukan kary
                $createHeader = $this->create([
                    "m_comp_id" => $req->m_comp_id,
                    "m_dir_id" => $req->m_dir_id,
                    "m_divisi_id" => $req->m_divisi_id,
                    "m_dept_id" => $req->m_dept_id,
                    "m_zona_id" => $req->m_zona_id,
                    "grading_id" => $req->grading_id ?? null,
                    "costcontre_id" => $req->costcontre_id,
                    "kode" => $req->kode ?? null,
                    "m_posisi_id" => $req->m_posisi_id,
                    "m_jam_kerja_id" => $req->m_jam_kerja_id,
                    "kode_presensi" => $req->kode_presensi ?? null,
                    "nik" => $req->nik,
                    "nama_depan" => $req->nama_depan,
                    "nama_belakang" => $req->nama_belakang ?? "",
                    "nama_lengkap" => $req->nama_lengkap,
                    "nama_panggilan" => $req->nama_panggilan,
                    "jk_id" => $req->jk_id,
                    "tempat_lahir" => $req->tempat_lahir,
                    "tgl_lahir" => $req->tgl_lahir,
                    "provinsi_id" => $req->provinsi_id,
                    "kota_id" => $req->kota_id,
                    "kecamatan_id" => $req->kecamatan_id,
                    "kode_pos" => $req->kode_pos,
                    "alamat_asli" => $req->alamat_asli,
                    "alamat_domisili" => $req->alamat_domisili,
                    "no_tlp" => $req->no_tlp,
                    "no_tlp_lainnya" => $req->no_tlp_lainnya,
                    "no_darurat" => $req->no_darurat,
                    "nama_kontak_darurat" => $req->nama_kontak_darurat,
                    "agama_id" => $req->agama_id,
                    "gol_darah_id" => $req->gol_darah_id,
                    "status_nikah_id" => $req->status_nikah_id,
                    "tanggungan_id" => $req->tanggungan_id,
                    "hub_dgn_karyawan" => $req->hub_dgn_karyawan,
                    "cuti_jatah_reguler" => $req->cuti_jatah_reguler,
                    "cuti_sisa_reguler" => $req->cuti_sisa_reguler,
                    "cuti_panjang" => $req->cuti_panjang,
                    "cuti_sisa_panjang" => $req->cuti_sisa_panjang,
                    "status_kary_id" => $req->status_kary_id ?? null,
                    "lama_kontrak_awal" => $req->lama_kontrak_awal ?? null,
                    "lama_kontrak_akhir" => $req->lama_kontrak_akhir ?? null,
                    "tgl_masuk" => $req->tgl_masuk,
                    "tgl_berhenti" => $req->tgl_berhenti ?? null,
                    "alasan_berhenti" => $req->alasan_berhenti ?? null,
                    "uk_baju" => $req->uk_baju,
                    "uk_celana" => $req->uk_celana,
                    "uk_sepatu" => $req->uk_sepatu,
                    "desc" => $req->desc ?? null
                ]);
                // update user -> isikan m_kary_id
                default_users::where('id',auth()->user()->id)->update(['m_kary_id'=>$createHeader->id]);
            }else{
                $createHeader = m_kary::where('id', $id_kary)->update([
                    "m_comp_id" => $req->m_comp_id ?? $kar->m_comp_id,
                    "m_dir_id" => $req->m_dir_id ?? $kar->m_dir_id,
                    "m_divisi_id" => $req->m_divisi_id ?? $kar->m_divisi_id,
                    "m_dept_id" => $req->m_dept_id ?? $kar->m_dept_id,
                    "m_zona_id" => $req->m_zona_id ?? $kar->m_zona_id,
                    "grading_id" => $req->grading_id ?? $kar->grading_id ?? null,
                    "costcontre_id" => $req->costcontre_id ?? $kar->costcontre_id,
                    "kode" => $req->kode ?? $kar->kode ?? null,
                    "m_posisi_id" => $req->m_posisi_id ?? $kar->m_posisi_id,
                    "m_jam_kerja_id" => $req->m_jam_kerja_id ?? $kar->m_jam_kerja_id,
                    "kode_presensi" => $req->kode_presensi ?? $kar->kode_presensi ?? null,
                    "nik" => $req->nik ?? $kar->nik,
                    "nama_depan" => $req->nama_depan ?? $kar->nama_depan,
                    "nama_belakang" => $req->nama_belakang ?? $kar->nama_belakang,
                    "nama_lengkap" => $req->nama_lengkap ?? $kar->nama_lengkap,
                    "nama_panggilan" => $req->nama_panggilan ?? $kar->nama_panggilan,
                    "jk_id" => $req->jk_id ?? $kar->jk_id,
                    "tempat_lahir" => $req->tempat_lahir ?? $kar->tempat_lahir,
                    "tgl_lahir" => $req->tgl_lahir ?? $kar->tgl_lahir,
                    "provinsi_id" => $req->provinsi_id ?? $kar->provinsi_id,
                    "kota_id" => $req->kota_id ?? $kar->kota_id,
                    "kecamatan_id" => $req->kecamatan_id ?? $kar->kecamatan_id,
                    "kode_pos" => $req->kode_pos ?? $kar->kode_pos,
                    "alamat_asli" => $req->alamat_asli ?? $kar->alamat_asli,
                    "alamat_domisili" => $req->alamat_domisili ?? $kar->alamat_domisili,
                    "no_tlp" => $req->no_tlp ?? $kar->no_tlp,
                    "no_tlp_lainnya" => $req->no_tlp_lainnya ?? $kar->no_tlp_lainnya,
                    "no_darurat" => $req->no_darurat ?? $kar->no_darurat,
                    "nama_kontak_darurat" => $req->nama_kontak_darurat ?? $kar->nama_kontak_darurat,
                    "agama_id" => $req->agama_id ?? $kar->agama_id,
                    "gol_darah_id" => $req->gol_darah_id ?? $kar->gol_darah_id,
                    "status_nikah_id" => $req->status_nikah_id ?? $kar->status_nikah_id,
                    "tanggungan_id" => $req->tanggungan_id ?? $kar->tanggungan_id,
                    "hub_dgn_karyawan" => $req->hub_dgn_karyawan ?? $kar->hub_dgn_karyawan,
                    "cuti_jatah_reguler" => $req->cuti_jatah_reguler ?? $kar->cuti_jatah_reguler,
                    "cuti_sisa_reguler" => $req->cuti_sisa_reguler ?? $kar->cuti_sisa_reguler,
                    "cuti_panjang" => $req->cuti_panjang ?? $kar->cuti_panjang,
                    "cuti_sisa_panjang" => $req->cuti_sisa_panjang ?? $kar->cuti_sisa_panjang,
                    "status_kary_id" => $req->status_kary_id ?? $kar->status_kary_id ?? null,
                    "lama_kontrak_awal" => $req->lama_kontrak_awal ?? $kar->lama_kontrak_awal ?? null,
                    "lama_kontrak_akhir" => $req->lama_kontrak_akhir ?? $kar->lama_kontrak_akhir ?? null,
                    "tgl_masuk" => $req->tgl_masuk ?? $kar->tgl_masuk,
                    "tgl_berhenti" => $req->tgl_berhenti ?? $kar->tgl_berhenti ?? null,
                    "alasan_berhenti" => $req->alasan_berhenti ?? $kar->alasan_berhenti ?? null,
                    "uk_baju" => $req->uk_baju ?? $kar->uk_baju,
                    "uk_celana" => $req->uk_celana ?? $kar->uk_celana,
                    "uk_sepatu" => $req->uk_sepatu ?? $kar->uk_sepatu,
                    "desc" => $req->desc ?? $kar->desc ?? null
                ]);
            }
            \DB::commit();

            
            if($createHeader){
                $check = m_kary_det_kartu::where('m_kary_id', $id_kary)->first();
                $check_pemb = m_kary_det_pemb::where('m_kary_id', $id_kary)->first();


                $file = $req->file('ktp_foto');
                $fileName_ktp = $this->uploadFile($file);
                // if(!$fileName_ktp) return $this->helper->customResponse('Foto KTP tidak valid, silahkan melakukan upload ulang file', 422);

                $file = $req->file('pas_foto');
                $fileName_pas = $this->uploadFile($file);
                // if(!$fileName_pas) return $this->helper->customResponse('Pas foto tidak valid, silahkan melakukan upload ulang file', 422);

                $file = $req->file('kk_foto');
                $fileName_kk = $this->uploadFile($file);
                // if(!$fileName_kk) return $this->helper->customResponse('Foto KK tidak valid, silahkan melakukan upload ulang file', 422);

                $file = $req->file('npwp_foto');
                $fileName_npwp = $this->uploadFile($file);
                // if(!$fileName_npwp) return $this->helper->customResponse('Foto NPWP tidak valid, silahkan melakukan upload ulang file', 422);

                $file = $req->file('bpjs_foto');
                $fileName_bpjs = $this->uploadFile($file);
                // if(!$fileName_bpjs) return $this->helper->customResponse('Foto BPJS tidak valid, silahkan melakukan upload ulang file', 422);

                $file = $req->file('berkas_lain');
                $fileName_berkas = $this->uploadFile($file);
                // if(!$fileName_berkas) return $this->helper->customResponse('Upload berkas lain tidak valid, silahkan melakukan upload ulang file', 422);
                \DB::beginTransaction();
                if($check){

                    \DB::table('m_kary_det_kartu')
                        ->where('m_kary_id', $id_kary)
                        ->update([
                        "m_kary_id" => $id_kary,
                        "ktp_no" => $req->ktp_no ?? @$check->ktp_no,
                        "ktp_foto" => $fileName_ktp ?? @$check->ktp_foto,
                        "pas_foto" => $fileName_pas ?? @$check->pas_foto,
                        "kk_no" => $req->kk_no ?? @$check->kk_no,
                        "kk_foto" => $fileName_kk ?? @$check->kk_foto,
                        "npwp_no" => $req->npwp_no ?? @$check->npwp_no,
                        "npwp_foto" => $fileName_npwp ?? @$check->npwp_foto,
                        "npwp_tgl_berlaku" => $req->npwp_tgl_berlaku ?? @$check->npwp_tgl_berlaku,
                        "bpjs_tipe_id" => $req->bpjs_tipe_id ?? @$check->bpjs_tipe_id,
                        "bpjs_no" => $req->bpjs_no ?? @$check->bpjs_no,
                        "bpjs_no_kesehatan" => $req->bpjs_no_kesehatan ?? @$check->bpjs_no_kesehatan,
                        "bpjs_no_ketenagakerjaan" => $req->bpjs_no_ketenagakerjaan ?? @$check->bpjs_no_ketenagakerjaan,
                        "bpjs_foto" => $fileName_bpjs ?? @$check->bpjs_foto,
                        "berkas_lain" => $fileName_berkas ?? @$check->berkas_lain,
                        "desc_file" => $req->desc_file ?? @$check->desc_file,
                    ]);           
                }else{
                        \DB::table('m_kary_det_kartu')->insert([
                            "m_kary_id" => $id_kary,
                            "ktp_no" => @$req->ktp_no,
                            "ktp_foto" => @$fileName_ktp,
                            "pas_foto" => @$fileName_pas,
                            "kk_no" => @$req->kk_no,
                            "kk_foto" => @$fileName_kk,
                            "npwp_no" => @$req->npwp_no,
                            "npwp_foto" => @$fileName_npwp,
                            "npwp_tgl_berlaku" => @$req->npwp_tgl_berlaku,
                            "bpjs_tipe_id" => @$req->bpjs_tipe_id,
                            "bpjs_no" => @$req->bpjs_no,
                            "bpjs_no_kesehatan" => @$req->bpjs_no_kesehatan,
                            "bpjs_no_ketenagakerjaan" => @$req->bpjs_no_ketenagakerjaan ,
                            "bpjs_foto" => @$fileName_bpjs,
                            "berkas_lain" => @$fileName_berkas,
                            "desc_file" => @$req->desc_file
                        ]);
                }
                \DB::commit();

                \DB::beginTransaction();
                if($check_pemb){
                    \DB::table('m_kary_det_pemb')
                        ->where('m_kary_id', $id_kary)
                        ->update([
                            'bank_id' => @$req->bank_id ?? @$check_pemb->bank_id,
                            'no_rek' => @$req->no_rek ?? @$check_pemb->no_rek,
                            'atas_nama_rek' => @$req->atas_nama_rek ?? @$check_pemb->atas_nama_rek
                    ]);
                }else{
                    \DB::table('m_kary_det_pemb')
                        ->insert([
                            'm_kary_id' => $id_kary,
                            'm_comp_id' => @$req->m_comp_id,
                            'm_dir_id' => @$req->m_dir_id,
                            'periode_gaji_id' => @$req->periode_gaji_id ?? 362,
                            'metode_id' => @$req->metode_id ?? 0,
                            'tipe_id' => @$req->tipe_id ?? 956,
                            'bank_id' => @$req->bank_id ?? 0,
                            'no_rek' => @$req->no_rek ?? 0,
                            'atas_nama_rek' => @$req->atas_nama_rek ?? 0,
                            'desc' => @$req->desc
                    ]);
    
                }       
                \DB::commit();
            }
           
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }
        return $this->helper->customResponse('Data diri berhasil diupdate');
    }

    /**
     * collection data pendidikan karyawan 
     * for mobile profile
     */

    public function custom_pendidikan($req)
    {
        $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
        $tbl = 'm_kary_det_pend';
        $data = m_kary_det_pend::query()
            ->selectRaw("
                k.nama_lengkap karyawan,
                tingkat.value as tingkat,
                kota.value as kota,
                $tbl.*
            ")
            ->leftJoin('m_general as tingkat','tingkat.id',"$tbl.tingkat_id")
            ->leftJoin('m_general as kota','kota.id',"$tbl.kota_id")
            ->join('m_kary as k','k.id',"$tbl.m_kary_id")
            ->orderBy("$tbl.created_at", 'desc')
            ->where("$tbl.m_kary_id",$id_kary)->get();
        return $this->helper->customResponse('OK', 200, $data);
    }

    public function custom_pendidikan_create($req)
    {
        \DB::beginTransaction();
        $fileName = null;

        if ($req->hasFile("ijazah_foto")) {
            $file = $req->file("ijazah_foto");
            $fileName =
                md5(time()) .
                ":::" .
                $file->getClientOriginalName().
                "." .
                $file->getClientOriginalExtension();
            $file->move(public_path("uploads/m_kary_det_pend"), $fileName);
        } 

        try{
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            \DB::table('m_kary_det_pend')->insert([
                'm_kary_id' => $id_kary,
                'tingkat_id' => $req->tingkat_id,
                'nama_sekolah' => $req->nama_sekolah,
                'thn_masuk' => $req->thn_masuk,
                'thn_lulus' => $req->thn_lulus,
                'kota_id' => $req->kota_id,
                'nilai' => $req->nilai,
                'jurusan' => $req->jurusan,
                'is_pend_terakhir' => $req->is_pend_terakhir,
                'ijazah_no' => $req->ijazah_no,
                'ijazah_foto' => $fileName,
                'desc' => $req->desc,
                'creator_id' => auth()->user()->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data pendidikan berhasil ditambahkan');
    }

    public function custom_pendidikan_update($req)
    {
        \DB::beginTransaction();
        $id = $req->id;
        try {
            $pendidikan = \DB::table('m_kary_det_pend')->find($id);

            if (!$pendidikan) {
                return $this->helper->customResponse('Data pendidikan tidak ditemukan', 404);
            }

            $fileName = $pendidikan->ijazah_foto;

            if ($req->hasFile("ijazah_foto")) {
                $oldFilePath = public_path("uploads/m_kary_det_pend/{$fileName}");
                if ($fileName && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }

                $file = $req->file("ijazah_foto");
                $fileName =
                    md5(time()) .
                    ":::" .
                    $file->getClientOriginalName() .
                    "." .
                    $file->getClientOriginalExtension();
                $file->move(public_path("uploads/m_kary_det_pend"), $fileName);
            } else {
                $fileName = $pendidikan->ijazah_foto;
            }

            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            \DB::table('m_kary_det_pend')->where('id', $id)->update([
                'm_kary_id' => $id_kary,
                'tingkat_id' => $req->tingkat_id ?? $pendidikan->tingkat_id,
                'nama_sekolah' => $req->nama_sekolah ?? $pendidikan->nama_sekolah,
                'thn_masuk' => $req->thn_masuk ?? $pendidikan->thn_masuk,
                'thn_lulus' => $req->thn_lulus ?? $pendidikan->thn_lulus,
                'kota_id' => $req->kota_id ?? $pendidikan->kota_id,
                'nilai' => $req->nilai ?? $pendidikan->nilai,
                'jurusan' => $req->jurusan ?? $pendidikan->jurusan,
                'is_pend_terakhir' => $req->is_pend_terakhir ?? $pendidikan->is_pend_terakhir,
                'ijazah_no' => $req->ijazah_no ?? $pendidikan->ijazah_no,
                'ijazah_foto' => $fileName,
                'desc' => $req->desc ?? $pendidikan->desc,
                'creator_id' => auth()->user()->id,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data pendidikan berhasil diperbarui');
    }


    public function custom_pendidikan_delete($req)
    {
        \DB::beginTransaction();
        try{
            m_kary_det_pend::find($req->id)->delete();
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data pendidikan berhasil dihapus');
    }

    public function custom_keluarga($req)
    {
        try {
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            
            $data = m_kary_det_kel::where('m_kary_id', $id_kary)
            ->select(
                'm_kary_det_kel.*', 
                'keluarga.value AS keluarga', 
                'pend_terakhir.value AS pendidikan', 
                'jk.value AS jenis_kelamin', 
                'pekerjaan.value AS pekerjaan'
            )
            ->leftJoin('m_general AS keluarga', 'm_kary_det_kel.keluarga_id', '=', 'keluarga.id')
            ->leftJoin('m_general AS pend_terakhir', 'm_kary_det_kel.pend_terakhir_id', '=', 'pend_terakhir.id')
            ->leftJoin('m_general AS jk', 'm_kary_det_kel.jk_id', '=', 'jk.id')
            ->leftJoin('m_general AS pekerjaan', 'm_kary_det_kel.pekerjaan_id', '=', 'pekerjaan.id')
            ->orderBy('m_kary_det_kel.created_at', 'desc')
            ->get();

            return $this->helper->customResponse('OK', 200, $data);
        } catch (\Exception $e) {
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_keluarga_create($req)
    {
        try{
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            \DB::table('m_kary_det_kel')->insert([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->m_comp_id ?? null,
                'm_dir_id' => $req->m_dir_id ?? null,
                'keluarga_id' => $req->keluarga_id,
                'nama' => $req->nama,
                'pend_terakhir_id' => $req->pend_terakhir_id,
                'jk_id' => $req->jk_id,
                'pekerjaan_id' => $req->pekerjaan_id,
                'usia' => $req->usia,
                'desc' => $req->desc,
                'creator_id' => auth()->user()->id,
                'last_editor_id' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data keluarga berhasil ditambahkan');
    }

    public function custom_keluarga_update($req)
    {
        \DB::beginTransaction();
        $id = $req->id;
        try {
            $keluarga = \DB::table('m_kary_det_kel')->find($id);

            if (!$keluarga) {
                return $this->helper->customResponse('Data keluarga tidak ditemukan', 404);
            }

            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;

            \DB::table('m_kary_det_kel')->where('id', $id)->update([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->input('m_comp_id', $keluarga->m_comp_id),
                'm_dir_id' => $req->input('m_dir_id', $keluarga->m_dir_id),
                'keluarga_id' => $req->input('keluarga_id', $keluarga->keluarga_id),
                'nama' => $req->input('nama', $keluarga->nama),
                'pend_terakhir_id' => $req->input('pend_terakhir_id', $keluarga->pend_terakhir_id),
                'jk_id' => $req->input('jk_id', $keluarga->jk_id),
                'pekerjaan_id' => $req->input('pekerjaan_id', $keluarga->pekerjaan_id),
                'usia' => $req->input('usia', $keluarga->usia),
                'desc' => $req->input('desc', $keluarga->desc),
                'last_editor_id' => auth()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data keluarga berhasil diperbarui');
    }


    public function custom_keluarga_delete($req)
    {
        \DB::beginTransaction();
        try{

            m_kary_det_kel::find($req->id)->delete();
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data keluarga berhasil dihapus');
    }

    public function custom_pelatihan($req)
    {
        try {
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            
            $data = m_kary_det_pel::where('m_kary_id', $id_kary)
            ->select(
                'm_kary_det_pel.*', 
                'kota.value AS kota'
            )
            ->leftJoin('m_general AS kota', 'm_kary_det_pel.kota_id', '=', 'kota.id')
            ->orderBy('m_kary_det_pel.created_at', 'desc')
            ->get();

            return $this->helper->customResponse('OK', 200, $data);
        } catch (\Exception $e) {
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_pelatihan_create($req)
    {
        try{
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            \DB::table('m_kary_det_pel')->insert([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->m_comp_id ?? null,
                'm_dir_id' => $req->m_dir_id ?? null,
                'nama_pel' => $req->nama_pel,
                'tahun' => $req->tahun,
                'nama_lem' => $req->nama_lem,
                'kota_id' => $req->kota_id ?? null,
                'creator_id' => auth()->user()->id,
                'last_editor_id' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data pelatihan berhasil ditambahkan');
    }

    public function custom_pelatihan_update($req)
    {
        \DB::beginTransaction();
        $id = $req->id;
        try {
            $pelatihan = \DB::table('m_kary_det_pel')->find($id);

            if (!$pelatihan) {
                return $this->helper->customResponse('Data pelatihan tidak ditemukan', 404);
            }

            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;

            \DB::table('m_kary_det_pel')->where('id', $id)->update([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->input('m_comp_id', $pelatihan->m_comp_id),
                'm_dir_id' => $req->input('m_dir_id', $pelatihan->m_dir_id),
                'nama_pel' => $req->input('nama_pel', $pelatihan->nama_pel),
                'tahun' => $req->input('tahun', $pelatihan->tahun),
                'nama_lem' => $req->input('nama_lem', $pelatihan->nama_lem),
                'kota_id' => $req->input('kota_id', $pelatihan->kota_id),
                'last_editor_id' => auth()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data pelatihan berhasil diperbarui');
    }


    public function custom_pelatihan_delete($req)
    {
        \DB::beginTransaction();
        try{

            m_kary_det_pel::find($req->id)->delete();
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data keluarga berhasil dihapus');
    }

    public function custom_prestasi($req)
    {
        try {
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            
            $data = m_kary_det_pres::where('m_kary_id', $id_kary)
            ->select(
                'm_kary_det_pres.*', 
                'tingkat.value AS tingkat_prestasi'
            )
            ->leftJoin('m_general AS tingkat', 'm_kary_det_pres.tingkat_pres_id', '=', 'tingkat.id')
            ->orderBy('m_kary_det_pres.created_at', 'desc')
            ->get();

            return $this->helper->customResponse('OK', 200, $data);
        } catch (\Exception $e) {
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_prestasi_create($req)
    {
        try{
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            \DB::table('m_kary_det_pres')->insert([
                'm_kary_id' => $id_kary,
               	'm_comp_id' => $req->m_comp_id ?? null,
                'm_dir_id' => $req->m_dir_id ?? null,
                'nama_pres' => $req->nama_pres,
                'tahun' => $req->tahun,
                'tingkat_pres_id' => $req->tingkat_pres_id,
                'desc' => $req->desc,
                'creator_id' => auth()->user()->id,
                'last_editor_id' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data prestasi berhasil ditambahkan');
    }

    public function custom_prestasi_update($req)
    {
        \DB::beginTransaction();
        $id = $req->id;
        try {
            $prestasi = \DB::table('m_kary_det_pres')->find($id);

            if (!$prestasi) {
                return $this->helper->customResponse('Data prestasi tidak ditemukan', 404);
            }

            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;

            \DB::table('m_kary_det_pres')->where('id', $id)->update([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->input('m_comp_id', $prestasi->m_comp_id),
                'm_dir_id' => $req->input('m_dir_id', $prestasi->m_dir_id),
                'nama_pres' => $req->input('nama_pres', $prestasi->nama_pres),
                'tahun' => $req->input('tahun', $prestasi->tahun),
                'tingkat_pres_id' => $req->input('tingkat_pres_id', $prestasi->tingkat_pres_id),
                'desc' => $req->input('desc', $prestasi->desc),
                'last_editor_id' => auth()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data prestasi berhasil diperbarui');
    }


    public function custom_prestasi_delete($req)
    {
        \DB::beginTransaction();
        try{

            m_kary_det_pres::find($req->id)->delete();
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data prestasi berhasil dihapus');
    }

    public function custom_organisasi($req)
    {
        try {
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            
            $data = m_kary_det_org::where('m_kary_id', $id_kary)
            ->select(
                'm_kary_det_org.*', 
                'jenis.value AS jenis_organisasi',
                'kota.value AS kota',
            )
            ->leftJoin('m_general AS jenis', 'm_kary_det_org.jenis_org_id', '=', 'jenis.id')
            ->leftJoin('m_general AS kota', 'm_kary_det_org.kota_id', '=', 'kota.id')
            ->orderBy('m_kary_det_org.created_at', 'desc')
            ->get();

            return $this->helper->customResponse('OK', 200, $data);
        } catch (\Exception $e) {
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_organisasi_create($req)
    {
        try{
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            \DB::table('m_kary_det_org')->insert([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->m_comp_id ?? null,
                'm_dir_id' => $req->m_dir_id ?? null,
                'nama' => $req->nama,
                'tahun' => $req->tahun,
                'jenis_org_id' => $req->jenis_org_id,
                'kota_id' => $req->kota_id,
                'posisi' => $req->posisi,
                'desc' => $req->desc,
                'creator_id' => auth()->user()->id,
                'last_editor_id' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data organisasi berhasil ditambahkan');
    }

    public function custom_organisasi_update($req)
    {
        \DB::beginTransaction();
        $id = $req->id;
        try {
            $organisasi = \DB::table('m_kary_det_org')->find($id);

            if (!$organisasi) {
                return $this->helper->customResponse('Data organisasi tidak ditemukan', 404);
            }

            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;

            \DB::table('m_kary_det_org')->where('id', $id)->update([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->input('m_comp_id', $organisasi->m_comp_id),
                'm_dir_id' => $req->input('m_dir_id', $organisasi->m_dir_id),
                'nama' => $req->input('nama', $organisasi->nama),
                'tahun' => $req->input('tahun', $organisasi->tahun),
                'jenis_org_id' => $req->input('jenis_org_id', $organisasi->jenis_org_id),
                'kota_id' => $req->input('kota_id', $organisasi->kota_id),
                'posisi' => $req->input('posisi', $organisasi->posisi),
                'desc' => $req->input('desc', $organisasi->desc),
                'last_editor_id' => auth()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data organisasi berhasil diperbarui');
    }


    public function custom_organisasi_delete($req)
    {
        \DB::beginTransaction();
        try{

            m_kary_det_org::find($req->id)->delete();
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data organisasi berhasil dihapus');
    }

    public function custom_bahasa($req)
    {
        try {
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            
            $data = m_kary_det_bhs::where('m_kary_id', $id_kary)
            ->orderBy('created_at', 'desc')
            ->get();

            return $this->helper->customResponse('OK', 200, $data);
        } catch (\Exception $e) {
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_bahasa_create($req)
    {
        try{
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            \DB::table('m_kary_det_bhs')->insert([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->m_comp_id ?? null,
                'm_dir_id' => $req->m_dir_id ?? null,
                'bhs_dikuasai' => $req->bhs_dikuasai,
                'nilai_lisan' => $req->nilai_lisan,
                'nilai_tertulis' => $req->nilai_tertulis,
                'level_lisan' => $req->level_lisan,
                'level_tertulis' => $req->level_tertulis,
                'desc' => $req->desc,
                'creator_id' => auth()->user()->id,
                'last_editor_id' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data bahasa berhasil ditambahkan');
    }

    public function custom_bahasa_update($req)
    {
        \DB::beginTransaction();
        $id = $req->id;
        try {
            $bahasa = \DB::table('m_kary_det_bhs')->find($id);

            if (!$bahasa) {
                return $this->helper->customResponse('Data bahasa tidak ditemukan', 404);
            }

            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;

            \DB::table('m_kary_det_bhs')->where('id', $id)->update([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->input('m_comp_id', $bahasa->m_comp_id),
                'm_dir_id' => $req->input('m_dir_id', $bahasa->m_dir_id),
                'bhs_dikuasai' => $req->input('bhs_dikuasai', $bahasa->bhs_dikuasai),
                'nilai_lisan' => $req->input('nilai_lisan', $bahasa->nilai_lisan),
                'nilai_tertulis' => $req->input('nilai_tertulis', $bahasa->nilai_tertulis),
                'level_lisan' => $req->input('level_lisan', $bahasa->level_lisan),
                'level_tertulis' => $req->input('level_tertulis', $bahasa->level_tertulis),
                'desc' => $req->input('desc', $bahasa->desc),
                'last_editor_id' => auth()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data bahasa berhasil diperbarui');
    }


    public function custom_bahasa_delete($req)
    {
        \DB::beginTransaction();
        try{

            m_kary_det_bhs::find($req->id)->delete();
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data bahasa berhasil dihapus');
    }

    public function custom_pk($req)
    {
        try {
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;
            
            $data = m_kary_det_pk::where('m_kary_id', $id_kary)
            ->select(
                'm_kary_det_pk.*', 
                'kota.value AS kota',
            )
            ->leftJoin('m_general AS kota', 'm_kary_det_pk.kota_id', '=', 'kota.id')
            ->orderBy('m_kary_det_pk.created_at', 'desc')
            ->get();

            return $this->helper->customResponse('OK', 200, $data);
        } catch (\Exception $e) {
            return $this->helper->responseCatch($e);
        }
    }

    public function custom_pk_create($req)
    {
        try{
            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;

            $file = $req->file('surat_referensi');
            $fileName_berkas = $this->uploadFile($file);

            \DB::table('m_kary_det_pk')->insert([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->m_comp_id ?? null,
                'm_dir_id' => $req->m_dir_id ?? null,
                'instansi' => $req->instansi,
                'bidang_usaha' => $req->bidang_usaha,
                'no_tlp' => $req->no_tlp,
                'posisi' => $req->posisi,
                'thn_masuk' => $req->thn_masuk,
                'thn_keluar' => $req->thn_keluar,
                'alamat_kantor' => $req->alamat_kantor,
                'kota_id' => $req->kota_id,
                'surat_referensi' => $fileName_berkas,
                'creator_id' => auth()->user()->id,
                'last_editor_id' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data pengalaman kerja berhasil ditambahkan');
    }

    public function custom_pk_update($req)
    {
        \DB::beginTransaction();
        $id = $req->id;
        try {
            $pengalaman_kerja = \DB::table('m_kary_det_pk')->find($id);

            if (!$pengalaman_kerja) {
                return $this->helper->customResponse('Data pengalaman kerja tidak ditemukan', 404);
            }

            $id_kary = default_users::find(auth()->user()->id)->m_kary_id;

            $file = $req->file('surat_referensi');
            $fileName_berkas = $pengalaman_kerja->surat_referensi;

            if ($file) {
                $oldFilePath = public_path("uploads/m_kary_det_pk/{$fileName_berkas}");
                if ($fileName_berkas&& file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
                $fileName_berkas = $this->uploadFile($file);
            }else{
                $fileName_berkas = $pengalaman_kerja->surat_referensi;
            }

            \DB::table('m_kary_det_pk')->where('id', $id)->update([
                'm_kary_id' => $id_kary,
                'm_comp_id' => $req->input('m_comp_id', $pengalaman_kerja->m_comp_id),
                'm_dir_id' => $req->input('m_dir_id', $pengalaman_kerja->m_dir_id),
                'instansi' => $req->input('instansi', $pengalaman_kerja->instansi),
                'bidang_usaha' => $req->input('bidang_usaha', $pengalaman_kerja->bidang_usaha),
                'no_tlp' => $req->input('no_tlp', $pengalaman_kerja->no_tlp),
                'posisi' => $req->input('posisi', $pengalaman_kerja->posisi),
                'thn_masuk' => $req->input('thn_masuk', $pengalaman_kerja->thn_masuk),
                'thn_keluar' => $req->input('thn_keluar', $pengalaman_kerja->thn_keluar),
                'alamat_kantor' => $req->input('alamat_kantor', $pengalaman_kerja->alamat_kantor),
                'kota_id' => $req->input('kota_id', $pengalaman_kerja->kota_id),
                'surat_referensi' => $fileName_berkas,
                'last_editor_id' => auth()->user()->id,
                'updated_at' => Carbon::now(),
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data pengalaman kerja berhasil diperbarui');
    }


    public function custom_pk_delete($req)
    {
        \DB::beginTransaction();
        try{

            m_kary_det_pk::find($req->id)->delete();
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return $this->helper->responseCatch($e);
        }

        return $this->helper->customResponse('Data pengalaman kerja berhasil dihapus');
    }

    public function scopeKaryawanOffice($model)
    {
        return $model->join('m_general','m_general.id','m_kary.tipe_jam_kerja_id')
            ->whereRaw("lower(m_general.code) = 'office'");
    }

    public function scopeKaryawanShift($model)
    {
        return $model->join('m_general','m_general.id','m_kary.tipe_jam_kerja_id')
            ->whereRaw("lower(m_general.code) != 'office'")
            ->orWhere('m_kary.tipe_jam_kerja_id',null);
    }

    public function scopeNotInGenerate($model)
    {
        $t_jadwal_kerja_id = app()->request->t_jadwal_kerja_id;

        return $model
        ->whereRaw("
            m_kary.id not in(select d.m_kary_id from t_jadwal_kerja_det d 
            join t_jadwal_kerja t on t.id = d.t_jadwal_kerja_id where t.status = 'POSTED')
            or m_kary.id in(select d.m_kary_id from t_jadwal_kerja_det d 
            join t_jadwal_kerja t on t.id = d.t_jadwal_kerja_id where t.id = ?)
        ", [$t_jadwal_kerja_id ?? 0]);
    }


}
