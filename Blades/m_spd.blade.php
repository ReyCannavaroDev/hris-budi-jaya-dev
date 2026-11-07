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
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Form Surat Perjalanan Dinas
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
        <!-- START COLUMN -->
        <!-- <div>
          <label for="Direktorat" class="font-semibold select-all">Direktorat <span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.direktorat" :errorText="formErrors.direktorat?'failed':''"
              @input="v=>values.direktorat=v" :hints="formErrors.direktorat" 
              :check="false"
              label=""
              placeholder=""
          />
        </div> -->
        <div>
          <label class="font-semibold">Kode<span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.kode" :errorText="formErrors.kode?'failed':''"
              @input="v=>values.kode=v" :hints="formErrors.kode" 
              :check="false"
              label=""
              placeholder=""
          />
        </div>
        <div>
          <label class="font-semibold">Divisi<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
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
          <label class="font-semibold">Departemen<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
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
          <label class="font-semibold">Posisi<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
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
          <label class="font-semibold">Zona<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
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
          <label class="font-semibold">Keterangan <span class="text-red-500 space-x-0 pl-0"></span></label>
          <FieldX :bind="{ readonly: !actionText }" class="w-full py-2 !mt-0"
              :value="values.desc" :errorText="formErrors.desc?'failed':''"
              @input="v=>values.desc=v" :hints="formErrors.desc" 
              :check="false"
              type="textarea"
              label=""
              placeholder="Tuliskan Keterangan"
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
              <i class="text-red-500">Inactive</i>
            </div>
            <div class="flex-auto">
              <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
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
        <!-- END COLUMN -->
      </div>
      <div class="col-span-8 md:col-span-12 mt-5">
      <hr>
        <div class="flex items-stretch mt-2">
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
                      <!-- <td class="text-center">
                        <FieldSelect
                          class="w-full py-1 !mt-0"
                          :bind="{ disabled: !actionText, clearable:false }"
                          :value="trx_dtl_sub.items[i].zona_tujuan_id" 
                          @input="(v)=>{
                            trx_dtl_sub.items[i].zona_tujuan_id=v
                          }"
                          :check="false"
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
                      </td> -->
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
        <!-- <TableStatic
            customClass="h-50vh"
            ref="detail" 
            :key="detailKey"
            @input="v=>trx_dtl=v"
            :value="trx_dtl"
            :columns="[{
                headerName: 'No',
                cellRenderer: !actionText?null:'ButtonGrid',
                valueGetter:p=>p.node.rowIndex + 1,
                cellRendererParams: !actionText?null:{
                  showValue: true,
                  icon: 'times',
                  class: 'btn-text-danger',
                  click:(app)=>{
                    if (app && app.params) {
                      const row = app.params.node.data
                      swal.fire({
                        icon: 'warning', showDenyButton: true,
                        text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
                      }).then((res) => {
                        if (res.isConfirmed) {
                          trx_dtl.splice(app.params.node.rowIndex, 1)
                          app.params.api.applyTransaction({ remove: [app.params.node.data] })
                        }
                      })
                    }
                  }
                },
                width: 60,
                sortable: false, resizable: true, filter: false, wrapText: true,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                headerName: 'Biaya',
                field: 'biaya',
                sortable: false, resizable: true, filter: false, wrapText: true,
                cellClass: ['!border-gray-200', 'justify-center'],
                cellRenderer: ()=>{
                  return `<button class='p-4 bg-red-500'>CHECK</button>`
                },
                cellEditorParams: {
                  input(val, api){
                    api.data['colname']=val
                  }
                }
              },
              {
                flex: 1,
                headerName: 'Tipe',
                field: 'tipe',
                sortable: false, resizable: true, filter: false, wrapText: true,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              {
                flex: 1,
                headerName: 'Keterangan',
                field: 'keterangan',
                sortable: false, resizable: true, filter: false, wrapText: true,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              {
                flex: 1,
                headerName: 'Status',
                field: 'status',
                sortable: false, resizable: true, filter: false, wrapText: true,
                cellClass: ['!border-gray-200', 'justify-center'],
              }
              ]"
            >
          </TableStatic> -->
        
        </div>
        <!-- ACTION BUTTON START -->
        <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Batal
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
        </div>
    </div>
  </div>
</div>
@endverbatim
@endif