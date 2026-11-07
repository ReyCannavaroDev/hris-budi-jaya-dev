<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class mPenguranganGajiTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/m_pengurangan_gaji', $payload);

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
		    "tipe" => "string:100:required",
		    "k_minimun" => "decimal:optional",
		    "k_maksimum" => "decimal:optional",
		    "berdasarkan" => "string:100:required",
		    "n_pengurangan" => "decimal:required",
		    "periode" => "string:100:required",
		    "referensi" => "string:100:required",
		    "variabel" => "string:100:required",
		    "deskripsi" => "text:optional",
		    "is_active" => "boolean:required",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/m_pengurangan_gaji', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('m_pengurangan_gaji', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}