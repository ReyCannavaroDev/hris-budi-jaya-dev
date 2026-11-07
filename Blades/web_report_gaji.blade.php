@php
$req = app()->request;
$periode = $req->periode ?? '2023-12-01';
$raw = \DB::select("
                    SELECT 
                      k.id,k.kode,k.m_standart_gaji_id, k.nama_depan ,k.nama_lengkap, (select employee_attendance(?,?)) absensi, mp.desc_kerja, msg.gaji_pokok, msg.tunjangan_posisi,
                      msg.tunjangan_tetap, msg.uang_makan, msg.uang_saku 
                    FROM 
                      m_kary k
                    JOIN 
                      m_posisi mp on mp.id = k.m_posisi_id
                    LEFT JOIN
                      m_standart_gaji msg on msg.id = k.m_standart_gaji_id 
                    WHERE 
                      k.is_active = true and k.m_dept_id is not null; ", [ $periode, $req->m_kary_id ]);
@endphp
<span style=" width:100%;text-align:center;font-weight:bold;"> Laporan Gaji Karyawan </span><br/>

@php
  $periode = date('d-m-Y', strtotime($periode));
@endphp

  <span style="width:100%;text-align:center; font-size:10pt"> {{ $periode }} </span><br/>
  <br/>
  <table width="100%" style="font-size:10px;" cellpadding="1">
    <thead style="font-weight:semibold;">
      <tr style="">
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          rowspan="2">
          NO</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          rowspan="2">
          NAMA</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          rowspan="2">
          JABATAN</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          rowspan="2">
          HADIR</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          rowspan="2">
          GAJI POKOK</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          rowspan="2">
          GAJI PROPOSIONAL</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          rowspan="2">
          IJIN</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          colspan="5">Tunjangan</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          colspan="5">Potongan</td>
                  <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;"
          rowspan="2">
          TOTAL</td>

      </tr>
      <!-- 2 -->
      <tr style="">
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          JABATAN</td>
                  <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          KENDARAAN</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          KOST</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          MAKAN DAN TRANSPORT</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          MASA KERJA</td>
                  <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          PERFOM</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          BPJS</td>
                  <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          KOPRASI</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          HP</td>
        <td
          style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">
          LAIN LAIN</td>
      </tr>

    </thead>
    <tbody>
      @foreach($raw as $i => $d)
      @php
      $backgroundColor = $i % 2 === 1 ? '#f8f8f8' : '';
      $tunjangan_jabatan = ($d->tunjangan_posisi != 0) ? number_format($d->tunjangan_posisi, 2, ',', '.') : $d->tunjangan_posisi ;
      $uang_makan = ($d->uang_makan != 0) ? number_format($d->uang_makan, 2, ',', '.') : $d->uang_makan;
      $absen = json_decode($d->absensi);
      @endphp
      <tr style="background-color: {{$backgroundColor}}">
        <td style="border:0.5px solid black;text-align:center;">{{ $i+1 }}</td>
        <td style="border:0.5px solid black;text-align:left;">{{ $d->nama_lengkap }}</td>
        <td style="border:0.5px solid black;text-align:left;">{{ $d->desc_kerja }}</td>
        <td style="border:0.5px solid black;text-align:left;">{{ $absen->work_present }}</td>
        <td style="border:0.5px solid black;text-align:left;">{{ $d->gaji_pokok }}</td>
        <td style="border:0.5px solid black;text-align:left;">0</td>
        <td style="border:0.5px solid black;text-align:left;">{{ $absen->cuti_terpakai }}</td>
        <td style="border:0.5px solid black;text-align:right;">{{ $tunjangan_jabatan }}</td>
        <td style="border:0.5px solid black;text-align:right;">0</td>
        <td style="border:0.5px solid black;text-align:right;">0</td>
        <td style="border:0.5px solid black;text-align:right;">{{ $uang_makan }}</td>
        <td style="border:0.5px solid black;text-align:right;">{{ $absen->work_days_in_month }}</td>
        <td style="border:0.5px solid black;text-align:right;">{{ $absen->potongan_cuti }}</td>
        <td style="border:0.5px solid black;text-align:right;">0</td>
        <td style="border:0.5px solid black;text-align:right;">0</td>
        <td style="border:0.5px solid black;text-align:right;">0</td>
        <td style="border:0.5px solid black;text-align:right;">0</td>
        <td style="border:0.5px solid black;text-align:right;">0</td>




      </tr>
      @endforeach
    </tbody>
  </table>