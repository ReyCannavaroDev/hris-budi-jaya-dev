<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tFinalGajiDetRincianTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_final_gaji_det_rincian', $payload);

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
		    "t_final_gaji_det_id" => "bigint:optional",
		    "seq" => "decimal:required",
		    "name" => "string:191:optional",
		    "label" => "text:required",
		    "type" => "string:191:required",
		    "factor" => "string:191:required",
		    "value_ref" => "decimal:optional",
		    "value" => "decimal:required",
		    "can_adjust" => "boolean:optional",
		    "detail" => "json:optional",
		    "deskripsi" => "text:optional",
		    "status" => "string:50:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "t_potongan_id" => "bigint:optional",
		    "t_cuti_id" => "bigint:optional"
		];

        $this->call('POST', '/operation/t_final_gaji_det_rincian', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_final_gaji_det_rincian', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}