@verbatim

<div class="flex flex-col gap-y-3">
  <div class="flex gap-x-4 px-2">
    <div class="flex flex-col border rounded shadow-sm px-6 py-6 <md:w-full w-full bg-white">
      <div class="mb-4">
        <h1 class="text-[24px] mb-4 font-bold">
          Account Profile
        </h1>
        <hr>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <!-- START COLUMN -->
        <!-- <div>
          <label class="font-semibold">Direktorat <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.direktorat" :errorname="formErrors.direktorat?'failed':''"
              @input="v=>values.direktorat=v" :hints="formErrors.direktorat" 
              :check="false"
          />
        </div> -->
        <div>
          <label class="font-semibold">Divisi <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.divisi" :errorname="formErrors.divisi?'failed':''"
              @input="v=>values.divisi=v" :hints="formErrors.divisi" 
              :check="false"
          />
        </div>
        <div>
          <label class="font-semibold">Departemen <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.dept" :errorname="formErrors.dept?'failed':''"
              @input="v=>values.dept=v" :hints="formErrors.dept" 
              :check="false"
          />
        </div>
        <div>
          <label class="font-semibold">NIK <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.kode" :errorname="formErrors.kode?'failed':''"
              @input="v=>values.kode=v" :hints="formErrors.kode" 
              :check="false"
              placeholder=""
          />
        </div>
        <div>
          <label class="font-semibold">No KTP <label class="text-red-500 space-x-0 pl-0"></label></label>
           <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.nik" :errorname="formErrors.nik?'failed':''"
              @input="v=>values.nik=v" :hints="formErrors.nik" 
              :check="false"
              placeholder=""/>
        </div>
        <div>
          <label class="font-semibold">Nama Lengkap <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: true }" class="w-full py-2 !mt-0"
              :value="values.name" :errorname="formErrors.name?'failed':''"
              @input="v=>values.name=v" :hints="formErrors.name" 
              :check="false"
          />
        </div>
        <div>
          <label class="font-semibold">Username <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{  }" class="w-full py-2 !mt-0"
              :value="values.username" :errorname="formErrors.username?'failed':''"
              @input="v=>values.username=v" :hints="formErrors.username" 
              :check="false"
          />
        </div>
        <div>
          <label class="font-semibold">Email <label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{  }" class="w-full py-2 !mt-0"
              :value="values.email" :errorname="formErrors.email?'failed':''"
              @input="v=>values.email=v" :hints="formErrors.email" 
              :check="false"
          />
        </div>
        <div class="col-span-2 text-red-500">
          <i>Mengisi kolom password akan mereset password pengguna</i>
        </div>
        <div class="col-span-2 text-red-500" v-if="values.default_password">
          <i>Default Password : {{values.default_password}}</i>
        </div>
        <div >
          <label class="font-semibold">Password<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: false }" class="w-full py-2 !mt-0"
              :value="values.password" :errorname="formErrors.password?'failed':''"
              @input="v=>values.password=v" :hints="formErrors.password" 
              :check="false"
              type="password"
              fa-icon="lock"
          />
        </div>
        <div >
          <label class="font-semibold">Konfirmasi Password<label class="text-red-500 space-x-0 pl-0"></label></label>
          <FieldX :bind="{ readonly: false }" class="w-full py-2 !mt-0"
              :value="values.password_confirm" :errorname="formErrors.password_confirm?'failed':''"
              @input="v=>values.password_confirm=v" :hints="formErrors.password_confirm" 
              :check="false"
              type="password"
              fa-icon="lock"
          />
        </div>
        <!-- END COLUMN -->
        <!-- ACTION BUTTON START -->
      </div>
        <div class="flex flex-row justify-end space-x-[20px] mt-[10px]">
          <button @click="redirectKary" v-if="values.m_kary_id" class="bg-blue-500 hover:bg-blue-400 text-white text-[10pt] font-semibold px-[36.5px] py-[10px] rounded-[6px] ">
            <Icon fa="triangle-exclamation"/>Lengkapi Data Diri Anda
          </button>
          <button @click="onSave" class="bg-[#10B981] hover:bg-[#0ea774] text-white text-[10pt] font-semibold px-[36.5px] py-[10px] rounded-[6px] ">
            Simpan
          </button>
        </div>
    </div>
  </div>
</div>
@endverbatim