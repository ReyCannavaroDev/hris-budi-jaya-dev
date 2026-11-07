<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tJadwalKerjaTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_jadwal_kerja', $payload);

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
		    "m_divisi_id" => "bigint:optional",
		    "m_dept_id" => "bigint:optional",
		    "tipe_jam_kerja_id" => "bigint:optional",
		    "keterangan" => "text:optional",
		    "status" => "string:50:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "t_jadwal_kerja_det_hari" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_jadwal_kerja_id" => "bigint:optional:autocreate",
		            "day" => "string:191:required",
		            "tipe_hari" => "string:191:required",
		            "tanggal" => "date:optional",
		            "day_num" => "integer:required",
		            "m_jam_kerja_id" => "bigint:optional",
		            "waktu_mulai" => "time:required",
		            "waktu_akhir" => "time:required",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate",
		            "t_jadwal_kerja_det" => [
		                [
		                    "id" => "bigint:optional:autocreate",
		                    "t_jadwal_kerja_det_hari_id" => "bigint:optional:autocreate",
		                    "t_jadwal_kerja_id" => "bigint:optional",
		                    "m_dir_id" => "bigint:optional",
		                    "m_divisi_id" => "bigint:optional",
		                    "m_dept_id" => "bigint:optional",
		                    "m_kary_id" => "bigint:required",
		                    "creator_id" => "bigint:optional",
		                    "last_editor_id" => "bigint:optional",
		                    "created_at" => "datetime:optional:autocreate",
		                    "updated_at" => "datetime:optional:autocreate"
		                ]
		            ]
		        ]
		    ]
		];

        $this->call('POST', '/operation/t_jadwal_kerja', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_jadwal_kerja', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}