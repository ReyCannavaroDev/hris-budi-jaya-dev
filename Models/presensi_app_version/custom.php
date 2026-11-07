<?php

namespace App\Models\CustomModels;

class presensi_app_version extends \App\Models\BasicModels\presensi_app_version
{    
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore('Helper');

    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];

    public function public_current(){
        return $this->helper->customResponse("OK", 200, 
            $this->where('is_active', true)->orderBy('id','desc')->first()
        );
    }
}