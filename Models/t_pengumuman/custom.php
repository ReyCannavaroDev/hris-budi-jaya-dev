<?php

namespace App\Models\CustomModels;

class t_pengumuman extends \App\Models\BasicModels\t_pengumuman
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ 'thumb' ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}