@php
  $req = app()->request;
  $periode_from = $req->periode_from ?? date('Y-m').'-01';
  $periode_to = $req->periode_to ?? date('Y-m-d');

  $header = \DB::select("
    select tl.*, mg.value jenis_loker, mg1.value prioritas
        from t_loker tl 
        join m_general mg on mg.id = tl.jenis_loker_id
        join m_general mg1 on mg1.id = tl.prioritas_id
  ");

@endphp
<span style="width:100%;text-align:center;font-weight:bold;"> Rekrutmen Karyawan </span><br/>
@php
  $periode_from1 = date('d-m-Y', strtotime($periode_from));
  $periode_to1 = date('d-m-Y', strtotime($periode_to));
@endphp
<span style="width:100%;text-align:center; font-size:10pt"> {{ $periode_from1 }} - {{ $periode_to1 }}</span><br/>
<br/>
<br>
    @foreach($header as $index => $h)
    <div>
      <table style="border-collapse: collapse; width: 100%; font-size: 10px;" id="ketTabel">
        <tr>
          <td style="width: 15%;">Nomor Loker</td>
          <td>: {{$h->nomor}}</td>
        </tr>
        <tr>
          <td>Jenis Loker</td>
          <td>: {{$h->jenis_loker}}</td>
        </tr>
        <tr>
          <td>Prioritas Loker</td>
          <td>: {{$h->prioritas}}</td>
        </tr>
        <tr>
          <td>Periode Loker</td>
          <td>: {{$h->tgl_dibuka}}</td>
        </tr>
        <tr>
          <td>Deskripsi Loker</td>
          <td>: {{$h->deskripsi}}</td>
        </tr>
      </table>
    </div>
    <br>
      <!-- <div>
        <span style="text-align:center; font-size:10pt">Nomor Loker : {{$h->nomor}} </span><br>
        <span style="text-align:center; font-size:10pt">Jenis Loker : {{$h->jenis_loker}} </span><br>
        <span style="text-align:center; font-size:10pt">Prioritas Loker : {{$h->prioritas}} </span><br>
        <span style="text-align:center; font-size:10pt">Periode Loker : {{$h->tgl_dibuka}} - {{$h->tgl_akhir}} </span><br>
        <span style="text-align:center; font-size:10pt">Deskripsi Loker : {{$h->deskripsi}} </span>
      </div><br> -->
      @php 
        $raw = \DB::select("
          select 
            (select mg1.value from m_general mg1 
              join t_pelamar_det_pend tpd on tpd.tingkat_id = mg1.id where tpd.t_pelamar_id = tp.id 
                ORDER BY tpd.tingkat_id DESC LIMIT 1) pend, 
          tp.status,tp.id, tp.tanggal, tp.nomor,tp.nama_pelamar,tp.ktp_no,tp.telp, tp.tgl_lahir, tp.tempat_lahir, mg.value jk,tp.salary,tp.deskripsi,kd.nama dir,kdi.nama div, kde.nama dept,mp.desc_kerja posisi
          from t_pelamar tp
            join m_general mg on mg.id = tp.jk_id
            join t_loker tl on tl.id = tp.t_loker_id
            left join m_dir kd on kd.id = tp.m_dir_id 
            left join m_divisi kdi on kdi.id = tp.m_divisi_id 
            left join m_dept kde on kde.id = tp.m_dept_id
            left join m_posisi mp on mp.id = tp.m_posisi_id
            where tp.t_loker_id = ? and tp.status = 'active' and tp.tanggal BETWEEN ? AND ? and kd.id = coalesce(?,kd.id) and kdi.id = coalesce(?,kdi.id) and kde.id = coalesce(?,kde.id)
          and mp.id = coalesce(?, mp.id)
  ", [ $h->id ?? 0, $periode_from, $periode_to, $req->m_dir_id, $req->m_divisi_id, $req->m_dept_id, $req->m_posisi_id ]);
      @endphp
      <table width="100%" style="font-size:10px;" cellpadding="2">
        <thead style="font-weight:semibold;">
          <tr style="">
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">No</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Tanggal</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">No Pelamar</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">KTP</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Nama Pelamar</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">No Telp</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Pend Terakhir</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Jenis Kelamin</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">TTL</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Direktorat</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Divisi</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Departemen</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Posisi</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Gaji Min</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Deskripsi</td>
            <td style="border:0.5px solid black; font-weight: bold; text-align:center; background-color: #c6c6c6;">Status</td>
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
            <td style="border:0.5px solid black;text-align:left;">{{ $d->ktp_no }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->nama_pelamar }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->telp }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->pend }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->jk }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->tempat_lahir }} - {{$d->tgl_lahir}}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->dir }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->div }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->dept }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->posisi }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->salary }}</td>
            <td style="border:0.5px solid black;text-align:left;">{{ $d->deskripsi }}</td>
            <td style="border:0.5px solid black;text-align:right;">{{ $d->status }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <br>
    @endforeach