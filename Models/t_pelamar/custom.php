<?php

namespace App\Models\CustomModels;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;

class YourImportClass implements ToModel, WithHeadingRow
{
    protected $response = [];
    protected $currentSheet = null;

    public function model(array $row)
    {
        // Check if the current sheet is 't_pelamar'
        if ($this->currentSheet === "t_pelamar") {
            // Include only the desired fields if they exist in the $row array
            if (isset($row["temp_id"])) {
                $filteredRow = [
                    "temp_id" => $row["temp_id"],
                    "nomor" => $row["nomor"] ?? null,
                    "m_comp_id" => $row["m_comp_id"] ?? null,
                    "m_dir_id" => $row["m_dir_id"] ?? null,
                    "m_divisi_id" => $row["m_divisi_id"] ?? null,
                    "m_dept_id" => $row["m_dept_id"] ?? null,
                    "m_posisi_id" => $row["m_posisi_id"] ?? null,
                    "nama_pelamar" => $row["nama_pelamar"] ?? null,
                    "ktp_no" => $row["ktp_no"] ?? null,
                    "tanggal" => $row["tanggal"] ?? null,
                    "ref" => $row["ref"] ?? null,
                    "telp" => $row["telp"] ?? null,
                    "jk_id" => $row["jk_id"] ?? null,
                    "tempat_lahir" => $row["tempat_lahir"] ?? null,
                    "tgl_lahir" => $row["tgl_lahir"] ?? null,
                    "salary" => $row["salary"] ?? null,
                    "deskripsi" => $row["deskripsi"] ?? null,
                    "status" => $row["status"] ?? null,
                ];

                $filteredRow = array_filter($filteredRow, function ($value) {
                    return $value !== null;
                });

                if (!empty($filteredRow)) {
                    $this->response[] = $filteredRow;
                }
            }
        } elseif ($this->currentSheet === "t_pelamar_pend") {
            if (isset($row["nama_sekolah"]) && isset($row["pelamar_temp_id"])) {
                $filteredRow = [
                    "pelamar_temp_id" => $row["pelamar_temp_id"],
                    "tingkat_id" => $row["tingkat_id"] ?? null,
                    "nama_sekolah" => $row["nama_sekolah"] ?? null,
                    "tahun_masuk" => $row["tahun_masuk"] ?? null,
                    "tahun_lulus" => $row["tahun_lulus"] ?? null,
                    "kota_id" => $row["kota_id"] ?? null,
                    "nilai" => $row["nilai"] ?? null,
                    "jurusan" => $row["jurusan"] ?? null,
                    "is_pend_terakhir" => (($row["is_pend_terakhir"] == '=TRUE()') ? 1 : 0 ) ?? null,
                    "ijazah_no" => $row["ijazah_no"] ?? null,
                    "ijazah_foto" => $row["ijazah_foto"] ?? null,
                    "keterangan" => $row["keterangan"] ?? null,
                    "is_active" => (($row["is_active"] == '=TRUE()') ? 1 : 0 ) ?? null,
                ];

                $filteredRow = array_filter($filteredRow, function ($value) {
                    return $value !== null;
                });

                // Add to the response if there are non-null values
                if (!empty($filteredRow)) {
                    $this->response[] = $filteredRow;
                }
            }
        } elseif ($this->currentSheet === "t_pelamar_peng") {
            // Include only the desired fields for 't_pelamar_pend' if they exist in the $row array
            if (
                isset($row["nama_pengalaman"]) &&
                isset($row["pelamar_temp_id"])
            ) {
                $filteredRow = [
                    "pelamar_temp_id" => $row["pelamar_temp_id"],
                    "nama_pengalaman" => $row["nama_pengalaman"] ?? null,
                    "posisi" => $row["posisi"] ?? null,
                    "date_from" => $row["date_from"] ?? null,
                    "date_to" => $row["date_to"] ?? null,
                    "kota_id" => $row["kota_id"] ?? null,
                    "keterangan" => $row["keterangan"] ?? null,
                    "is_active" => $row["is_active"] ?? null,
                ];

                $filteredRow = array_filter($filteredRow, function ($value) {
                    return $value !== null;
                });

                // Add to the response if there are non-null values
                if (!empty($filteredRow)) {
                    $this->response[] = $filteredRow;
                }
            }
        }

        return null;
    }

    public function setSheet($sheetName)
    {
        $this->currentSheet = $sheetName;
    }

    public function getResponse()
    {
        return response()->json($this->response);
    }

    public function resetResponse()
    {
        $this->response = [];
    }
}

class t_pelamar extends \App\Models\BasicModels\t_pelamar
{
    private $helper;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore("Helper");
    }

    public $fileColumns = [
        /*file_column*/
    ];

    public $createAdditionalData = ["creator_id" => "auth:id"];
    public $updateAdditionalData = ["last_editor_id" => "auth:id"];

    public function createBefore($model, $arrayData, $metaData, $id = null)
    {
        $newArrayData = array_merge($arrayData, [
            "nomor" => $this->helper->generateNomor("KODE PELAMAR"),
        ]);

        return [
            "model" => $model,
            "data" => $newArrayData,
            // "errors" => ['error1']
        ];
    }

    private function fetchData($request)
    {
        if (!$request->hasFile("file")) {
            return response()->json("file harus ada", 400);
        }

        $sheets = ["t_pelamar", "t_pelamar_pend", "t_pelamar_peng"];
        $jsonResults = [];

        foreach ($sheets as $sheet) {
            try {
                $import = new YourImportClass(); // Adjust with the correct namespace
                $import->setSheet($sheet);
                Excel::import(
                    $import,
                    $request->file("file")->getRealPath(),
                    null,
                    \Maatwebsite\Excel\Excel::XLSX,
                    $sheet
                );
                $jsonResults[$sheet] = $import->getResponse();
                $import->resetResponse();
            } catch (\Exception $e) {
                // Handle exception, for example, log error messages
                return response()->json(
                    "Error importing data from sheet {$sheet}: " .
                        $e->getMessage()
                );
            }
        }

        return $jsonResults;
    }

    public function custom_generate_import($request)
    {
        $response = $this->fetchData($request);
        $data = $response["t_pelamar"]->getData(true);
        $detData = $response["t_pelamar_peng"]->getData(true);
        $detData1 = $response["t_pelamar_pend"]->getData(true);
        $mappedData = [];
        foreach ($data as $header) {
            $jk = m_general::where("id", $header["jk_id"])->value("value");
            $mappedHeader = [
                "nomor" => $header["nomor"],
                "m_comp_id" => $header["m_comp_id"],
                "m_dir_id" => $header["m_dir_id"],
                "m_divisi_id" => $header["m_divisi_id"],
                "m_dept_id" => $header["m_dept_id"],
                "m_posisi_id" => $header["m_posisi_id"],
                "nama_pelamar" => $header["nama_pelamar"],
                "ktp_no" => $header["ktp_no"],
                "tanggal" => $header["tanggal"],
                "ref" => $header["ref"],
                "telp" => $header["telp"],
                "jk_id" => $header["jk_id"],
                "jk" => $jk,
                "tempat_lahir" => $header["tempat_lahir"],
                "tgl_lahir" => $header["tgl_lahir"],
                "salary" => $header["salary"],
                "deskripsi" => $header["deskripsi"],
                "status" => $header["status"],
                "t_pelamar_det_peng" => [],
                "t_pelamar_det_pend" => [],
            ];

            foreach ($detData as $detailPeng) {
                if ($detailPeng["pelamar_temp_id"] == $header["temp_id"]) {
                    $mappedDetail = [
                        "nama_pengalaman" =>
                            $detailPeng["nama_pengalaman"] ?? null,
                        "posisi" => $detailPeng["posisi"] ?? null,
                        "date_from" => $detailPeng["date_from"] ?? null,
                        "date_to" => $detailPeng["date_to"] ?? null,
                        "kota_id" => $detailPeng["kota_id"] ?? null,
                        "keterangan" => $detailPeng["keterangan"] ?? null,
                        "is_active" => $detailPeng["is_active"] ?? null,
                        "creator_id" => $detailPeng["creator_id"] ?? null,
                        "last_editor_id" =>
                            $detailPeng["last_editor_id"] ?? null,
                    ];
                    $mappedHeader["t_pelamar_det_peng"][] = $mappedDetail;
                }
            }

            foreach ($detData1 as $detailPend) {
                if ($detailPend["pelamar_temp_id"] == $header["temp_id"]) {
                    $mappedDetail = [
                        "tingkat_id" => $detailPend["tingkat_id"] ?? null,
                        "nama_sekolah" => $detailPend["nama_sekolah"] ?? null,
                        "tahun_masuk" => $detailPend["tahun_masuk"] ?? null,
                        "tahun_lulus" => $detailPend["tahun_lulus"] ?? null,
                        "kota_id" => $detailPend["kota_id"] ?? null,
                        "nilai" => $detailPend["nilai"] ?? null,
                        "jurusan" => $detailPend["jurusan"] ?? null,
                        "is_pend_terakhir" =>
                            $detailPend["is_pend_terakhir"] ?? null,
                        "ijazah_no" => $detailPend["ijazah_no"] ?? null,
                        "ijazah_foto" => $detailPend["ijazah_foto"] ?? null,
                        "keterangan" => $detailPend["keterangan"] ?? null,
                        "is_active" => $detailPend["is_active"] ?? null,
                        "creator_id" => $detailPend["creator_id"] ?? null,
                        "last_editor_id" =>
                            $detailPend["last_editor_id"] ?? null,
                    ];
                    $mappedHeader["t_pelamar_det_pend"][] = $mappedDetail;
                }
            }
            $mappedData[] = $mappedHeader;
        }

        return $mappedData;
    }

    public function custom_saveExcel($req)
    {
        $data = $req->all();
        if (!$data) {
            return response()->json(["errors" => "Data Tidak Terbaca"], 422);
        }

        try {
            \DB::beginTransaction();
            foreach ($data as $datas) {
                if (is_array($datas)) {
                    $pelamar = t_pelamar::create([
                        "nomor" => $datas["nomor"] ?? null,
                        "m_comp_id" => $datas["m_comp_id"] ?? null,
                        "m_dir_id" => $datas["m_dir_id"] ?? null,
                        "m_divisi_id" => $datas["m_divisi_id"] ?? null,
                        "m_dept_id" => $datas["m_dept_id"] ?? null,
                        "m_posisi_id" => $datas["m_posisi_id"] ?? null,
                        "nama_pelamar" => $datas["nama_pelamar"] ?? null,
                        "ktp_no" => $datas["ktp_no"] ?? null,
                        "tanggal" => $datas["tanggal"] ?? null,
                        "ref" => $datas["ref"] ?? null,
                        "telp" => $datas["telp"] ?? null,
                        "jk_id" => $datas["jk_id"] ?? null,
                        "tempat_lahir" => $datas["tempat_lahir"] ?? null,
                        "tgl_lahir" => $datas["tgl_lahir"] ?? null,
                        "salary" => $datas["salary"] ?? null,
                        "deskripsi" => $datas["deskripsi"] ?? null,
                        "status" => $datas["status"] ?? null,
                    ]);

                    foreach ($datas["t_pelamar_det_peng"] as $detPeng) {
                        t_pelamar_det_peng::create([
                            "t_pelamar_id" => $pelamar->id,
                            "nama_pengalaman" => $detPeng["nama_pengalaman"],
                            "posisi" => $detPeng["posisi"],
                            "date_from" => $detPeng["date_from"],
                            "date_to" => $detPeng["date_to"],
                            "kota_id" => $detPeng["kota_id"],
                            "keterangan" => $detPeng["keterangan"],
                            "is_active" => $detPeng["is_active"],
                        ]);
                    }

                    foreach ($datas["t_pelamar_det_pend"] as $detPend) {
                        t_pelamar_det_pend::create([
                            "t_pelamar_id" => $pelamar->id,
                            "tingkat_id" => $detPend["tingkat_id"],
                            "nama_sekolah" => $detPend["nama_sekolah"],
                            "tahun_masuk" => $detPend["tahun_masuk"],
                            "tahun_lulus" => $detPend["tahun_lulus"],
                            "kota_id" => $detPend["kota_id"],
                            "nilai" => $detPend["nilai"],
                            "jurusan" => $detPend["jurusan"],
                            "is_pend_terakhir" => $detPend["is_pend_terakhir"],
                            "ijazah_no" => $detPend["ijazah_no"],
                            "ijazah_foto" => $detPend["ijazah_foto"],
                            "keterangan" => $detPend["keterangan"],
                            "is_active" => $detPend["is_active"],
                        ]);
                    }
                }
            }

            \DB::commit();

            return $this->helper->customResponse("Data berhasil disimpan");
        } catch (\Exception $e) {
            \DB::rollBack();

            return $this->helper->responseCatch($e);
        }
    }

    public function custom_importexcel($request)
    {
        if (!$request->hasFile("file")) {
            return response()->json("file harus ada", 400);
        }
        return _uploadexcel($this, $request);
    }

    public function public_pelamar($request)
    {
        \DB::beginTransaction();

        try {
            $data = $request->all();

            if (is_array($data)) {
                $pelamar = t_pelamar::create([
                    "nomor" => $this->helper->generateNomor("KODE PELAMAR") ?? $data["nomor"],
                    "m_comp_id" => $data["m_comp_id"] ?? null,
                    "m_dir_id" => $data["m_dir_id"] ?? null,
                    "m_divisi_id" => $data["m_divisi_id"] ?? null,
                    "m_dept_id" => $data["m_dept_id"] ?? null,
                    "m_posisi_id" => $data["m_posisi_id"] ?? null,
                    "nama_pelamar" => $data["nama_pelamar"] ?? null,
                    "ktp_no" => $data["ktp_no"] ?? null,
                    "tanggal" => $data["tanggal"] ?? null,
                    "ref" => $data["ref"] ?? null,
                    "telp" => $data["telp"] ?? null,
                    "jk_id" => m_general::where('value', $data["jk_id"])->value('id') ?? 0,
                    "tempat_lahir" => $data["tempat_lahir"] ?? null,
                    "tgl_lahir" => $data["tgl_lahir"] ?? null,
                    "salary" => $data["salary"] ?? null,
                    "deskripsi" => $data["deskripsi"] ?? null,
                    "status" => $data["status"] ?? null,
                ]);
                if(isset($data["t_pelamar_det_peng"]))
                {
                    foreach ($data["t_pelamar_det_peng"] as $detPeng) {
                        t_pelamar_det_peng::create([
                            "t_pelamar_id" => $pelamar->id,
                            "nama_pengalaman" => $detPeng["nama_pengalaman"],
                            "posisi" => $detPeng["posisi"],
                            "date_from" => $detPeng["date_from"],
                            "date_to" => $detPeng["date_to"],
                            "kota_id" => m_general::where('value', $detPeng["kota_id"])->value('id') ?? 0,
                            "keterangan" => $detPeng["keterangan"],
                            "is_active" => $detPeng["is_active"],
                        ]);
                    }
                }
                if(isset($data["t_pelamar_det_pend"]))
                {
                    foreach ($data["t_pelamar_det_pend"] as $detPend) {
                        t_pelamar_det_pend::create([
                            "t_pelamar_id" => $pelamar->id,
                            "tingkat_id" =>  m_general::where('value', $detPend["tingkat_id"])->value('id') ?? 0,
                            "nama_sekolah" => $detPend["nama_sekolah"],
                            "tahun_masuk" => $detPend["tahun_masuk"],
                            "tahun_lulus" => $detPend["tahun_lulus"],
                            "kota_id" => m_general::where('value', $detPend["kota_id"])->value('id') ?? 0 ,
                            "nilai" => $detPend["nilai"],
                            "jurusan" => $detPend["jurusan"],
                            "is_pend_terakhir" => $detPend["is_pend_terakhir"],
                            "ijazah_no" => $detPend["ijazah_no"],
                            // "ijazah_foto" => $detPend["ijazah_foto"] ?? null,
                            "keterangan" => $detPend["keterangan"],
                            "is_active" => $detPend["is_active"],
                        ]);
                    }
                }
                if(isset($data["t_pelamar_det_pel"]))
                {
                    foreach ($data["t_pelamar_det_pel"] as $detPel) {
                        t_pelamar_det_pel::create([
                            "t_pelamar_id" => $pelamar->id,
                            "nama_pel"=> $detPel['nama_pel'],
                            "tahun"=> $detPel['tahun'],
                            "nama_lem"=> $detPel['nama_lem'],
                            "kota_id"=>  m_general::where('value', $detPel["kota_id"])->value('id') ?? 0,
                        ]);
                    }
                }

                if(isset($data["t_pelamar_det_org"]))
                {
                    foreach ($data["t_pelamar_det_org"] as $detOrg) {
                        t_pelamar_det_org::create([
                            "t_pelamar_id" => $pelamar->id,
                            "nama"=> $detOrg['nama'],
                            "tahun"=> $detOrg['tahun'],
                            // "jenis_org_id"=> m_general::where('value', $detPend["jenis_org_id"])->value('value') ?? 0,
                            "kota_id"=> m_general::where('value', $detOrg["kota_id"])->value('id') ?? 0,
                            "posisi"=> $detOrg['posisi'],
                            "desc"=> $detOrg['desc'],
                        ]);
                    }
                }

                if(isset($data["t_pelamar_det_bhs"]))
                {
                    foreach ($data["t_pelamar_det_bhs"] as $detBhs) {
                        t_pelamar_det_bhs::create([
                            "t_pelamar_id" => $pelamar->id,
                            "bhs_dikuasai"=> $detBhs['bhs_dikuasai'],
                            "nilai_lisan"=> $detBhs['nilai_lisan'] ?? null,
                            "level_lisan" => $detBhs('level_lisan') ?? null,
                            "nilai_tertulis"=> $detBhs['nilai_tertulis'] ?? null,
                            "level_tertulis" => $detBhs('level_tertulis') ?? null,
                            "desc"=>$detBhs['desc'],
                        ]);
                    }
                }

                if(isset($data["t_pelamar_det_pres"]))
                {
                    foreach ($data["t_pelamar_det_pres"] as $detPres) {
                        t_pelamar_det_pres::create([
                            "t_pelamar_id" => $pelamar->id,
                            "nama_pres"=> $detPres['nama_pres'],
                            "tahun"=> $detPres['tahun'],
                            "tingkat_pres_id" => m_general::where('value', $detPres["tingkat_pres_id"])->value('id') ?? 0,
                            "desc"=>$detPres['desc'],
                        ]);
                    }
                }

                
                \DB::commit(); // Commit transaksi jika semuanya berhasil
            }

        } catch (\Exception $e) {
            \DB::rollback(); // Rollback transaksi jika terjadi kesalahan
            throw $e; // Lepaskan exception setelah rollback
        }
    }
}
