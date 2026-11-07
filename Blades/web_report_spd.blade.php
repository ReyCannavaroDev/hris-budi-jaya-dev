@php
  $req = app()->request;
  $periode_from = $req->periode_from ?? date('Y-m').'-01';
  $periode_to = $req->periode_to ?? date('Y-m-d');
  $raw = \DB::select("
    select t.nomor, kd.nama dir, d.nama div, dp.nama dept, 
    coalesce(j.value, '-') jenis_spd, t.tanggal, t.tgl_acara_awal, t.tgl_acara_akhir , za.nama zona_asal, zt.nama zona_tujuan,
    l.nama lokasi, u.name pic, t.total_biaya, (select r.total_biaya_spd from t_rpd r where r.t_spd_id = t.id and r.status = 'POSTED' limit 1) total_biaya_real,
    t.kegiatan, t.keterangan, t.status, t.is_kend_dinas
    from t_spd t
    left join m_dir kd on kd.id = t.m_dir_id 
    left join m_divisi d on d.id = t.m_divisi_id 
    left join m_dept dp on dp.id = t.m_dept_id 
    left join m_general j on j.id = t.jenis_spd_id 
    left join m_zona za on za.id = t.m_zona_asal_id 
    left join m_zona zt on zt.id = t.m_zona_tujuan_id 
    left join m_lokasi l on l.id = t.m_lokasi_tujuan_id 
    left join default_users u on u.id = t.pic_id 
    where upper(t.status) = 'APPROVED' and t.tanggal >= ? and t.tanggal <= ? and kd.id = coalesce(?,t.m_dir_id) and d.id = coalesce(?,t.m_divisi_id) and dp.id = coalesce(?,t.m_dept_id)
  ", [ $periode_from, $periode_to, $req->m_dir_id, $req->m_divisi_id, $req->m_dept_id ]);
@endphp
<span style="width:100%;text-align:center;font-weight:bold;"> Perjalanan Dinas </span><br/>
@php
  $periode_from = date('d-m-Y', strtotime($periode_from));
  $periode_to = date('d-m-Y', strtotime($periode_to));
@endphp
<span style="width:100%;text-align:center; font-size:10pt"> {{ $periode_from }} - {{ $periode_to }}</span><br/>
<br/>
<table width="100%" style="font-size: 8px;" cellpadding="2">
  <thead style="font-weight:semibold;">
    <tr style="">
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">No</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Tanggal</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Nomor</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Direktorat</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Divisi</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Departemen</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Tgl Acara</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Zona Asal</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Zona Tujuan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Lokasi Tujuan</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">PIC</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Pakai KD</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Total Biaya</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Biaya Realisasi</td>
      <td style="border:0.5px solid black; font-weight: bold; line-height: 20px;text-align:center; background-color: #c6c6c6;">Kegiatan</td>
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
      <td style="border:0.5px solid black;text-align:left;">{{ $d->tanggal }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->nomor }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->dir }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->div }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->dept }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->tgl_acara_awal.' - '.$d->tgl_acara_akhir }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->zona_asal }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->zona_tujuan }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->lokasi }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->pic }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->is_kend_dinas ? 'Ya' : 'Tidak' }}</td>
      <td style="border:0.5px solid black;text-align:right;">{{ $d->total_biaya }}</td>
      <td style="border:0.5px solid black;text-align:right;">{{ $d->total_biaya_real }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->kegiatan }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->keterangan }}</td>
      <td style="border:0.5px solid black;text-align:left;">{{ $d->status }}</td>
    </tr>
    @endforeach
  </tbody>
</table>