@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi class="rounded-2xl" ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'"
        :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
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
    <div class="flex flex-col border rounded-2xl shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <!-- HEADER START -->
      <div class="flex items-center mb-2 border-b pb-4">
        <button class="py-1 px-2 rounded transition-all text-blue-900 bg-white border border-blue-900 duration-300 hover:text-white hover:bg-blue-600" @click="onBack">
            <icon fa="arrow-left" size="sm"/>
            
          </button>
        <h2 class="mx-4 font-sans text-xl flex justify-left font-bold">
          {{actionText==='Edit'?'Ubah':actionText}} Approval
        </h2>

      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid <md:grid-cols-1 grid-cols-2 grid-flow-row gap-x-10 gap-y-2">

        <div>
          <label class="text-sm">Nama<label class="text-red-500 space-x-0 pl-0"> *</label></label>
          <FieldX class="w-full py-2 !mt-0" :bind="{ readonly: !actionText }" label="" :value="values.nama"
            :errorText="formErrors.nama?'failed':''" placeholder="Tuliskan Nama" @input="v=>values.nama=v"
            :hints="formErrors.nama" :check="false" />
        </div>
        <div>
          <label class="text-sm">Menu<label class="text-red-500 space-x-0 pl-0"> *</label></label>
          <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
            :value="values.m_menu_id" @input="v=>values.m_menu_id=v" :errorText="formErrors.m_menu_id?'failed':''"
            @update:valueFull="(objVal)=>{
              values.m_dept_id = null
            }" label="" placeholder="Pilih Menu" :hints="formErrors.m_menu_id" :api="{
                url: `${store.server.url_backend}/operation/m_menu`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  where: `this.is_active = 'true'`,
                  selectfield: 'id,modul,menu'
                }
            }" valueField="id" displayField="menu" :check="false" />
        </div>
        <div>
          <label class="text-sm">Keterangan<label class="text-red-500 space-x-0 pl-0"> *</label></label>
          <FieldX class="w-full py-2 !mt-0" type="textarea" :bind="{ readonly: !actionText }" label=""
            :value="values.desc" :errorText="formErrors.desc?'failed':''" placeholder="Tuliskan Keterangan"
            @input="v=>values.desc=v" :hints="formErrors.desc" :check="false" />
        </div>
        <div class="grid grid-cols-12 items-start gap-y-2">
          <label class="col-span-12">Status <label class="text-red-500 space-x-0 pl-0">*</label></label>
          <input
            class="mr-2 h-3.5 !-mt-0 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
            type="checkbox"
            role="switch"
            id="is_active"
            :disabled="!actionText"
            v-model="values.is_active" />
        </div>
      </div>

      <div class="flex container-sm grid-cols-2 col justify-end right-0 mt-5">

        <button v-show="!actionText" class="py-[10px] px-[28px] rounded-lg transition-all text-white border  duration-300 hover:bg-red-900 bg-red-600" @click="onBack">
        Kembali
      </button>
        <button v-show="actionText" class="py-[10px] px-[28px] rounded-lg transition-all text-white border  duration-300 border-[#1CC2B9] text-[#1CC2B9] hover:bg-gray-100" @click="onBack">
        Cancel
      </button>
        <button v-show="actionText" class="mx-2 bg-hex-2DA96D text-white hover:bg-green-600 rounded-lg py-[10px] px-[28px] border border-hex-2DA96D " @click="onSave">
        Simpan
      </button>
      </div>

      <!-- FORM END -->

    </div>
  </div>
  <!-- DETAIL -->
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-4 py-3 w-full bg-white">
      <TableStatic customClass="h-50vh" ref="detail" :value="detailArr" @input="v=>detailArr=v" :columns="[{
                pinned:true,
                headerName: 'No',
                cellRenderer: !actionText?null:'ButtonGrid',
                valueGetter:p=>p.node.rowIndex + 1,
                cellRendererParams: !actionText?null:{
                  showValue: true,
                  icon: 'times',
                  class: 'btn-text-danger',
                  click:(app)=>{
                    if (app && app.params) {
                      if(app.params.rowIndex == 0 || app.params.node.data.level == 1){
                        swal.fire({
                          icon: 'warning',
                          text: `Data level 1 tipe MENGAJUKAN tidak dapat dihapus`,
                        })
                      return
                      }
                      const row = app.params.node.data
                      swal.fire({
                        icon: 'warning',
                        text: `Hapus Baris ${app.params.node.rowIndex-(-1)}?`,
                        showDenyButton: true
                      }).then((res) => {
                        if (res.isConfirmed) {
                          app.params.api.applyTransaction({ remove: [app.params.node.data] })
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
                width:70,
                field:'level',
                cellClass: [ 'border-r', '!border-gray-200','justify-center'],
                sortable: true, resizable: false, filter: false,
                cellEditor: 'FieldNumber',
                editable: false,
              },
              {
                width:150,
                field:'tipe',
                cellClass: [ 'border-r', '!border-gray-200','justify-center'],
                autoHeight:true,wrapText: true,
                sortable: false, resizable: false, filter: false,
                cellEditor: 'FieldSelect',
                cellEditorParams: {
                  options:['MENGETAHUI','MENYETUJUI'],
                  bind: { clearable:true },
                  input(v){
                    values.tipe=v
                  }
                },
                editable(params) {
                  if((params.node.rowIndex == 0 || params.data.level) == 1){
                    swal.fire({
                      icon: 'warning',
                      text: `Data level 1 tipe MENGAJUKAN tidak dapat diubah`,
                    })
                    return actionText ? actionText :false
                  }else{
                    return actionText ? actionText :false
                  }
                } 
              },
              {
                field:'m_role_id',
                headerName: 'Role',
                autoHeight:true,wrapText: true,
                sortable: false, resizable: false, filter: false,
                valueGetter:(p)=> p.node.data['m_role.name'],
                cellClass: [ 'border-r', '!border-gray-200'],
                cellEditor: 'FieldSelect',
                cellEditorParams: {
                  api: {
                    url: `${store.server.url_backend}/operation/m_role`,
                    headers: {
                      'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest:true,
                      transform:false,
                      join:false,
                      selectfield:`name,id`,
                      searchfield:'name'
                    }
                  },
                  displayField:'name',
                  valueField:'id',
                  bind: { clearable:true },
                  input(val, api){
                    api.data['m_role_id']=val.id
                    api.data['m_role.name']=val.name
                  }
                },
                editable: actionText?true:false,
              },
              {
                field:'default_user_id',
                headerName: 'User Action',
                autoHeight:true,wrapText: true,
                sortable: false, resizable: false, filter: false,
                valueGetter:(p)=> p.node.data['default_user.username'],
                cellClass: [ 'border-r', '!border-gray-200'],
                cellEditor: 'FieldSelect',
                cellEditorParams: {
                  api: {
                    url: `${store.server.url_backend}/operation/default_users`,
                    headers: {
                      'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                      simplest:true,
                      transform:false,
                      join:false,
                      selectfield:`username,id`,
                      searchfield:'username'
                    }
                  },
                  displayField:'username',
                  valueField:'id',
                  bind: { clearable:true },
                  input(val, api){
                    api.data['default_user.username']=val.text
                    api.data['default_user_id']=val.id
                  }
                },
                editable: actionText?true:false,
              },
              {
                width:100,
                headerName: 'Full Approval?',
                field:'is_full_approve',
                cellClass: [ 'border-r', '!border-gray-200','justify-center'],
                sortable: true, resizable: false, filter: false,
                cellEditor: 'FieldNumber',
                editable: false,
                cellRenderer: 'ButtonGridCheck',
                cellRendererParams: !actionText?null:{
                  readonly: !actionText,
                  change:(app, isChecked)=>{
                    if(isChecked){
                      app.params.node.data['is_full_approve'] = isChecked
                      app.params.api.applyTransaction({ update: [app.params.node.data] })
                    }else{
                      app.params.node.data['is_full_approve'] = false
                      app.params.api.applyTransaction({ update: [app.params.node.data] })
                    }
                  }
                }
              }
              ,]">

        <!-- =====ADD ITEMS MULTI -->
        <template #header>
          <div class="flex items-center flex-grow px-2 !select-none">
            <icon fa="list-ol" size="md mr-4" />
            Detail Approval (Total: {{detailArr.length}})
          </div>

          <div class="gap-x-2 ml-auto flex items-center !select-none" v-if="actionText">
            <button title="Add Row"
                        @click="onDetailAdd"
                        class="flex justify-center w-18 focus:(!outline-none) items-center rounded px-2 py-1.5 text-xs hover:text-white hover:bg-green-600 transition-all duration-200">
                        <icon fa="plus" size="mr-0.5"/> Add
                      </button>

            <button title="Clear All"
                        @click="clearDetailArr"
                        class="flex justify-center w-18 focus:(!outline-none) items-center rounded px-2 py-1.5 text-xs hover:text-white hover:bg-red-600 transition-all duration-200">
                        <icon fa="times" size="mr-0.5"/> Clear
                      </button>
          </div>

        </template>
      </TableStatic>
    </div>
  </div>

</div>
@endverbatim
@endif