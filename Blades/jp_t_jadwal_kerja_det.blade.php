@if(!$req->has('id'))
<div class="bg-white p-3 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'" :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
        <icon fa="plus" />
        Tambah Data
      </RouterLink>
    </template>
  </TableApi>
</div>
@else

@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
  <div class="bg-gray-500 text-white rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Detail Jadwal Kerja</h1>
        <p class="text-gray-100">Detail Jadwal Kerja</p>
      </div>
    </div>
  </div>
      
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
        <!-- START COLUMN -->
        <div>
          <label class="font-semibold">Nomor<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" 
              class="col-span-12 !mt-0 w-full"
              :value="values.nomor" :errorText="formErrors.nomor?'failed':''"
              @input="v=>values.nomor=v" :hints="formErrors.nomor" 
              :check="false"
              label=""
          />
        </div>
        <div>
          <label class="font-semibold">Tipe Jam Kerja<span class="text-red-500 space-x-0 pl-0"></span></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" 
              class="col-span-12 !mt-0 w-full"
              :value="values.tipe_jam_kerja_id" 
              @input="v=>values.tipe_jam_kerja_id=v"
              @update:valueFull="(v)=>{
                values.tipe_jam_kerja = v.value
                generate()
              }"
              :errorText="formErrors.tipe_jam_kerja_id?'failed':''" 
              :hints="formErrors.tipe_jam_kerja_id"
              valueField="id" displayField="value"
              placeholder="Pilih Tipe Jam"
              label=""
              :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest:true,
                      transform:false,
                      where:`this.group='TIPEJAM' AND this.is_active='true'`,
                      join:true, 
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                }"
              :check="false"
            />
        </div>
       
        <div>
          <label class="col-span-12">Keterangan<label class="text-red-500 space-x-0 pl-0">*</label></label>
           <FieldX :bind="{ readonly: !actionText }" 
              type="textarea" 
              :value="values.keterangan"
              class="col-span-12 !mt-0 w-full"
              @input="v=>values.keterangan=v" 
              :check="false"
              />        
        </div>
       
        <div >
           <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0"></label></label>
            <FieldX :bind="{ readonly: true }" 
              type="text" 
              :value="values.status"
              class="col-span-12 !mt-0 w-full"
              @input="v=>values.status=v" 
              :check="false"
              /> 
        </div>
        <!-- END COLUMN -->
      </div>
      <div class="col-span-8 md:col-span-12 mt-5">
      <div class="flex items-stretch w-full text-[12px] overflow-x-auto">

          <button
            v-for="a,i in tabs" :key="i"
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === a.day_num}"
            @click="()=>{
              activeTabIndex = a.day_num
              mapView(i)
            }"
          >
            <i class="font-semibold" :class="a.tipe_hari == 'LIBUR' ? 'text-red-600' : ''">{{a.day}}</p><br>
            <i>{{a.waktu_mulai+'-'+a.waktu_akhir}}</i>
          </button>
        </div>
        <div class="flex my-6 justify-between">
          <div class="space-x-6">
            <span class="font-semibold text-base text-left">Detail Hari</span>
          
          </div>
          <div class="content-end flex">
            <ButtonMultiSelect class="inline-block" title="Tambah Karyawan" @add="onDetailAdd" :api="{
                  url: `${store.server.url_backend}/operation/m_kary`,
                  headers: {'Content-Type': 'Application/json', authorization: `${store.user.token_type} ${store.user.token}`},
                  params: { 
                    simplest: true,
                    searchfield: 'this.id, this.nama_depan, this.nama_belakang, this.nama_lengkap, m_divisi.nama, m_dept.nama',
                    where: 'this.is_active = true',
                    scopes: 'karyawanShift,notInGenerate',
                    t_jadwal_kerja_id : route.params.id
                  },
                }" :columns="[{
                    checkboxSelection: true,
                    headerCheckboxSelection: true,
                    headerName: 'No',
                    valueGetter:(params)=>{
                      return ''
                    },
                    width: 60,
                    sortable: false, resizable: false, filter: false,
                    cellClass: ['justify-center', 'bg-gray-50']
                  },
                  {
                    pinned: false,
                    field: 'nama_lengkap',
                    headerName: 'Nama',
                    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                    filter:false,
                    flex: 1
                  },
                  {
                    pinned: false,
                    field: 'm_divisi.nama',
                    headerName: 'Divisi',
                    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                    filter:false,
                    flex: 1
                  },
                  {
                    pinned: false,
                    field: 'm_dept.nama',
                    headerName: 'Departemen',
                    cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                    filter:false,
                    flex: 1
                  },
                  ]">
                <div
                  class="flex justify-center w-full h-full items-center px-2 py-1.5 text-xs rounded text-white bg-blue-500 hover:bg-blue-700 hover:bg-blue-600 transition-all duration-200">
                  <icon fa="plus" size="sm mr-0.5" /> Plih Karyawan
                </div>
            </ButtonMultiSelect>
            <button @click="generate(true)" type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold ml-2 px-2 py-1 rounded flex items-center justify-center">
                <icon fa="bolt" size="sm mr-0.5"/> Generate Karyawan Shift
            </button>
            
            
          </div>
        </div>
        <table class="w-full overflow-x-auto mt-2">
          <thead>
            <tr class="border-y bg-gray-300">
              <td class="text-black font-bold text-capitalize px-2 text-left w-[5%]">No.</td>
              <td class="text-black font-bold text-capitalize px-2 text-left w-[20%]">Karyawan</td>
              <td class="text-black font-bold text-capitalize px-2 text-left w-[15%]">Divisi</td>
              <td class="text-black font-bold text-capitalize px-2 text-left w-[17%]">Departemen</td>
              <td class="text-black font-bold text-capitalize px-2 text-left w-[2%]">Aksi</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="a,i in dataActive.items" :key="a._id">
              <td class="text-left px-1 border border-gray-200">{{ i+1 }}</td>
              <td class="text-left px-1 border border-gray-200">{{ a.nama_lengkap }}</td>
              <td class="text-left px-1 border border-gray-200">{{a['m_divisi.nama']}}</td>
              <td class="text-left px-1 border border-gray-200">{{a['m_dept.nama']}}</td>
              <td class="text-left px-1 border border-gray-200">
                <div class="flex justify-center">
                  <button type="button" @click="removeDetail(a)">
                  <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                  </svg>
                </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <!-- ACTION BUTTON START -->
        <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <button @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[12px] py-[8px] rounded-md ">
            <Icon fa="save"/> Simpan
          </button>
        </div>
    </div>
  </div>
</div>
@endverbatim
@endif