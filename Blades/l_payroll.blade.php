
@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Payroll
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-x-[60px] gap-y-[12px] px-4">
        <!-- START COLUMN -->
          <div>
            <label class="font-semibold">Tipe Export</label>
              <FieldSelect 
                :bind="{ readonly: !actionText, clearable: false }" 
                class="w-full py-2 !mt-0"
                :value="values.tipe" 
                :errorText="formErrors.tipe ? 'failed' : ''"
                @input="v => values.tipe = v" 
                :hints="formErrors.tipe" 
                :check="false"
                label=""
                :options="['Excel','HTML']"
                placeholder="Pilih Tipe Export"
                valueField="key" 
                displayField="key"
            />
          </div>
          <div >
            <label class="font-semibold">Pilih Final Gaji</label>
            <FieldPopup
            :value="values.f_id" 
            :errorText="formErrors.f_id ? 'failed' : ''"
            @input="v => values.f_id = v" 
            :hints="formErrors.f_id" 
            class="w-full py-2 !mt-0"
            valueField="id" 
            displayField="nomor"
            :api="{
                url: `${store.server.url_backend}/operation/t_final_gaji`,
                headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                params: {
                  simplest:true,
                  searchfield: 'this.desc, this.nomor, this.periode_awal, this.periode_akhir'
                }
              }"
              placeholder="" label="" :check="false" 
              :columns="[{
                headerName: 'No',
                valueGetter:(p)=>p.node.rowIndex + 1,
                width: 60,
                sortable: false, resizable: false, filter: false,
                cellClass: ['justify-center', 'bg-gray-50']
              },
              {
                flex: 1,
                field: 'nomor',
                wrapText:true,
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-end']
              },
              {
                flex: 1,
                field: 'desc',
                wrapText:true,
                headerName: 'Deskripsi',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                field: 'periode_awal',
                wrapText:true,
                headerName: 'Periode Awal',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              {
                flex: 1,
                wrapText:true,
                field: 'periode_akhir',
                headerName: 'Periode Akhir',
                sortable: false, resizable: true, filter: 'ColFilter',
                cellClass: ['border-r', '!border-gray-200', 'justify-start']
              },
              ]"
          />
          </div>
          <div>
            <label class="font-semibold">Direktorat</label>
              <FieldSelect 
                :bind="{ readonly: !actionText }" 
                class="w-full py-2 !mt-0"
                :value="values.m_dir_id" 
                :errorText="formErrors.m_dir_id ? 'failed' : ''"
                @input="v => values.m_dir_id = v" 
                :hints="formErrors.m_dir_id" 
                :check="false"
                label=""
                @update:valueFull="(objVal)=>{
                  values.m_divisi_id = null
                }"
                placeholder="Pilih Direktorat"
                valueField="id" 
                displayField="nama"
                :api="{
                    url: `${store.server.url_backend}/operation/m_dir`,
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
            <label class="font-semibold">Divisi</label>
              <FieldSelect 
                :bind="{ readonly: !actionText }" 
                class="w-full py-2 !mt-0"
                :value="values.m_divisi_id" 
                :errorText="formErrors.m_divisi_id ? 'failed' : ''"
                @input="v => values.m_divisi_id = v" 
                :hints="formErrors.m_divisi_id" 
                :check="false"
                label=""
                placeholder="Pilih Divisi"
                valueField="id" 
                displayField="nama"
                :api="{
                    url: `${store.server.url_backend}/operation/m_divisi`,
                    headers: { 
                        'Content-Type': 'Application/json', 
                        Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                        single: true,
                        join: false,
                        where: `this.m_dir_id=${values.m_dir_id ?? 0} AND this.is_active='true'`
                    }
                }"
            />
          </div>
          <div>
            <label class="font-semibold">Departemen</label>
              <FieldSelect 
                :bind="{ readonly: !actionText }" 
                class="w-full py-2 !mt-0"
                :value="values.m_dept_id" 
                :errorText="formErrors.m_dept_id ? 'failed' : ''"
                @input="v => values.m_dept_id = v" 
                :hints="formErrors.m_dept_id" 
                :check="false"
                label=""
                placeholder="Pilih Departement"
                valueField="id" 
                displayField="nama"
                :api="{
                    url: `${store.server.url_backend}/operation/m_dept`,
                    headers: { 
                        'Content-Type': 'Application/json', 
                        Authorization: `${store.user.token_type} ${store.user.token}`
                    },
                    params: {
                        single: true,
                        join: false,                    
                        where: `m_divisi_id=${values.m_divisi_id ?? 0} AND this.is_active='true'`
                    }
                }"
            />
          </div>
          <div>
            <label class="font-semibold">Posisi</label>
              <FieldSelect 
                :bind="{ readonly: !actionText }" 
                class="w-full py-2 !mt-0"
                :value="values.m_posisi_id" 
                :errorText="formErrors.m_posisi_id ? 'failed' : ''"
                @input="v => values.m_posisi_id = v" 
                :hints="formErrors.m_posisi_id" 
                :check="false"
                label=""
                placeholder="Pilih Posisi"
                valueField="id" 
                displayField="desc_kerja"
                :api="{
                    url: `${store.server.url_backend}/operation/m_posisi`,
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