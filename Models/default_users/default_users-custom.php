<?php

namespace App\Models\CustomModels;

use DB;

class default_users extends \App\Models\BasicModels\default_users
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore('Helper');
        // SELECT * FROM default_users
    }

    protected $hidden = ["password"];

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function presensi_absensi() :\HasMany
    {
        return $this->HasMany('App\Models\BasicModels\presensi_absensi', 'default_user_id', 'id');
    }

    public function transformRowData( array $row )
    {
        $object = [];
        $req = app()->request;
        if($req->from == 'role_access'){
            $object['is_superadmin'] = 
                m_role_access::join('m_role as r','r.id','m_role_access.m_role_id')
                ->where('m_role_access.user_id', $row['id'])
                ->pluck('is_superadmin')->first();
            if($req->detail)
                $object['detail'] =  m_role_access::join('m_role as r','r.id','m_role_access.m_role_id')
                ->select("r.*")
                ->where('m_role_access.user_id', $row['id'])
                ->get();
        }
        if($req->withKary){
            $kary = m_kary::find($row['m_kary_id']);
            $object['nama_lengkap'] = @$kary->nama_lengkap;
            $object['m_kary_id'] = @$kary->id;
            $object['kode'] = @$kary->kode;
            $object['nik'] = @$kary->nik;
            $object['divisi'] = @m_divisi::find($kary->m_divisi_id)->nama;
            $object['dept'] = @m_dept::find($kary->m_dept_id)->nama;
            $object['dir'] = @m_dir::find($kary->m_dir_id)->nama;

        }
        $object['atasan'] = m_kary::where('id',@$row['m_kary.atasan_id']??0)->pluck('nama_lengkap')->first();

        if(app()->request->header('Source') === 'mobile'){
            //$data = \DB::select("select public.employee_attendance(?,?)",[Date('Y-m-d'),$row['m_kary_id'] ??0]);
            $data = \DB::select("select public.employee_attendance_fix_cuti(?,?) as employee_attendance",[Date('Y-m-d'),$row['m_kary_id'] ??0]);

            $data = json_decode($data[0]->employee_attendance);
            $object['m_kary.sisa_cuti_satu_hari'] = $data->sisa_cuti_satu_hari ?? 0;
            $object['m_kary.sisa_cuti_setengah_hari'] = $data->sisa_cuti_setengah_hari ?? 0;
            $object['m_kary.cuti_satu_hari'] = $data->cuti_satu_hari ?? 0;
            $object['m_kary.cuti_setengah_hari'] = $data->cuti_setengah_hari ?? 0;
            $object['info_cuti'] = $data;
            
        }
        $object['profil_image'] = url('').'/'.$row['profil_image'];
        
        return array_merge( $row, $object );
    }

    // public function onRetrieved($model)
    // {
    //     $req = app()->request;
    //     if($req->from == 'role_access'){
    //         $model->is_superadmin = 
    //             m_role_access::join('m_role as r','r.id','m_role_access.m_role_id')
    //             ->where('m_role_access.user_id', $model->id)
    //             ->pluck('is_superadmin')->first();
    //         if($req->detail)
    //             $model->detail =  m_role_access::join('m_role as r','r.id','m_role_access.m_role_id')
    //             ->select("r.*")
    //             ->where('m_role_access.user_id', $model->id)
    //             ->get();
    //     }
    //     if($req->withKary){
    //         $kary = m_kary::find($model->m_kary_id);
    //         $model->nama_lengkap = @$kary->nama_lengkap;
    //         $model->m_kary_id = @$kary->id;
    //         $model->kode = @$kary->kode;
    //         $model->nik = @$kary->nik;
    //         $model->divisi = @m_divisi::find($kary->m_divisi_id)->nama;
    //         $model->dept = @m_dept::find($kary->m_dept_id)->nama;
    //     }
    //     $model->atasan = m_kary::where('id',@$model['m_kary.atasan_id']??0)->pluck('nama_lengkap')->first();

    //     if(app()->request->header('Source') === 'mobile'){
    //         $data = \DB::select("select public.employee_attendance(?,?)",[Date('Y-m-d'),$model['m_kary_id'] ??0]);
    //         $data = json_decode($data[0]->employee_attendance);
    //         $model['m_kary.sisa_cuti_satu_hari'] = $data->sisa_cuti_satu_hari ?? 0;
    //         $model['m_kary.sisa_cuti_setengah_hari'] = $data->sisa_cuti_setengah_hari ?? 0;
    //         $model['m_kary.cuti_satu_hari'] = $data->cuti_satu_hari ?? 0;
    //         $model['m_kary.cuti_setengah_hari'] = $data->cuti_setengah_hari ?? 0;
    //         $model['info_cuti'] = $data;
            
    //     }
    // }

    public function custom_getHBDKaryawan($request){
        try {
                $today = \Carbon::today();

                $karyawanHariIni = m_kary::leftJoin('default_users', 'm_kary.id', '=', 'default_users.m_kary_id')
                    ->whereMonth('m_kary.tgl_lahir', $today->month)
                    ->whereDay('m_kary.tgl_lahir', '>=', $today->day)
                    ->select('m_kary.*', 'default_users.name as default_users_nama')
                    ->get();
                
                $karyawanHariIni->each(function($karyawan) use ($today) {
                    $tanggalLahir = \Carbon::parse($karyawan->tgl_lahir);

                    $ulangTahunTahunIni = $tanggalLahir->copy()->year($today->year);

                    $countdown = $today->diffInDays($ulangTahunTahunIni, false);

                    $karyawan->countdown = $countdown;
                });



                return response()->json([
                    'data' => $karyawanHariIni,
                    'message' => 'Daftar karyawan yang ulang tahun hari ini',
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mendapatkan data karyawan yang ulang tahun hari ini',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $check = $model->where("username", req("username"))->exists();
        if ($check && req("username")) {
            return ["errors" => ["Username sudah dipakai"]];
        }

        $check = $model->where("email", req("email"))->exists();
        if ($check && req("email")) {
            return ["errors" => ["Email sudah dipakai"]];
        }

        if (req("password") && req("password") != req("password_confirm")) {
            return ["errors" => ["Konfirmasi password salah"]];
        }

        $checkNIK = $model->where('m_kary_id',req('m_kary_id'))->exists();
        if($checkNIK){
            return ["errors" => ["Karyawan sudah digunakan"]];
        }
       
        $hasher = app()->make("hash");
        return [
            "model" => $model,
            "data" => array_merge($arrayData, [
                "password" => $hasher->make(req("password")),
            ]),
        ];
    }


    

    public function custom_update_foto_profil($req)
    {
        $validator = \Validator::make($req->all(), [
            "profil_image" => "required",
            "id" => "required",
        ]);
        if ($validator->fails()) {
            return $this->helper->responseValidate($validator);
        }

        DB::beginTransaction();
        try {
            if ($req->hasFile("profil_image")) {
                $file = $req->file("profil_image");
                $fileName =
                    auth()->user()->username .
                    ":::" .
                    md5(time()) .
                    "." .
                    $file->getClientOriginalExtension();
                $file->move(public_path("uploads/profile"), $fileName);
            } else {
                trigger_error("IMAGE NOT VALID");
            }

            $this->where("id", $req->id)->update([
                "profil_image" => "uploads/profile/$fileName",
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->helper->customResponse(
                "Update Foto Profil gagal, coba kembali nanti",
                400
            );
        }
        return $this->helper->customResponse(
            "Update Foto Profil berhasil",
            200,
            $this->where("id", $req->id)->first()
        );
    }

    public function public_generate()
    {
        $kary = m_kary::whereRaw("m_kary.id not in(select u.m_kary_id from default_users u where u.m_kary_id is not null)")->limit(200)->get();
        
        \DB::beginTransaction();
        try{
              $hasher = app()->make("hash");
            foreach($kary as $k) {
                if($k->kode){
                    $this->create([
                        'username' => $k->kode,
                        'name' => $k->nama_lengkap,
                        'email' => $k->kode."@hris.com",
                        'password' => $hasher->make($k->kode),
                        'm_kary_id' => $k->id
                    ]);

                }
            }
            \DB::commit();
        }catch(\Exception $e) {
            return response(['m'=>$e->getMessage().' - '.$e->getLine()], 400);
        }
        
        return response(['m'=>$kary]);
    }

    public function updateBefore( $model, $arrayData, $metaData, $id=null )
    {
        $hasher = app()->make("hash");
        return [
            "model" => $model,
            "data" => array_merge($arrayData, [
                "password" => $hasher->make(req("password")),
            ]),
        ];
        $newArrayData  = array_merge( $arrayData,[] );
       
    }
    

    public function custom_reset_password($req)
    {
         if (req("password") && !req("password_confirm")) {
            return ["errors" => "Masukkan password Konfirmasi"];
        }

        if (req("password") && req("password") != req("password_confirm")) {
            return ["errors" => "Konfirmasi password salah"];
        }

        $hasher = app()->make("hash");

        if($req->email && $req->username){
            \DB::table('default_users')->where('id',$req->id_user ?? auth()->user()->id)->update([
                'username' => $req->username,
                'email' => $req->email,
                'updated_at' => \Carbon::now(),
                'updated_id' => auth()->id(),
            ]);
        }
        
        if(req("password")){
            \DB::table('default_users')->where('id',$req->id_user ?? auth()->user()->id)->update([
                'password' => $hasher->make(req("password")),
                'updated_at' => \Carbon::now(),
                'updated_id' => auth()->id(),

            ]);
        }

        return response([
            'message' => 'Update password berhasil'
        ]);

    }


    public function scopePic($model){

        $kary_id = default_users::where('id', auth()->user()->id)->pluck('m_kary_id')->first();
        return $model->whereRaw("default_users.id = ? or default_users.m_kary_id in (select k.id from default_users u join m_kary k on k.id = u.m_kary_id where k.atasan_id = ?)", [auth()->user()->id ?? 0, $kary_id]);
    }

    // public function public_phpinfo(){
    //     return phpinfo();
    // }

}
