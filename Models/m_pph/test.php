<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class mPphTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/m_pph', $payload);

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
		    "comp_id" => "bigint:optional",
		    "tgl_pengaturan" => "date:required",
		    "dependant_amt" => "decimal:required",
		    "cost_level" => "decimal:optional",
		    "metode_penentuan" => "string:191:required",
		    "note" => "string:191:optional",
		    "besaran_nikah_pria" => "decimal:required",
		    "besaran_nikah_wanita" => "decimal:required",
		    "besaran_single_pria" => "decimal:required",
		    "besaran_single_wanita" => "decimal:required",
		    "is_active" => "boolean:required",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "m_pph_det" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_pph_id" => "bigint:optional:autocreate",
		            "gaji_min" => "decimal:required",
		            "gaji_max" => "decimal:required",
		            "npwp" => "decimal:required",
		            "non_npwp" => "decimal:required",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ]
		];

        $this->call('POST', '/operation/m_pph', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('m_pph', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}