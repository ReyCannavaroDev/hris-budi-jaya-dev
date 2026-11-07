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
          <h1 class="text-20px font-bold">Form Blacklist</h1>
          <p class="text-gray-100">Master Blacklist</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
            :value="values.no_ktp" :errorText="formErrors.no_ktp?'failed':''"
            @input="v=>values.no_ktp=v" :hints="formErrors.no_ktp" 
            :check="false"
            label="No. KTP"
            placeholder="Tuliskan No. KTP"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" label="Nama" placeholder="Tuliskan Nama" class="w-full !mt-3"
            :value="values.nama" :errorText="formErrors.nama?'failed':''"
            @input="v=>values.nama=v" :hints="formErrors.nama" 
            :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" label="Alamat" placeholder="Tuliskan Alamat" class="w-full !mt-3"
            :value="values.alamat" :errorText="formErrors.alamat?'failed':''"
            @input="v=>values.alamat=v" :hints="formErrors.alamat" 
            :check="false"
            type="textarea"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" label="No. Telepon" placeholder="Tuliskan No. Telepon" class="w-full !mt-3"
            :value="values.telp" :errorText="formErrors.telp?'failed':''"
            @input="v=>values.telp=v" :hints="formErrors.telp" 
            :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" label="Tempat Lahir" placeholder="Tuliskan Tempat Lahir" class="w-full !mt-3"
            :value="values.tempat_lahir" :errorText="formErrors.tempat_lahir?'failed':''"
            @input="v=>values.tempat_lahir=v" :hints="formErrors.tempat_lahir" 
            :check="false"
        />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText , required: true}" type="date" label="Tanggal Lahir" placeholder="Pilih Tanggal Lahir" class="w-full !mt-3"
            :value="values.tgl_lahir" :errorText="formErrors.tgl_lahir?'failed':''"
            @input="v=>values.tgl_lahir=v" :hints="formErrors.tgl_lahir" 
            :check="false"
            type="date"
        />
      </div>
      <div>
        <FieldX  
            class="w-full !mt-3"
            :bind="{ readonly: !actionText }"
            :value="values.keterangan" :errorText="formErrors.keterangan?'failed':''"
            @input="v=>values.keterangan=v"
            type="textarea"
            :hints="formErrors.keterangan"
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