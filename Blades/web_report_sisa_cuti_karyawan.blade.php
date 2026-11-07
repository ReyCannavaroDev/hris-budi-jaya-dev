@php
  $req = app()->request;
  $tipe = $req->tipe_report;

  $rekap = [];
  $periode_asli = $req->periode;
  $periode = $req->periode.'-'.date('d');
  $data = \DB::select("
    select 
      employee_attendance(?,k.id) absen,
      k.id, kode, k.tgl_masuk, nama_lengkap, dv.nama div, d.nama dept 
    from m_kary k
    join default_users u on u.m_kary_id = k.id
    join m_divisi dv on dv.id = k.m_divisi_id
    join m_dept d on d.id = k.m_dept_id
        where k.is_active = true 
        and k.m_dept_id IS NOT NULL and k.m_dept_id != 0
        and k.m_divisi_id = COALESCE(?, k.m_divisi_id) 
        and k.m_dept_id = COALESCE(?, k.m_dept_id)
        and k.id = COALESCE(?, k.id)
        ",[
          $periode, $req->m_divisi_id, $req->m_dept_id, $req->kary_id
        ]);
  
@endphp
<h4 style="font-weight:bold; font-size: 10pt">{{ $tipe }}</h4>
<h4 style="font-weight:bold; font-size: 10pt; margin-top: -15px">{{ 'Periode : '.$periode_asli}}</h4>
<i style="font-weight:bold; font-style: italic; font-size: 8pt;"> LK     : Lama Kerja</i><br/>
<i style="font-weight:bold; font-style: italic; font-size: 8pt;"> CTH     : Cuti Tahunan</i><br/>
<i style="font-weight:bold; font-style: italic; font-size: 8pt;"> CTH (-) : Sisa Cuti Tahunan</i><br/>
<br/>
<table width="100%" style="border-collapse: collapse;" cellpadding="3">
  <thead>
    <tr>
      <th style="border:0.5px solid black; font-size: 9pt; background-color: #c6c6c6; width: 10%; ">NIK</th>
      <th style="border:0.5px solid black; font-size: 9pt; background-color: #c6c6c6; width: 20%;">Karyawan</th>
      <!-- <th style="border:0.5px solid black; font-size: 9pt; background-color: #c6c6c6; width: 14%;">Divisi</th> -->
      <th style="border:0.5px solid black; font-size: 9pt; background-color: #c6c6c6; width: 25%;">Departemen</th>
      <th style="border:0.5px solid black; font-size: 9pt; background-color: #c6c6c6;">Tgl Masuk</th>
      <th style="border:0.5px solid black; background-color: #c6c6c6; font-size: 9pt; width: 5%;">LK</th>
      <th style="border:0.5px solid black; background-color: #c6c6c6; font-size: 9pt; width: 5%;">CTH</th>
      <th style="border:0.5px solid black; background-color: #c6c6c6; font-size: 9pt; width: 5%;">CTH (-)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $dt)
        <tr>
          <td style="border:0.5px solid black; font-size: 9pt; width: 10%;">{{ $dt->kode }}</td>
          <td style="border:0.5px solid black; font-size: 9pt; width: 20%;">{{ $dt->nama_lengkap }}</td>
          <!-- <td style="border:0.5px solid black; font-size: 9pt; width: 14%;">{{ $dt->div }}</td> -->
          <td style="border:0.5px solid black; font-size: 9pt; width: 25%;">{{ $dt->dept }}</td>
          <td style="border:0.5px solid black; font-size: 9pt; text-align: center;">{{ $dt->tgl_masuk }}</td>
          <td style="border:0.5px solid black; font-size: 9pt; text-align: right; width: 5%;">{{ @json_decode($dt->absen)->masa_kerja ?? 0 }}</td>
          <td style="border:0.5px solid black; font-size: 9pt; text-align: right; width: 5%;">{{ @json_decode($dt->absen)->cuti_satu_hari ?? 0 }}</td>
          <td style="border:0.5px solid black; font-size: 9pt; text-align: right; width: 5%;">{{ @json_decode($dt->absen)->sisa_cuti_satu_hari ?? 0 }}</td>
        </tr>
    @endforeach
  </tbody>
</table>
