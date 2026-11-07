<?php

namespace App\Models\CustomModels;

class t_mutasi extends \App\Models\BasicModels\t_mutasi
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = [
        'file_dokumen'
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newArrayData = array_merge($arrayData, [
            "nomor" => $this->helper->generateNomor("KODE MUTASI"),
        ]);

        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    public function custom_postData($request)
    {
        try {
            // Begin a database transaction
            \DB::beginTransaction();

            $data = t_mutasi::find($request->id);
            if (!$data) {
                return response()->json(
                    ["error" => "Data tidak ditemukan."],
                    404
                );
            }

            $karyawan = m_kary::find($data["m_kary_id"]);
            if (!$karyawan) {
                return response()->json(
                    ["error" => "Data karyawan tidak ditemukan."],
                    404
                );
            }
            $update = $data->update([
                "status" => "POSTED",
            ]);

            $updateKary = $karyawan->update([
                "m_divisi_id" => $data["m_devisi_baru_id"],
                "m_dept_id" => $data["m_dept_baru_id"],
                "m_posisi_id" => $data["m_posisi_baru_id"],
                "m_standart_gaji_id" => $data["m_standart_baru_id"],
            ]);

            if ($update && $updateKary) {
                // If both updates are successful, commit the transaction
                \DB::commit();

                return response()->json([
                    "message" => "Data berhasil diposting.",
                ]);
            } else {
                // If any update fails, rollback the transaction
                \DB::rollBack();

                return response()->json(
                    ["error" => "Gagal memperbarui status."],
                    500
                );
            }
        } catch (\Exception $e) {
            // Handle exception, log error messages, etc.

            // Rollback the transaction in case of any exception
            \DB::rollBack();

            return response()->json(
                ["error" => "Terjadi kesalahan: " . $e->getMessage()],
                500
            );
        }
    }
}
