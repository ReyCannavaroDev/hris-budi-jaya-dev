<?php

namespace App\Models\CustomModels;

class m_spd_det_biaya extends \App\Models\BasicModels\m_spd_det_biaya
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}