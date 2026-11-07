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
          <h1 class="text-20px font-bold">Form Standard Gaji</h1>
          <p class="text-gray-100">Master Standard Gaji</p>
        </div>
      </div>
    </div>

    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: !actionText }" 
          :value="values.kode" :errorText="formErrors.kode?'failed':''"
          @input="v=>values.kode=v" :hints="formErrors.kode" 
          :check="false"
          class="w-full !mt-3"
          label="Kode"
          placeholder="Tuliskan Kode"
        />
      </div>
      <div>   
        <FieldSelect
            :bind="{ disabled: !actionText, clearable:false }"
            class="w-full !mt-3"
            :value="values.grading_id"
            @input="(v) => {
              values.grading_id = v;
              updateTunjanganKemahalan(); // Memanggil fungsi untuk memperbarui nilai tunjangan_kemahalan_id
            }"
            :errorText="formErrors.grading_id?'failed':''"
            displayField="value" 
            @update:valueFull="(v)=>{
              if(values.kode?.split(' ').length > 1){
                values.kode = `${v.code} ${values.kode?.split(' ')[1]} ${values.kode?.split(' ')[2]}`
              }else{                
                values.kode=v.code
              }
            }"
            :hints="formErrors.grading_id"
              :api="{
                  url: `${store.server.url_backend}/operation/m_general`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    where: `this.group = 'GRADING'`,
                    selectfield:'this.id,this.value,this.code'
                  }
            }"
            valueField="id"
            :check="false"
            label="Grading"
            placeholder="Pilih Grading" 
        />   
      </div>
      <div>
        <FieldSelect
            :bind="{ disabled: !actionText, clearable:false }"
            class="w-full !mt-3"
            :value="values.m_zona_id"
            @input="(v) => {
              values.m_zona_id = v;
              updateTunjanganKemahalan(); // Memanggil fungsi untuk memperbarui nilai tunjangan_kemahalan_id
            }"
            :errorText="formErrors.m_zona_id?'failed':''" 
            :hints="formErrors.m_zona_id"
            displayField="nama"
            @update:valueFull="(objVal)=>{
              if(values.kode?.split(' ').length === 1){                
                values.kode+=` ${objVal.nama}`
              }else if(values.kode?.split(' ').length > 1){
                values.kode = `${values.kode?.split(' ')[0]} ${objVal.nama}`
              }
            }"
            :api="{
                  url: `${store.server.url_backend}/operation/m_zona`,
                  headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                  params: {
                    join:true, 
                    single: true,
                    selectfield: 'this.id,this.nama,this.kode'
                  }
            }"
            valueField="id"
            :check="false"
            label="Zona"
            placeholder="Pilih Zona" 
        />
      </div>
      <div>
        <FieldSelect
          :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3"
          :value="values.m_posisi_id" @input="v=>values.m_posisi_id=v"
          :errorText="formErrors.m_posisi_id?'failed':''" 
          :hints="formErrors.m_posisi_id"
          displayField="desc_kerja"
          :api="{
                url: `${store.server.url_backend}/operation/m_posisi`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
          }"
          valueField="id"
          :check="false"
          label="Posisi"
          placeholder="Pilih Posisi" 
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

    <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px] mx-4">
        <div class="col-span-8 md:col-span-6">
          <div class="flex items-center gap-2">
            <div class="flex flex-col w-[161px] items-start gap-y-2">
              <label class="col-span-12">Gaji Pokok<label class="text-red-500 space-x-0 pl-0">*</label></label>
                <FieldSelect
                  :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
                  :value="values.gaji_pokok_periode || 'Bulanan'" @input="v=>values.gaji_pokok_periode=v"
                  :errorText="formErrors.gaji_pokok_periode?'failed':''" 
                  :hints="formErrors.gaji_pokok_periode"
                  valueField="id" displayField="key"
                  :options="['Bulanan']"
                  :check="false"
                  label=""
                  placeholder="Pilih Periode" 
                 />            
            </div>
            <div class="flex flex-col flex-1 ">
              <FieldNumber
                :bind="{ readonly: !actionText }"
                :value="values.gaji_pokok" @input="(v)=>values.gaji_pokok=v"
                :errorText="formErrors.gaji_pokok?'failed':''" 
                :hints="formErrors.gaji_pokok"
                :check="false"
                class="col-span-12 !mt-5 w-full"
                label=""
                placeholder="0" 
              />         
            </div>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="flex items-center gap-2">
            <div class="flex flex-col w-[161px] items-start gap-y-2">
              <label class="col-span-12">Uang Saku<label class="text-red-500 space-x-0 pl-0">*</label></label>
                <FieldSelect
                  :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
                  :value="values.uang_saku_periode || 'Bulanan'" @input="v=>values.uang_saku_periode=v"
                  :errorText="formErrors.uang_saku_periode?'failed':''" 
                  :hints="formErrors.uang_saku_periode"
                  valueField="id" displayField="key"
                  :options="['Bulanan']"
                  :check="false"
                  label=""
                  placeholder="Pilih Periode" 
                 />            
            </div>
            <div class="flex flex-col flex-1 ">
               <FieldNumber
                :bind="{ readonly: !actionText }"
                :value="values.uang_saku" @input="(v)=>values.uang_saku=v"
                :errorText="formErrors.uang_saku?'failed':''" 
                :hints="formErrors.uang_saku"
                :check="false"
                class="col-span-12 !mt-5 w-full"
                label=""
                placeholder="0" 
              />            
            </div>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="flex items-center gap-2">
            <div class="flex flex-col w-[161px] items-start gap-y-2">
              <label class="col-span-12">Tunjangan Posisi<label class="text-red-500 space-x-0 pl-0">*</label></label>
                <FieldSelect
                  :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
                  :value="values.tunjangan_posisi_periode || 'Bulanan'" @input="v=>values.tunjangan_posisi_periode=v"
                  :errorText="formErrors.tunjangan_posisi_periode?'failed':''" 
                  :hints="formErrors.tunjangan_posisi_periode"
                  valueField="id" displayField="key"
                  :options="['Bulanan']"
                  :check="false"
                  label=""
                  placeholder="Pilih Periode" 
                 />            
            </div>
            <div class="flex flex-col flex-1 ">
                 <FieldNumber
                :bind="{ readonly: !actionText }"
                :value="values.tunjangan_posisi" @input="(v)=>values.tunjangan_posisi=v"
                :errorText="formErrors.tunjangan_posisi?'failed':''" 
                :hints="formErrors.tunjangan_posisi"
                :check="false"
                class="col-span-12 !mt-5 w-full"
                label=""
                placeholder="0" 
              />            
            </div>
          </div>
        </div>

        <div class="col-span-8 md:col-span-6">
          <div class="flex items-center gap-2">
            <div class="flex flex-col w-[161px] items-start gap-y-2">
              <label class="col-span-12">Tunjangan Kemahalan<label class="text-red-500 space-x-0 pl-0">*</label></label>
                <FieldSelect
                  :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
                  :value="values.tunjangan_kemahalan_periode || 'Bulanan'" @input="v=>values.tunjangan_kemahalan_periode=v"
                  :errorText="formErrors.tunjangan_kemahalan_periode?'failed':''" 
                  :hints="formErrors.tunjangan_kemahalan_periode"
                  valueField="id" displayField="key"
                  :options="['Bulanan']"
                  :check="false"
                  label=""
                  placeholder="Pilih Periode" 
                 />            
            </div>
            
            <div class="flex flex-col flex-1 ">
              <FieldPopup
              class="w-full py-2 mt-5"
                :bind="{ readonly: !actionText }"
                :value="values.tunjangan_kemahalan_id" @input="(v)=>values.tunjangan_kemahalan_id=v"
                :errorText="formErrors.tunjangan_kemahalan_id?'failed':''" 
                :hints="formErrors.tunjangan_kemahalan_id" 
                valueField="id" displayField="besaran"
                :api="{
                    url: `${store.server.url_backend}/operation/m_tunj_kemahalan`,
                    headers: {
                      //'Content-Type': 'Application/json',
                      Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest:true,
                      single:true,
                      where:`this.is_active='true'`,
                      transform:false,
                      searchfield: 'this.kode,this.besaran,this.desc'
                    }
                }"
                placeholder="Pilih Tunjangan Kemahalan" label="" :check="false" 
                :columns="[{
                  headerName: 'No',
                  valueGetter:(p)=>p.node.rowIndex + 1,
                  width: 60,
                  sortable: false, resizable: false, filter: false,
                  cellClass: ['justify-center', 'bg-gray-50']
                },
                {
                  flex: 1,
                  field: 'kode',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-center']
                },
                {
                  flex: 1,
                  field: 'besaran',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-center']
                },
                {
                  flex: 1,
                  field: 'desc',
                  sortable: false, resizable: true, filter: 'ColFilter',
                  cellClass: ['border-r', '!border-gray-200', 'justify-center']
                },
                ]"
              />
              

            </div>
          </div>
        </div>
        
        <div class="col-span-8 md:col-span-6">
          <div class="flex items-center gap-2">
            <div class="flex flex-col w-[161px] items-start gap-y-2">
              <label class="col-span-12">Uang Makan<label class="text-red-500 space-x-0 pl-0">*</label></label>
                <FieldSelect
                  :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
                  :value="values.uang_makan_periode || 'Bulanan'" @input="v=>values.uang_makan_periode=v"
                  :errorText="formErrors.uang_makan_periode?'failed':''" 
                  :hints="formErrors.uang_makan_periode"
                  valueField="id" displayField="key"
                  :options="['Bulanan']"
                  :check="false"
                  label=""
                  placeholder="Pilih Periode" 
                 />            
            </div>
            <div class="flex flex-col flex-1 ">
                <FieldNumber
                :bind="{ readonly: !actionText }"
                :value="values.uang_makan" @input="(v)=>values.uang_makan=v"
                :errorText="formErrors.uang_makan?'failed':''" 
                :hints="formErrors.uang_makan"
                :check="false"
                class="col-span-12 !mt-5 w-full"
                label=""
                placeholder="0" 
              />                
            </div>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="flex items-center gap-2">
            <div class="flex flex-col w-[161px] items-start gap-y-2">
              <label class="col-span-12">Tunjangan Masa Kerja<label class="text-red-500 space-x-0 pl-0">*</label></label>
                <FieldSelect
                  :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
                  :value="values.tunjangan_tetap_periode || 'Bulanan'" @input="v=>values.tunjangan_tetap_periode=v"
                  :errorText="formErrors.tunjangan_tetap_periode?'failed':''" 
                  :hints="formErrors.tunjangan_tetap_periode"
                  valueField="id" displayField="key"
                  :options="['Bulanan']"
                  :check="false"
                  label=""
                  placeholder="Pilih Periode" 
                 />            
            </div>
            <div class="flex flex-col flex-1 ">
                 <FieldNumber
                :bind="{ readonly: !actionText }"
                :value="values.tunjangan_tetap" @input="(v)=>values.tunjangan_tetap=v"
                :errorText="formErrors.tunjangan_tetap?'failed':''" 
                :hints="formErrors.tunjangan_tetap"
                :check="false"
                class="col-span-12 !mt-5 w-full"
                label=""
                placeholder="0" 
              />              
            </div>
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
            <button :disabled="!actionText" @click="addDetail" type="button" class="bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
              <icon fa="plus" /> <span>Add to List</span></button>
        <div class="mx-1 mt-4">
          <table class="w-full overflow-x-auto table-auto border border-[#CACACA]">
            <thead>
              <tr class="border">
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 py-[14.5px] text-center w-[5%] border bg-[#f8f8f8] border-[#CACACA]">No.</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center w-[20%] border bg-[#f8f8f8] border-[#CACACA]">Komponen</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Faktor</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border bg-[#f8f8f8] border-[#CACACA]">Nilai</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[20%] bg-[#f8f8f8] border-[#CACACA]">Periode</td>
                <td class="text-[#8F8F8F] font-semibold text-[14px] text-capitalize px-2 text-center border w-[5%] bg-[#f8f8f8] border-[#CACACA]">Aksi</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t" v-if="detailArr.length > 0">
                <td class="p-2 text-center border border-[#CACACA]">
                  {{ i + 1 }}.
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldX :bind="{ readonly: !actionText }" class="!mt-0"
                    :value="item.komponen" @input="v=>item.komponen=v"
                    :errorText="formErrors.komponen?'failed':''" 
                    :hints="formErrors.komponen" label="" placeholder="Tuliskan Komponen" :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldSelect
                    :bind="{ disabled: !actionText, clearable:false }" class="!mt-0 w-full"
                    :value="item.faktor" @input="v=>item.faktor=v"
                    :errorText="formErrors.faktor?'failed':''" 
                    label="" placeholder="Pilih Faktor"
                    :hints="formErrors.faktor"
                    :options="['-','+']"
                    valueField="key" displayField="key" :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldNumber class="!mt-0 w-full"
                    :bind="{ readonly: !actionText }"
                    :value="item.nilai" 
                    @input="(v)=>item.nilai=v"
                    :errorText="formErrors.nilai?'failed':''" 
                    :hints="formErrors.nilai"
                    placeholder="Tuliskan Nilai" label="" :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <FieldSelect
                    :bind="{ disabled: !actionText, clearable:false }" class="!mt-0 w-full"
                    :value="item.periode" @input="v=>item.periode=v"
                    :errorText="formErrors.periode?'failed':''" 
                    label="" placeholder="Pilih Periode"
                    :hints="formErrors.periode"
                    :options="['Bulanan','Harian']"
                    valueField="key" displayField="key" :check="false"
                  />
                </td>
                <td class="p-2 border border-[#CACACA]">
                  <div class="flex justify-center">
                    <button type="button" @click="removeDetail(item)" :disabled="!actionText">
                    <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                    </svg>
                  </button>
                  </div>

                </td>
              </tr>
              <tr v-else class="text-center">
                <td colspan="7" class="py-[20px]">
                  No data to show
                </td>
              </tr>
            </tbody>
          </table>
      </div>
    </div>
      <!--BUTTON-->
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