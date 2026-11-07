<?php

namespace App\Models\CustomModels;

class generate_num_det extends \App\Models\BasicModels\generate_num_det
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function onCreating($model)
    {
        $generate_num_type = generate_num_type::find($model->generate_num_type_id);
        $model->generate_num_type = $generate_num_type->value;
    }
  
}