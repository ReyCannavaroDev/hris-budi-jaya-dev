<?php

namespace App\Models\CustomModels;

class m_dir extends \App\Models\BasicModels\m_dir
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function createBefore( $model, $arrayData, $metaData, $id=null )
    {
      $newArrayData  = array_merge( $arrayData,[
        'm_comp_id' => m_general::where('group', "COMPANY GROUP")->first()->value ?? 0
      ] );
      return [
          "model"  => $model,
          "data"   => $newArrayData,
          // "errors" => ['error1']
      ];
    }

    public function custom_seeder(){
        $data = [
            [
                "nama" => "Business & Network Building Material",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "nama" => "Business & Network Consumer Goods",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "nama" => "Operation & Business Development",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "nama" => "Finance, Accounting & Tax",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "nama" => "People Performance & Culture",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "nama" => "Information Technology",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "nama" => "CEO Office",
                "desc" => null,
                "is_active" => 1,
            ],
            [
                "nama" => "Business Development Office",
                "desc" => null,
                "is_active" => 1,
            ],
        ];

        m_dir::insert($data);
    }
}