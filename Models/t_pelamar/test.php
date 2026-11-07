<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class tPelamarTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/t_pelamar', $payload);

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
		    "nama_pelamar" => "string:191:required",
		    "ktp_no" => "string:191:required",
		    "tanggal" => "date:optional",
		    "ref" => "string:191:required",
		    "telp" => "string:191:required",
		    "jk_id" => "bigint:optional",
		    "tgl_lahir" => "date:required",
		    "salary" => "decimal:required",
		    "deskripsi" => "text:required",
		    "status" => "string:50:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "m_divisi_id" => "bigint:optional",
		    "m_dept_id" => "bigint:optional",
		    "m_posisi_id" => "bigint:optional",
		    "tempat_lahir" => "string:100:optional",
		    "t_loker_id" => "bigint:optional",
		    "t_pelamar_det_bhs" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_pelamar_id" => "bigint:optional:autocreate",
		            "bhs_dikuasai" => "string:100:required",
		            "nilai_lisan" => "integer:optional",
		            "level_lisan" => "string:191:required",
		            "nilai_tertulis" => "integer:optional",
		            "level_tertulis" => "string:191:required",
		            "desc" => "text:optional",
		            "creator_id" => "integer:optional",
		            "last_editor_id" => "integer:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "t_pelamar_det_kartu" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_pelamar_id" => "bigint:optional:autocreate",
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
		        ]
		    ],
		    "t_pelamar_det_org" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_pelamar_id" => "bigint:optional:autocreate",
		            "nama" => "string:100:required",
		            "tahun" => "integer:required",
		            "jenis_org_id" => "bigint:optional",
		            "kota_id" => "bigint:required",
		            "posisi" => "string:100:required",
		            "desc" => "text:optional",
		            "creator_id" => "integer:optional",
		            "last_editor_id" => "integer:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "t_pelamar_det_pel" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_pelamar_id" => "bigint:optional:autocreate",
		            "nama_pel" => "string:100:required",
		            "tahun" => "integer:required",
		            "nama_lem" => "string:100:required",
		            "kota_id" => "bigint:optional",
		            "creator_id" => "integer:optional",
		            "last_editor_id" => "integer:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "t_pelamar_det_pend" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_pelamar_id" => "bigint:optional:autocreate",
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
		        ]
		    ],
		    "t_pelamar_det_peng" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_pelamar_id" => "bigint:optional:autocreate",
		            "nama_pengalaman" => "string:191:required",
		            "posisi" => "string:191:required",
		            "date_from" => "date:required",
		            "date_to" => "date:required",
		            "kota_id" => "bigint:required",
		            "keterangan" => "text:optional",
		            "is_active" => "boolean:required",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "t_pelamar_det_pk" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_pelamar_id" => "bigint:optional:autocreate",
		            "instansi" => "string:100:required",
		            "bidang_usaha" => "string:100:required",
		            "no_tlp" => "string:20:required",
		            "posisi" => "string:100:required",
		            "thn_masuk" => "integer:required",
		            "thn_keluar" => "integer:required",
		            "alamat_kantor" => "text:required",
		            "kota_id" => "bigint:required",
		            "surat_referensi" => "string:191:required",
		            "creator_id" => "integer:optional",
		            "last_editor_id" => "integer:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "t_pelamar_det_pres" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "t_pelamar_id" => "bigint:optional:autocreate",
		            "nama_pres" => "string:191:required",
		            "tahun" => "integer:required",
		            "tingkat_pres_id" => "bigint:required",
		            "desc" => "text:optional",
		            "creator_id" => "integer:optional",
		            "last_editor_id" => "integer:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ]
		];

        $this->call('POST', '/operation/t_pelamar', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('t_pelamar', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}