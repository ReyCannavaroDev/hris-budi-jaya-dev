<?php

namespace App\Models\CustomModels;

class m_knd_dinas extends \App\Models\BasicModels\m_knd_dinas
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

}