@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'" :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
        Tambah
        <icon fa="plus" />
      </RouterLink>
    </template>
  </TableApi>
</div>
@else

@verbatim

<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <!-- HEADER START -->
      <div class="flex flex-col items-start mb-2 pb-4">
        <h1 class="text-[24px] mb-[15px] font-bold">
         Form Pengaturan Periode
        </h1>
      </div>
      <!-- HEADER END -->
      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Icon<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }"  class="col-span-12 !mt-0 w-full"
              :value="values.icon" :errorText="formErrors.icon?'failed':''"
              @input="v=>values.icon=v" :hints="formErrors.icon" :check="false"
            /> 
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tanggal Awal<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="date" class="col-span-12 !mt-0 w-full"
              :value="values.tgl" :errorText="formErrors.tgl?'failed':''"
              @input="v=>values.tgl=v" :hints="formErrors.tgl" :check="false"
            /> 
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tanggal Akhir<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="date" class="col-span-12 !mt-0 w-full"
              :value="values.tgl2" :errorText="formErrors.tgl2?'failed':''"
              @input="v=>values.tgl2=v" :hints="formErrors.tgl2" :check="false"
            /> 
          </div>
        </div>
        <div class="col-span-8 md:col-span-12">
      <div>
      <div class="mt-4">
          <button @click="onGenerate" type="button" class="bg-[#10B981] hover:bg-[#18b17e] text-white py-[12px] px-[16px] flex items-center justify-center rounded">Auto Generate</button>
        </div>
        <div class="mt-4">
          <table class="w-full overflow-x-auto table-auto">
            <thead>
              <tr class="border-y">
                <td class="text-blue-500 font-bold text-capitalize px-2 py-2 text-center w-[10%]">No.</td>
                <td class="text-blue-500 font-bold text-capitalize px-2 text-center w-[30%]">Icon</td>
                <td class="text-blue-500 font-bold text-capitalize px-2 text-center w-[30%]">Tanggal Dibuat</td>
                <td class="text-blue-500 font-bold text-capitalize px-2 text-center w-[30%]">Action</td>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, i) in detailArr" :key="item.id" class="border-t">
                <td class="p-2 text-center">
                  {{ i + 1 }}.
                </td>
                <td class="p-2">
                  <FieldX :bind="{ readonly: !actionText }" class="!mt-0"
                    :value="item.menu" @input="v=>values.menu=v" :check="false"
                  />
                </td>
                <td class="p-2">
                  <FieldX :bind="{ readonly: !actionText }" class="!mt-0"
                    :value="item.created_at" @input="v=>values.created_at=v" type="date" :check="false"
                  />
                </td>
                <td class="p-2">
                  <div class="flex justify-center">
                    <button type="button" @click="removeDetail(item)">
                    <svg width="14" height="18" viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path id="Vector" d="M14 1H10.5L9.5 0H4.5L3.5 1H0V3H14M1 16C1 16.5304 1.21071 17.0391 1.58579 17.4142C1.96086 17.7893 2.46957 18 3 18H11C11.5304 18 12.0391 17.7893 12.4142 17.4142C12.7893 17.0391 13 16.5304 13 16V4H1V16Z" fill="#F24E1E"/>
                    </svg>
                  </button>
                  </div>

                </td>
              </tr>
            </tbody>
          </table>

        </div>
      <!--BUTTON-->
      </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">

            <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] w-32 ">
            Kembali
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] w-32 ">
            Simpan
          </button>
      </div>
      <!-- FORM END -->
    </div>
  </div>
</div>
@endverbatim
@endif