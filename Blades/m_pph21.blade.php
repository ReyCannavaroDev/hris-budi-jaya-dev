@if(!$req->has('id'))
<div class="bg-white p-6 rounded-xl h-[570px]">
  <TableApi ref='apiTable' :api="landing.api" :columns="landing.columns" :actions="landing.actions">
    <template #header>
      <RouterLink v-if="currentMenu?.can_create||true||store.user.data.username==='developer'" :to="$route.path+'/create?'+(Date.parse(new Date()))" class="bg-green-500 text-white hover:bg-green-600 rounded-[6px] py-2 px-[12.5px]">
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
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">

      <!-- HEADER START -->
      <div class="flex flex-col items-start mb-2 pb-4">
        <h1 class="text-[24px] mb-[15px] font-bold">
          Form Pengaturan PPH 21
        </h1>
        <h2 class="font-bold mb-[15px] text-[18px]">
          Memperbarui Gaji
        </h2>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Tanggal Pengaturan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="date" class="col-span-12 !mt-0 w-full"
              :value="values.tgl_pengaturan" :errorText="formErrors.tgl_pengaturan?'failed':''"
              @input="v=>values.tgl_pengaturan=v" :hints="formErrors.tgl_pengaturan" :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Dependant Amt.<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.dependant_amt" @input="(v)=>values.dependant_amt=v"
              :errorText="formErrors.dependant_amt?'failed':''" 
              :hints="formErrors.dependant_amt" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Cost Level (%)<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.cost_level" :errorText="formErrors.cost_level?'failed':''"
              @input="v=>values.cost_level=parseFloat(v)" :hints="formErrors.cost_level" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Status<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.is_active" @input="v=>values.is_active=v"
              :errorText="formErrors.is_active?'failed':''" 
              :hints="formErrors.is_active"
              valueField="id" displayField="key"
              :options="[{'id' : 1 , 'key' : 'Active'},{'id': 0, 'key' : 'InActive'}]"
              :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Metode Penentuan<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldSelect
              :bind="{ disabled: !actionText, clearable:false }" class="col-span-12 !mt-0 w-full"
              :value="values.metode_penentuan" @input="v=>values.metode_penentuan=v"
              :errorText="formErrors.metode_penentuan?'failed':''" 
              :hints="formErrors.metode_penentuan"
              valueField="key" displayField="key"
              :options="['GROSS', 'NET', 'GROSS UP', 'GROSS UP FLAT']"
              :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Note<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="textarea" class="col-span-12 !mt-0 w-full"
              :value="values.note" :errorText="formErrors.note?'failed':''"
              @input="v=>values.note=v" :hints="formErrors.note" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6 mt-[10px]">
          <h2 class="font-bold text-[18px]">Pria</h2>
        </div>
        <div class="col-span-8 md:col-span-6 mt-[10px]">
          <h2 class="font-bold text-[18px]">Wanita</h2>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Besaran (Menikah)<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.besaran_nikah_pria" @input="(v)=>values.besaran_nikah_pria=v"
              :errorText="formErrors.besaran_nikah_pria?'failed':''" 
              :hints="formErrors.besaran_nikah_pria" :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Besaran (Menikah)<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.besaran_nikah_wanita" @input="(v)=>values.besaran_nikah_wanita=v"
              :errorText="formErrors.besaran_nikah_wanita?'failed':''" 
              :hints="formErrors.besaran_nikah_wanita" :check="false"
            />
            
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Besaran (Single)<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.besaran_single_pria" @input="(v)=>values.besaran_single_pria=v"
              :errorText="formErrors.besaran_single_pria?'failed':''" 
              :hints="formErrors.besaran_single_pria" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Besaran (Single)<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.besaran_single_wanita" @input="(v)=>values.besaran_single_wanita=v"
              :errorText="formErrors.besaran_single_wanita?'failed':''" 
              :hints="formErrors.besaran_single_wanita":check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6 mt-[10px]">
          <h2 class="font-bold text-[18px]">Kisaran Pajak</h2>
        </div>
        <div class="col-span-8 md:col-span-6 mt-[10px]">
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Gaji Minimum<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.gajiMin" @input="(v)=>values.gajiMin=v"
              :errorText="formErrors.gajiMin?'failed':''" 
              :hints="formErrors.gajiMin":check="false"
            />
            <span class="col-span-12 text-right text-[#F82619] italic text-[12px]">Set 0 if min salary have no range</span>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Gaji Maksimum<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldNumber
              :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.gajiMax" @input="(v)=>values.gajiMax=v"
              :errorText="formErrors.gajiMax?'failed':''" 
              :hints="formErrors.gajiMax" :check="false"
            />
            <span class="col-span-12 text-right text-[#F82619] italic text-[12px]">Set 0 if min salary have no range</span>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">NPWP (%)<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.npwpPj" :errorText="formErrors.npwpPj?'failed':''"
              @input="v=>values.npwpPj=parseFloat(v)" :hints="formErrors.npwpPj" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Non NPWP (%)<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" type="number" class="col-span-12 !mt-0 w-full"
              :value="values.nonnpwpPj" :errorText="formErrors.nonnpwpPj?'failed':''"
              @input="v=>values.nonnpwpPj=parseFloat(v)" :hints="formErrors.nonnpwpPj" :check="false"
            />
          </div>
        </div>
        
        <div class="col-span-8 md:col-span-12">
          <TableStatic
            customClass="h-50vh"
            ref="detail" 
            :value="detailArr"
            :columns="[{
                headerName: 'No',
                cellRenderer: !actionText?null:'ButtonGrid',
                valueGetter:p=>p.node.rowIndex + 1,
                cellRendererParams: !actionText?null:{
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
                          detailArr = detailArr.filter((e) => e.__id != app.params.node.data.__id)
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
                headerName: 'Gaji Minimum',
                cellRenderer: (params)=>{
                  return formatCurrency(params.data.gaji_min)
                },
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              {
                flex: 1,
                headerName: 'Gaji Maksimum',
                cellRenderer: (params)=>{
                  return formatCurrency(params.data.gaji_max)
                },
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              {
                flex: 1,
                headerName: 'NPWP(%)',
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
                cellRenderer: (e) =>{
                  return e.data.npwp + '%'
                }
              },
               {
                flex: 1,
                headerName: 'NON NPWP(%)',
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
                cellRenderer: (e) =>{
                  return e.data.non_npwp + '%'
                }
              },
              ]"
            >
            <template #header>
                <button :disabled="!actionText" @click="addDetail" type="button" class="mr-[10px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="plus" /> <span>Add to List</span></button>
                <button :disabled="!actionText" @click="detailArr = []" type="button" class="bg-[#DD4B39] hover:bg-[#da3c28] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="trash" /> <span>Remove</span>
                </button>
            </template>
          </TableStatic>
        </div>
        </div>
      <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
            <button @click="onBack" class="bg-[#EF4444] hover:bg-[#ed3232] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Kembali
          </button>
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Simpan
          </button>
      </div>
      <!-- FORM END -->
    </div>
  </div>
</div>
@endverbatim
@endif