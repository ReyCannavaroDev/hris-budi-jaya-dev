@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Laporan Hasil Borongan
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-x-[60px] gap-y-[12px] px-4">
        <!-- START COLUMN -->
          <div>
            <label class="font-semibold">Tipe Export</label>
              <FieldSelect 
                :bind="{ readonly: !actionText }" 
                class="w-full py-2 !mt-0"
                :value="values.tipe" 
                :errorText="formErrors.tipe ? 'failed' : ''"
                @input="v => values.tipe = v" 
                :hints="formErrors.tipe" 
                :check="false"
                label=""
                :options="['Excel','PDF','HTML']"
                placeholder="Pilih Tipe Export"
                valueField="key" 
                displayField="key"
            />
          </div>
          <div class="grid grid-cols-2 gap-2">
              <div>
                  <label class="font-semibold">Periode
                      <label class="text-red-500 space-x-0 pl-0"></label>
                  </label>
                  <FieldX 
                      type="date"
                      :bind="{ readonly: false }" 
                      class="w-full py-2 !mt-0" 
                      :value="values.periode_from" 
                      label="" 
                      placeholder="DD/MM/YY" 
                      :errorText="formErrors.periode_from?'failed':''"
                      @input="v=>values.periode_from=v" 
                      :hints="formErrors.periode_from" 
                      :check="false"
                  />
              </div>
              <div>
                  <FieldX 
                      type="date"
                      :bind="{ readonly: false }" 
                      class="w-full py-2 !mt-5" 
                      :value="values.periode_to" 
                      label="" 
                      placeholder="DD/MM/YY" 
                      :errorText="formErrors.periode_to?'failed':''"
                      @input="v=>values.periode_to=v" 
                      :hints="formErrors.periode_to"  
                      :check="false"
                  />
              </div>
          </div>
          
          <div>
            <label class="font-semibold">Group Karyawan</label>
              <FieldSelect 
                :bind="{ readonly: !actionText }" 
                class="w-full py-2 !mt-0"
                :value="values.m_kary_group_id" 
                :errorText="formErrors.m_kary_group_id ? 'failed' : ''"
                @input="v =>{
                 values.m_kary_group_id = v
                 if(!v){
                  delete values.label
                 }
                }
                 
                 " 
                :hints="formErrors.m_kary_group_id" 
                :check="false"
                label=""
                @update:valueFull="(objVal)=>{
                  values.label=objVal.nama
                }"
                placeholder="Pilih Group Karyawan"
                valueField="id" 
                displayField="nama"
                :api="{
                    url: `${store.server.url_backend}/operation/m_kary_group`,
                    headers: { 
                        'Content-Type': 'Application/json', 
                        Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                        single: true,
                        join: false,                    
                        where: `this.is_active='true'`
                    }
                }"
            />
          </div>
          <div>
            <label class="font-semibold">Nama Karyawan</label>
            <FieldPopup
            :value="values.m_kary_id" 
            :errorText="formErrors.m_kary_id ? 'failed' : ''"
            @input="v => values.m_kary_id = v" 
            :hints="formErrors.m_kary_id" 
            class="w-full py-2 !mt-0"
            valueField="id" 
            displayField="nama_lengkap"
            :api="{
                url: `${store.server.url_backend}/operation/m_kary`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  searchfield: 'this.nik, this.nama_lengkap, this.nama_depan, this.nama_belakang, m_zona.nama, m_dir.nama, m_divisi.nama, m_dept.nama'
                }
              }"
              placeholder="Cari Nama Karyawan" label="" :check="false" 
              :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'nik',
                wrapText:true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-end']
              },
              {
                flex: 1,
                field: 'nama_lengkap',
                wrapText:true,
                headerName: 'Nama Karyawan',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'm_zona.nama',
                wrapText:true,
                headerName: 'Zona',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'm_dir.nama',
                headerName: 'Direktorat',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'm_divisi.nama',
                headerName: 'Divisi',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'm_dept.nama',
                headerName: 'Departemen',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              ]"
          />
          </div>
      </div>
        <div class="flex flex-row justify-end space-x-[20px] mt-[1em]">
          <button @click="onGenerate" class="bg-green-600 hover:bg-green-800 duration-300 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            {{ values.tipe?.toLowerCase() === 'html' ? 'View' : 'Export' }}
          </button>
        </div>
        <!-- END COLUMN -->
        <!-- ACTION BUTTON START -->
        <div class="overflow-x-auto mt-6 mb-4 px-4" v-show="exportHtml">
          <hr>
          <div id="exportTable">
          </div>
        </div>
      
      </div>
    </div>
  </div>
</div>
@endverbatim