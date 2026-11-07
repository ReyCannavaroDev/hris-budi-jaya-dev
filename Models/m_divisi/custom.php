<?php

namespace App\Models\CustomModels;

class m_divisi extends \App\Models\BasicModels\m_divisi
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function custom_seeder(){
        $dirData = m_dir::where('nama', 'People Performance & Culture')->first();

        $data = [
            [
                "m_dir_id" => $dirData->id,
                "nama" => "People & Organization Development",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "m_dir_id" => $dirData->id,
                "nama" => "Performance & General Affair",
                "desc" => null,
                "is_active" => 1,
            ],
                        [
                "m_dir_id" => $dirData->id,
                "nama" => "Legal",
                "desc" => null,
                "is_active" => 1,
            ],
        ];
        $tes = m_divisi::insert($data);
        return $tes;
    }

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
        $namaDivisi = strtolower($arrayData['nama']);
        $getData = $model->where(\DB::raw('LOWER(nama)'), $namaDivisi)->first();
        $newArrayData  = array_merge( $arrayData,[] );
        if($getData){
            return [
                "errors" => ['Nama Divisi '.$arrayData['nama'].' Sudah ada']
            ];
        } 
        return [
            "model"  => $model,
            "data"   => $newArrayData,
            // "errors" => ['error1']
        ];
    }
    
    

}