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
          <h1 class="text-20px font-bold">Form Role Akses</h1>
          <p class="text-gray-100">Master Role Akses</p>
        </div>
      </div>
    </div>
    <div class="p-4 grid <md:grid-cols-1 grid-cols-3 gap-2 ">
      <!-- START COLUMN -->
      <div>
        <FieldX :bind="{ readonly: true }" class="w-full !mt-3" :value="values.name"
            :errorname="formErrors.name?'failed':''" @input="v=>values.name=v" :hints="formErrors.name"
            :check="false" />
      </div>
      <!-- END COLUMN -->
      <!-- ACTION BUTTON START -->
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

      <div class="mx-4">
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