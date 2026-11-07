<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tSalaryBoronganTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_salary_borongan', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        ff( $responseArr, 'dump data' );

        $this->assertTrue(true);
    }

    public function testCreatingData()
    {
        $user = User::where('username', 'USERNAME')->first();
        $this->assertNotEmpty( $user );
        
        Passport::actingAs($user);

        $payload = [
		    "id" => "bigint:optional:autocreate",
		    "label" => "string:191:required",
		    "date" => "date:required",
		    "jml_kary" => "integer:optional",
		    "total_pendapatan" => "decimal:required",
		    "pic_id" => "bigint:required",
		    "keterangan" => "text:optional",
		    "status" => "string:191:optional",
		    "creator_id" => "integer:optional",
		    "last_editor_id" => "integer:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "t_salary_borongan_det" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_salary_borongan_id" => "bigint:optional:autocreate",
		            "m_tarif_group_id" => "bigint:required",
		            "m_tarif_id" => "bigint:required",
		            "tarif" => "decimal:required",
		            "tarif_desc" => "string:100:optional",
		            "qty" => "decimal:required",
		            "subtotal" => "decimal:required",
		            "keterangan" => "text:optional",
		            "creator_id" => "integer:optional",
		            "last_editor_id" => "integer:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "t_salary_borongan_det_kary" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_salary_borongan_id" => "bigint:optional:autocreate",
		            "m_kary_id" => "bigint:required",
		            "diterima" => "decimal:optional",
		            "is_pic" => "boolean:optional",
		            "is_done" => "boolean:optional",
		            "creator_id" => "integer:optional",
		            "last_editor_id" => "integer:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ]
		];

        $this->call('POST', '/operation/t_salary_borongan', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_salary_borongan', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}