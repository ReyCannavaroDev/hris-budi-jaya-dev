<?php

namespace App\Models\CustomModels;

class m_kary_det_pk extends \App\Models\BasicModels\m_kary_det_pk
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = ['surat_referensi'];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}