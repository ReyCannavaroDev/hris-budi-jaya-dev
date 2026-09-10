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
          <h1 class="text-20px font-bold">Form Lokasi Absensi</h1>
          <p class="text-gray-100">Master Lokasi Absensi</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldSelect
            class="col-span-12 !mt-0"
            label="Company"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.comp_id" @input="v=>values.comp_id=v"
            :errorText="formErrors.comp_id?'failed':''" 
            :hints="formErrors.comp_id"
            valueField="id" displayField="nama"
            :api="{
                url: `${store.server.url_backend}/operation/m_comp`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  where: 'this.is_active = true'
                }
            }"
            placeholder="Pilih Company" :check="false"
          />
      </div>
      <div>   
        <FieldPopup
            class="col-span-12 !mt-0"
            :bind="{ readonly: !actionText }"
            :value="values.default_user_id" @input="(v)=>values.default_user_id=v"
            :errorText="formErrors.default_user_id?'failed':''" 
            :hints="formErrors.default_user_id" 
            valueField="id" displayField="name"
            :api="{
              url: `${store.server.url_backend}/operation/default_users`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
                searchfield: 'this.name,this.username,m_kary.no_tlp,m_comp.nama',
                where: `this.m_comp_id = ${values.comp_id ?? 0}`
              }
            }"
            placeholder="Pilih User" label="User" :check="false" 
            :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'name',
              headerName:  'Nama Karyawan',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'm_comp.nama',
              headerName:  'Nama Company',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'username',
              headerName:  'Username',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'm_kary.no_tlp',
              headerName:  'Nomer Telepon',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            ]"
          />
      </div>
      <div>
        <FieldX  
            class="col-span-12 !mt-0"
            :bind="{ readonly: !actionText }"
            :value="values.nama" :errorText="formErrors.nama?'failed':''"
            @input="v=>values.nama=v"
            :hints="formErrors.nama"
            label="Nama Lokasi"
            placeholder="Tuliskan Nama Lokasi"
            :check="false" />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" type="number" label=""  class="w-full !mt-3"
            :value="values.lat" :errorText="formErrors.lat ? 'failed' : ''"
            @input="v=>values.lat=v" :hints="formErrors.lat" 
            @change="(e)=>{
              if(values.long && values.lat){  
                values.geo_checkin = `POINT(${values.long} ${values.lat})`
              }
            }"
            :check="false"
            label="Latitude"
            placeholder="Tuliskan Latitude"
          />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }"type="number" label=""  class="w-full !mt-3"
            :value="values.long" :errorText="formErrors.long ? 'failed' : ''"
            @input="v=>values.long=v" :hints="formErrors.long" 
            @change="(e)=>{
              if(values.long && values.lat){  
                values.geo_checkin = `POINT(${values.long} ${values.lat})`
              }
            }"
            :check="false"
            label="Longtitude"
            placeholder="Tuliskan Longtitude"
          />
      </div>
      <div>
        <FieldGeo class="w-full !mt-3"
          :bind="{ readonly: !actionText, search:true}"  
          @input="(v)=>{values.geo_checkin=v}"
          :center="[-7.3244677, 112.7550714]"
          :errorText="formErrors.geo_checkin?'failed':''" 
          :hints="formErrors.geo_checkin"
          geostring="POINT(112.7550714 -7.3244677)"
          :value="values.geo_checkin" Label="Titik lokasi" placeholder="Pilih Titik Lokasi" fa-icon="map-marker-alt" :check="false"
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