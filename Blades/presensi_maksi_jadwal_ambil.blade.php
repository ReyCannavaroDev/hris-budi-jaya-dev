@verbatim
  <h1 class="text-3xl text-center font-bold mb-2">Jadwal Pengambilan Makan Siang</h1>
  <div class="hidden lg:flex flex-col lg:flex-row">
  <div class="lg:w-1/2 p-4">
    <div class="bg-gray-200 p-4 rounded-lg">
      <div class="flex justify-between">
        <ButtonMultiSelect
            title="Pilih Karyawan"
            @add="onDetailAdd"
            :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: {'Content-Type': 'Application/json', authorization: `${store.user.token_type} ${store.user.token}`},
                params: { 
                    simplest: true,
                    searchfield: 'nik,nama_lengkap'
                },
                onsuccess:(response)=>{
                    response.data = [...response.data].map((dt)=>{
                        Object.assign(dt,{
                            can_create: true, can_update: true, can_delete: true, can_read: true
                        })
                        return dt
                    })
                    response.page = 1
                    response.hasNext = false
                    return response
                }
            }"
            :columns="[{
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
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['border-r', '!border-gray-200', 'justify-left'],
            },
            {
                pinned: false,
                field: 'nik',
                headerName: 'NIK',
                cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                filter:false,
                flex: 1
            },
            {
                flex: 1,
                field: 'nama_lengkap',
                headerName:  'Nama Karyawan',
                sortable: false, resizable: true, filter: false,
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
                flex: 1,
                field: 'm_dept.nama',
                headerName:  'Departemen',
                sortable: false, resizable: true, filter: false,
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
            }]"
        >
            <div class="flex justify-center w-full h-full items-center px-2 py-1.5 text-xs rounded text-white bg-blue-500 hover:bg-blue-700 hover:bg-blue-600 transition-all duration-200" :class="{ 'animate-pulse': detailArrKaryawan.length < 1 }">
                <icon fa="user" size="sm mr-0.5"/> Pilih Karyawan
            </div>
        </ButtonMultiSelect>
        
        <button @click="detailArrKaryawan = []" class="flex justify-center w-20 h-full items-center px-2 py-1.5 text-xs rounded text-white bg-red-500 hover:bg-red-700 hover:bg-red-600 transition-all duration-200">
            <Icon fa="eraser"/> Hapus Semua
        </button>
      </div>
    </div>
    <div class="bg-gray-200 p-4 rounded-lg mt-2">
      <div class="overflow-x-auto max-h-[370px]">
        <table class="w-full table-auto border border-[#CACACA]">
          <thead class="sticky top-0 bg-[#f8f8f8]">
            <tr class="border">
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">NIK</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[40%] border-[#CACACA]">Nama</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">Dept.</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[5%] bg-[#f8f8f8] border-[#CACACA]">Aksi</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArrKaryawan" :key="item.id" class="border-t" v-if="detailArrKaryawan.length">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.nik }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.nama_lengkap }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item['m_dept.nama'] }}
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="removeDetail(item)">
                  <svg width="10" height="15" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                  </svg>
                </button>
                </div>

              </td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="7" class="py-[20px]">
                No data to show
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="lg:w-1/2 p-4">
    <div class="bg-blue-200 p-4 rounded-lg">
      <div class="flex" style="height: 100%;">
        <div class="w-1/2 flex flex-col" style="height: 100%;">
            <div class="flex-1 p-4 overflow-auto">
              <div class="grid grid-cols-12 items-center mb-4">
                  <label class="col-span-4 text-[12px]">Minggu ke</label>
                  <FieldX :bind="{ readonly: true }" class="col-span-8 !mt-0 w-full"
                    :value="values.minggu" :errorText="formErrors.minggu?'failed':''"
                    @input="v=>values.minggu=v" :hints="formErrors.minggu" :check="false"
                  />
              </div>
              <div class="grid grid-cols-12 items-center mb-4">
                <label class="col-span-4 text-[12px]">Bulan</label>
                <FieldX :bind="{ readonly: true }" class="col-span-8 !mt-0 w-full"
                  :value="values.bulan" :errorText="formErrors.bulan?'failed':''"
                  @input="v=>values.bulan=v" :hints="formErrors.bulan" :check="false" 
                />
              </div>
              <div class="grid grid-cols-12 items-center">
                <label class="col-span-4 text-[12px]">Tahun</label>
                <FieldX :bind="{ readonly: true }" class="col-span-8 !mt-0 w-full"
                  :value="values.tahun" :errorText="formErrors.tahun?'failed':''"
                  @input="v=>values.tahun=v" :hints="formErrors.tahun" :check="false"
                />
              </div>
            </div>
        </div>
        
        <div class="w-1/2 flex flex-col" style="height: 100%;">
            <div class="flex-1 p-4">
                <button @click="generateJadwal" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-[18px] py-[8px] rounded-[4px] ">
                    <Icon fa="bolt"/> Generate
                  </button>
            </div>
            <div class="flex-1 p-4">
                  <button @click="" class="bg-green-500 hover:bg-green-600 text-white text-sm px-[18px] py-[8px] rounded-[4px] ">
                     <Icon fa="save"/> Simpan
                  </button>
            </div>
        </div>
    </div>
    </div>
    <div class="bg-blue-200 p-4 rounded-lg mt-2">
      <div class="overflow-x-auto max-h-[247px]">
        <table class="w-full table-auto border border-[#CACACA]">
          <thead class="sticky top-0 bg-[#f8f8f8]">
            <tr class="border">
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">Hari</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">Tanggal</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">NIK</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[20%] bg-[#f8f8f8] border-[#CACACA]">Nama</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArrJadwal" :key="item.id" class="border-t" v-if="detailArrJadwal.length">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.hari }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.tanggal }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item['nik'] }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item['nama_lengkap'] }}
              </td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="7" class="py-[20px]">
                No data to show
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </div>

<!-- Untuk tampilan mobile -->
  <div class="lg:hidden">
    <div class="bg-gray-200 p-4 rounded-lg">
      <div class="flex justify-between">
          <ButtonMultiSelect
              title="Pilih Karyawan"
              @add="onDetailAdd"
              :api="{
                  url: `${store.server.url_backend}/operation/m_kary`,
                  headers: {'Content-Type': 'Application/json', authorization: `${store.user.token_type} ${store.user.token}`},
                  params: { 
                      simplest: true,
                      searchfield: 'nik,nama_lengkap'
                  },
                  onsuccess:(response)=>{
                      response.data = [...response.data].map((dt)=>{
                          Object.assign(dt,{
                              can_create: true, can_update: true, can_delete: true, can_read: true
                          })
                          return dt
                      })
                      response.page = 1
                      response.hasNext = false
                      return response
                  }
              }"
              :columns="[{
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
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['border-r', '!border-gray-200', 'justify-left'],
              },
              {
                  pinned: false,
                  field: 'nik',
                  headerName: 'NIK',
                  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
                  filter:false,
                  flex: 1
              },
              {
                  flex: 1,
                  field: 'nama_lengkap',
                  headerName:  'Nama Karyawan',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                  flex: 1,
                  field: 'm_dept.nama',
                  headerName:  'Departemen',
                  sortable: false, resizable: true, filter: false,
                  cellClass: ['border-r', '!border-gray-200', 'justify-center']
              }]"
          >
              <div class="flex justify-center w-20 h-full items-center px-2 py-1.5 text-xs rounded text-white bg-blue-500 hover:bg-blue-700 hover:bg-blue-600 transition-all duration-200" :class="{ 'animate-pulse': detailArrKaryawan.length < 1 }">
                  <icon fa="user" size="sm mr-0.5"/> Pilih Karyawan
              </div>
          </ButtonMultiSelect>
          
          <button @click="detailArrKaryawan = []" class="flex justify-center w-20 h-full items-center px-2 py-1.5 text-xs rounded text-white bg-red-500 hover:bg-red-700 hover:bg-red-600 transition-all duration-200">
              <Icon fa="eraser"/> Hapus Semua
          </button>
      </div>
    </div>
    <div class="bg-gray-200 p-4 rounded-lg mt-2">
      <div class="overflow-x-auto max-h-[400px]">
        <table class="w-full table-auto border border-[#CACACA]">
          <thead class="sticky top-0 bg-[#f8f8f8]">
            <tr class="border">
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">NIK</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[40%] border-[#CACACA]">Nama</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">Dept.</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[5%] bg-[#f8f8f8] border-[#CACACA]">Aksi</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArrKaryawan" :key="item.id" class="border-t" v-if="detailArrKaryawan.length">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.nik }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.nama_lengkap }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item['m_dept.nama'] }}
              </td>
              <td class="p-2 border border-[#CACACA]">
                <div class="flex justify-center">
                  <button type="button" @click="removeDetail(item)">
                  <svg width="10" height="15" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                  </svg>
                </button>
                </div>

              </td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="7" class="py-[20px]">
                No data to show
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="bg-blue-200 p-4 rounded-lg">
      <div class="flex" style="height: 100%;">
        <div class="w-1/2 flex flex-col" style="height: 100%;">
            <div class="flex-1 p-4 overflow-auto">
              <div class="grid grid-cols-12 items-center mb-4">
                  <label class="col-span-4 text-[12px]">Minggu ke</label>
                  <FieldX :bind="{ readonly: true }" class="col-span-8 !mt-0 w-full"
                    :value="values.minggu" :errorText="formErrors.minggu?'failed':''"
                    @input="v=>values.minggu=v" :hints="formErrors.minggu" :check="false"
                  />
              </div>
              <div class="grid grid-cols-12 items-center mb-4">
                <label class="col-span-4 text-[12px]">Bulan</label>
                <FieldX :bind="{ readonly: true }" class="col-span-8 !mt-0 w-full"
                  :value="values.bulan" :errorText="formErrors.bulan?'failed':''"
                  @input="v=>values.bulan=v" :hints="formErrors.bulan" :check="false" 
                />
              </div>
              <div class="grid grid-cols-12 items-center">
                <label class="col-span-4 text-[12px]">Tahun</label>
                <FieldX :bind="{ readonly: true }" class="col-span-8 !mt-0 w-full"
                  :value="values.tahun" :errorText="formErrors.tahun?'failed':''"
                  @input="v=>values.tahun=v" :hints="formErrors.tahun" :check="false"
                />
              </div>
            </div>
        </div>
        
        <div class="w-1/2 flex flex-col" style="height: 100%;">
            <div class="flex-1 p-4">
                <button @click="generateJadwal" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-[18px] py-[8px] rounded-[4px] ">
                    <Icon fa="bolt"/> Generate
                  </button>
            </div>
            <div class="flex-1 p-4">
                  <button @click="" class="bg-green-500 hover:bg-green-600 text-white text-sm px-[18px] py-[8px] rounded-[4px] ">
                    <Icon fa="save"/> Simpan
                  </button>
            </div>
        </div>
    </div>
    </div>
    <div class="bg-blue-200 p-4 rounded-lg mt-2">
      <div class="overflow-x-auto max-h-[285px]">
        <table class="w-full table-auto border border-[#CACACA]">
          <thead class="sticky top-0 bg-[#f8f8f8]">
            <tr class="border">
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">Hari</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">Tanggal</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] w-[25%] border-[#CACACA]">NIK</td>
              <td class="sticky top-0 text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[20%] bg-[#f8f8f8] border-[#CACACA]">Nama</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArrJadwal" :key="item.id" class="border-t" v-if="detailArrJadwal.length">
              <td class="p-2 text-center border border-[#CACACA]">
                {{ i + 1 }}.
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.hari }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item.tanggal }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item['nik'] }}
              </td>
              <td class="text-left border border-[#CACACA] px-2">
                {{ item['nama_lengkap'] }}
              </td>
            </tr>
            <tr v-else class="text-center">
              <td colspan="7" class="py-[20px]">
                No data to show
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endverbatim