@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Laporan Absensi Karyawan
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-x-[60px] gap-y-[12px] px-4">
        <!-- START COLUMN -->
        <div class="col-span-2">
          <label class="font-semibold">Tipe Report</label>
          <FieldSelect :bind="{ readonly: !actionText, clearable: false }" class="w-full py-2 !mt-0"
            :value="values.tipe_report" :errorText="formErrors.tipe_report ? 'failed' : ''"
            @input="v => values.tipe_report = v" @update:valueFull="(objVal)=>{
                  resetValuesPeriode()
                }" :hints="formErrors.tipe_report" :check="false" label=""
            :options="['Laporan Absensi Karyawan Rekap','Laporan Absensi Karyawan Rekap Tidak Absen', 'Laporan Absensi Karyawan Detail', 'Laporan Absensi Karyawan Group','Laporan Sisa Cuti Karyawan']"
            placeholder="Pilih Tipe Report Absensi" valueField="key" displayField="key" />
        </div>
        <div>
          <label class="font-semibold">Tipe Export</label>
          <FieldSelect :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.tipe"
            :errorText="formErrors.tipe ? 'failed' : ''" @input="v => values.tipe = v" :hints="formErrors.tipe"
            :check="false" label="" :options="['Excel','PDF','HTML']" placeholder="Pilih Tipe Export" valueField="key"
            displayField="key" />
        </div>
        <div class="grid grid-cols-2 gap-2" v-if="values.tipe_report != 'Laporan Absensi Karyawan Group'">
          <div class="col-span-2">
            <label class="font-semibold">Periode
                  </label>
            <FieldX :bind="{ readonly: openDateSelected ? true : false , required: true} "
              v-if="values.tipe_report != 'Laporan Absensi Karyawan Rekap Tidak Absen' " class="w-full py-2 !mt-0"
              :value="values.periode" :check="false" type="month" label="" @input="(v)=>{
                      values.periode = v
                  }" />
            <FieldX :bind="{ readonly: openDateSelected ? true : false , required: true}"
              v-if="values.tipe_report === 'Laporan Absensi Karyawan Rekap Tidak Absen'" class="w-full py-2 !mt-0"
              :value="values.date" :check="false" type="date" label="" @input="(v)=>{
                      values.date = v
                  }" />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-2" v-if="values.tipe_report === 'Laporan Absensi Karyawan Group'">
          <div>
            <label class="font-semibold">Periode Start
        <label class="text-red-500 space-x-0 pl-0">*</label>
            </label>
            <FieldX type="date" :bind="{ readonly: false }" class="w-full py-2 !mt-0" :value="values.date_start"
              label="" placeholder="DD/MM/YY" :errorText="formErrors.date_start?'failed':''"
              @input="v => { values.date_start = v; onDateStartChange(v) }" :hints="formErrors.date_start"
              :check="false" />
          </div>
          <div>
            <label class="font-semibold">Periode End</label>
                      <FieldX type="date" :bind="{ readonly: false }" class="w-full py-2 !mt-0" :value="values.date_end" label=""
            placeholder="DD/MM/YY" :errorText="formErrors.date_end ? 'failed' : ''"
            @input="v => { onDateEndChange(v); }" :hints="formErrors.date_end" :check="false" />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-2" v-if="values.tipe_report != 'Laporan Absensi Karyawan Detail'">
          <div>
            <label class="font-semibold">Divisi</label>
            <FieldSelect :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.m_divisi_id"
              :errorText="formErrors.m_divisi_id ? 'failed' : ''" @input="v => values.m_divisi_id = v"
              :hints="formErrors.m_divisi_id" :check="false" label="" placeholder="Pilih Divisi" valueField="id"
              displayField="nama" :api="{
                      url: `${store.server.url_backend}/operation/m_divisi`,
                      headers: { 
                          'Content-Type': 'Application/json', 
                          Authorization: `${store.user.token_type} ${store.user.token}`
                      },
                      params: {
                          single: true,
                          join: false,
                          where: `this.is_active='true'`
                      }
                  }" />
          </div>
          <div>
            <label class="font-semibold">Departemen</label>
            <FieldSelect :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0" :value="values.m_dept_id"
              :errorText="formErrors.m_dept_id ? 'failed' : ''" @input="v => values.m_dept_id = v"
              :hints="formErrors.m_dept_id" :check="false" label="" placeholder="Pilih Departement" valueField="id"
              displayField="nama" :api="{
                      url: `${store.server.url_backend}/operation/m_dept`,
                      headers: { 
                          'Content-Type': 'Application/json', 
                          Authorization: `${store.user.token_type} ${store.user.token}`
                      },
                      params: {
                          single: true,
                          join: false,                    
                          where: `m_divisi_id=${values.m_divisi_id ?? 0} AND this.is_active='true'`
                      }
                  }" />
          </div>
        </div>
        <div
          v-if="values.tipe_report !== 'Laporan Absensi Karyawan Group' && values.tipe_report !== 'Laporan Absensi Karyawan Rekap Tidak Absen'">
          <label class="font-semibold">Karyawan</label>
          <FieldPopup :bind="{ readonly: false }" class="col-span-12 !mt-2 w-full" :value="values.m_kary_id"
            @input="(v)=>values.m_kary_id=v" :errorText="formErrors.m_kary_id?'failed':''" :hints="formErrors.m_kary_id"
            valueField="id" displayField="nama_lengkap" :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  dept_id :  values.m_dept_id ?? null,
                  searchfield: 'this.kode, this.nama_lengkap, this.nama_depan, this.nama_belakang, m_zona.nama, m_dir.nama, m_divisi.nama, m_dept.nama'
                }
              }" placeholder="Pilih NIK Karyawan" label="" :check="false" :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'kode',
                wrapText:true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-end']
              },
              {
                flex: 1,
                field: 'nama_lengkap',
                wrapText:true,
                headerName: 'Nama Karyawan',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_zona.nama',
                wrapText:true,
                headerName: 'Zona',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'm_dir.nama',
                headerName: 'Direktorat',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'm_divisi.nama',
                headerName: 'Divisi',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'm_dept.nama',
                headerName: 'Departemen',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              ]" />
        </div>


      </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[1em]">
        <button @click="onGenerate" class="bg-green-600 hover:bg-green-800 duration-300 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            {{ values.tipe?.toLowerCase() === 'html' ? 'View' : 'Export' }}
          </button>
      </div>
      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
      <div class="overflow-x-auto mt-6 mb-4 px-4" v-show="exportHtml">
        <hr>
        <div id="exportTable" class="w-[100%] mt-6 h-screen overflow-auto">
        </div>
      </div>

    </div>

  </div>

</div>
</div>
@endverbatim