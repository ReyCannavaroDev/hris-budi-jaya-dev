
@verbatim
<div class="flex flex-col gap-y-3">
  
  <div class="flex p-2.5 items-center gap-4">
    <p>Show Data :</p>
    <div class="flex gap-2">
      <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Aktif</button>
      <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
      <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Inaktif</button>
    </div>
  </div>

  
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
    
      <div class="grid grid-cols-1 md:grid-cols-2">
        <!-- Content for the first column -->
        <div class="col-span-2">
          <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions" class="!p-0">
            <template #header>
              <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded py-1 px-2">
                <icon fa="plus" />
                Tambah File
              </RouterLink>
            </template>
          </TableApi>
        </div>
        <div class="col-span-2">
          <TableStatic
            customClass="h-50vh"
            ref="detail" 
            :value="detailArr" 
            @input="onRetotal"
            :columns="[{
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
                flex: 1,
                headerName: 'Nama Menu',
                field: 'menu',
                editable: actionText?true:false,
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200'],
                cellEditor: 'FieldNumber',
                cellEditorParams: {
                  input(val, api){
                    api.data['colname']=val
                  }
                }
              },
              {
                flex: 1,
                headerName: 'Nama Projek',
                field: 'project',
                editable: actionText?true:false,
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200'],
                cellEditor: 'FieldNumber',
                cellEditorParams: {
                  input(val, api){
                    api.data['colname']=val
                  }
                }
              },
              {
                flex: 1,
                headerName: 'Nama Modul',
                field: 'modul',
                editable: actionText?true:false,
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200'],
                cellEditor: 'FieldNumber',
                cellEditorParams: {
                  input(val, api){
                    api.data['colname']=val
                  }
                }
              },]"
            >
            <template #header></template>
          </TableStatic>
          
        </div>
        <div class="p-4">
          <FieldX :bind="{ readonly: false }" :value="values.username" @input="v=>values.username=v" placeholder="Username" fa-icon="user" :check="false"/>
        </div>
        <!-- Content for the second column -->
        <div class="p-4"> 
          <FieldX :bind="{ readonly: false }" :value="values.password" @input="v=>values.password=v"  type="password" placeholder="Password" fa-icon="lock" :check="false"/>
        </div>
        <div class="p-4">
          <FieldX :bind="{ readonly: false }" type="time" placeholder="Masukan Jam" fa-icon="time" :check="false"/>
        </div>
        <div class="p-4"> 
          <FieldGeo
            :bind="{ readonly: false }"
            :center="[-7.3244677, 112.7550714]" placeholder="Pilih Lokasi" fa-icon="map-marker-alt" :check="false"
          />
        </div>
        <div class="p-4"> 
          <FieldNumber
            :bind="{ readonly: false }" placeholder="Masukan Nominal Uang" fa-icon="dollar" :check="false"
          />
        </div>

        <div class="p-4"> 
          <FieldSelect
            :value="values.dropdown"
            @input="v=>values.dropdown=v" 
            valueField="id" displayField="menu"
            :api="{
                url: `${store.server.url_backend}/operation/m_menu`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  single: true,
                  join: false,
                }
            }"
            placeholder="Pilih Dropdown" fa-icon="bookmark" :check="false"
          />
        </div>

        <div class="p-4"> 
          <FieldPopup
            valueField="id" displayField="menu"
            :api="{
              url: `${store.server.url_backend}/operation/m_menu`,
              headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
              params: {
                simplest:true,
              }
            }"
            placeholder="Pilih Pop Up" fa-icon="Pilih Pop Up" :check="false" 
            :columns="[{
              headerName: 'No',
              valueGetter:(p)=>p.node.rowIndex + 1,
              width: 60,
              sortable: false, resizable: false, filter: false,
              cellClass: ['justify-center', 'bg-gray-50']
            },
            {
              flex: 1,
              field: 'menu',
              headerName:  'Nama Menu',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            {
              flex: 1,
              field: 'path',
              headerName:  'Path Menu',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },
            ]"
          />
        </div>


        <div class="p-4">
          <FieldX :bind="{ readonly: false }" type="date" :value="values.date"
            @input="v=>values.date=v" 
            placeholder="Pilih Tanggal" fa-icon="calender" :check="false"
          />
        </div>
        <div class="p-4">
          <FieldX :bind="{ readonly: false }" type="textarea" :value="values.textarea"
            @input="v=>values.textarea=v" 
            placeholder="Masukan Kata Kata" fa-icon="edit" :check="false"
          />
        </div>
        <div class="p-4">
          <FieldUpload
            :reducerDisplay="val=>!val?null:val.split(':::')[val.split(':::').length-1]"
            :api="{
              url: `${store.server.url_backend}/operation/m_menu/upload`,
              headers: { Authorization: `${store.user.token_type} ${store.user.token}`},
              params: { field: 'name' },
              onsuccess: response=>response,
              onerror:(error)=>{},
             }" placeholder="Masukan File" fa-icon="upload"
             accept="*" :check="false"  
          />
          
        </div>
        <div class="p-4 flex items-end">
          <ButtonMultiSelect
          title="Pop Up Multi Select"
          @add="onDetailAdd"
          :api="{
            url: `${store.server.url_backend}/operation/m_menu`,
            headers: {'Content-Type': 'Application/json', authorization: `${store.user.token_type} ${store.user.token}`},
            params: { simplest: true },
            onsuccess:(response)=>{
              response.data = [...response.data].map((dt)=>{
                Object.assign(dt,{
                  can_create: true, can_update: true, can_delete: true, can_read: true, role_id: values.role_id
                })
                return dt
              })
              response.page = 1
              response.hasNext = false
              return response
            }
          }"
            :columns="[{
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
              field: 'menu',
              headerName: 'Nama Menu',
              cellClass: ['border-r', '!border-gray-200', 'justify-center'],
              filter:false,
              flex: 1
            },
            {
              flex: 1,
              field: 'path',
              headerName:  'Path Menu',
              sortable: false, resizable: true, filter: 'ColFilter',
              cellClass: ['border-r', '!border-gray-200', 'justify-center']
            },]"
          >
            <div class="flex justify-center w-full h-full items-center px-2 py-1.5 text-xs rounded text-white bg-blue-500 hover:bg-blue-700 hover:bg-blue-600 transition-all duration-200">
              <icon fa="plus" size="sm mr-0.5"/> Pop Up Multi Select
            </div>
          </ButtonMultiSelect>
          <FieldSelect
            :bind="{ disabled: false,multiple:true }"
            :value="values.dropdown"
            @input="v=>values.dropdown=v" 
            valueField="id" displayField="menu"
            :api="{
                url: `${store.server.url_backend}/operation/m_menu`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  single: true,
                  join: false,
                }
            }"
            placeholder="Pilih Dropdown" fa-icon="bookmark" :check="false"
          />
          
        </div>
      
          <FieldX :bind="{ readonly: false }" :value="values.textarea"
            @input="v=>values.textarea=v" 
            placeholder="Masukan Kata Kata" :check="false"
          />
          <div class="min-h-[32.391px] w-full bg-white md:text-xs rounded input-target outline-none border !focus:border-blue-600 focus:shadow-md focus:bg-white transition-all duration-300 p-1">
              <icon fa="bookmark" v-if="dataArr.length === 0"/>
            <span class="inline-block bg-[#f0f0f0] text-[12px] mt-[4px] mx-[2px] px-[0.25em] rounded-[4px] border  border-gray-300 p-1" v-for="item in dataArr">
              {{item.menu}}
              <icon fa="times" @click="sliceArr(item)"class="text-[15px] font-semibold" style="cursor: pointer; color: red;"/>
            </span>
          </div>
      </div>
      <!--BUTTON-->

       <h1 class=" text-center text-2xl font-bold"> BUTTON </h1>
        <!-- Button Biru -->
<button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-xl flex items-center justify-center mt-2">
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus mr-2" viewBox="0 0 16 16">
    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
  </svg>
  Add to list
</button>

<!-- Button Kuning -->
<button class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-xl flex items-center justify-center mt-2">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text mr-2" viewBox="0 0 16 16">
  <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
  <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5L9.5 0zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
</svg>
  CVS Export
</button>

<!-- Button Hijau -->
<button class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-xl flex items-center justify-center mt-2">
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus mr-2" viewBox="0 0 16 16">
    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
  </svg>
  Tambah File
</button>

<!-- Button Merah -->
<button class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-xl flex items-center justify-center mt-2">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3 mr-2" viewBox="0 0 16 16">
  <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
</svg>
  Remove
</button>

    </div>
  </div>
</div>

@endverbatim