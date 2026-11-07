<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class mKaryDetPendTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/m_kary_det_pend', $payload);

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
		    "m_kary_id" => "bigint:optional",
		    "m_comp_id" => "bigint:optional",
		    "m_dir_id" => "bigint:optional",
		    "tingkat_id" => "bigint:required",
		    "nama_sekolah" => "string:100:required",
		    "thn_masuk" => "integer:required",
		    "thn_lulus" => "integer:required",
		    "kota_id" => "bigint:required",
		    "nilai" => "decimal:required",
		    "jurusan" => "string:50:required",
		    "is_pend_terakhir" => "boolean:required",
		    "ijazah_no" => "string:191:optional",
		    "ijazah_foto" => "string:191:optional",
		    "desc" => "text:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/m_kary_det_pend', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('m_kary_det_pend', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}