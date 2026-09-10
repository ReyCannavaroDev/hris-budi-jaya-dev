<?php
namespace Tests;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Passport\Passport;
use App\Models\Defaults\User;

class mKaryTest extends TestCase
{
    use DatabaseTransactions;

    public function testReadingData()
    {
        Passport::actingAs(User::first());
        $payload = [
            'paginate' => 25
        ];

        $this->call('GET', '/operation/m_kary', $payload);

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
		    "m_divisi_id" => "bigint:optional",
		    "m_dept_id" => "bigint:optional",
		    "m_zona_id" => "bigint:optional",
		    "grading_id" => "bigint:optional",
		    "costcontre_id" => "bigint:optional",
		    "kode" => "string:100:optional",
		    "m_posisi_id" => "bigint:optional",
		    "m_jam_kerja_id" => "bigint:optional",
		    "kode_presensi" => "string:100:optional",
		    "nik" => "string:20:optional",
		    "nama_depan" => "string:100:optional",
		    "nama_belakang" => "string:100:optional",
		    "nama_lengkap" => "string:100:optional",
		    "nama_panggilan" => "string:100:optional",
		    "jk_id" => "bigint:optional",
		    "tempat_lahir" => "string:100:optional",
		    "tgl_lahir" => "date:optional",
		    "provinsi_id" => "bigint:optional",
		    "kota_id" => "bigint:optional",
		    "kecamatan_id" => "bigint:optional",
		    "kode_pos" => "string:10:optional",
		    "alamat_asli" => "text:optional",
		    "alamat_domisili" => "text:optional",
		    "no_tlp" => "string:20:optional",
		    "no_tlp_lainnya" => "string:20:optional",
		    "no_darurat" => "string:20:optional",
		    "nama_kontak_darurat" => "string:100:optional",
		    "agama_id" => "bigint:optional",
		    "gol_darah_id" => "bigint:optional",
		    "status_nikah_id" => "bigint:optional",
		    "tanggungan_id" => "bigint:optional",
		    "hub_dgn_karyawan" => "string:100:optional",
		    "cuti_jatah_reguler" => "integer:optional",
		    "cuti_sisa_reguler" => "integer:optional",
		    "cuti_panjang" => "integer:optional",
		    "cuti_sisa_panjang" => "integer:optional",
		    "status_kary_id" => "bigint:optional",
		    "lama_kontrak_awal" => "date:optional",
		    "lama_kontrak_akhir" => "date:optional",
		    "tgl_masuk" => "date:optional",
		    "tgl_berhenti" => "date:optional",
		    "alasan_berhenti" => "text:optional",
		    "uk_baju" => "string:50:optional",
		    "uk_celana" => "string:50:optional",
		    "uk_sepatu" => "string:50:optional",
		    "desc" => "text:optional",
		    "is_active" => "boolean:optional",
		    "creator_id" => "bigint:optional",
		    "last_editor_id" => "bigint:optional",
		    "created_at" => "datetime:optional:autocreate",
		    "updated_at" => "datetime:optional:autocreate",
		    "m_standart_gaji_id" => "bigint:optional",
		    "periode_gaji_id" => "bigint:optional",
		    "ref_id" => "bigint:optional",
		    "presensi_lokasi_default_id" => "bigint:optional",
		    "exp_date_cuti" => "date:optional",
		    "limit_potong" => "integer:optional",
		    "atasan_id" => "bigint:optional",
		    "cuti_p24" => "decimal:optional",
		    "cuti_sisa_p24" => "decimal:optional",
		    "tipe_jam_kerja_id" => "bigint:optional",
		    "t_jadwal_kerja_id" => "bigint:optional",
		    "m_kary_det_bhs" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_kary_id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "m_dir_id" => "bigint:optional",
		            "bhs_dikuasai" => "string:100:required",
		            "nilai_lisan" => "integer:optional",
		            "nilai_tertulis" => "integer:optional",
		            "desc" => "text:optional",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate",
		            "level_lisan" => "string:191:optional",
		            "level_tertulis" => "string:191:optional"
		        ]
		    ],
		    "m_kary_det_kartu" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_kary_id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "m_dir_id" => "bigint:optional",
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
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate",
		            "bpjs_no_kesehatan" => "string:191:optional",
		            "bpjs_no_ketenagakerjaan" => "string:191:optional"
		        ]
		    ],
		    "m_kary_det_kel" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "m_dir_id" => "bigint:optional",
		            "m_kary_id" => "bigint:optional:autocreate",
		            "keluarga_id" => "bigint:optional",
		            "nama" => "string:100:required",
		            "pend_terakhir_id" => "bigint:required",
		            "jk_id" => "bigint:required",
		            "pekerjaan_id" => "bigint:required",
		            "usia" => "integer:required",
		            "desc" => "text:optional",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "m_kary_det_org" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_kary_id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "m_dir_id" => "bigint:optional",
		            "nama" => "string:100:required",
		            "tahun" => "integer:required",
		            "jenis_org_id" => "bigint:required",
		            "kota_id" => "bigint:required",
		            "posisi" => "string:100:required",
		            "desc" => "text:optional",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "m_kary_det_pel" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_kary_id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "m_dir_id" => "bigint:optional",
		            "nama_pel" => "string:100:required",
		            "tahun" => "integer:required",
		            "nama_lem" => "string:100:required",
		            "kota_id" => "bigint:optional",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "m_kary_det_pemb" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_kary_id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "m_dir_id" => "bigint:optional",
		            "periode_gaji_id" => "bigint:required",
		            "metode_id" => "bigint:optional",
		            "tipe_id" => "bigint:optional",
		            "bank_id" => "bigint:optional",
		            "no_rek" => "string:50:optional",
		            "atas_nama_rek" => "string:191:optional",
		            "desc" => "text:optional",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "m_kary_det_pend" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_kary_id" => "bigint:optional:autocreate",
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
		        ]
		    ],
		    "m_kary_det_pk" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "m_dir_id" => "bigint:optional",
		            "m_kary_id" => "bigint:optional:autocreate",
		            "instansi" => "string:100:required",
		            "bidang_usaha" => "string:100:required",
		            "no_tlp" => "string:20:required",
		            "posisi" => "string:100:required",
		            "thn_masuk" => "integer:required",
		            "thn_keluar" => "integer:required",
		            "alamat_kantor" => "text:required",
		            "kota_id" => "bigint:required",
		            "surat_referensi" => "string:191:optional",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ],
		    "m_kary_det_pres" => [
		        [
		            "id" => "bigint:optional:autocreate",
		            "m_comp_id" => "bigint:optional",
		            "m_dir_id" => "bigint:optional",
		            "m_kary_id" => "bigint:optional:autocreate",
		            "nama_pres" => "string:191:required",
		            "tahun" => "integer:required",
		            "tingkat_pres_id" => "bigint:required",
		            "desc" => "text:optional",
		            "creator_id" => "bigint:optional",
		            "last_editor_id" => "bigint:optional",
		            "created_at" => "datetime:optional:autocreate",
		            "updated_at" => "datetime:optional:autocreate"
		        ]
		    ]
		];

        $this->call('POST', '/operation/m_kary', $payload);

        $responseArr = json_decode( $this->response->getContent(),true );
        // ff( $responseArr, 'dump data' );

        $this->assertEquals( 200, $this->response->status() );
        // $this->seeJsonStructure( ['status'] );

        $this->seeInDatabase('m_kary', array_filter($payload, function($dt){
            return !is_array($dt);
        } ));
    }
}