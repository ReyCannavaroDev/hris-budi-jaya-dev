@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
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
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Format Nomor
        </h1>
        <hr>
      </div>
      
      <div class="grid <md:grid-cols-1 grid-cols-3 gap-2">
        <!-- START COLUMN -->
        <div>
          <label class="font-semibold">Nama Format<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.nama" :errorText="formErrors.nama?'failed':''"
              @input="v=>values.nama=v" :hints="formErrors.nama" 
              :check="false"
              label=""
              placeholder="ex: KODE LOKASI"
          />
        </div>
        <div>
          <label class="font-semibold">Pratinjau<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.pratinjau" :errorText="formErrors.pratinjau?'failed':''"
              @input="v=>values.pratinjau=v" :hints="formErrors.pratinjau" 
              :check="false"
              label=""
              placeholder=""
          />
        </div>
        <div class="flex flex-col gap-2">
          <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer font-semibold"
            for="is_active_for_click"
            >Status :</label
          >
          <div class="flex w-40">
            <div class="flex-auto">
              <i class="text-red-500">InActive</i>
            </div>
            <div class="flex-auto">
              <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                :class="{'after:bg-gray-500': values.is_active === false}"
                type="checkbox"
                role="switch"
                id="is_active_for_click"
                :disabled="!actionText"
                v-model="values.is_active"
                />
            </div>
            <div class="flex-auto">
              <i class="text-green-500">Active</i>
            </div>
          </div>
        </div>
        <!-- END COLUMN -->
      </div>
      <div class="col-span-8 md:col-span-12 mt-5">
      <hr>
        <div class="flex mt-2 justify-end">
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
        <!-- ACTION BUTTON START -->
        <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Batal
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
        </div>
    </div>
  </div>
</div>
@endverbatim
@endif