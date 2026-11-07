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
          <h1 class="text-20px font-bold">Form Surat Perjalanan Dinas</h1>
          <p class="text-gray-100">Master Surat Perjalanan Dinas</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3"
              :value="values.kode" :errorText="formErrors.kode?'failed':''"
              @input="v=>values.kode=v" :hints="formErrors.kode" 
              :check="false"
              label="Kode"
              placeholder="Auto Generate Kode"
          />
      </div>
      <div>
        <FieldSelect
            class="w-full !mt-3"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.m_divisi_id" 
            @input="(v)=>{
              //$log(v)
              values.m_divisi_id=v
              //$log(values.m_divisi_id)
            }"
            :errorText="formErrors.m_divisi_id?'failed':''"
            :hints="formErrors.m_divisi_id"
            displayField="nama"
            valueField="id"
            :check="false"
            label="Divisi"
            placeholder="Pilih Divisi"
            :api="{
                url: `${store.server.url_backend}/operation/m_divisi`,
                headers: {
                  //'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }"
            fa-icon="search" :check="true" />
      </div>
      <div>
        <FieldSelect
            label="Departemen"
            placeholder="Pilih Departemen"
            class="w-full !mt-3"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.m_dept_id" 
            :check="false"
            @input="(v)=>{
              //$log(v)
              values.m_dept_id=v
              //$log(values.m_dept_id)
            }"
            :errorText="formErrors.m_dept_id?'failed':''"
            :hints="formErrors.m_dept_id"
            displayField="nama"
            valueField="id"
            :api="{
                url: `${store.server.url_backend}/operation/m_dept`,
                headers: {
                  //'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  simplest:true,
                  single:true,
                  scopes: 'filterDivisi',
                  divisi_id: values.m_divisi_id ?? null,
                  transform:false,
                }
            }"
            fa-icon="search" :check="true" />
      </div>
      <div>
        <FieldSelect
            class="w-full !mt-3"
            label="Posisi"
            placeholder="Pilih Posisi"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.m_posisi_id" 
            :check="false"
            @input="(v)=>{
              //$log(v)
              values.m_posisi_id=v
              //$log(values.m_posisi_id)
            }"
            :errorText="formErrors.m_posisi_id?'failed':''"
            :hints="formErrors.m_posisi_id"
            displayField="desc_kerja"
            valueField="id"
            :api="{
                url: `${store.server.url_backend}/operation/m_posisi`,
                headers: {
                  //'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }"
            fa-icon="search" :check="true" />
      </div>
      <div>
        <FieldSelect
            class="w-full !mt-3"
            label="Zona"
            placeholder="Pilih Zona"
            :bind="{ disabled: !actionText, clearable:false }"
            :value="values.m_zona_id" 
            :check="false"
            @input="(v)=>{
              //$log(v)
              values.m_zona_id=v
              //$log(values.m_zona_id)
            }"
            :errorText="formErrors.m_zona_id?'failed':''"
            :hints="formErrors.m_zona_id"
            displayField="nama"
            valueField="id"
            :api="{
                url: `${store.server.url_backend}/operation/m_zona`,
                headers: {
                  //'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                },
                params: {
                  simplest:true,
                  single:true,
                  where:`this.is_active='true'`,
                  transform:false,
                }
            }"
            fa-icon="search" :check="true" />
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
    </div><hr>
        <div class="flex items-stretch mt-2 mx-4">
          <h3 class="font-semibold">Detail Biaya</h4><br>
          <div class="content-end flex">
            <button @click="addRow" type="button"  v-show="actionText" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold ml-2 px-2 py-1 rounded-md flex items-center justify-center mt-2">
                <icon fa="plus" size="sm mr-0.5"/> Tambah Baris
            </button>
            <button @click="deleteAll" type="button"  v-show="actionText" class="bg-red-500 hover:bg-red-600 text-white font-semibold ml-2 px-2 py-1 rounded-md flex items-center justify-center mt-2">
                <icon fa="trash" size="sm mr-0.5"/> Hapus Semua
            </button>
          </div>
        </div>

        <div class="mx-4">
          <table class="w-full overflow-x-auto mt-2">
            <thead>
              <tr class="border-y">
                <td class="text-black font-bold text-capitalize px-2 text-left w-[5%]">No.</td>
                <td class="text-black font-bold text-capitalize px-2 text-left w-[20%]">Tipe <label class="text-red-500 space-x-0 pl-0">*</label></td>
                <td class="text-black font-bold text-capitalize px-0 text-left w-[30%]">Total Biaya <label class="text-red-500 space-x-0 pl-0">*</label></td>
                <td class="text-black font-bold text-capitalize px-2 text-left w-[30%]">Keterangan</td>
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
                      :value="trx_dtl.items[i].tipe_id" 
                      :check="false"
                      @input="(v)=>{
                        trx_dtl.items[i].tipe_id=v
                      }"
                      @update:valueFull="(v)=>{
                        trx_dtl.items[i].tipe=v
                      }"
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
                            where:`this.is_active='true' and this.group = 'TIPE SPD'`,
                            transform:false,
                          }
                      }"
                      fa-icon="search" :check="true" />
                  </td>
                  <td class="text-right px-1">
                    <div class="flex items-center">
                      <FieldNumber
                        :bind="{ readonly: !actionText || (trx_dtl.items[i].tipe?.value == 'Transport' || trx_dtl.items[i]['tipe.value'] == 'Transport') && actionText}"
                        :value="trx_dtl.items[i].total_biaya" 
                        @input="(v)=>trx_dtl.items[i].total_biaya=v"
                        :check="false"
                        class="p-0 !m-0"
                      />   
                        <button v-show="(trx_dtl.items[i].tipe?.value == 'Transport' || trx_dtl.items[i]['tipe.value'] == 'Transport') && actionText" @click="openSub(i)" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold ml-2 px-2 py-1 rounded-sm flex items-center justify-center mt-2 " @click="clearAll">
                          <icon fa="folder" size="sm">
                        </button>  
                    </div>
                  </td>
                  <td class="text-center px-1">   
                    <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
                        :value="trx_dtl.items[i].keterangan" 
                        @input="v=>trx_dtl.items[i].keterangan=v" 
                        :check="false"
                        type="textarea"
                        label=""
                        placeholder="Keterangan"
                    />
                  </td>
                  <td>
                    <button v-show="actionText" @click="deleteDetail(a)" type="button" class="bg-red-500 hover:bg-red-600 text-white font-semibold ml-2 px-2 py-1 rounded-md flex items-center justify-center mt-4">
                      <icon fa="trash" size="sm mr-0.5"/>
                    </button>
                  </td>
                </tr>
            </tbody>
          </table>
        </div>
    <!-- Modal Overlay (background) -->

    <!-- Modal Content -->
      <div v-show="modalOpen" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white  w-[70%] mx-auto rounded shadow-lg z-50 overflow-y-auto">
          <div class="modal-content py-4 text-left px-6">
            <!-- Modal Header -->
          <div class="modal-header flex items-center justify-between flex-wrap">
            <div class="flex items-center">
              <h3 class="text-xl font-semibold ml-2">Sub Detail Transportasi</h3>
              <button @click="addRowSub" type="button" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold ml-2 px-2 py-1 rounded-md flex items-center justify-center mt-2">
                <icon fa="plus" size="sm mr-0.5"/> Tambah Baris
              </button>
            </div>
            <div>
              <h4 class="text-md font-semibold text-green-600">Total : {{total_sub_text}}</h4>
            </div>
          </div>

            <!-- Modal Body -->
            <div class="modal-body">
               <table class="w-full overflow-x-auto mt-2">
                  <thead>
                    <tr class="border-y">
                      <td class="text-black font-bold text-capitalize px-2 text-left w-[5%]">No.</td>
                      <!-- <td class="text-black font-bold text-capitalize px-0 text-left w-[20%]">Zona Tujuan <label class="text-red-500 space-x-0 pl-0">*</label></td> -->
                      <td class="text-black font-bold text-capitalize px-0 text-left w-[15%]">Jenis Transportasi <label class="text-red-500 space-x-0 pl-0">*</label></td>
                      <td class="text-black font-bold text-capitalize px-2 text-left w-[20%]">Transportasi  <label class="text-red-500 space-x-0 pl-0">*</label></td>
                      <td class="text-black font-bold text-capitalize px-2 text-left w-[20%]">Biaya Transportasi  <label class="text-red-500 space-x-0 pl-0">*</label></td>
                      <td class="text-black font-bold text-capitalize px-2 text-left w-[15%]">Keterangan</td>
                      <td class="text-black font-bold text-capitalize px-2 text-left w-[5%]">Aksi</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(a,i) in trx_dtl_sub.items" :key="a.id">
                      <td class="text-center">{{ i+1 }}</td>
                      <td class="text-center">
                        <FieldSelect
                          class="w-full py-1 !mt-0"
                          :bind="{ disabled: !actionText, clearable:false }"
                          :value="trx_dtl_sub.items[i].jenis_transport_id" 
                          @input="(v)=>{
                            trx_dtl_sub.items[i].jenis_transport_id=v
                          }"
                          :check="false"
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
                                where:`this.is_active='true' and this.group='JENIS TRANSPORTASI'`,
                                transform:false,
                              }
                          }"
                          fa-icon="search" :check="true" />
                      </td>
                      <td class="text-center">   
                        <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
                            :value="trx_dtl_sub.items[i].nama_transport" 
                            @input="v=>trx_dtl_sub.items[i].nama_transport=v" 
                            :check="false"
                            type="text"
                            label=""
                            placeholder="Nama Transportasi"
                        />
                      </td>
                      <td class="text-center">   
                         <FieldNumber
                          :bind="{ readonly: !actionText}"
                          :value="trx_dtl_sub.items[i].biaya_transport" 
                          @input="(v)=>{
                            trx_dtl_sub.items[i].biaya_transport=v
                            countSub()
                          }"
                          :check="false"
                          class="p-0 !m-0"
                        />  
                      </td>
                      <td class="text-center">   
                        <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
                            :value="trx_dtl_sub.items[i].keterangan" 
                            @input="v=>trx_dtl_sub.items[i].keterangan=v" 
                            :check="false"
                            type="textarea"
                            label=""
                            placeholder="Keterangan"
                        />
                      </td>
                      <button v-show="actionText" @click="deleteSub(a)" type="button" class="bg-red-500 hover:bg-red-600 text-white font-semibold ml-2 px-2 py-1 rounded-md flex items-center justify-center mt-4">
                        <icon fa="trash" size="sm mr-0.5"/>
                      </button>
                    </tr>
                </tbody>
            </table>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer flex justify-end mt-2">
              <button @click="closeModal" class="modal-button bg-yellow-500 hover:bg-yellow-600 text-white font-semibold ml-2 px-2 py-1 rounded-sm">
                Tutup
              </button>
              <button @click="saveSub" class="modal-button bg-green-500 hover:bg-green-600 text-white font-semibold ml-2 px-2 py-1 rounded-sm">
                Simpan
              </button>
            </div>
          </div>
        </div>
      </div>
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