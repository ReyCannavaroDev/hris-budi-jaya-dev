@php
  $req = app()->request;
  $periode_from = $req->periode_from ?? date('Y-m').'-01';
  $periode_to = $req->periode_to ?? date('Y-m-d');
  $raw = \DB::select("
    select 
    tm.tgl, tm.nomor,tm.keterangan,tm.status, k.nik, k.nama_lengkap, kd.nama dir, kdi.nama divisi_lama, kdi1.nama divisi_baru, kde.nama dept_lama
    ,kde1.nama dept_baru, mp.desc_kerja posisi_lama, mp1.desc_kerja posisi_baru, mg.kode gaji_lama, mg1.kode gaji_baru  
    from t_mutasi tm
      join m_kary k on k.id = tm.m_kary_id 
      left join m_dir kd on kd.id = tm.m_dir_id 
      left join m_divisi kdi on kdi.id = tm.m_divisi_lama_id
      left join m_divisi kdi1 on kdi1.id = tm.m_devisi_baru_id 
      left join m_dept kde on kde.id = tm.m_dept_lama_id
      left join m_dept kde1 on kde1.id = tm.m_dept_baru_id
      left join m_posisi mp on mp.id = tm.m_posisi_lama_id
      left join m_posisi mp1 on mp1.id = tm.m_posisi_baru_id
      left join m_standart_gaji mg on mg.id = tm.m_standart_posisi_id
      left join m_standart_gaji mg1 on mg1.id = tm.m_standart_baru_id  
      where tm.status = 'POSTED' and tm.tgl BETWEEN ? AND ? and kd.id = coalesce(?,kd.id) and kdi.id = coalesce(?,kdi.id) and kde.id = coalesce(?,kde.id)
      and k.m_posisi_id = coalesce(?, k.m_posisi_id) and k.id = coalesce(?, k.id) 
      order by tm.id
  ", [ $periode_from, $periode_to, $req->m_dir_id, $req->m_divisi_id, $req->m_dept_id, $req->m_posisi_id, $req->m_kary_id ]);
@endphp
<span style="width:100%;text-align:center;font-weight:bold;"> Mutasi </span><br/>
@php
  $periode_from = date('d-m-Y', strtotime($periode_from));
  $periode_to = date('d-m-Y', strtotime($periode_to));
@endphp
<span style="width:100%;text-align:center; font-size:10pt"> {{ $periode_from }} - {{ $periode_to }}</span><br/>
<br/>
<table width="100%" style="font-size:10px;" cellpadding="2">
  <thead style="font-weight:semibold;">
    <tr style="">
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Tanggal</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No Mutasi</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">NIK</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Nama Karyawan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Direktorat</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Divisi Lama</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Divisi Baru</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Departemen Lama</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Departemen Baru</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Posisi Lama</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Posisi Baru</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Standart Gaji Lama</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Standart Gaji Baru</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Keterangan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Status</td>
    </tr>
  </thead>
  <tbody>
    @foreach($raw as $i => $d)
    @php
        $backgroundColor = $i % 2 === 1 ? '#f8f8f8' : ''; 
    @endphp
    <tr style="background-color: {{$backgroundColor}}">
      <td style="border:0.5px solid black;text-align:center;">{{ $i+1 }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->tgl }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->nomor }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->nik }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->nama_lengkap }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->dir }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->divisi_lama }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->divisi_baru }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->dept_lama }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->dept_baru }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->posisi_lama }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->posisi_baru }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->gaji_lama }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->gaji_baru }}</td>
      <td style="border:0.5px solid black;text-align:right;">{{ $d->keterangan }}</td>
      <td style="border:0.5px solid black;text-align:right;">{{ $d->status }}</td>
    </tr>
    @endforeach
  </tbody>
</table>