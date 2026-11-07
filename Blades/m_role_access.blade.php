@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
  </TableApi>
</div>
@else

@verbatim

<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Form Role Akses
        </h1>
        <hr>
      </div>
      <div class="grid grid-cols-2 gap-3">
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
        <div >
          <label class="font-semibold">Pengguna <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: true }" class="py-2 !mt-0" :value="values.name"
            :errorname="formErrors.name?'failed':''" @input="v=>values.name=v" :hints="formErrors.name"
            :check="false" />
        </div>
        <!-- END COLUMN -->
      </div>

      <div class="p-4 flex items-end" v-if="actionText">
        <ButtonMultiSelect title="Tambah Akses" @add="onDetailAdd" :api="{
            url: `${store.server.url_backend}/operation/m_role`,
            headers: {'Content-Type': 'Application/json', authorization: `${store.user.token_type} ${store.user.token}`},
            params: { 
              simplest: true,
              where: 'm_role.is_active = true'  
            },
            onsuccess:(response)=>{
              response.data = [...response.data].map((dt)=>{
                Object.assign(dt,{
                  can_create: true, can_update: true, can_delete: true, can_read: true, role_id: values.role_id, can_verify : false
                })
                return dt
              })
              response.page = 1
              response.hasNext = false
              return response
            }
          }" :columns="[{
              checkboxSelection: true,
              headerCheckboxSelection: true,
              headerName: 'No',
              valueGetter:(params)=>{
                return ''
              },
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              pinned: false,
              field: 'name',
              headerName: 'Role',
              cellClass: ['border-r', '!border-gray-200', 'justify-center'],
              filter:false,
              flex: 1
            },
            ]">
          <div
            class="flex justify-center w-full h-full items-center px-2 py-1.5 text-xs rounded text-white bg-blue-500 hover:bg-blue-700 hover:bg-blue-600 transition-all duration-200">
            <icon fa="plus" size="sm mr-0.5" /> Tambah Akses
          </div>
        </ButtonMultiSelect>

      </div>

      <div>
        <TableStatic customClass="h-50vh" ref="detail" :value="trx_dtl" @input="onRetotal" :columns="[{
                headerName: 'No',
                cellRenderer:'ButtonGrid',
                valueGetter:p=>p.node.rowIndex + 1,
                cellRendererParams:{
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
                          app.params.api.applyTransaction({ remove: [app.params.node.data] })
                          trx_dtl.splice(app.params.node.rowIndex, 1)
                        }
                      })
                    }
                  }
                },
                width: 60,
                sortable: false, resizable: true, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                headerName: 'Role',
                field: 'name',
                flex: 1,
                editable: actionText?true:false,
                sortable: false, resizable: true, filter: false, editable: false,
                cellClass: ['!border-gray-200'],
                cellEditorParams: {
                  input(val, api){
                    api.data['colname']=val
                  }
                }
              },
              {
                headerName: 'Superadmin',
                field: 'is_superadmin',
                cellClass: ['justify-center', 'border-r','!border-gray-200', '!text-gray-500'],
                flex: 1, resizable: false, sortable: false, filter: false,
                cellRenderer: 'ButtonGridCheck',
                cellRendererParams: {
                  readonly: true,
                  change:(app, isChecked)=>{
                    app.params.node.data['can_read'] = isChecked
                    app.params.api.applyTransaction({ update: [app.params.node.data] })
                  }
                }
              }
              ]">
          <template #header></template>
        </TableStatic>

      </div>
      <!-- ACTION BUTTON START -->
      <div class="flex flex-row justify-end space-x-[20px] mt-[2em]">
        <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Kembali
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