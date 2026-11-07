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
          <h1 class="text-20px font-bold">Form Pengguna</h1>
          <p class="text-gray-100">Master Pengguna</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
              :value="values.divisi" :errorname="formErrors.divisi?'failed':''"
              @input="v=>values.divisi=v" :hints="formErrors.divisi" 
              :check="false"
              placeholder="Pilih NIK dahulu" label="Divisi"
          />
      </div>
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
              :value="values.departemen" :errorname="formErrors.departemen?'failed':''"
              @input="v=>values.departemen=v" :hints="formErrors.departemen" 
              :check="false"
              placeholder="Pilih NIK dahulu" label="Departemen"
          />
      </div>
      <div>
        <FieldPopup 
              class="w-full !mt-3"
              placeholder="Pilih NIK" label="NIK"
              valueField="id" displayField="kode"
              @input="nikChange"
              :value="values.m_kary_id" @input="(v)=>values.m_kary_id=v"
              :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                }
              }"
               fa-icon="user" :check="false" 
              :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'nama_lengkap',
                headerName:  'Nama Lengkap',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
                field: 'nik',
                headerName:  'Kode',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
                field: 'nomor_ktp',
                headerName: 'No KTP',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
                field: 'm_dept.nama',
                headerName:  'Departemen',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              {
                flex: 1,
                field: 'm_zona.nama',
                headerName:  'Zona',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-center']
              },
              ]"
            />
      </div>
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
              :value="values.kode" :errorname="formErrors.kode?'failed':''"
              @input="v=>values.kode=v" :hints="formErrors.kode" 
              :check="false"
              placeholder="Pilih NIK dahulu" label="Kode"
          />
      </div>
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
              :value="values.name" :errorname="formErrors.name?'failed':''"
              @input="v=>values.name=v" :hints="formErrors.name" 
              :check="false"
              placeholder="Pilih NIK dahulu" label="Nama Pegawai"
          />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
              :value="values.username" :errorname="formErrors.username?'failed':''"
              @input="v=>values.username=v" :hints="formErrors.username" 
              :check="false" placeholder="Tuliskan Username" label="Username"
          />
      </div>
      <div>
        <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
              :value="values.email" :errorname="formErrors.email?'failed':''"
              @input="v=>values.email=v" :hints="formErrors.email" 
              :check="false" placeholder="Tuliskan Email" label="Email"
          />
      </div>
      <div class="col-span-2 text-red-500" v-if="isRead && actionText">
          <i>Mengisi kolom password akan mereset password pengguna</i>
        </div>
        <div class="col-span-2 text-red-500" v-if="values.default_password">
          <i>Default Password : {{values.default_password}}</i>
        </div>
        <div v-if="actionText">
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
              :value="values.password" :errorname="formErrors.password?'failed':''"
              @input="v=>values.password=v" :hints="formErrors.password" 
              :check="false"
              type="password"
              fa-icon="lock"
              placeholder="Tuliskan Password" label="Password"
          />
        </div>
        <div v-if="actionText">
          <FieldX :bind="{ readonly: !actionText }" class="w-full !mt-3"
              :value="values.password_confirm" :errorname="formErrors.password_confirm?'failed':''"
              @input="v=>values.password_confirm=v" :hints="formErrors.password_confirm" 
              :check="false"
              type="password"
              fa-icon="lock"
              placeholder="Tuliskan Konfirmasi Password" label="Konfirmasi Password"
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