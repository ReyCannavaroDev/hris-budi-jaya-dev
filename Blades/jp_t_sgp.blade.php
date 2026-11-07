<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
      <!-- <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Inactive</button>
      </div> -->
    </div>
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
    <!-- <template #header>
    </template> -->
  </TableApi>
</div>
@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Surat Peringatan</h1>
        <p class="text-gray-100">Surat Peringatan</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" type="text" :value="values.nomor" class="w-full mt-3"
        @input="v=>values.nomor=v" :check="false" placeholder="Masukan Nomor" label="Nomor" />
    </div>
    <div>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
              :value="values.m_dir_id" @input="v=>values.m_dir_id=v"
              :errorText="formErrors.m_dir_id?'failed':''" 
              label="Direktorat" placeholder="Pilih Direktorat"
              :hints="formErrors.m_dir_id"
              :api="{
                  url: `${store.server.url_backend}/operation/m_dir`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  
              }"
              valueField="id" displayField="nama" :check="false"
            />
    </div>
    <div>
            <FieldPopup
              :bind="{ readonly: !actionText }" class="w-full mt-3"
              :value="values.m_kary_id" @input="(v)=>values.m_kary_id=v"
              :errorText="formErrors.m_kary_id?'failed':''" 
              :hints="formErrors.m_kary_id" 
              @update:valueFull="(objVal)=>{
                  $log(objVal)
                }"
              valueField="id" displayField="nik"
              :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  searchfield: 'this.nik, m_zona.nama, m_dir.nama, m_divisi.nama, m_dept.nama'
                }
              }"
              placeholder="Cari Nomor Induk Karyawan" label="Nomer Induk Karyawan" :check="false" 
              :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'nik',
                wrapText: true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-end']
              },
              {
                flex: 1,
                field: 'nama_panggilan',
                headerName: 'Nama',
                wrapText: true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_zona.nama',
                headerName: 'Zona',
                wrapText: true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_dir.nama',
                headerName: 'Direktorat',
                wrapText: true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_divisi.nama',
                wrapText: true,
                headerName: 'Divisi',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText: true,
                field: 'm_dept.nama',
                headerName: 'Departemen',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              ]"
            />
    </div>
    <div>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3"
              :value="values.tipe_sgp_id" @input="v=>values.tipe_sgp_id=v"
              :errorText="formErrors.tipe_sgp_id?'failed':''" 
              label="Tipe" placeholder="Pilih Tipe "
              :hints="formErrors.tipe_sgp_id"
              :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    where: `this.group = 'TIPE SGP'`
                  }
                  
              }"
              valueField="id" displayField="value" :check="false"
            />
    </div>

    <div>
            <FieldX :bind="{ readonly: !actionText }" type="date" class="w-full mt-3"
              :value="values.tgl" :errorText="formErrors.tgl?'failed':''"
              @input="v=>values.tgl=v" :hints="formErrors.tgl" :check="false"
              label="Tanggal" placeholder="Pilih Tanggal"
            />
    </div>

    <div>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="w-full mt-3"
              :value="values.no_dokumen" :errorText="formErrors.no_dokumen?'failed':''"
              @input="v=>values.no_dokumen=v" :hints="formErrors.no_dokumen" :check="false"
              label="Nomer Dokumen" placeholder="Tuliskan Nomer Dokumen"
            />
    </div>



    <div>
            <FieldUpload class="w-full mt-3" :bind="{ readonly: !actionText }"
              :value="values.file_dokumen" @input="(v)=>values.file_dokumen=v" :maxSize="10"
              :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]"
              :api="{
                url: `${store.server.url_backend}/operation/t_sgp/upload`,
                headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
                params: { field: 'file_dokumen' },
                onsuccess: response=>response,
                onerror:(error)=>{},
               }"
               :hints="formErrors.file_dokumen" label="File" placeholder="Masukan File Dokumen" fa-icon="upload"
               accept="application/pdf" :check="false"  
            />
    </div>
    <div>
            <FieldX :bind="{ readonly: !actionText }" type="textarea" class="w-full mt-3"
              :value="values.keterangan" :errorText="formErrors.keterangan?'failed':''"
              @input="v=>values.keterangan=v" :hints="formErrors.keterangan" :check="false"
              label="" placeholder="Tuliskan Keterangan"
            />
    </div>


    <div>
      <FieldX placeholder="Masukan Status" label="Status" :bind="{ readonly: true }" type="text" :value="values.status"
        class="w-full mt-3" @input="v=>values.status=v" :check="false" />
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
  <hr>
  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i>
    <button
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onReset(true)"
      >
        <icon fa="times" />
        Reset
      </button>
    <button v-show="route.query.action?.toLowerCase() === 'verifikasi'" @click="posted" class="bg-orange-500 hover:bg-orange-600 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Posted
          </button>
    <button
        class="bg-green-600 text-white font-semibold hover:bg-green-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText"
        @click="onSave"
      >
        <icon fa="save" />
        Simpan
      </button>
  </div>
</div>
@endverbatim
@endif