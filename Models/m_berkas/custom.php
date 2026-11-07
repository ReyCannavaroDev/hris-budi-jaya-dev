<?php

namespace App\Models\CustomModels;

class m_berkas extends \App\Models\BasicModels\m_berkas
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = ['url'];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}