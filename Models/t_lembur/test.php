<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tLemburTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_lembur', $payload);

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
		    "tanggal" => "date:required",
		    "jam_mulai" => "time:required",
		    "jam_selesai" => "time:required",
		    "tipe_lembur_id" => "bigint:required",
		    "alasan_id" => "bigint:required",
		    "no_doc" => "string:191:optional",
		    "doc" => "string:191:optional",
		    "keterangan" => "text:optional",
		    "status" => "string:50:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "interval_min" => "integer:optional",
		    "pic_id" => "bigint:optional",
		    "ref_table" => "string:191:optional",
		    "ref_id" => "bigint:optional"
		];

        $this->call('POST', '/operation/t_lembur', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_lembur', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}