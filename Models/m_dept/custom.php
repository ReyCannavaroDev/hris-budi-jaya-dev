<?php

namespace App\Models\CustomModels;

class m_dept extends \App\Models\BasicModels\m_dept
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];


    public function scopeFilterDivisi($model)
    {
        $divisi_id = app()->request->divisi_id ?? 0;
        return $model->where('m_dept.m_divisi_id', $divisi_id)->where('m_dept.is_active', true);
    }

    public function custom_seeder(){
        // $div = m_divisi::where('nama', 'Performance & General Affair')->first();

         $data = [
            [
                "m_dir_id" => 10,
                "m_divisi_id" => null,
                "nama" => "Brand Development",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "m_dir_id" => 10,
                "m_divisi_id" => null,
                "nama" => "Group E-Commerce & Business Development",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "m_dir_id" => 10,
                "m_divisi_id" => null,
                "nama" => "EV Mart",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "m_dir_id" => 10,
                "m_divisi_id" => null,
                "nama" => "JNJ",
                "desc" => null,
                "is_active" => 1,
            ],
        ];
        $tes = m_dept::insert($data);
        return $tes;
    }

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $namaDept = strtolower($arrayData['nama']);
        $getData = $model->where(\DB::raw('LOWER(nama)'), $namaDept)->first();
        $newArrayData  = array_merge( $arrayData,[]);
        if($getData){
            return [
                "errors" => ['Nama Department '.$arrayData['nama'].' Sudah ada']
            ];
        }
        $newArrayData  = array_merge( $arrayData,[] );
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }
    
    
}