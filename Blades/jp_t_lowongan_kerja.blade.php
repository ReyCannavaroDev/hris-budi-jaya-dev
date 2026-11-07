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
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
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
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Lowongan Kerja</h1>
        <p class="text-gray-100">Lowongan Kerja</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <FieldX :bind="{ readonly: true }" type="text" :value="values.nomor" class="w-full mt-3"
        @input="v=>values.nomor=v" :check="false" placeholder="Masukan Nomor" label="Nomor" />
    </div>
    <div>
      <FieldSelect placeholder="Masukan Departemen" label="Departemen"
        :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" :value="values.m_dept_id"
        @input="v=>values.m_dept_id=v" :errorText="formErrors.m_dept_id?'failed':''" :hints="formErrors.m_dept_id"
        @update:valueFull="(objVal)=>{
                  values.m_dept_id = null
                }" displayField="nama" :api="{
                    url: `${store.server.url_backend}/operation/m_dept`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              }" valueField="id" :check="false" />
    </div>
    <div>
      <FieldX placeholder="Masukan Tanggal" label="Tanggal" :bind="{ readonly: false }" type="date"
        :value="values.tanggal" class="w-full mt-3" @input="v=>values.tanggal=v" fa-icon="calender" :check="false" />
    </div>
    <div>
      <FieldSelect placeholder="Masukan Jenis Lowongan Pekerjaan" label="Jenis Lowongan Pekerjaan"
        :bind="{ disabled: !actionText, clearable:false }" class="w-full mt-3" :value="values.jenis_loker_id"
        @input="v=>values.jenis_loker_id=v" :errorText="formErrors.jenis_loker_id?'failed':''"
        :hints="formErrors.jenis_loker_id" @update:valueFull="(objVal)=>{
                  values.jenis_loker_id = null
                }" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                    params: {
                      where: `this.group='JENIS LOKER'`
                    }
              }" valueField="id" :check="false" />
    </div>

    <div>
      <FieldSelect placeholder="Masukan Prioritas" label="Prioritas" :bind="{ disabled: !actionText, clearable:false }"
        class="w-full mt-3" :value="values.prioritas_id" @input="v=>values.prioritas_id=v"
        :errorText="formErrors.prioritas_id?'failed':''" :hints="formErrors.prioritas_id" @update:valueFull="(objVal)=>{
                  values.prioritas_id = null
                }" displayField="value" :api="{
                    url: `${store.server.url_backend}/operation/m_general`,
                    headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                     params: {
                      where: `this.group='PRIORITAS'`
                    }
              }" valueField="id" :check="false" />
    </div>

    <div>
      <FieldX placeholder="Masukan Tanggal Dibuka" label="Tanggal Dibuka" :bind="{ readonly: false }" type="date"
        :value="values.tgl_dibuka" class="w-full mt-3" @input="v=>values.tgl_dibuka=v" fa-icon="calender"
        :check="false" />
    </div>

    <div>
      <FieldX placeholder="Masukan Tanggal Berakhir" label="Tanggal Berakhir" :bind="{ readonly: false }" type="date"
        :value="values.tgl_akhir" class="w-full mt-3" @input="v=>values.tgl_akhir=v" fa-icon="calender"
        :check="false" />
    </div>

    <div>
      <FieldX placeholder="Masukan Deskripsi" label="Deskripsi" :bind="{ readonly: false }" type="textarea"
        :value="values.deskripsi" class="w-full mt-3" @input="v=>values.deskripsi=v" :check="false" />
    </div>

    <div>
      <FieldX placeholder="Masukan Status" label="Status" :bind="{ readonly: true }" type="text" :value="values.status"
        class="w-full mt-3" @input="v=>values.status=v" :check="false" />
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