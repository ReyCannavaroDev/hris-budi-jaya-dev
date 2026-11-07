<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px] border-t-10 border-gray-500">
  <div class="flex justify-between items-center px-2.5 py-1">
    <div class="flex items-center gap-x-4">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Inactive</button>
      </div>
    </div>
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))" class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="max-h-[450px]">
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
        <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali" @click="onBack"/>
        <div>
          <h1 class="text-20px font-bold">Form Zona</h1>
          <p class="text-gray-100">Master Zona</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
              :value="values.kode" :errorText="formErrors.kode?'failed':''"
              @input="v=>values.kode=v" :hints="formErrors.kode" 
              :check="false"
              label="Kode"
              placeholder="Auto Generate Kode"
          />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
              :value="values.nama" :errorText="formErrors.nama?'failed':''"
              @input="v=>values.nama=v" :hints="formErrors.nama" 
              :check="false"
              label="Nama"
              placeholder="Tuliskan Nama"
          />
      </div>
      <div>
        <FieldX  
            class="w-full !mt-3"
            :bind="{ readonly: !actionText }"
            :value="values.desc" :errorText="formErrors.desc?'failed':''"
            @input="v=>values.desc=v"
            type="textarea"
            :hints="formErrors.desc"
            label="Keterangan"
            placeholder="Tuliskan Keterangan"
            :check="false" />
      </div>
      <div>
        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3"
          :value="values.is_active" @input="v=>values.is_active=v"
          :errorText="formErrors.is_active?'failed':''" 
          :hints="formErrors.is_active"
          valueField="id" displayField="key"
          :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
          placeholder="Pilih Status" label="Status" :check="false"
        />
      </div>
      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
    </div>
      <hr>
      
    
    <div class="flex items-stretch mt-2 mx-4">
      <h3 class="font-semibold">Detail Zona</h4><br>
      <div class="content-end flex">
        <button @click="addDetail" type="button"  v-show="actionText" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold ml-2 px-2 py-1 rounded-md flex items-center justify-center mt-2">
            <icon fa="plus" size="sm mr-0.5"/> Tambah Baris
        </button>
        <button @click="deleteAll" type="button"  v-show="actionText" class="bg-red-500 hover:bg-red-600 text-white font-semibold ml-2 px-2 py-1 rounded-md flex items-center justify-center mt-2">
            <icon fa="trash" size="sm mr-0.5"/> Hapus Semua
        </button>
      </div>
    </div>
    <div class="mx-4">
      <table class="w-full overflow-x-auto mt-2">
        <thead>
          <tr class="border-y">
            <td class="text-black font-bold text-capitalize px-2 text-center w-[5%]">No.</td>
            <td class="text-black font-bold text-capitalize px-2 text-center w-[50%]">Lokasi <label class="text-red-500 space-x-0 pl-0">*</label></td>
            <td class="text-black font-bold text-capitalize px-0 text-center w-[50%]">Keterangan <label class="text-red-500 space-x-0 pl-0">*</label></td>
            <td v-show="actionText" class="text-black font-bold text-capitalize px-2 text-center w-[5%]"></td>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in detail.items" :key="item._id" class="border-t">
            <td class="p-2 text-center pt-4 pb-4">
              {{ i + 1 }}.
            </td>
            <td class="p-2 pt-4 pb-4">
              <FieldPopup
              :bind="{ readonly: !actionText }" class="col-span-8 !mt-0 w-full"
              :value="item.m_lokasi_id" @input="(v)=>item.m_lokasi_id=v"
              :errorText="formErrors.m_lokasi_id?'failed':''" 
              :hints="formErrors.m_lokasi_id" 
              valueField="id" displayField="nama"
              label=""
              :api="{
                url: `${store.server.url_backend}/operation/m_lokasi`,
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  simplest:true,
                  transform:false,
                  where:`this.is_active=true`,
                  join:true, 
                  searchfield:'this.id, this.kode, this.nama',
                }
              }"
              :check="false" 
              :columns="[{
                flex: 1,
                field: 'kode',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
                field: 'nama',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              ]"
            />
            </td>
            <td class="p-2 pt-4 pb-4">
              <FieldX
              @input="(v) => item.desc = v"
              :value="item.desc"
              :error-text="formErrors.desc"
              :bind="{ readonly: !actionText }"
              placeholder="Masukan Keterangan"
              label=""
              class="col-span-8 !mt-0 w-full" :check="false"
            />
            </td>
            <td class="p-2 pt-4 pb-4" v-show="actionText">  
              <div class="flex justify-end">
                <button type="button" @click="removeDetail(item)">
                  <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                </svg>
              </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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