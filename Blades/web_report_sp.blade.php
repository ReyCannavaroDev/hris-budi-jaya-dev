@php
  $req = app()->request;
  $periode_from = $req->periode_from ?? date('Y-m').'-01';
  $periode_to = $req->periode_to ?? date('Y-m-d');
  $raw = \DB::select("
    select 
    ts.tgl, ts.nomor,ts.keterangan,ts.status,ts.no_dokumen, k.nik, k.nama_lengkap, kd.nama dir, kdi.nama div, kde.nama dept, mg.value tipe_sgp  
    from t_sgp ts
      join m_kary k on k.id = ts.m_kary_id 
      join m_general mg on mg.id = ts.tipe_sgp_id
      left join m_dir kd on kd.id = ts.m_dir_id 
      left join m_divisi kdi on kdi.id = k.m_divisi_id 
      left join m_dept kde on kde.id = k.m_dept_id 
      where ts.status = 'POSTED' and ts.tgl BETWEEN ? AND ? and kd.id = coalesce(?,kd.id) and kdi.id = coalesce(?,kdi.id) and kde.id = coalesce(?,kde.id)
      and k.m_posisi_id = coalesce(?, k.m_posisi_id) and k.id = coalesce(?, k.id) 
      order by ts.id
  ", [ $periode_from, $periode_to, $req->m_dir_id, $req->m_divisi_id, $req->m_dept_id, $req->m_posisi_id, $req->m_kary_id ]);
@endphp
<span style="width:100%;text-align:center;font-weight:bold;"> Surat Penghargaan atau Surat Peringatan </span><br/>
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
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No SP</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">NIK</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Nama Karyawan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Direktorat</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Divisi</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Departemen</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Tipe SP</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No Dokumen</td>
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
      <td style="border:0.5px solid black;text-align:left;">{{ $d->div }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->dept }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->tipe_sgp }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->no_dokumen }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->keterangan }}</td>
      <td style="border:0.5px solid black;text-align:right;">{{ $d->status }}</td>
    </tr>
    @endforeach
  </tbody>
</table>  