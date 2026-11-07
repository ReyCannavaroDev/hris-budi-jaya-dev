<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tPelamarDetPendTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_pelamar_det_pend', $payload);

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
		    "t_pelamar_id" => "bigint:optional",
		    "tingkat_id" => "bigint:required",
		    "nama_sekolah" => "string:191:required",
		    "tahun_masuk" => "string:191:required",
		    "tahun_lulus" => "string:191:required",
		    "kota_id" => "bigint:required",
		    "nilai" => "decimal:required",
		    "jurusan" => "string:191:required",
		    "is_pend_terakhir" => "boolean:required",
		    "ijazah_no" => "string:191:required",
		    "ijazah_foto" => "string:191:optional",
		    "keterangan" => "text:optional",
		    "is_active" => "boolean:required",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/t_pelamar_det_pend', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_pelamar_det_pend', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}