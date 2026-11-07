<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tMutasiTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_mutasi', $payload);

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
		    "tgl" => "date:required",
		    "status_kary_id" => "bigint:required",
		    "tipe_mutasi" => "string:191:required",
		    "m_divisi_lama_id" => "bigint:required",
		    "m_dept_lama_id" => "bigint:optional",
		    "m_posisi_lama_id" => "bigint:required",
		    "m_standart_posisi_id" => "bigint:optional",
		    "m_devisi_baru_id" => "bigint:required",
		    "m_dept_baru_id" => "bigint:optional",
		    "m_posisi_baru_id" => "bigint:required",
		    "m_standart_baru_id" => "bigint:optional",
		    "no_dokumen" => "string:191:required",
		    "file_dokumen" => "string:191:required",
		    "keterangan" => "text:optional",
		    "status" => "string:50:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate"
		];

        $this->call('POST', '/operation/t_mutasi', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_mutasi', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}