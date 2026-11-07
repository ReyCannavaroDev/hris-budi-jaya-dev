<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tPotonganTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_potongan', $payload);

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
		    "nomor" => "string:50:optional",
		    "m_comp_id" => "bigint:optional",
		    "m_dir_id" => "bigint:optional",
		    "m_kary_id" => "bigint:required",
		    "no_doc" => "string:191:optional",
		    "doc" => "string:191:optional",
		    "nilai" => "decimal:required",
		    "keterangan" => "text:optional",
		    "status" => "string:50:optional",
		    "jenis_potongan_id" => "bigint:required",
		    "date_from" => "date:required",
		    "date_to" => "date:required",
		    "is_all_kary" => "boolean:required",
		    "percentage" => "decimal:required",
		    "is_lunas" => "boolean:required",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "status_bayar" => "string:191:optional",
		    "t_potongan_det_bayar" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "t_potongan_id" => "bigint:optional:autocreate",
		            "t_final_gaji_id" => "bigint:optional",
		            "percentage" => "decimal:optional",
		            "nilai" => "decimal:required",
		            "paid_at" => "datetime:required",
		            "creator_id" => "integer:optional",
		            "last_editor_id" => "integer:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ]
		];

        $this->call('POST', '/operation/t_potongan', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_potongan', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}