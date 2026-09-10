<?php

namespace App\Models\CustomModels;

class t_perhitungan_gaji extends \App\Models\BasicModels\t_perhitungan_gaji
{    
    private $helper;
    private $perhitunganGaji;
    public function __construct()
    {
        parent::__construct();
        $this->helper = getCore('Helper');
        $this->perhitunganGaji = getCore('PerhitunganGaji');
    }
    
    public $fileColumns    = [ /*file_column*/ ];

    public $createAdditionalData = ["creator_id"=>"auth:id"];
    public $updateAdditionalData = ["last_editor_id"=>"auth:id"];


    protected $factorAdded = [];

 

    public function generateSalary()
    {
        try{
            $req =  app()->request;
            
            if($req->periode_awal && $req->periode_akhir){
                $kary = m_kary::selectRaw("m_kary.*,m_general.value periode_text, m_dir.nama dir, m_divisi.nama divisi, m_dept.nama dept")
                    ->leftJoin('m_dir','m_dir.id','m_kary.m_dir_id')
                    ->leftJoin('m_divisi','m_divisi.id','m_kary.m_divisi_id')
                    ->leftJoin('m_dept','m_dept.id','m_kary.m_divisi_id')
                    ->join('m_general','m_general.id','m_kary.periode_gaji_id')
                    ->whereRaw('m_kary.m_standart_gaji_id in(select s.id from m_standart_gaji s where s.is_active = true)');

                if($req->m_dept_id) $kary = $kary->where('m_kary.m_dept_id', $req->m_dept_id);
                if($req->m_divisi_id) $kary = $kary->where('m_kary.m_divisi_id', $req->m_divisi_id);

                $kary = $kary->get();
                $date_from = \DateTime::createFromFormat('Y-m-d', $req->periode_awal.'-20');
                $date_to = \DateTime::createFromFormat('Y-m-d', $req->periode_akhir.'-20');
                // Menghitung jumlah bulan antara tanggal_awal dan tanggal_akhir
                $interval = $date_from->diff($date_to);
                $jumlah_bulan = (($interval->y) * 12) + ($interval->m);

                $data = [];
                for ($i = 0; $i <= $jumlah_bulan; $i++) {
                    $date = $date_from->format('Y-m-d');
                    foreach($kary as $key)
                    {
                        $gaji = $this->perhitunganGaji->salaryOfKary($key->id, $date_from->format('Y-m'));
                        $data[] = [
                            'm_kary_id'         => $key->id,
                            'm_kary.nik'        => $key->nik,
                            'm_kary_dir_id'     => $key->m_dir_id,
                            'm_kary_dir.nama'   => $key->dir,
                            'm_kary_divisi_id'  => $key->m_divisi_id,
                            'm_kary_divisi.nama'=> $key->divisi,
                            'm_kary_dept_id'    => $key->m_dept_id,
                            'm_kary_dept.nama'  => $key->dept,
                            'nik'               => $key->nik,
                            'nama_lengkap'      => $key->nama_lengkap,
                            'periode'           => $date_from->format('d-m-Y'),
                            'periode_in_date'   => $date,
                            'periode_id'        => $key->periode_gaji_id,
                            'periode_text'      => $key->periode_text,
                            'total_tax'         => $gaji['total_tax'] ?? 0,
                            'total_gaji'        => $gaji['total_gaji'],
                            'netto'             => $gaji['netto'],
                            'detail_gaji'       => $gaji['detail'],
                        ];
                    }

                    // Menambahkan satu bulan untuk iterasi berikutnya
                    $date_from->add(new \DateInterval('P1M'));
                }
            }else if($req->tgl_awal && $req->tgl_akhir){
                $kary = m_kary::selectRaw("m_kary.*,m_general.value periode_text, m_dir.nama dir, m_divisi.nama divisi, m_dept.nama dept")
                    ->leftJoin('m_dir','m_dir.id','m_kary.m_dir_id')
                    ->leftJoin('m_divisi','m_divisi.id','m_kary.m_divisi_id')
                    ->leftJoin('m_dept','m_dept.id','m_kary.m_dept_id')
                    ->leftJoin('m_general','m_general.id','m_kary.periode_gaji_id')
                    ->whereRaw('m_kary.id in(select s.m_kary_id from t_kary_salary s where s.is_active = true) AND m_kary.is_active = true');

                if($req->m_dept_id) $kary = $kary->where('m_kary.m_dept_id', $req->m_dept_id);
                if($req->m_divisi_id) $kary = $kary->where('m_kary.m_divisi_id', $req->m_divisi_id);

                $kary = $kary->get();

                $date_from = \DateTime::createFromFormat('Y-m-d', $req->tgl_awal);
                $date_to = \DateTime::createFromFormat('Y-m-d', $req->tgl_akhir);
                $date_from = $date_from->format('Y-m-d');
                $date_to = $date_to->format('Y-m-d');

                $data = [];

                foreach($kary as $key)
                {
                    $gaji = $this->perhitunganGaji->salaryOfKaryManual($key->id, $date_from, $date_to, $req['is_tunjangan']);
                    // trigger_error(json_encode($gaji));
                    $data[] = [
                        'm_kary_id'         => $key->id,
                        'm_kary.nik'        => $key->nik,
                        'm_kary_dir_id'     => $key->m_dir_id,
                        'm_kary_dir.nama'   => $key->dir,
                        'm_kary_divisi_id'  => $key->m_divisi_id,
                        'm_kary_divisi.nama'=> $key->divisi,
                        'm_kary_dept_id'    => $key->m_dept_id,
                        'm_kary_dept.nama'  => $key->dept,
                        'nik'               => $key->nik,
                        'nama_lengkap'      => $key->nama_lengkap,
                        'periode'           => $date_from.' - '.$date_to,
                        'periode_in_date'   => $date_to,
                        'periode_id'        => $key->periode_gaji_id,
                        'periode_text'      => $key->periode_text,
                        'total_tax'         => @$gaji['total_tax'] ?? 0,
                        'total_gaji'        => $gaji['total_gaji'],
                        'netto'             => $gaji['netto'],
                        'detail_gaji'       => $gaji['detail'],
                    ];
                                        
                }
            }else{
                trigger_error('Filter periode tidak valid');
            }

            
            

            return $this->helper->customResponse('OK', 200, $data);
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
            return $this->helper->responseCatch($e);
        }
    }

    public function public_generate()
    {
        $data =  $this->perhitunganGaji->salaryOfKary(app()->request->id ?? 8, '2024-03');
       
        return response(['msg'=>$data]);
    }

    public function custom_generate()
    {
        return $this->generateSalary();
    }

    public function custom_generatePPH($req)
    {
        $netto = $req->netto;
        $kary = m_kary::find($req->m_kary_id);
        return $this->perhitunganGaji->countPPH21($kary, $netto);
    }

    public function custom_save($req)
    {
        $counter = count($req->detail);
        if($counter){
            $nomor = $this->helper->generateNomor('KODE PERHITUNGAN GAJI');
            if($req->tipe == 'BORONGAN'){
                $this->whereRaw("periode_in_date <= ? AND 
                    (periode_in_date + INTERVAL '7 days') >= ?", [$req->periode_akhir, $req->periode_awal])->delete();
            }
            foreach($req->detail as $key){
                if($req->tipe != 'BORONGAN'){
                    $checkAndDelete = $this->where('m_kary_id', @$key['m_kary_id'])->where('periode', @$key['periode'])->delete();
                }
                $key['nomor'] =  $nomor;
                $key['type_perhitungan'] = $req->tipe == 'BORONGAN' ? 'BORONGAN' : '';
                $key['detail_gaji'] = json_encode($key['detail_gaji']);
                $hdr = $this->create($key);
            }
        }
        return $this->helper->customResponse("$counter Data berhasil disimpan");
    }
    
       
    // public function scopeGenerateForFinal($model)
    // {
    //     $req = app()->request;
    //     $date_from = \DateTime::createFromFormat('Y-m-d', $req->periode_awal) ?? null;
    //     $date_to = \DateTime::createFromFormat('Y-m-d', $req->periode_akhir) ?? null;

    //     $model = $model->whereBetween('periode_in_date', [$date_from,$date_to]);
    //     if($req->m_divisi_id) $model = $model->where('t_perhitungan_gaji.m_kary_divisi_id', $req->m_divisi_id);
    //     if($req->m_dept_id) $model = $model->where('t_perhitungan_gaji.m_kary_dept_id', $req->m_dept_id);
    //     if($req->type_perhitungan == 'BORONGAN') $model = $model->where('t_perhitungan_gaji.type_perhitungan', 'BORONGAN');
    //     if($req->type_perhitungan != 'BORONGAN') $model = $model->where('t_perhitungan_gaji.type_perhitungan','!=','BORONGAN');

    //     return $model;
    // }
    public function t_final_gaji_det() {
        return $this->hasOne(t_final_gaji_det::class, 't_perhitungan_gaji_id');
    }

    public function scopeGenerateForFinal($model)
    {
        $req = app()->request;
        $date_from = \DateTime::createFromFormat('Y-m-d', $req->periode_awal) ?? null;
        $date_to = \DateTime::createFromFormat('Y-m-d', $req->periode_akhir) ?? null;

        // 1. Filter dasar periode
        $model = $model->whereBetween('periode_in_date', [$date_from, $date_to]);

        // 2. Filter Organisasi & Tipe
        if($req->m_divisi_id) $model = $model->where('t_perhitungan_gaji.m_kary_divisi_id', $req->m_divisi_id);
        if($req->m_dept_id) $model = $model->where('t_perhitungan_gaji.m_kary_dept_id', $req->m_dept_id);
        
        if($req->type_perhitungan == 'BORONGAN') {
            $model = $model->where('t_perhitungan_gaji.type_perhitungan', 'BORONGAN');
        } else {
            $model = $model->where('t_perhitungan_gaji.type_perhitungan', '!=', 'BORONGAN');
        }

        /**
        * PENGECEKAN DUPLIKASI:
        * Pastikan t_perhitungan_gaji_id belum ada di tabel t_final_gaji_det
        * Asumsi: Nama relasi di model TPerhitunganGaji ke TFinalGajiDet adalah 'final_gaji_det'
        */
        $model = $model->whereDoesntHave('t_final_gaji_det');

        return $model;
    }
}