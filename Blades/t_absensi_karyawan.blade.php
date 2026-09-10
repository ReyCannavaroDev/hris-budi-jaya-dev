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
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Form Rekap Absensi Karyawan
        </h1>
        <hr>
      </div>
      <div class="grid <md:grid-cols-1 grid-cols-2 gap-2">
        <!-- START COLUMN -->
        <div>
          <label class="font-semibold">Bulan<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
            :value="values.tipe_alasan_id"
            @input="v => values.tipe_alasan_id=v"
            placeholder="Pilih Bulan"
            label=""
            :check="false"
            :options="['Alasan 1', 'Alasan 2']"
          />
        </div>
        <div>
          <label class="font-semibold">Tahun<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
            :value="values.tipe_alasan_id"
            @input="v => values.tipe_alasan_id=v"
            placeholder="Pilih Tahun"
            label=""
            :check="false"
            :options="['Alasan 1', 'Alasan 2']"
          />
        </div>
        <div>
          <label class="font-semibold">Tipe<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
            :value="values.tipe_alasan_id"
            @input="v => values.tipe_alasan_id=v"
            placeholder="Pilih Tipe"
            label=""
            :check="false"
            :options="['Alasan 1', 'Alasan 2']"
          />
        </div>
       <div>
          <label class="font-semibold">Periode<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
            :value="values.tipe_alasan_id"
            @input="v => values.tipe_alasan_id=v"
            placeholder="Pilih Periode"
            label=""
            :check="false"
            :options="['Alasan 1', 'Alasan 2']"
          />
        </div>
      
        <div>
          <label class="font-semibold">Direktorat<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
            :value="values.tipe_alasan_id"
            @input="v => values.tipe_alasan_id=v"
            placeholder="Pilih Direktorat"
            label=""
            :check="false"
            :options="['Alasan 1', 'Alasan 2']"
          />
        </div>

        <div>
          <label class="font-semibold">Departemen<span class="text-red-500 space-x-0 pl-0">*</span></label>
          <FieldSelect
            class="w-full py-2 !mt-0"
            :value="values.tipe_alasan_id"
            @input="v => values.tipe_alasan_id=v"
            placeholder="Pilih Departemen"
            label=""
            :check="false"
            :options="['Alasan 1', 'Alasan 2']"
          />
        </div>

        <!-- END COLUMN -->
      </div>
        <!-- ACTION BUTTON START -->
        <div class="flex flex-row justify-end space-x-[20px] mt-[5em]">
          <!-- <button @click="onPost" class="bg-orange-500 hover:bg-orange-600 text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Mengajukan Persetujuan
          </button> -->
          
          <button v-show="actionText" @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white px-[36.5px] py-[12px] rounded-[6px] ">
            Auto Generate
          </button>
        </div>
    </div>
  </div>
</div>
@endverbatim
@endif