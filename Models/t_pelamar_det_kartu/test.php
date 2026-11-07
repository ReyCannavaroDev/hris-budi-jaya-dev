<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tPelamarDetKartuTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_pelamar_det_kartu', $payload);

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
		    "ktp_no" => "string:25:optional",
		    "ktp_foto" => "string:191:optional",
		    "pas_foto" => "string:191:optional",
		    "kk_no" => "string:25:optional",
		    "kk_foto" => "string:191:optional",
		    "npwp_no" => "string:25:optional",
		    "npwp_foto" => "string:191:optional",
		    "npwp_tgl_berlaku" => "date:optional",
		    "bpjs_tipe_id" => "bigint:optional",
		    "bpjs_no" => "string:30:optional",
		    "bpjs_foto" => "string:191:optional",
		    "berkas_lain" => "string:191:optional",
		    "desc_file" => "text:optional",
		    "creator_id" => "integer:optional",
		    "last_editor_id" => "integer:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/t_pelamar_det_kartu', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_pelamar_det_kartu', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}