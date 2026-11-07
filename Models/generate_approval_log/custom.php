<?php

namespace App\Models\CustomModels;

class generate_approval_log extends \App\Models\BasicModels\generate_approval_log
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
   

    public function custom_notif()
    {
        $data = $this->orderBy('id','desc')->paginate(25);
        foreach($data as $row) {
            if(app()->request->header('Source') === 'mobile'){
                $text = '';
                if($row->action_type == 'MENGAJUKAN') $text = "$row->trx_name -  $row->trx_nomor berhasil, silahkan menunggu proses validasi dari admin";
                if($row->action_type == 'APPROVED') $text = "$row->trx_name - $row->trx_nomor berhasil sudah di setujui oleh admin";
               
                $row->text = $text;
            }
        }
        return $data;
    }
}