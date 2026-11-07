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
      <div class="flex flex-col items-start mb-2 pb-4">
        <h1 class="text-[24px] mb-[15px] font-bold">
          Form Posisi
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <!-- <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Direktorat<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" label="" placeholder="Autofill"  class="col-span-12 !mt-0 w-full"
              :value="values.direktorat" :errorText="formErrors.direktorat?'failed':''"
              @input="v=>values.direktorat=v" :hints="formErrors.direktorat" :check="false"
            />
          </div>
        </div> -->
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Kode<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" label="" placeholder="Autofill" class="col-span-12 !mt-0 w-full"
              :value="values.kode" :errorText="formErrors.kode?'failed':''"
              @input="v=>values.kode=v" :hints="formErrors.kode" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Deskripsi<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Deskripsi" class="col-span-12 !mt-0 w-full"
              :value="values.desc_kerja" :errorText="formErrors.desc_kerja?'failed':''"
              @input="v=>values.desc_kerja=v" :hints="formErrors.desc_kerja" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Deskripsi Kerja</label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Deskripsi Kerja" class="col-span-12 !mt-0 w-full"
              :value="values.desc_kerja_1" :errorText="formErrors.desc_kerja_1?'failed':''"
              @input="v=>values.desc_kerja_1=v" :hints="formErrors.desc_kerja_1" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Deskripsi Kerja 2</label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Deskripsi Kerja 2" class="col-span-12 !mt-0 w-full"
              :value="values.desc_kerja_2" :errorText="formErrors.desc_kerja_2?'failed':''"
              @input="v=>values.desc_kerja_2=v" :hints="formErrors.desc_kerja_2" :check="false"
            />
          </div>
        </div>
        <!-- <div class="col-span-8 md:col-span-6">
        </div> -->
        <!-- <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.is_active" @input="v=>values.is_active=v"
              :errorText="formErrors.is_active?'failed':''" 
              :hints="formErrors.is_active"
              valueField="id" displayField="key"
              :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
              :check="false"
            />
          </div>
        </div> -->
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Minimal Pengalaman<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Minimal Pengalaman" class="col-span-11 !mt-0 w-full"
              :value="values.min_pengalaman" type="number" :errorText="formErrors.min_pengalaman?'failed':''"
              @input="v=>values.min_pengalaman=v" :hints="formErrors.min_pengalaman" :check="false"
            />
            <span class="flex items-center col-span-2 col-start-12 pl-2">Year</span>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Minimal Pendidikan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.min_pendidikan_id" label="" placeholder="Pilih Minimal Pendidikan" @input="v=>values.min_pendidikan_id=v"
              :errorText="formErrors.min_pendidikan_id?'failed':''" 
              :hints="formErrors.min_pendidikan_id"
              valueField="id" displayField="value"
              :api="{
                url: `${store.server.url_backend}/operation/m_general`,
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  simplest: true,
                  transform: false,
                  where: `this.group='PENDIDIKAN' AND this.is_active='true'`,
                  join: true,
                  selectfield: 'this.id, this.code, this.value, this.is_active'
                }
              }"
              :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Minimal Gaji Pokok<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.min_gaji_pokok" label="" placeholder="Tuliskan Minimal Gaji Pokok" @input="(v)=>values.min_gaji_pokok=v"
              :errorText="formErrors.min_gaji_pokok?'failed':''" 
              :hints="formErrors.min_gaji_pokok" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Maksimal Gaji Pokok<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Maksimal Gaji Pokok" class="col-span-12 !mt-0 w-full"
              :value="values.max_gaji_pokok" @input="(v)=>values.max_gaji_pokok=v"
              :errorText="formErrors.max_gaji_pokok?'failed':''" 
              :hints="formErrors.max_gaji_pokok" :check="false"
            />
          </div>
        </div>

                <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Jatah Jam Lembur<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Jatah Jam Lembur" class="col-span-12 !mt-0 w-full"
              :value="values.jatah_jam_lembur" @input="(v)=>values.jatah_jam_lembur=v"
              :errorText="formErrors.jatah_jam_lembur?'failed':''" 
              :hints="formErrors.jatah_jam_lembur" :check="false"
            />
          </div>
        </div>

                  <div class="col-span-8 md:col-span-6">
          <label class="font-semibold">Ongkos Lembur<span class="text-red-500 space-x-0 pl-0">*</span><label class="text-red-500 space-x-0 pl-0"></label></label>
          <div class=" flex flex-row items-center space-x-1"> 
            <FieldNumber :bind="{ readonly: !actionText }" class="w-1/2 py-2 !mt-0"
            placeholder="Masukan Persetase"
            :value="values.percentage" :errorText="formErrors.percentage?'failed':''"
            @input="v=>values.percentage=v" :hints="formErrors.percentage" 
            :check="false"
            label=""
          />
          <span class="text-xl font-semibold">%</span> 
          </div>

        </div>

        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Potongan BPJS<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.potongan_bpjs" @input="(v)=>values.potongan_bpjs=v"
              :errorText="formErrors.potongan_bpjs?'failed':''" 
              :hints="formErrors.potongan_bpjs"
              placeholder="Masukan Potongan BPJS" label="" :check="false"
            />
            
          </div>
        </div>




        <div class="col-span-8 md:col-span-6">
            <div class="grid grid-cols-12 items-center gap-y-2">
              <label class="col-span-12">Tipe BPJS<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldSelect
                  :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
                  :value="values.tipe_bpjs_id" @input="v=>values.tipe_bpjs_id=v"
                  :errorText="formErrors.tipe_bpjs_id?'failed':''" 
                  :hints="formErrors.tipe_bpjs_id" label="" placeholder="Pilih Tipe BPJS"
                  valueField="id" displayField="value"
                  :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: {
                      'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest: true,
                      transform: false,
                      where: `this.group='TIPE BPJS' AND this.is_active='true'`,
                      join: true,
                      selectfield: 'this.id, this.code, this.value, this.is_active'
                    }
                  }"
                  :check="false"
                />
            </div>
          </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Keterangan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" label="" placeholder="Tuliskan Keterangan" type="textarea" class="col-span-12 !mt-0 w-full"
              :value="values.desc" :errorText="formErrors.desc?'failed':''"
              @input="v=>values.desc=v" :hints="formErrors.desc" :check="false"
            />
          </div>
        </div>
        <div class="flex flex-col gap-2">
          <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer"
            for="is_active"
            >Status</label
          >
          <div class="flex w-40 items-center">
            <div class="flex-auto">
              <i class="text-red-500">InActive</i>
            </div>
            <div class="flex-auto">
              <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="is_active"
                :disabled="!actionText"
                v-model="values.is_active" />
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