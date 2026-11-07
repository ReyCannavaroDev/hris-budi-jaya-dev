<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tSpdTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_spd', $payload);

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
		    "m_spd_id" => "bigint:optional",
		    "m_dir_id" => "bigint:optional",
		    "m_divisi_id" => "bigint:required",
		    "m_dept_id" => "bigint:required",
		    "m_posisi_id" => "bigint:required",
		    "tanggal" => "date:required",
		    "tgl_acara_awal" => "date:required",
		    "tgl_acara_akhir" => "date:required",
		    "jenis_spd_id" => "bigint:required",
		    "m_zona_asal_id" => "bigint:required",
		    "m_zona_tujuan_id" => "bigint:required",
		    "m_lokasi_tujuan_id" => "bigint:required",
		    "m_kary_id" => "bigint:optional",
		    "pic_id" => "bigint:optional",
		    "total_biaya" => "decimal:required",
		    "kegiatan" => "string:191:optional",
		    "keterangan" => "text:optional",
		    "status" => "string:50:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "is_kend_dinas" => "boolean:required",
		    "interval" => "integer:optional",
		    "catatan_kend" => "text:optional",
		    "t_spd_det" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_spd_id" => "bigint:optional:autocreate",
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
		        ]
		    ]
		];

        $this->call('POST', '/operation/t_spd', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_spd', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}