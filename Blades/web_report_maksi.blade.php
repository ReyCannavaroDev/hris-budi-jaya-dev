@php
  $req = app()->request;
  $date = $req->date;
  $dateEx = explode('/',$date);
  if(count($dateEx)){
    $date = $dateEx[2].'/'.$dateEx[1].'/'.$dateEx[0];
  }
  $raw = \DB::select("
    select d.created_at, m.tanggal,k.nama_lengkap, d.lauk from presensi_maksi_det d 
    join m_kary k on k.id = d.m_kary_id 
    join presensi_maksi m on m.id = d.presensi_maksi_id 
    where m.tanggal = ?
  ", [ $date ]);

  foreach($raw as $d){
    $d->lauk = json_decode($d->lauk);
  }
@endphp
<span style="width:100%;text-align:center;font-weight:bold; font-size:14pt"> Rekap Pesan Makan Siang </span><br/>
<span style="width:100%;text-align:center; font-size:12pt"> {{ $date }}</span><br/>
<span style="width:100%;text-align:center; font-size:10pt"> {{ @$raw[0]->keterangan }}</span>
<br/>
<table width="100%" style="font-size:12px;" cellpadding="2">
  <thead style="font-weight:semibold;">
    <tr style="">
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 4%; font-size: 14px;">No</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:left; background-color: #c6c6c6; width: 25%; font-size: 14px;">Pemesan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:left; background-color: #c6c6c6; width: 15%; font-size: 14px;">Dipesan Pada</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6; width: 55%; font-size: 14px;">Detail</td>
    </tr>
  </thead>
  <tbody>
    @foreach($raw as $i => $d)
    @php
        $backgroundColor = $i % 2 === 1 ? '#f8f8f8' : ''; // Logika untuk menentukan warna latar belakang
    @endphp
    <tr>
      <td style="border:0.5px solid black;text-align:center; width: 4%; font-size: 12px;">{{ $i+1 }}</td>
      <td style="border:0.5px solid black;text-align:left; width: 25%; font-size: 12px;">{{ $d->nama_lengkap }}</td>
      <td style="border:0.5px solid black;text-align:left; width: 15%; font-size: 12px;">{{ $d->created_at }}</td>
      <td style="border:0.5px solid black;text-align:left; width: 55%; font-size: 12px;">
        <ul>
          @foreach($d->lauk as $l)
            <li>{{ $l->tipe_lauk }} : {{ $l->detail_text }}</li>
          @endforeach
        </ul>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>  