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

      <!-- HEADER START -->
      <div class="flex flex-col items-start mb-2">
        <h1 class="text-[24px] mb-[10px] font-bold">
          {{actionText}} Form Jam Kerja
        </h1>
      </div>
        <!-- Form Jam Kerja -->
        <div class="grid grid-cols-2 md:grid-cols-2 text-[14px] gap-10 gap-y-[26px] mt-[36px]" v-if="activeTabIndex === 0">
        <!-- <div>
          <label for="Direktorat" class="font-semibold select-all">Direktorat <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX  
            class="w-full py-2 !mt-0"
            :bind="{ disabled:true, readonly:true }"
            :value="values.direktorat" :errorText="formErrors.direktorat?'failed':''"
            @input="v=>values.direktorat=v"
            :hints="formErrors.direktorat"
            :check="false" />
        </div> -->
        <div>
          <label class="font-semibold">Kode<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.kode" :errorText="formErrors.kode?'failed':''"
              @input="v=>values.kode=v" :hints="formErrors.kode" 
              :check="false"
              label=""
          />
        </div>
          <div>
              <label class="font-semibold">Pilih Tipe <label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="w-full py-2 !mt-0"
              :value="values.tipe_jam_kerja_id" @input="v=>values.tipe_jam_kerja_id=v"
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
          
          <div class="col-span-1 md:col-span-1">
              <label class="font-semibold">Waktu Mulai<label class="text-red-500 space-x-0 pl-0">*</label></label>
                <FieldX 
                    :bind="{ readonly: !actionText }"
                    class="py-2 !mt-0 w-full"
                    type="time" 
                    fa-icon="clock"
                    :value="values.waktu_mulai"
                    :errorText="formErrors.waktu_mulai ? 'failed' : ''"
                    @input="v => values.waktu_mulai =v"
                    :hints="formErrors.waktu_mulai"  
                    :check="false"
                />
          </div>
          <div class="col-span-1 md:col-span-1 ">
              <label class="font-semibold">Waktu Berakhir<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldX :bind="{ readonly: !actionText }" class="py-2 !mt-0 w-full"
                  type="time" 
                  fa-icon="clock"
                  :value="values.waktu_akhir" :errorText="formErrors.waktu_akhir?'failed':''"
                  @input="v=>values.waktu_akhir=v" :hints="formErrors.waktu_akhir"  
                  :check="false"
              />
              <div class="mt-2 ">
                  <input :disabled="!actionText"  class="!mt-0" type="checkbox" v-model="values.is_hari_berikutnya"
                    :check="false"
                  />
                  <label class="ml-2" for="hariberikutnya">Hari berikutnya</label>
              </div>
          </div>

          <div>
          <label class="font-semibold"> Keterangan Jam Kerja <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.desc" :errorText="formErrors.desc?'failed':''"
              @input="v=>values.desc=v" :hints="formErrors.desc" 
              :check="false"
              type="textarea"
              label=""
              placeholder="Tuliskan Role disini"
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
      </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
            <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Kembali
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
      </div>
      <!-- FORM END -->
    </div>
  </div>
</div>
@endverbatim
@endif