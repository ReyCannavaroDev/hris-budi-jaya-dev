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
        <h1 class="text-[24px] mb-[10px] font-bold">
          {{actionText}} Otoritas Mengakses
        </h1>
      </div>
      <!-- HEADER END -->

      <!-- FORM START -->
      <div class="grid grid-cols-8 md:grid-cols-12 text-[14px] gap-x-[29px] gap-y-[26px]">
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">ID Pengguna<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: true }" class="col-span-12 !mt-0 w-full"
              :value="values.id_pengguna" :errorText="formErrors.id_pengguna?'failed':''"
              @input="v=>values.id_pengguna=v" :hints="formErrors.id_pengguna" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 items-center gap-y-2">
            <label class="col-span-12">Nama Peran<label class="text-red-500 space-x-0 pl-0">*</label></label>
            <FieldX :bind="{ readonly: !actionText }" class="col-span-12 !mt-0 w-full"
              :value="values.nama" :errorText="formErrors.nama?'failed':''"
              @input="v=>values.nama=v" :hints="formErrors.nama" :check="false"
            />
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 gap-y-2 items-center">
            <label class="col-span-12">Special Akses<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <div class="col-span-12">
                <div class="grid grid-cols-12">
                  <div class="flex items-center col-span-6">
                    <input type="radio" value="Iya" v-model="values.akses" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                    <label for="aktif_status" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Iya</label>
                  </div>
                  <div class="flex items-center col-span-6">
                    <input type="radio" value="Tidak" v-model="values.akses" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                    <label for="tidak_aktif_status" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="col-span-8 md:col-span-6">
          <div class="grid grid-cols-12 gap-y-2 items-center">
            <label class="col-span-12">Admin Divisi<label class="text-red-500 space-x-0 pl-0">*</label></label>
              <div class="col-span-12">
                <div class="grid grid-cols-12">
                  <div class="flex items-center col-span-6">
                    <input type="radio" value="Iya" v-model="values.admin" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                    <label for="aktif_status" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Iya</label>
                  </div>
                  <div class="flex items-center col-span-6">
                    <input type="radio" value="Tidak" v-model="values.admin" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"/>
                    <label for="tidak_aktif_status" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tidak</label>
                  </div>
                </div>
              </div>
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
                headerName: 'Nama Otoritas Mengakses',
                field: 'nama',
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              {
                flex: 1,
                headerName: 'Akses Spesial',
                field: 'akses',
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              {
                flex: 1,
                headerName: 'Admin Divisi',
                field: 'admin',
                sortable: false, resizable: true, filter: false,
                cellClass: ['!border-gray-200', 'justify-center'],
              },
              ]"
            >
            <template #header>
                <button @click="addOtoritas" type="button" class="mr-[10px] bg-[#005FBF] hover:bg-[#0055ab] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="plus" /> <span>Add to List</span></button>
                <button @click="detailArr = []" type="button" class="bg-[#DD4B39] hover:bg-[#da3c28] text-white py-[12px] px-[19.5px] flex items-center justify-center space-x-2 rounded">
                  <icon fa="trash" /> <span>Remove</span>
                </button>
              </div>
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