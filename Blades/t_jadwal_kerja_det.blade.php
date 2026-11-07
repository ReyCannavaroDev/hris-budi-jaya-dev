@if(!$req->has('id'))
<div class="bg-white p-3 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'"
        :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
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
      <div class="mb-4 flex">
        <button @click="onBack" title="Kembali" class="mr-2 mt-[-8px]"><Icon fa="arrow-left"/></button>
        <h1 class="text-[24px] mb-4 font-bold">
          Jadwal Kerja Detail Karyawan
        </h1>
        <hr>
      </div>

      <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
        <!-- START COLUMN -->
        <div>
          <label class="font-semibold">Nomor<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" class="col-span-12 !mt-0 w-full" :value="values.nomor"
            :errorText="formErrors.nomor?'failed':''" @input="v=>values.nomor=v" :hints="formErrors.nomor"
            :check="false" label="" />
        </div>
        <div>
          <label class="font-semibold">Tipe Jam Kerja<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
            :value="values.tipe_jam_kerja_id" @input="v=>values.tipe_jam_kerja_id=v" @update:valueFull="(v)=>{
                values.tipe_jam_kerja = v.value
                generate()
              }" :errorText="formErrors.tipe_jam_kerja_id?'failed':''" :hints="formErrors.tipe_jam_kerja_id"
            valueField="id" displayField="value" placeholder="Pilih Tipe Jam" label="" :api="{
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
                }" :check="false" />
        </div>

        <div>
          <label class="col-span-12">Keterangan<label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" type="textarea" :value="values.keterangan"
            class="col-span-12 !mt-0 w-full" @input="v=>values.keterangan=v" :check="false" />
        </div>

        <div>
          <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: true }" type="text" :value="values.status" class="col-span-12 !mt-0 w-full"
            @input="v=>values.status=v" :check="false" />
        </div>
        <!-- END COLUMN -->
      </div>
      <div class="col-span-8 md:col-span-12 mt-5">
        <div class="flex items-stretch w-full text-[12px] overflow-x-auto">

          <button
            v-for="a,i in tabs" :key="i"
            class="block w-full flex items-center justify-center border-b-2 border-gray-100 p-3 hover:border-blue-600 hover:text-blue-600 duration-300"
            :class="{'border-blue-600 text-blue-600 font-bold': activeTabIndex === i}"
            @click="()=>{
              activeTabIndex = i
              mapView(i)
            }"
          >
            <i class="font-semibold" :class="a.tipe_hari == 'LIBUR' ? 'text-red-600' : ''">{{a.day}}</p><br>
            <i>{{a.waktu_mulai+'-'+a.waktu_akhir}}</i>
          </button>
        </div>
        <div class="flex my-6 justify-between">
          <div class="space-x-6">
            <span class="font-semibold text-base text-left"> </span>

          </div>
          <div class="content-end flex">
            <!-- ACTION BUTTON START -->
            <ButtonMultiSelect class="inline-block" title="Tambah Karyawan" @add="onDetailAdd" 
            :api="{
                  url: `${store.server.url_backend}/operation/m_kary`,
                  headers: {'Content-Type': 'Application/json', authorization: `${store.user.token_type} ${store.user.token}`},
                  params: { 
                    simplest: true,
                    searchfield: 'this.id, this.nama_depan, this.nama_belakang, this.nama_lengkap, m_divisi.nama, m_dept.nama',
                    where: 'this.is_active = true',
                    scopes: 'karyawanShift,notInGenerate',
                    t_jadwal_kerja_id : route.params.id,
                    notin: dataActive.length>0?`this.id:${dataActive.map(dt=>dt.id).join(',')}`:null
                    },
                    onsuccess:(response)=>{
                      response.data = [...response.data].map((dt)=>{
                        return dt
                      })
                      response.page = response.current_page
                      response.hasNext = response.has_next
                      return response
                    }
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
                class="flex justify-center w-full font-semibold h-full items-center px-2 py-1.5  rounded text-white bg-blue-500 hover:bg-blue-700 hover:bg-blue-600 transition-all duration-200">
                <icon fa="plus" size="sm mr-0.5" /> Plih Karyawan
              </div>
            </ButtonMultiSelect>

            <button @click="generate(true)" type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold ml-2 px-2 py-1 rounded flex items-center justify-center">
                <icon fa="bolt" size="sm mr-0.5"/> Generate Karyawan Shift
            </button>

          </div>
        </div>


<!--  SEARCH -->
<div class="flex px-4 py-3 rounded-md overflow-hidden max-w-md border font-[sans-serif]">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" width="16px"
    class="fill-gray-600 mr-3 rotate-90">
    <path
      d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z">
    </path>
  </svg>
  <input 
    type="text" 
    v-model="searchQuery" 
    placeholder="Cari Karyawan / Departemen" 
    class="w-full outline-none bg-transparent text-gray-600 text-sm " 
    @input="onSearch" />
</div>
<!-- END SEARCH -->



  <!-- DELETE BUTTON -->
  <button
  @click="selectDel"
  type="button"
  class="bg-red-500 hover:bg-red-600 text-white font-semibold  px-2 py-1 mt-3 rounded flex items-center justify-center"
  v-if="selectedIndexes.length > 0">
  <icon fa="trash" size="sm mr-0.5"/> Hapus Karyawan Dipilih
</button>



        <!-- table -->
        <div>
  <table class="w-full overflow-x-auto mt-2">
    <thead>
      <tr class="border-y bg-gray-300">
        <td class="text-black font-bold border text-capitalize px-2 text-left w-[1%]">
          <input type="checkbox" @change="toggleSelectAll" :checked="isAllSelected" />
        </td>
        <td class="text-black font-bold text-capitalize border  px-2 text-center w-[1%]">No.</td>
        <td class="text-black font-bold text-capitalize border px-2 text-left w-[20%]">Karyawan</td>
        <td class="text-black font-bold text-capitalize border px-2 text-left w-[15%]">Divisi</td>
        <td class="text-black font-bold text-capitalize  borderpx-2 text-left w-[17%]">Departemen</td>
      </tr>
    </thead>
    <tbody>
<tr v-for="(a, i) in paginatedData" :key="i">
  <td class="text-left px-2 border border-gray-200">
    <input type="checkbox" :value="i" v-model="selectedIndexes" />
  </td>
  <td class="text-center px-1 border border-gray-200">{{ (currentPage - 1) * itemsPerPage + i + 1 }}</td>
  <td class="text-left px-1 border border-gray-200">{{ a.nama_lengkap }}</td>
  <td class="text-left px-1 border border-gray-200">{{ a['m_divisi.nama'] }}</td>
  <td class="text-left px-1 border border-gray-200">{{ a['m_dept.nama'] }}</td>
</tr>
<tr v-if="paginatedData.length === 0">
  <td colspan="5" class="text-center px-1 border border-gray-200">No data to show</td>
</tr>
    </tbody>
  </table>

  <!-- PAGINATION -->
  <div class="flex justify-between items-center mt-4">
    <button @click="goToPreviousPage" :disabled="currentPage === 1">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>

    <div>
      <span v-for="page in pageRange" :key="page" class="mx-2 cursor-pointer" :class="{ 'font-bold': page === currentPage }" @click="goToPage(page)">
        {{ page }}
      </span>
    </div>

    <button @click="goToNextPage" :disabled="currentPage === totalPages">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
      </svg>
    </button>
  </div>
        </div>


      </div>
    </div>
  </div>
  @endverbatim
  @endif