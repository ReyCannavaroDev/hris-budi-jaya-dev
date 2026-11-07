<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tSpdDetTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_spd_det', $payload);

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
		    "t_spd_id" => "bigint:optional",
		    "tipe_spd_id" => "bigint:optional",
		    "biaya" => "decimal:optional",
		    "biaya_realisasi" => "decimal:optional",
		    "detail_transport" => "json:optional",
		    "m_knd_dinas_id" => "bigint:optional",
		    "is_kendaraan_dinas" => "boolean:optional",
		    "keterangan" => "text:optional",
		    "catatan_realisasi" => "string:191:optional",
		    "is_now" => "boolean:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/t_spd_det', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_spd_det', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}