<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class mSpdDetBiayaTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/m_spd_det_biaya', $payload);

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
		    "m_spd_id" => "bigint:optional",
		    "m_dir_id" => "bigint:optional",
		    "total_biaya" => "decimal:required",
		    "tipe_id" => "bigint:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "keterangan" => "text:optional",
		    "m_spd_det_transport" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "m_dir_id" => "bigint:optional",
		            "m_spd_det_biaya_id" => "bigint:optional:autocreate",
		            "jenis_transport_id" => "bigint:optional",
		            "nama_transport" => "string:191:required",
		            "biaya_transport" => "decimal:required",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ]
		];

        $this->call('POST', '/operation/m_spd_det_biaya', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('m_spd_det_biaya', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}