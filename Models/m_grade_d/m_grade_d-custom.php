<?php

namespace App\Models\CustomModels;

class m_grade_d extends \App\Models\BasicModels\m_grade_d
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

    public function custom_save($req){
            try{
                    \DB::beginTransaction();
                    $header = m_general::updateOrCreate(['id' => is_numeric($req['id']) ? (int) $req['id'] : null],[
                        'key' => $req['key'],
                        'group' => $req['group'],
                        'direktorat' => $req['direktorat'],
                        'm_comp_id' => $req['m_comp_id'],
                        'm_dir_id' => $req['m_dir_id'],
                        'code' => $req['code'],
                        'value' => $req['value'],
                        'value_2' => $req['value_2'],
                        'desc' => $req['desc'],
                        'is_active' => $req['is_active'],
                    ]);
                    
                    $collect = collect($req['treatments']);
                    foreach($collect as $single){
                        $single['m_grade_id'] = $header['id'];
                        m_grade_d::updateOrCreate(['id'=> @$single['id']], $single);
                    }

                    \DB::commit();
                    return $this->helper->customResponse("Success",201);
                } catch(\Exception $e) {
                    \DB::rollback();
                    return $this->helper->customResponse("Terjadi kesalahan, silahkan hubungin admin - ".$e->getMessage(),500);
                }    
    }

    public function custom_get_grading_treatment($grade){
        // $grade = isset($grade['grade']) ? $grade['grade'] : $grade;
        // $data = $this->where('m_grade_id',)->get(); 
        // return $data;
    }
}