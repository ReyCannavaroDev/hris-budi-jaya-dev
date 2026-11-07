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
          <h1 class="text-20px font-bold">Form Posisi</h1>
          <p class="text-gray-100">Master Posisi</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" label="Kode" placeholder="Autofill" class="w-full !mt-3"
          :value="values.kode" :errorText="formErrors.kode?'failed':''"
          @input="v=>values.kode=v" :hints="formErrors.kode" :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" label="Deskripsi" placeholder="Tuliskan Deskripsi" class="w-full !mt-3"
          :value="values.desc_kerja" :errorText="formErrors.desc_kerja?'failed':''"
          @input="v=>values.desc_kerja=v" :hints="formErrors.desc_kerja" :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" label="Deskripsi Kerja 1" placeholder="Tuliskan Deskripsi Kerja 1" class="w-full !mt-3"
          :value="values.desc_kerja_1" :errorText="formErrors.desc_kerja_1?'failed':''"
          @input="v=>values.desc_kerja_1=v" :hints="formErrors.desc_kerja_1" :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" label="Deskripsi Kerja 2" placeholder="Tuliskan Deskripsi Kerja 2" class="w-full !mt-3"
          :value="values.desc_kerja_2" :errorText="formErrors.desc_kerja_2?'failed':''"
          @input="v=>values.desc_kerja_2=v" :hints="formErrors.desc_kerja_2" :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" label="Minimal Pengalaman (Tahun)" placeholder="Tuliskan Minimal Pengalaman (Tahun)" class="w-full !mt-3"
            :value="values.min_pengalaman" type="number" :errorText="formErrors.min_pengalaman?'failed':''"
            @input="v=>values.min_pengalaman=v" :hints="formErrors.min_pengalaman" :check="false"
          />
      </div>
      <div>
        <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3"
              :value="values.min_pendidikan_id" label="Minimal Pendidikan" placeholder="Pilih Minimal Pendidikan" @input="v=>values.min_pendidikan_id=v"
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
      <div>
        <FieldNumber
          :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.min_gaji_pokok" label="Minimal Gaji Pokok" placeholder="Tuliskan Minimal Gaji Pokok" @input="(v)=>values.min_gaji_pokok=v"
          :errorText="formErrors.min_gaji_pokok?'failed':''" 
          :hints="formErrors.min_gaji_pokok" :check="false"
        />
      </div>
      <div>
        <FieldNumber
          :bind="{ readonly: !actionText }" label="Maksimal Gaji Pokok" placeholder="Tuliskan Maksimal Gaji Pokok" class="w-full !mt-3"
          :value="values.max_gaji_pokok" @input="(v)=>values.max_gaji_pokok=v"
          :errorText="formErrors.max_gaji_pokok?'failed':''" 
          :hints="formErrors.max_gaji_pokok" :check="false"
        />
      </div>
      <div>
        <FieldNumber
          :bind="{ readonly: !actionText }" class="w-full !mt-3"
          :value="values.potongan_bpjs" @input="(v)=>values.potongan_bpjs=v"
          :errorText="formErrors.potongan_bpjs?'failed':''" 
          :hints="formErrors.potongan_bpjs"
          placeholder="Masukan Potongan BPJS" label="Potongan BPJS" :check="false"
        />
      </div>
      <div>
        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3"
          :value="values.tipe_bpjs_id" @input="v=>values.tipe_bpjs_id=v"
          :errorText="formErrors.tipe_bpjs_id?'failed':''" 
          :hints="formErrors.tipe_bpjs_id" label="Tipe BPJS" placeholder="Pilih Tipe BPJS"
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