
@verbatim
<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Laporan Jadwal Kerja
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-x-[60px] gap-y-[12px] px-4">
        <!-- START COLUMN -->
          <div>
            <label class="font-semibold">Tipe Export</label>
              <FieldSelect 
                :bind="{ readonly: !actionText }" 
                class="!mt-0 w-full"
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
          <div >
           <label class="col-span-12">Tipe Jam Kerja<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <FieldSelect
                  :bind="{ clearable:false }" 
                  class="!mt-0 w-full"
                  :value="values.tipe_jam_kerja_id" 
                  @input="v => values.tipe_jam_kerja_id = v"
                  :errorText="formErrors.tipe_jam_kerja_id ? 'failed' : ''" 
                  label="" 
                  @update:valueFull="changeTipeJamKerja"
                  placeholder="Pilih Tipe Jam Kerja"
                  :hints="formErrors.tipe_jam_kerja_id"
                  :api="{
                      url: `${store.server.url_backend}/operation/m_general`,
                      headers: { 'Content-Type': 'Application/json', Authorization: `${store.user.token_type} ${store.user.token}`},
                      params: {
                        where:`this.group='TIPEJAM' AND this.is_active='true'`,
                        join:true, 
                        selectfield: 'this.id, this.code, this.value, this.is_active'
                      }
                  }"
                  valueField="id" 
                  displayField="value" 
                  :check="false"
              />
          </div>
      </div>
        <!-- END COLUMN -->
        <div class="flex flex-row justify-end space-x-[20px] mt-[1em]">
          <button @click="onGenerate" class="bg-green-600 hover:bg-green-800 duration-300 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            {{ values.tipe?.toLowerCase() === 'html' ? 'View' : 'Export' }}
          </button>
        </div>
        <!-- ACTION BUTTON START -->
        <div class="overflow-x-auto mt-6 mb-4 px-4" v-show="exportHtml">
          <hr>
          <div id="exportTable" class="w-[100%] mt-4">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endverbatim