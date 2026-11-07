@php
$req = app()->request;

$f_id = $req->f_id;

$raw = \DB::select("
select
k.nik, k.jk_id, k.nama_lengkap, kd.nama dir, kdi.nama divisi, kde.nama dept, fd.total_gaji, fd.total_tax, fd.netto,
fd.periode_in_date , fd.periode,f.created_at, f.desc
from t_final_gaji_det fd
join t_final_gaji f on fd.t_final_gaji_id = f.id
join m_kary k on k.id = fd.m_kary_id
left join m_dir kd on kd.id = fd.m_kary_dir_id
left join m_divisi kdi on kdi.id = fd.m_kary_divisi_id
left join m_dept kde on kde.id = fd.m_kary_dept_id
where f.status = 'POSTED' and f.id = ?
", [$f_id ?? 0]);

$totalGaji = 0;

$rekeningFrom = \DB::select("select g.value from m_general g where g.group = 'REKENING TRANSFER' limit 1");

// Loop melalui hasil query dan menjumlahkan total_gaji
foreach ($raw as $row) {
    $totalGaji += $row->netto;
}
@endphp
@if(!count($raw))
  <i>data fianal gaji tidak ditemukan</i>
@else
  <table width="100%">
    <tbody>
      <tr>
        <td>P</td>
        <td>{{ @$raw[0]->created_at }}</td>
        <td>{{ @$rekeningFrom[0]->value }}</td>
        <td>{{count($raw)}}</td>
        <td>{{$totalGaji}}</td>
      </tr>
      @foreach($raw as $i => $d)      
        <tr>
          <td>{{$d->nik}}</td>
          <td>{{ $d->nama_lengkap}}</td>
          <td></td>
          <td></td>
          <td></td>
          <td>IDR</td>
          <td>{{ $d->netto}}</td>
          <td>{{$d->desc}}</td>
          <td>{{ ($d->jk_id === 346) ? 'IBU' : 'BPK'}}</td>
          <td>MANDIRI</td>
          <td>MADIUN</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif