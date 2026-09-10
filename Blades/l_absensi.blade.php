
@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Laporan Absensi
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-x-[60px] gap-y-[12px] px-4">
        <!-- START COLUMN -->
        <!-- TIPE REPORT -->
          <div class="col-span-2">

            <label class="font-semibold">Tipe Report</label>
              <FieldSelect 
                :bind="{ readonly: !actionText }" 
                class="w-full py-2 !mt-0"
                :value="values.tipe_report" 
                :errorText="formErrors.tipe_report ? 'failed' : ''"
                @input="v => values.tipe_report = v"
                @update:valueFull="(objVal)=>{
                  resetValuesPeriode()
                }" 
                :hints="formErrors.tipe_report" 
                :check="false"
                label=""
                :options="['Rekap Harian','Rekap','Detail']"
                placeholder="Pilih Tipe Report Absensi"
                valueField="key" 
                displayField="key"
            />
          </div>
          <!-- TIPE EXPORT -->
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
          <!-- PERIODE -->
          <div class="grid grid-cols-2 gap-2">
            <div v-if="values.tipe_report == 'Rekap Harian'">
              <label class="font-semibold">Tanggal</label>
              <FieldX :bind="{ readonly: openDateSelected ? true : false , required: true}" 
                class="w-full py-2 !mt-0"
                :value="values.date" 
                :check="false" 
                type="date" 
                label=""
                @input="(v)=>{
                  values.date = v
                }" />
            </div>
            <div v-else v-show="values.tipe_report === 'Rekap' || !values.tipe_report">
                <label class="font-semibold">Periode
                    <label class="text-red-500 space-x-0 pl-0"></label>
                </label>
                <FieldX :bind="{ readonly: openDateSelected ? true : false , required: true}" 
                  class="w-full py-2 !mt-0"
                  :value="values.periode_from" 
                  :check="false" 
                  type="month" 
                  label=""
                  @input="(v)=>{
                    values.periode_from = v
                  }" />
            </div>
            <div v-show="values.tipe_report === 'Rekap' || !values.tipe_report">
                <FieldX :bind="{ readonly: openDateSelected ? true : false , required: true}" 
                  class="w-full py-2 !mt-5"
                  :value="values.periode_to" 
                  :check="false" 
                  type="month" 
                  label=""
                  @input="(v)=>{
                    values.periode_to = v
                  }" />
            </div>
            <div class="col-span-2" v-show="values.tipe_report === 'Detail'">
                <label class="font-semibold">Periode
                </label>
                <FieldX :bind="{ readonly: openDateSelected ? true : false , required: true}" 
                  class="w-full py-2 !mt-0"
                  :value="values.periode" 
                  :check="false" 
                  type="month" 
                  label=""
                  @input="(v)=>{
                    values.periode = v
                }" />
            </div>
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
                        where: `this.is_active='true'`
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
          <div id="exportTable" class="w-[100%] mt-6 h-screen overflow-auto">
          </div>
        </div>

      </div>
      
    </div>
    
  </div>
</div>
@endverbatim