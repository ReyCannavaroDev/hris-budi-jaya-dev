@php
  $req = app()->request;
  $periode_from = $req->periode_from ?? date('Y-m').'-01';
  $periode_to = $req->periode_to ?? date('Y-m-d');

  $rawSql = "";

  if($req->filled('label')){
    $rawSql .= " and b.label = '$req->label'";
  }
  if($req->filled('m_kary_id')){
    $rawSql .= " and b.pic_id = $req->m_kary_id";
  }

  $raw = \DB::select("
   select b.id, b.label, to_char(b.date, 'dd-mm-yyyy') tgl, b.jml_kary, b.total_pendapatan, k.nama_lengkap, k.kode from t_salary_borongan b
   left join m_kary k on k.id = b.pic_id 
   where b.date >= ? and b.date <= ? $rawSql
  ", [ $periode_from, $periode_to ]);

  function formatRibuan($number) {
    return number_format($number, 0, ',', '.');
  }
@endphp
<span style="width:100%;text-align:center;font-weight:bold;"> Hasil Borongan </span><br/>
@php
  $periode_from = date('d-m-Y', strtotime($periode_from));
  $periode_to = date('d-m-Y', strtotime($periode_to));
@endphp
<span style="width:100%;text-align:center; font-size:10pt"> {{ $periode_from }} - {{ $periode_to }}</span><br/>
<br/>
@foreach($raw as $d)
<span style="width:100%;text-align:left; font-size: 10pt"> Tanggal : {{ $d->tgl }} </span><br>
<span style="width:100%;text-align:left; font-size: 10pt"> Label : {{ $d->label }} </span><br>
<span style="width:100%;text-align:left; font-size: 10pt"> Total Pendapatan : {{ formatRibuan($d->total_pendapatan) }} </span><br>
<span style="width:100%;text-align:left; font-size: 10pt"> PIC : {{ $d->kode .' - '. $d->nama_lengkap }} </span><br>
<span style="width:100%;text-align:left; font-size: 10pt; font-weight:bold;"> Detail Tarif </span><br>
<table width="100%" style="font-size:10px;" cellpadding="2">
  <thead style="font-weight:semibold;">
    <tr style="">
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 2%">No</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 50%">Item</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 10%">Tarif</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 8%">Qty</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 10%">Subtotal</td>
    </tr>
  </thead>
  <tbody>
    @php
      $det_tarif = \DB::select("select tarif_desc, tarif, qty, subtotal from t_salary_borongan_det d where d.t_salary_borongan_id = ?", [$d->id])
    @endphp
    @foreach($det_tarif as $i => $d2)
    @php
        $backgroundColor = $i % 2 === 1 ? '#f8f8f8' : ''; 
    @endphp
    <tr style="background-color: {{$backgroundColor}}">
      <td style="border:0.5px solid black;text-align:center; width: 2%">{{ $i+1 }}</td>
      <td style="border:0.5px solid black;text-align:left; width: 50%">{{ $d2->tarif_desc }}</td>
      <td style="border:0.5px solid black;text-align:right; width: 10%">{{ formatRibuan($d2->tarif) }}</td>
      <td style="border:0.5px solid black;text-align:right; width: 8%">{{ formatRibuan($d2->qty) }}</td>
      <td style="border:0.5px solid black;text-align:right; width: 10%">{{ formatRibuan($d2->subtotal) }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<span style="width:100%;text-align:left; font-size: 10pt; font-weight:bold;"> Detail Karyawan </span><br>
<table width="100%" style="font-size:10px;" cellpadding="2">
  <thead style="font-weight:semibold;">
    <tr style="">
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 2%">No</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 50%">Karyawan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 10%">Departemen</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 8%">Diterima</td>
    </tr>
  </thead>
  <tbody>
    @php
      $det_kary = \DB::select("select coalesce(k.nama_lengkap, '-') nama_lengkap, dp.nama dept, d.diterima from t_salary_borongan_det_kary d 
        left join m_kary k on k.id = d.m_kary_id
        left join m_dept dp on dp.id = k.m_dept_id 
        where d.t_salary_borongan_id = ?", [$d->id])
    @endphp
    @foreach($det_kary as $i => $d3)
    @php
        $backgroundColor = $i % 2 === 1 ? '#f8f8f8' : ''; 
    @endphp
    <tr style="background-color: {{$backgroundColor}}">
      <td style="border:0.5px solid black;text-align:center; width: 2%">{{ $i+1 }}</td>
      <td style="border:0.5px solid black;text-align:left; width: 50%">{{ $d3->nama_lengkap }}</td>
      <td style="border:0.5px solid black;text-align:left; width: 10%">{{ $d3->dept }}</td>
      <td style="border:0.5px solid black;text-align:right; width: 8%">{{ formatRibuan($d3->diterima) }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<br>
@endforeach