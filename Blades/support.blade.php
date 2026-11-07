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
          Form Support Customer
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
     <div class="grid <md:grid-cols-1 grid-cols-2 grid-flow-row gap-x-4 mb-5">
        
        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }"
          :value="values.tipe_support_id" 
          :check="false"
          @input="(v)=>{
            values.tipe_support_id=v
          }"
          :errorText="formErrors.tipe_support_id?'failed':''"
          :hints="formErrors.tipe_support_id"
          displayField="value"
          valueField="id"
          :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: {
                //'Content-Type': 'Application/json',
                Authorization: `${store.user.token_type} ${store.user.token}`
              },
              params: {
                simplest:true,
                single:true,
                where:`this.is_active='true' and this.group='TIPE SUPPORT'`,
                transform:false,
                selectfield: 'value,id,group',
                searchfield: 'value'
              }
          }"
          placeholder="Tipe Support"
          fa-icon="external-link-square-alt" :check="true"
        />
        <div></div>
            
        <FieldX :bind="{ readonly: !actionText }" type="date"
          :value="values.tanggal" :errorText="formErrors.tanggal?'failed':''"
          placeholder="Tanggal" fa-icon="calendar" @input="v=>values.tanggal=v" :hints="formErrors.tanggal" :check="false"
        />

        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }"
          :value="values.kategori" :errorText="formErrors.kategori?'failed':''"
          @input="v=>values.kategori=v"
          :hints="formErrors.kategori"
          :options="['Dalam Kota','Luar Kota']"
          placeholder="Kategori" fa-icon="external-link-square-alt" :check="false"
        />

        <FieldX type="textarea"  
          :bind="{ readonly: !actionText }"  @input="(v)=>values.planning=v"
          :errorText="formErrors.planning?'failed':''" 
          :hints="formErrors.planning"
          :value="values.planning" @input="(v)=>values.planning=v" placeholder="Planning Support Activity" fa-icon="edit" :check="false"
        />

        <FieldX type="textarea"  
          :bind="{ readonly: !actionText }"  @input="(v)=>values.actual=v"
          :errorText="formErrors.actual?'failed':''" 
          :hints="formErrors.actual"
          :value="values.actual" @input="(v)=>values.actual=v" placeholder="Actual Support Activity" fa-icon="edit" :check="false"
        />

        <FieldX 
          :bind="{ readonly: !actionText }"
          type="time" 
          fa-icon="clock"
          placeholder="Waktu Mulai"
          :value="values.start"
          :errorText="formErrors.start ? 'failed' : ''"
          @input="v => values.start =v"
          :hints="formErrors.start"  
          :check="false"
        />

        <FieldX 
          :bind="{ readonly: !actionText }"
          type="time" 
          fa-icon="clock"
          placeholder="Waktu Selesai"
          :value="values.finish"
          :errorText="formErrors.finish ? 'failed' : ''"
          @input="v => values.finish =v"
          :hints="formErrors.finish"  
          :check="false"
        />

        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }"
          :value="values.project_id" 
          :check="false"
          @input="(v)=>{
            values.project_id=v
          }"
          :errorText="formErrors.project_id?'failed':''"
          :hints="formErrors.project_id"
          displayField="value"
          valueField="id"
          :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: {
                //'Content-Type': 'Application/json',
                Authorization: `${store.user.token_type} ${store.user.token}`
              },
              params: {
                simplest:true,
                single:true,
                where:`this.is_active='true' and this.group='PROJECT'`,
                transform:false,
                selectfield: 'value,id,group',
                searchfield: 'value'
              }
          }"
          placeholder="Project"
          fa-icon="building" :check="true"
        />

        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }"
          :value="values.pic_id" 
          :check="false"
          @input="(v)=>{
            values.pic_id=v
          }"
          :errorText="formErrors.pic_id?'failed':''"
          :hints="formErrors.pic_id"
          displayField="value"
          valueField="id"
          :api="{
              url: `${store.server.url_backend}/operation/m_general`,
              headers: {
                //'Content-Type': 'Application/json',
                Authorization: `${store.user.token_type} ${store.user.token}`
              },
              params: {
                simplest:true,
                single:true,
                where:`this.is_active='true' and this.group='PROJECT'`,
                transform:false,
                selectfield: 'value,id,group',
                searchfield: 'value'
              }
          }"
          placeholder="PIC Customer"
          fa-icon="user" :check="true"
        />
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