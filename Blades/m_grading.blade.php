<!-- LANDING -->
@if(!$req->has('id'))
<div class="bg-white p-1 rounded-md min-h-[520px]  ">
  <div class="flex justify-end items-center px-2.5 p-4">
    <!-- <div class="flex items-center gap-x-4">
      <p>Filter Status :</p>
      <div class="flex gap-x-2">
        <button @click="filterShowData(true,1)" :class="activeBtn === 1?'bg-green-600 text-white hover:bg-green-400':'border border-green-600 text-green-600 bg-white  hover:bg-green-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Active</button>
        <div class="flex my-auto h-4 w-0.5 bg-[#6E91D1]"></div>
        <button @click="filterShowData(false,2)" :class="activeBtn === 2?'bg-red-600 text-white hover:bg-red-400':'border border-red-600 text-red-600 bg-white  hover:bg-red-600 hover:text-white'" class="duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">Inactive</button>
      </div>
    </div> -->
    <div>
      <RouterLink :to="$route.path+'/create?'+(Date.parse(new Date()))"
        class="border border-blue-600 text-blue-600 bg-white  hover:bg-blue-600 hover:text-white duration-300 transform hover:-translate-y-0.5 rounded-md py-1 px-2">
        Create New
      </RouterLink>
    </div>
  </div>
  <hr>
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions"
    class="max-h-[450px]">
    <!-- <template #header>
    </template> -->
  </TableApi>
</div>
@else

<!-- CONTENT -->
@verbatim
<div class="flex flex-col border rounded-md shadow-md md:w-full w-full p-0 bg-white border-none">
  <div class=" text-black rounded-t-md py-2 px-4">
    <div class="flex items-center">
      <Icon fa="arrow-left" class="cursor-pointer mr-2 font-bold hover:text-yellow-500" title="Kembali"
        @click="onBack" />
      <div>
        <h1 class="text-20px font-bold">Form Grading</h1>
        <p class="text-black">Master Grading</p>
      </div>
    </div>
  </div>
  <div class="p-4 grid <md:grid-cols-1 grid-cols-2 gap-2 ">
    <!-- START COLUMN -->
    <div>
      <label class="text-base font-semibold"> Kode </label>
      <FieldX class="w-full !mt-2" :bind="{ readonly: !actionText }" :value="values.code"
        :errorText="formErrors.code?'failed':''" @input="v=>values.code=v" :hints="formErrors.code" label=""
        placeholder="Tuliskan Kode" :check="false" />
    </div>

    <div>
      <label class="text-base font-semibold"> Keterangan </label>
      <FieldX class="w-full !mt-2" :bind="{ readonly: !actionText }" :value="values.value"
        :errorText="formErrors.value?'failed':''" @input="v=>values.value=v" :hints="formErrors.value" label=""
        placeholder="Tuliskan Value" :check="false" />
    </div>

    <div>
      <label class="text-base font-semibold"> Tipe </label>
      <FieldSelect :bind="{ disabled: !actionText, clearable:false }" class="w-full !mt-3" :value="values.value_2"
        @input="v=>values.value_2=v" :errorText="formErrors.value_2?'failed':''" :hints="formErrors.value_2"
        valueField="key" displayField="key" :options="[{'key' : 'HARIAN'},{'key' : 'BORONGAN'}]"
        placeholder="Pilih Tipe" label="" :check="false" />
    </div>



    <div> </div>

    <div class="flex flex-col gap-2">
      <label
            class="inline-block pl-[0.15rem] hover:cursor-pointer"
            for="is_active"
            >Status</label>
      <div class="flex w-40 items-center">
        <div class="flex-auto">
          <i class="text-red-500">InActive</i>
        </div>
        <div class="flex-auto">
          <input
                class="mr-2 mt-[0.3rem] h-3.5 w-8 appearance-none rounded-[0.4375rem] bg-neutral-300 before:pointer-events-none before:absolute before:h-3.5 before:w-3.5 before:rounded-full before:bg-transparent before:content-[''] after:absolute after:z-[2] after:-mt-[0.1875rem] after:h-5 after:w-5 after:rounded-full after:border-none after:bg-blue-500 after:shadow-[0_0px_3px_0_rgb(0_0_0_/_7%),_0_2px_2px_0_rgb(0_0_0_/_4%)] after:transition-[background-color_0.2s,transform_0.2s] after:content-[''] checked:bg-primary checked:after:absolute checked:after:z-[2] checked:after:-mt-[3px] checked:after:ml-[1.0625rem] checked:after:h-5 checked:after:w-5 checked:after:rounded-full checked:after:border-none checked:after:bg-primary checked:after:shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),_0_2px_2px_0_rgba(0,0,0,0.14),_0_1px_5px_0_rgba(0,0,0,0.12)] checked:after:transition-[background-color_0.2s,transform_0.2s] checked:after:content-[''] hover:cursor-pointer focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[3px_-1px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-5 focus:after:w-5 focus:after:rounded-full focus:after:content-[''] checked:focus:border-primary checked:focus:bg-primary checked:focus:before:ml-[1.0625rem] checked:focus:before:scale-100 checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:bg-neutral-600 dark:after:bg-neutral-400 dark:checked:bg-primary dark:checked:after:bg-primary dark:focus:before:shadow-[3px_-1px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[3px_-1px_0px_13px_#3b71ca]"
                type="checkbox"
                role="switch"
                id="is_active"
                :disabled="!actionText"
                v-model="values.is_active" />
        </div>
        <div class="flex-auto">
          <i class="text-green-500">Active</i>
        </div>
      </div>
    </div>
    <!-- END COLUMN -->
    <!-- ACTION BUTTON START -->
  </div>
  <hr>
  <div class="p-4">
    <div class="col-span-8 md:col-span-12">
      <button
      :disabled="!actionText"
      @click="addDetail"
      type="button"
      class="bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
      <icon fa="plus" /> 
      <span>Tambah List Grading</span>
    </button>

      <div class="mt-4" style="overflow-x: auto; border: 1px solid #CACACA;">
        <table class="w-[120%] table-auto border border-[#CACACA]">

          <thead>
            <tr class="bg-[#f8f8f8] text-[#8F8F8F] font-semibold text-[14px] border border-[#CACACA]">
              <th class="px-2 py-[14.5px] text-center w-[2%] border border-[#CACACA]">No.</th>
              <th class="px-2 text-center w-[20%] border border-[#CACACA]">Keterangan</th>
              <th class="px-2 text-center w-[10%] border border-[#CACACA]">Tipe</th>
              <th class="px-2 text-center w-[5%] border border-[#CACACA]">Nominal</th>
              <th class="px-2 text-center w-[5%] border border-[#CACACA]">Faktor</th>
              <th class="px-2 text-center w-[10%] border border-[#CACACA]">Hari</th>
              <th class="px-2 text-center w-[8%] border border-[#CACACA]">Libur Nasional</th>
              <th class="px-2 text-center w-[8%] border border-[#CACACA]">1 Minggu Kehadiran</th>
              <th class="px-2 text-center w-[5%] border border-[#CACACA]">7-5</th>
              <th class="px-2 text-center w-[5%] border border-[#CACACA]">Perlu Lembur</th>
              <th class="px-2 text-center w-[10%] border border-[#CACACA]">Jam Lembur</th>
              <th class="px-2 text-center w-[5%] border border-[#CACACA]">Bulanan</th>
              <th class="px-2 text-center w-[8%] border border-[#CACACA]">Tidak Perlu Checkin</th>
              <th class="px-2 text-center w-[5%] border border-[#CACACA]">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t border-[#CACACA]">
              <td class="p-2 text-center border border-[#CACACA]">{{ i + 1 }}.</td>

              <td class="p-2 border border-[#CACACA]">
                <FieldX :bind="{ readonly: !actionText }" class="!mt-0" :value="item.keterangan"
                  @input="v => item.keterangan = v" :errorText="formErrors.keterangan ? 'failed' : ''"
                  :hints="formErrors.keterangan" label="" type="textarea" placeholder="Tuliskan Keterangan"
                  :check="false" />
              </td>

              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="!mt-0 w-full" :value="item.type"
                  @input="v => item.type = v" :errorText="formErrors.type ? 'failed' : ''" :hints="formErrors.type"
                  label="" valueField="key" displayField="key" :options="['field', 'percentage','multiplication']"
                  placeholder="" :check="false" />
              </td>

              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText || (values.value_2 === 'HARIAN' && item.type === 'field') }"
                  class="!mt-0" :value="item.value" @input="v => item.value = v"
                  :errorText="formErrors.value ? 'failed' : ''" :hints="formErrors.value" label=""
                  placeholder="Masukan Value" :check="false" />
              </td>

              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ disabled: !actionText, clearable: false }" class="!mt-0 w-full"
                  :value="item.factor" @input="v => item.factor = v" :errorText="formErrors.factor ? 'failed' : ''"
                  :hints="formErrors.factor" label="" :options="['+', '-']" placeholder="Pilih Faktor" :check="false" />
              </td>

              <td class="p-2 border border-[#CACACA]">
                <FieldSelect :bind="{ readonly: !actionText ,  disabled: item.type === 'field' && values.value_2 !== 'BORONGAN' }" class="!mt-0 w-full"
                  :value="item.day" @input="v => item.day = v" :errorText="formErrors.day ? 'failed' : ''"
                  :hints="formErrors.day" valueField="id" displayField="key" :options="[
                  { 'id': '0', 'key': 'MINGGU' },
                  { 'id': '1', 'key': 'SENIN' },
                  { 'id': '2', 'key': 'SELASA' },
                  { 'id': '3', 'key': 'RABU' },
                  { 'id': '4', 'key': 'KAMIS' },
                  { 'id': '5', 'key': 'JUMAT' },
                  { 'id': '6', 'key': 'SABTU' }
                   ]" label="" placeholder="" :check="false" />
              </td>

              <td class="p-2 border border-[#CACACA] text-center">
                <input
                  type="checkbox"
                  :checked="item.big_event"
                  @change="toggleBigEvent(i)"
                  :disabled="!actionText || (item.type === 'field' && values.value_2 !== 'BORONGAN')"
                  class="cursor-pointer w-6 h-6"
                />
              </td>


              <td class="p-2 border border-[#CACACA] text-center">
                <input
                  type="checkbox"
                  :checked="item.full_week"
                  @change="v => item.full_week = v.target.checked"
                  :disabled="!actionText || (item.type === 'field' && values.value_2 !== 'BORONGAN')"
                  class="cursor-pointer w-6 h-6"
                />
              </td>

              <td class="p-2 border border-[#CACACA] text-center">
                <input
                  type="checkbox"
                  :checked="item.is_7_5"
                  @change="v => item.is_7_5 = v.target.checked"
                  :disabled="!actionText || (item.type === 'field' && values.value_2 !== 'BORONGAN')"
                  class="cursor-pointer w-6 h-6"
                />
              </td>

              <td class="p-2 border border-[#CACACA] text-center">
                <input
                  type="checkbox"
                  :checked="item.need_overtime"
                  @change="v => item.need_overtime = v.target.checked"
                  :disabled="!actionText "
                  class="cursor-pointer w-6 h-6"
                />
              </td>

              <td class="p-2 border border-[#CACACA]">
                <FieldNumber :bind="{ readonly: !actionText, readonly: !item.need_overtime }" class="!mt-0"
                  :value="item.value_overtime" @input="v => item.value_overtime = v"
                  :errorText="formErrors.value_overtime ? 'failed' : ''" :hints="formErrors.value_overtime" label=""
                  placeholder="0" :check="false" />
              </td>

              <td class="p-2 border border-[#CACACA] text-center">
                <input
                  type="checkbox"
                  :checked="item.is_month"
                  @change="v => item.is_month = v.target.checked"
                  :disabled="!actionText"
                  class="cursor-pointer w-6 h-6"
                />
              </td>

              <td class="p-2 border border-[#CACACA] text-center">
                <input
                  type="checkbox"
                  :checked="item.not_checkin"
                  @change="v => item.not_checkin = v.target.checked"
                  :disabled="!actionText || item.type === 'field'"
                  class="cursor-pointer w-6 h-6"
                />
              </td>
              <td class="p-4 flex justify-center items-center ">
                <button type="button" @click="removeDetail(i)" :disabled="!actionText">
                <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                </svg>
              </button>
              </td>
            </tr>
            <tr v-if="detailArr.length === 0" class="text-center">
              <td colspan="12" class="py-[20px]">No data to show</td>
            </tr>
          </tbody>

        </table>
      </div>
    </div>
  </div>

  <div class="flex flex-row items-center justify-end space-x-2 p-2">
    <!-- <i class="text-gray-500 text-[12px]">Tekan CTRL + S untuk shortcut Save Data</i> -->
    <!-- <button 
        class="bg-red-600 text-white font-semibold hover:bg-red-500 transition-transform duration-300 transform hover:-translate-y-0.5 rounded-md p-2"
        v-show="actionText" 
        @click="onReset(true)" 
      >
        <icon fa="times" />
        Reset
      </button> -->
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