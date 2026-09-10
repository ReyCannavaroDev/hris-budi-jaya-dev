@php
  $req = app()->request;
  $tipe = $req->tipe_report;


  $rekap = [];

  if($tipe == 'Rekap Harian'){
    $date = $req->date;
    $dateArr = explode('/',$date);
    $dateOK = $dateArr[2].'/'.$dateArr[1].'/'.$dateArr[0];

    $rekap = \DB::select("
      SELECT json_agg(json_build_object(
          'm_kary_id', m_kary_id,
          'default_user_id', default_user_id,
          'kode', kode,
          'nama_lengkap', nama_lengkap,
          'dept', dept,
          'absensi', absensi
      )) AS att_report
      FROM get_employee_attendance_report(?,?,?)",[$dateOK,$req->m_divisi_id,$req->m_dept_id]);

      
  }

  if($tipe === 'Rekap'){
    $periode_from = $req->periode_from;
    $periode_to = $req->periode_to;
    $tanggal_awal = new DateTime($periode_from);
    $tanggal_akhir = new DateTime($periode_to);
    $range_bulan = [];
    while ($tanggal_awal <= $tanggal_akhir) {
        $range_bulan[] = $tanggal_awal->format('Y-m');
        $tanggal_awal->modify('+1 month');
    }
    for($i = 0 ; $i< count($range_bulan); $i++){
        $temp = $i +1;
        $rekap[] = \DB::select("
                  SELECT json_agg(json_build_object(
                      'all_days_of_month', all_days_of_month,
                      'date_to_idn', date_to_idn,
                      'day_name_idn', day_name_idn,
                      'type', type,
                      'presentase', presentase,
                      'attend', attend,
                      'cuti', cuti,
                      'alpha', alpha,
                      'total_kary', total_kary
                  )) AS monthly_report
                  FROM generate_monthly_report(?,?,?)
      ", [ $range_bulan[$i].'-01', $req->m_divisi_id, $req->m_dept_id ]);
    }
  }
  if($tipe === 'Detail')
  {
      $periode = $req->periode;
      $periodeArray = explode('-', $periode);
      $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $periodeArray[1], $periodeArray[0]);
      for ($day = 1; $day <= $daysInMonth; $day++) {
          $rekap[] = \DB::select("
              SELECT json_agg(json_build_object(
                  'm_kary_id', m_kary_id,
                  'default_user_id', default_user_id,
                  'kode', kode,
                  'nama_lengkap', nama_lengkap,
                  'dept', dept,
                  'absensi', absensi
              )) AS att_report
              FROM get_employee_attendance_report(?,?,?)
          ", [ $periode . '-' . sprintf("%02d", $day), $req->m_divisi_id, $req->m_dept_id ]);
      }
  }
@endphp
<span style="width:100%;text-align:left;font-weight:bold;">Laporan Absensi Karyawan ({{$tipe}})</span><br>
<span style="width:100%;text-align:left;font-weight:bold; font-size: 10pt !important">{{@$periode ?? (@$periode_from ? (@$periode_from. '-' .@$periode_to) : $req->date) }}</span><br>
@if(!$req->export == 'xls')
<style>
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  font-size: 6px;
}

th {
  border: 1px solid #4A5568;
}

td {
  border: 1px solid #4A5568;
}

td .cursor-pointer {
  cursor: pointer;
}

td.text-right {
  text-align: right;
}
</style>
@endif

<br/>

  @if($tipe === 'Rekap Harian')
    <table style="border-collapse: collapse; width: 100%" cellpadding="1">
      <thead >
        <tr>
          <th style="font-size: 10px; background-color: #c6c6c6;border:0.5px solid black; width: 5%;">No</th>
          <th style="font-size: 10px; background-color: #c6c6c6;border:0.5px solid black; width: 10%;">NIK</th>
          <th style="font-size: 10px; background-color: #c6c6c6;border:0.5px solid black; width: 15%;">Nama</th>
          <th style="font-size: 10px; background-color: #c6c6c6;border:0.5px solid black; width: 15%;">Departemen</th>
          <th style="font-size: 10px; background-color: #c6c6c6;border:0.5px solid black; width: 10%;">Status</th>
          <th style="font-size: 10px; background-color: #c6c6c6;border:0.5px solid black; width: 8%;">Waktu Checkin</th>
          <th style="font-size: 10px; background-color: #c6c6c6;border:0.5px solid black; width: 10%;">Kantor Checkin</th>
          <th style="font-size: 10px; background-color: #c6c6c6;border:0.5px solid black; width: 8%;">Waktu Checkout</th>
          <th style="font-size: 10px; background-color: #c6c6c6;border:0.5px solid black; width: 10%;">Kantor Checkout</th>
        </tr>
      </thead>
      <tbody>
        @foreach(json_decode($rekap[0]->att_report) as $idx=>$dt)
        <tr>
          <td style="font-size: 10px; border:0.5px solid black; width: 5%;">{{$idx+1}}</td>
          <td style="font-size: 10px; border:0.5px solid black; width: 10%;">{{$dt->kode}}</td>
          <td style="font-size: 10px; border:0.5px solid black; width: 15%;">{{$dt->nama_lengkap}}</td>
          <td style="font-size: 10px; border:0.5px solid black; width: 15%;">{{$dt->dept}}</td>
          <td style="font-size: 10px; border:0.5px solid black; width: 10%; text-align: center">{{ $dt->absensi->status }}</td>
          <td style="font-size: 10px; border:0.5px solid black; width: 8%; text-align: center">{{ @$dt->absensi->checkin_time }}</td>
          <td style="font-size: 10px; border:0.5px solid black; width: 10%; text-align: center">{{ @$dt->absensi->checkin_region }}</td>
          <td style="font-size: 10px; border:0.5px solid black; width: 8%; text-align: center">{{ @$dt->absensi->checkout_time }}</td>
          <td style="font-size: 10px; border:0.5px solid black; width: 10%; text-align: center">{{ @$dt->absensi->checkout_region }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @endif
  @if($tipe === 'Rekap')
    <table v-else class="table-auto w-full" style="border-collapse: collapse;font-size: 8pt !important" cellpadding="2">
      <thead class="bg-[#c6c6c6]">
        <tr>
          <th class="border-1 border-gray-500 px-3 py-1" style="background-color: #c6c6c6;border:0.5px solid black; width: 10%;">Hari</th>
          <th class="border-1 border-gray-500 px-3 py-1" style="background-color: #c6c6c6;border:0.5px solid black; width: 10%;">Tanggal</th>
          <th class="border-1 border-gray-500 px-3 py-1" style="background-color: #c6c6c6;border:0.5px solid black; width: 5%;">Hadir</th>
          <th class="border-1 border-gray-500 px-3 py-1" style="background-color: #c6c6c6;border:0.5px solid black; width: 5%;">Izin/Sakit/Cuti</th>
          <th class="border-1 border-gray-500 px-3 py-1" style="background-color: #c6c6c6;border:0.5px solid black; width: 5%;">Tidak Hadir</th>
          <th class="border-1 border-gray-500 px-3 py-1" style="background-color: #c6c6c6;border:0.5px solid black; width: 5%;">Karyawan Aktif</th>
          <th class="border-1 border-gray-500 px-3 py-1" style="background-color: #c6c6c6;border:0.5px solid black; width: 6%;">Presentase</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rekap as $rekaps)
          @foreach($rekaps as $detRekap)
            @php
                $data = json_decode($detRekap->monthly_report);
            @endphp
            @foreach($data as $i => $datas)
              @php 
                $bg_class = $datas->type == 'Hari Libur' ? 'bg-gray-500 text-white' : ( $datas->type == 'Cuti Bersama' ? 'bg-red-200' : '');
                $presentase = round($datas->presentase);
              @endphp
                <tr class="{{ $bg_class }}">
                    <td class="text-left border-1 border-gray-500 px-3" style="border:0.5px solid black; width: 10%;">{{ $datas->day_name_idn }}</td>
                    <td class="text-left border-1 border-gray-500 px-3" style="border:0.5px solid black; width: 10%;">{{ $datas->all_days_of_month }}</td>
                    <td class="text-right border-1 border-gray-500 px-3" style="border:0.5px solid black; text-align:right; width: 5%;">{{ $datas->attend }}</td>
                    <td class="text-right border-1 border-gray-500 px-3" style="border:0.5px solid black; text-align:right; width: 5%;">{{ $datas->cuti }}</td>
                    <td class="text-right border-1 border-gray-500 px-3" style="border:0.5px solid black; text-align:right; width: 5%;">{{ $datas->alpha }}</td>
                    <td class="text-right border-1 border-gray-500 px-3" style="border:0.5px solid black; text-align:right; width: 5%;">{{ $datas->total_kary }}</td>
                    <td class="text-right border-1 border-gray-500 px-3" style="border:0.5px solid black; text-align:right; width: 6%;">{{ $presentase }}%</td>
                </tr>
            @endforeach
          @endforeach
        @endforeach
      </tbody>
    </table>
  @elseif($tipe === 'Detail')
    @foreach($rekap as $key1 => $rekaps)
      @foreach($rekaps as $detRekap)
          <span style="width:100%;text-align:center;font-weight:bold;"> {{$periode . '-' . sprintf("%02d", ($key1+1))}} </span><br/>
        @php
            $data = @json_decode($detRekap->att_report) ?? [];
        @endphp
          <table class="table-auto w-full" cellpadding="9">
            <thead class="bg-[#c6c6c6]">
              <tr>
                <th class="border-1 border-gray-500 px-3 py-1 w-[5%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">No</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[10%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">NIK</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[25%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">Nama</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[27%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">Departemen</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[10%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">Status</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[12%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">Waktu Checkin</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[12%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">Lokasi Checkin</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[12%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">Onscope Checkin</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[12%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">Waktu Checkout</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[12%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">Lokasi Checkout</th>
                <th class="border-1 border-gray-500 px-3 py-1 w-[12%]" style="background-color: #c6c6c6; font-size: 9pt !important;border:0.5px solid black;">Onscope Checkout</th>
              </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $datas)
                @php 
                  $bg_class = $datas->absensi->status == 'NOT ATTEND' ? 'bg-red-200' : ($key % 2 === 0 ? 'bg-gray-200' :'');
                @endphp
                   <tr class="{{ $bg_class }}">
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;">{{ $key+1 }}</td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;">{{ $datas->kode }}</td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;">{{ $datas->nama_lengkap }}</td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;">{{ $datas->dept }}</td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;">{{ $datas->absensi->status }}</td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;">
                            <div class="flex items-center">
                                <icon  class="mx-1" class="" fa="map-marker-alt"/> {{ optional($datas->absensi)->checkin_time ?: '' }}
                            </div>
                        </td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;"> Lat :{{ optional($datas->absensi)->checkin_lat ?: '' }} <br> Long : {{ optional($datas->absensi)->checkin_long ?: '' }}</td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;">
                          @if(isset($datas->absensi->checkin_region))
                              {{ $datas->absensi->checkin_region === 'Out Scope' ? 'Tidak' : 'Iya' }}
                          @endif
                        </td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;">
                            <div class="flex items-center">
                                <icon class="mx-1" class="" fa="map-marker-alt"/> {{ optional($datas->absensi)->checkout_time ?: '' }}
                            </div>
                        </td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;"> Lat :{{ optional($datas->absensi)->checkout_lat ?: '' }} <br> Long : {{ optional($datas->absensi)->checkout_long ?: '' }}</td>
                        <td class="text-left border-1 border-gray-500 px-3" style="font-size: 9pt !important;border:0.5px solid black;">
                          @if(isset($datas->absensi->checkout_region))
                              {{ $datas->absensi->checkout_region === 'Out Scope' ? 'Tidak' : 'Iya' }}
                          @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>

      @endforeach
    @endforeach
   
  @else

  @endif
