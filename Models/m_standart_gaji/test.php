<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class mStandartGajiTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/m_standart_gaji', $payload);

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
		    "m_divisi_id" => "bigint:optional",
		    "m_dept_id" => "bigint:optional",
		    "m_zona_id" => "bigint:optional",
		    "m_posisi_id" => "bigint:optional",
		    "grading_id" => "bigint:optional",
		    "gaji_pokok" => "decimal:required",
		    "gaji_pokok_periode" => "string:50:optional",
		    "uang_saku" => "decimal:optional",
		    "uang_saku_periode" => "string:50:optional",
		    "tunjangan_posisi" => "decimal:optional",
		    "tunjangan_posisi_periode" => "string:50:optional",
		    "tunjangan_kemahalan_id" => "bigint:optional",
		    "tunjangan_kemahalan_periode" => "string:50:optional",
		    "uang_makan" => "decimal:optional",
		    "uang_makan_periode" => "string:50:optional",
		    "tunjangan_tetap" => "decimal:optional",
		    "tunjangan_tetap_periode" => "string:50:optional",
		    "desc" => "text:optional",
		    "is_active" => "boolean:required",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "m_standart_gaji_det" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_standart_gaji_id" => "bigint:optional:autocreate",
		            "komponen" => "string:191:required",
		            "faktor" => "string:191:required",
		            "nilai" => "decimal:required",
		            "periode" => "string:191:required",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ]
		];

        $this->call('POST', '/operation/m_standart_gaji', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('m_standart_gaji', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}