<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class mPosisiTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/m_posisi', $payload);

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
		    "m_comp_id" => "bigint:optional",
		    "m_dir_id" => "bigint:optional",
		    "kode" => "string:50:optional",
		    "desc_kerja" => "text:required",
		    "desc_kerja_1" => "text:optional",
		    "desc_kerja_2" => "text:optional",
		    "min_pengalaman" => "string:191:optional",
		    "min_pendidikan_id" => "bigint:optional",
		    "min_gaji_pokok" => "decimal:optional",
		    "max_gaji_pokok" => "decimal:optional",
		    "biaya" => "decimal:optional",
		    "tipe_bpjs_id" => "bigint:optional",
		    "potongan_bpjs" => "decimal:optional",
		    "desc" => "text:optional",
		    "is_active" => "boolean:required",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "jatah_jam_lembur" => "integer:optional",
		    "percentage" => "integer:optional"
		];

        $this->call('POST', '/operation/m_posisi', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('m_posisi', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}