<?php

namespace App\Models\CustomModels;

class m_general extends \App\Models\BasicModels\m_general
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function custom_massUpdate()
    {
        // SELECT m_general.group from m_general group by m_general.group
            \DB::beginTransaction();
        try{
            \DB::table('m_general')->update(['group' => \DB::raw('UPPER("group")')]);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return 'Terjadi kesalahan: ' . $e->getMessage();
        }


        return true;
    }

    public function custom_seeder()
    {
        $provinsiData = m_general::where('group', 'KOTA')->orderBy('id')->skip(500)->take(250)->get();
        // SELECT * FROM m_general where m_general.group = 'KECAMATAN' and m_general.key = '196' order by m_general.id desc
        \DB::beginTransaction();

        try{
            $kota = [];
                foreach($provinsiData as $prov){
                    $apiUrl = 'https://backend.qqltech.com/kodepos/region/kecamatan?kota=' . urlencode($prov->value);
                    $ch = curl_init($apiUrl);

                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                    $response = curl_exec($ch);
                    $data = json_decode($response, true);
                    foreach($data as $kot){
                        $kota[] = [
                            'm_comp_id' => 1,
                            'group' => 'KECAMATAN',
                            'key' => $prov->id,
                            'code' => null,
                            'value' => $kot,
                            'is_active' => true
                        ];
                    }
                    curl_close($ch);
                }

            m_general::insert($kota);
            // \DB::table('m_general')->where('group', 'KOTA')->delete();
            \DB::commit();

            return 'Proses selesai. Jumlah data yang ditambahkan: '. count($kota);
        } catch (\Exception $e) {
            \DB::rollBack();
            return 'Terjadi kesalahan: ' . $e->getMessage();
        }

    }

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $m_dir_id = auth()->user()->m_dir_id;
        if(!$m_dir_id)  
            return [
                "errors" => ['Maaf akun anda tidak memiliki akses untuk menambahkan data']
            ];
        return [
            "model"  => $model,
            "data"   => array_merge($arrayData,[
                'm_dir_id' =>  $m_dir_id
            ])
        ];
    }
    
    public function scopeGenProvinsi($model){
        $model->whereRaw("m_general.group = 'PROVINSI' and m_general.is_active = true");
        return $model;
    }

    public function scopeGenKota($model){
        $req = app()->request;
        $prov_id = $req->provinsi_id ?? 0;
        $model->where('group','KOTA')->where('key', $prov_id)->where('m_general.is_active', true);
        return $model;
    }

    public function scopeGenKecamatan($model){
        $req = app()->request;
        $kota_id = $req->kota_id ?? 0;
        $model->where('group','KECAMATAN')->where('key', $kota_id)->where('m_general.is_active', true);
        return $model;
    }

    public function scopeAlasanCuti($model){
        return $model->where('m_general.group','ALASAN CUTI')->where('m_general.is_active', true);
    }

    public function scopeTipeCuti($model){
        return $model->where('m_general.group','TIPE CUTI')->where('m_general.is_active', true);
    }

    public function scopeJenisSPD($model){
        return $model->where('m_general.group','JENIS SPD')->where('m_general.is_active', true);
    }

    public function scopeTipeLembur($model){
        return $model->where('group','TIPE LEMBUR');
    }

    public function scopeAlasanLembur($model){
        return $model->where('group','ALASAN LEMBUR');
    }

    public function scopeGrading($model){
        return $model->where('group','GRADING');
    }

    public function scopeCostcentre($model){
        return $model->where('group','COSTCENTRE');
    }

    public function scopeJenkel($model){
        return $model->where('group','JENIS KELAMIN');
    }

    public function scopeAgama($model){
        return $model->where('group','AGAMA');
    }

    public function scopeGolDarah($model){
        return $model->where('group','GOLONGAN DARAH');
    }

    public function scopeStatusNikah($model){
        return $model->where('group','STATUS NIKAH');
    }

    public function scopeTanggungan($model){
        return $model->where('group','TANGGUNGAN');
    }

    public function scopeTingkatPend($model){
        return $model->where('group','PENDIDIKAN');
    }

    public function treatments(){
        return $this->hasMany('App\Models\BasicModels\m_grade_d', 'm_grade_id', 'id');
    }

    public function scopeGradeWithDetail($model){
        return $model->where('group','GRADING')->with('treatments');
    }

    // select g.group from m_general g group by g.group

    public function public_get_basic($req){
        // return response()->json(['a'=>m_general::first()]);

        try{
            $data = [
                'brand_title' => $this->where('group','SETTING')->where('code','BRAND-TITLE')->pluck('value')->first(),
                'brand_logo' => $this->where('group','SETTING')->where('code','BRAND-LOGO')->pluck('value')->first(),
                'brand_logo_small' => $this->where('group','SETTING')->where('code','BRAND-LOGO-SMALL')->pluck('value')->first(),
                'brand_bg' => $this->where('group','SETTING')->where('code','BRAND-BG')->pluck('value')->first(),
            ];
            return response(['data'=>$data]);
        }catch(\Exception $e){
            return response(['errors'=>$e], 500);
        }
    }

    public function scopeTipeGrade($model){
        return $model->where('group','GRADING')->where('key','TIPE_GRADE');
    }

    public function scopeSearchGrade($model){
        $req = request("tipe", 'HARIAN');
        $model->where("value_2", $req);
    }
}