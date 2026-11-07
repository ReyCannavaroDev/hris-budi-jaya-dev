@php
$req = app()->request;

$periode_from = $req->periode_from ?? date('Y-m') . '-01';
$periode_to = $req->periode_to ?? date('Y-m-d');
$posisi_id = isset($req->m_posisi_id) ? (int) $req->m_posisi_id : null;

$query = \DB::table('t_lembur as tl')
    ->select(
        'tl.tanggal',
        'tl.jam_mulai',
        'tl.jam_selesai',
        'tl.nomor',
        'tl.keterangan',
        'tl.status',
        'tl.no_doc',
        'k.kode as nik',
        'k.nama_lengkap',
        'kd.nama as dir',
        'kdi.nama as div',
        'kde.nama as dept',
        'mg.value as tipe_lembur',
        'mg1.value as alasan'
    )
    ->join('m_kary as k', 'k.id', '=', 'tl.m_kary_id')
    ->leftJoin('m_general as mg', 'mg.id', '=', 'tl.tipe_lembur_id')
    ->leftJoin('m_general as mg1', 'mg1.id', '=', 'tl.alasan_id')
    ->leftJoin('m_dir as kd', 'kd.id', '=', 'k.m_dir_id')
    ->leftJoin('m_divisi as kdi', 'kdi.id', '=', 'k.m_divisi_id')
    ->leftJoin('m_dept as kde', 'kde.id', '=', 'k.m_dept_id')
    ->whereBetween('tl.tanggal', [$periode_from, $periode_to])
    ->where('tl.status', 'APPROVED');

    if ($req->m_dir_id) {
        $query->where('kd.id', $req->m_dir_id);
    }
    if ($req->m_divisi_id) {
        $query->where('kdi.id', $req->m_divisi_id);
    }
    if ($req->m_dept_id) {
        $query->where('kde.id', $req->m_dept_id);
    }
    if ($posisi_id) {
        $query->where('k.m_posisi_id', $posisi_id);
    }
    if ($req->tipe_lembur_id) {
        $query->where('tl.tipe_lembur_id', $req->tipe_lembur_id);
    }
    if ($req->m_kary_id) {
        $query->where('k.id', $req->m_kary_id);
    }

    $raw = $query->orderBy('tl.id')->get();

@endphp
<span style="width:100%;text-align:center;font-weight:bold;"> Surat Perintah Lembur </span><br/>
@php
  $periode_from = date('d-m-Y', strtotime($periode_from));
  $periode_to = date('d-m-Y', strtotime($periode_to));
@endphp
<span style="width:100%;text-align:center; font-size:10pt"> {{ $periode_from }} - {{ $periode_to }}</span><br/>
<br/>
<table width="100%" style="font-size:8px;" cellpadding="2">
  <thead style="font-weight:semibold;">
    <tr style="">
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Tanggal</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No Lembur</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">NIK</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Nama Karyawan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Direktorat</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Divisi</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Departemen</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Jam Mulai</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Jam Selesai</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Tipe Lembur</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Alasan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No Dokumen</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Keterangan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Status</td>
    </tr>
  </thead>
  <tbody>
    @foreach($raw as $i => $d)
    @php
        $backgroundColor = $i % 2 === 1 ? '#f8f8f8' : ''; // Logika untuk menentukan warna latar belakang
    @endphp
    <tr style="background-color: {{$backgroundColor}}">
      <td style="border:0.5px solid black;text-align:center;">{{ $i+1 }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->tanggal }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->nomor }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->nik }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->nama_lengkap }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->dir }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->div }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->dept }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->jam_mulai }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->jam_selesai }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->tipe_lembur }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->alasan }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->no_doc }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->keterangan }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->status }}</td>
    </tr>
    @endforeach
  </tbody>
</table>  