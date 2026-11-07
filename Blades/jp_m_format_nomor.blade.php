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
          <h1 class="text-20px font-bold">Form Format Nomor</h1>
          <p class="text-gray-100">Master Format Nomor</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
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
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
              :value="values.pratinjau" :errorText="formErrors.pratinjau?'failed':''"
              @input="v=>values.pratinjau=v" :hints="formErrors.pratinjau" 
              :check="false"
              label="Pratinjau"
              placeholder="AutoFill"
          />
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
      <div class="flex mt-2 justify-end mx-4">
          <h3 class="font-semibold text-right">Detail Susunan</h4><br>
          <div class="content-end flex">
            <button @click="pratinjau" type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold ml-2 px-2 py-1 rounded-md flex items-center justify-center mt-2">
                <icon fa="eye" size="sm mr-0.5"/> Pratinjau
            </button>
            <button @click="addRow" type="button"  v-show="actionText" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold ml-2 px-2 py-1 rounded-md flex items-center justify-center mt-2">
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
              <td class="text-black font-bold text-capitalize px-2 text-left w-[5%]">No.</td>
              <td class="text-black font-bold text-capitalize px-2 text-left w-[30%]">Prefix <label class="text-red-500 space-x-0 pl-0">*</label></td>
              <td class="text-black font-bold text-capitalize px-2 text-left w-[30%]">Prefix Value</td>
              <td class="text-black font-bold text-capitalize px-2 text-left w-[10%]">Aksi</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="a,i in trx_dtl.items" :key="a._id">
              <td class="text-center px-1">{{ i+1 }}</td>
              <td class="text-center px-1">
                <FieldSelect
                  class="w-full !mt-0"
                  :bind="{ disabled: !actionText, clearable:false }"
                  :value="trx_dtl.items[i].generate_num_type_id" 
                  :check="false"
                  @input="(v)=>{
                    trx_dtl.items[i].generate_num_type_id=v
                  }"
                  @update:valueFull="(v)=>{
                    trx_dtl.items[i].generate_num_type=v.value
                  }"
                  displayField="nama"
                  valueField="id"
                  :api="{
                      url: `${store.server.url_backend}/operation/generate_num_type`,
                      headers: {
                        'Content-Type': 'Application/json',
                        Authorization: `${store.user.token_type} ${store.user.token}`
                      },
                      params: {
                        selectfield: 'nama,value,id',
                        simplest:true,
                        single:true,
                        where:`this.is_active='true'`,
                        transform:false,
                      }
                  }"
                  fa-icon="search" :check="true" />
              </td>
              <td class="text-center px-1">   
                <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
                    :value="trx_dtl.items[i].generate_num_type ?? ''" 
                    @input="v=>trx_dtl.items[i].generate_num_type=v" 
                    :check="false"
                    type="text"
                    label=""
                    placeholder=""
                />
              </td>
              <td class="flex mt-3">
                <button title="geser keatas" v-show="actionText && i > 0" @click="moveUp(i)" type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold ml-1 px-2 py-2 rounded-md flex items-center">
                  <icon fa="arrow-up" size="sm mr-0.5"/>
                </button>
                <button title="geser kebawah" v-show="actionText && i < (trx_dtl.items.length-1)" @click="moveDown(i)" type="button" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold ml-1 px-2 py-2 rounded-md flex items-center">
                  <icon fa="arrow-down" size="sm mr-0.5"/>
                </button>
                <button title="hapus baris" v-show="actionText" @click="deleteDetail(a)" type="button" class="bg-red-500 hover:bg-red-600 text-white font-semibold ml-1 px-2 py-2 rounded-md flex items-center">
                  <icon fa="trash" size="sm mr-0.5"/>
                </button>
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