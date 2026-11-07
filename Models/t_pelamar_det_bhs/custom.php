<?php

namespace App\Models\CustomModels;

class t_pelamar_det_bhs extends \App\Models\BasicModels\t_pelamar_det_bhs
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    //public $createAdditionalData = ["creator_id"=>"auth:id"];
    //public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    
}