@php
  $req = app()->request;
  $periode = $req->periode;

  $now =  $periode.'-'.date('d');

  $additional = "";

  if($periode){
    $additional = "  and case when k.tgl_berhenti is not null then to_char(k.tgl_berhenti, 'yyyy-mm') != '$periode' else true end
      and case when k.tgl_masuk is not null then to_char(k.tgl_masuk, 'yyyy-mm') = '$periode' end";
  }

  $raw = \DB::select("
  select 
  k.id, k.kode nik, k.nik no_ktp,k.nama_lengkap, a.nama_lengkap atasan, d.nama dir, dv.nama divisi,TO_CHAR(k.tgl_lahir, 'DD Mon YYYY') as tgl_lahir,
  dp.nama dept, p.desc_kerja posisi, k.tgl_masuk, k.tgl_berhenti, mg_kelamin.value as jk, k.alamat_domisili,TO_CHAR(k.tgl_berhenti, 'DD Mon YYYY') as tgl_berhenti,
  mkdp.no_rek, mkdk.bpjs_no_ketenagakerjaan, mkdk.bpjs_no_kesehatan, mkdk2.nama as nama_ibu, k.is_active, mg_kawin.value as status_kawin
  from m_kary k 
  left join m_kary a on a.id = k.atasan_id 
  left join m_dir d on d.id = k.m_dir_id 
  left join m_divisi dv on dv.id = k.m_divisi_id 
  left join m_dept dp on dp.id = k.m_dept_id 
  left join m_posisi p on p.id = k.m_posisi_id
  left join m_general mg_kelamin on mg_kelamin.id = k.jk_id
  left join m_kary_det_pemb mkdp on mkdp.m_kary_id = k.id
  LEFT JOIN (
  SELECT DISTINCT ON (m_kary_id) 
    m_kary_id, 
    bpjs_no_ketenagakerjaan, 
    bpjs_no_kesehatan
  FROM m_kary_det_kartu
  ORDER BY m_kary_id, created_at DESC
  ) mkdk ON mkdk.m_kary_id = k.id  
  left join m_general mg_kawin on mg_kawin.id = k.status_nikah_id
  left join (
  	select m_kary_det_kel.*, mg_kel.* from m_kary_det_kel left join m_general mg_kel on mg_kel.id = m_kary_det_kel.keluarga_id where mg_kel.code = 'HK08' LIMIT 1
  ) mkdk2 on mkdk2.m_kary_id = k.id
  where case when  k.m_dir_id is not null then k.m_dir_id = coalesce(?,k.m_dir_id) end
    and k.m_divisi_id = coalesce(?,k.m_divisi_id) 
    and k.m_dept_id = coalesce(?,k.m_dept_id) 
    $additional
    order by k.tgl_masuk
", [ $req->m_dir_id, $req->m_divisi_id, $req->m_dept_id]);
@endphp

<span style="width:100%;text-align:center;font-weight:bold;"> Karyawan Aktif </span><br/>
<br/>
<table width="100%" style="font-size: 8px;" cellpadding="2">
  <thead style="font-weight:semibold;">
    <tr style="">
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">NIK</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No KTP</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Nama Lengkap</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Tanggal Lahir</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Atasan</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Direktorat</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Divisi</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Departemen</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Posisi</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Tgl Bergabung</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Ibu Kandung</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No. BPJS Kesehatan</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No. BPJS Ketenagakerjaan</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No. Rekening</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Status Kawin</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Jenis Kelamin</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Alamat</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Sisa Cuti Satu Hari</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Status</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Tgl Berhenti</td>
    </tr>
  </thead>
  <tbody>
    @foreach($raw as $i => $d)
    @php
        $attendance = \DB::select("select public.employee_attendance(?,?,?)",[date('Y-m-d'), $d->id,null ]);
        $attendance = json_decode($attendance[0]->employee_attendance);
        $backgroundColor = $i % 2 === 1 ? '#f8f8f8' : ''; // Logika untuk menentukan warna latar belakang
    @endphp
    <tr style="background-color: {{$backgroundColor}}">
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:center;">{{ $i+1 }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->nik }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->no_ktp }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->nama_lengkap }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->tgl_lahir }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->atasan }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->dir }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->divisi }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->dept }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->posisi }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->tgl_masuk }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->nama_ibu }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->bpjs_no_kesehatan }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->bpjs_no_ketenagakerjaan }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->no_rek }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;"> {{ $d->status_kawin }} </td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->jk }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->alamat_domisili }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $attendance->sisa_cuti_satu_hari }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->is_active == 1 ? "Aktif" : "Tidak Aktif"  }}</td>
      <td style="padding-right: 5px; padding-left: 7px; border:0.5px solid black;text-align:left;">{{ $d->tgl_berhenti  }}</td>
    </tr>
    @endforeach
  </tbody>
</table>  