<?php

namespace App\Models\CustomModels;

class generate_num extends \App\Models\BasicModels\generate_num
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

    public function custom_tes_nomor($req)
    {
        return $this->helper->customResponse('OK',200,
            $this->helper->generateNomor($req->nama, false, $req->detail ?? [])
        , true);
    }

    public function custom_trigger_seeder()
    {
        generate_num_type::truncate();
        generate_num::truncate();
        generate_num_det::truncate();
        $trx_type = [
            [
                "nama"    =>  'Separator (-)',
                "ref_type" => 'text',
                "value" => '-'
            ],
            [
                "nama"    =>  'Separator (.)',
                "ref_type" => 'text',
                "value" => '.'
            ],
            [
                "nama"    =>  'Separator (_)',
                "ref_type" => 'text',
                "value" => '_'
            ],
            [
                "nama"    =>  'DAY xx',
                "ref_type" => 'day',
                "value" => 'd'
            ],
            [
                "nama"    =>  'MONTH xx',
                "ref_type" => 'month',
                "value" => 'm'
            ],
            [
                "nama"    =>  'MONTH xxx',
                "ref_type" => 'month',
                "value" => 'M'
            ],
            [
                "nama"    =>  'YEAR xx',
                "ref_type" => 'year',
                "value" => 'y'
            ],
            [
                "nama"    =>  'YEAR xxxx',
                "ref_type" => 'year',
                "value" => 'Y'
            ],
            [
                "nama"    =>  'SEQUENCE xxxxxxx1',
                "ref_type" => 'seq',
                "value" => '8'
            ],
            [
                "nama"    =>  'SEQUENCE xxxxx1',
                "ref_type" => 'seq',
                "value" => '6'
            ],
            [
                "nama"    =>  'SEQUENCE xxx1',
                "ref_type" => 'seq',
                "value" => '4'
            ],
            [
                "nama"    =>  'EXAMPLE PREFIX',
                "ref_type" => 'text',
                "value" => 'EX'
            ]
        ];

        generate_num_type::insert($trx_type);
        $generate_num = generate_num::create([
            "nama" =>  'EXAMPLE CODE',
            "active_flag" => true,
        ]);
        $generate_num_det = [
            [
                "generate_num_id" =>  $generate_num->id,
                "generate_num_type_id" => 12,
                "seq" => 1
            ],
            [
                "generate_num_id" =>  $generate_num->id,
                "generate_num_type_id" => 1,
                "seq" => 2
            ],
            [
                "generate_num_id" =>  $generate_num->id,
                "generate_num_type_id" => 5,
                "seq" => 3
            ],
            [
                "generate_num_id" =>  $generate_num->id,
                "generate_num_type_id" => 8,
                "seq" => 4
            ],
            [
                "generate_num_id" =>  $generate_num->id,
                "generate_num_type_id" => 10,
                "seq" => 5
            ]
        ];
        foreach($generate_num_det as $d){
            generate_num_det::create($d);
        }

        return response(['message'=>'Well Done!']);
    }
}