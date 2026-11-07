<div v-if="beforeLoad" class="h-87vh w-full  items-center">
 <p class="italic font-semibold text-center text-gray-500">Harap tunggu sistem sedang menyusun konten..</p>
</div>
<div class="h-87vh w-full  items-center rounded text-sm" v-if="is_superadmin == true">
  <div class="grid grid-cols-4 gap-6 px-4 w-full">
    <div class="bg-white bg-opacity-80 hover:!bg-opacity-95 shadow-lg py-5 rounded-lg px-7 flex flex-col gap-2">
      <p>Total Divisi Aktif</p>
      <div class="text-green-500 font-bold text-2xl"> 3 </div>
    </div>

    <div class="bg-white bg-opacity-80 hover:!bg-opacity-95 shadow-lg py-5 rounded-lg px-7 flex flex-col gap-2">
      <p>Total Departemen Aktif</p>
      <div class="text-green-500 font-bold text-2xl"> 5 </div>
    </div>

    <div class="bg-white bg-opacity-80 hover:!bg-opacity-95 shadow-lg py-5 rounded-lg px-7 flex flex-col gap-2">
      <p> Pegawai Absen Hari Ini </p>
      <div class="text-red-500 font-bold text-2xl"> 4 </div>
    </div>

    <div class="bg-white bg-opacity-80 hover:!bg-opacity-95 shadow-lg py-5 rounded-lg px-7 flex flex-col gap-2">
      <p> Pegawai Masuk Hari Ini </p>
      <div class="text-green-500 font-bold text-2xl"> 457 </div>
    </div>
  </div>

  <div class="grid <md:grid-cols-1 grid-cols-2 gap-6 p-4">
     <div
      class="col-span-2 p-4 !select-none bg-white bg-opacity-80 hover:!bg-opacity-95 shadow-lg rounded-lg w-full w-full">
      <h2 class="font-semibold text-md justify-start mb-4">Pengeluaran Gaji Karyawan Bulan Ini Per Departemen</h2>
      <column-chart :stacked="true" :library="{
          accessibility: {
            enabled: false
          },
          yAxis: {
              min: 0,
              title: {
                  align: 'high'
              },
              labels: {
                  overflow: 'justify'
              },
              gridLineWidth: 0,
          },
          chart: {
              backgroundColor: 'rgba(0,0,0,0)',
          }
        }" :data="{'Departemen 1': 192300000,'Departemen 2': 134230000,'Departemen 3': 189230000, 'Departemen 4': 145230000, 'Departemen 5': 145230000}" adapter="highcharts">
      </column-chart>
    </div>
    <div
      class="p-4 !select-none bg-white bg-opacity-80 hover:!bg-opacity-95 shadow-lg rounded-lg w-full w-full">
      <h2 class="font-semibold text-md justify-start mb-4">Pengeluaran Gaji Karyawan Bulan Ini Per Divisi</h2>
      <column-chart :stacked="true" :library="{
          accessibility: {
            enabled: false
          },
          yAxis: {
              min: 0,
              title: {
                  align: 'high'
              },
              labels: {
                  overflow: 'justify'
              },
              gridLineWidth: 0,
          },
          chart: {
              backgroundColor: 'rgba(0,0,0,0)',
          }
        }" :data="{'Divisi 1': 192300000,'Divisi 2': 134230000,'Divisi 3': 189230000}" adapter="highcharts">
      </column-chart>
    </div>
    <div
      class="p-4 !select-none bg-white bg-opacity-80 hover:!bg-opacity-95 shadow-lg rounded-lg w-full w-full">
      <h2 class="font-semibold text-md justify-start mb-4">Pengeluaran Gaji Karyawan Bulan Ini Per Direktorat</h2>
      <column-chart :stacked="true" :library="{
          accessibility: {
            enabled: false
          },
          yAxis: {
              min: 0,
              title: {
                  align: 'high'
              },
              labels: {
                  overflow: 'justify'
              },
              gridLineWidth: 0,
          },
          chart: {
              backgroundColor: 'rgba(0,0,0,0)',
          }
        }" :data="{'Departemen 1': 192300000,'Departemen 2': 134230000,'Departemen 3': 189230000}" adapter="highcharts">
      </column-chart>
    </div>
</div>