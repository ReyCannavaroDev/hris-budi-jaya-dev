@verbatim
<div class="min-h-screen bg-gradient-to-r from-black to-blue-900 py-6 flex flex-col justify-center sm:py-12">
  <img style="object-fit: cover; object-position: right; width: 100%; height: 100%;"
  :src="getBasic.brand_bg" class="w-screen z-0 h-full absolute opacity-70">
  <div class="relative py-3 sm:max-w-xl sm:mx-auto">
    <div
      class="absolute inset-0 bg-gradient-to-r from-cyan-400  via-sky-500 to-purple-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
    </div>
     <form @submit="onLogin" class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-12">
      <div class="max-w-md mx-auto">
        <img :src="getBasic.brand_logo" @error="defaultImage" class="w-[100px] my-2 m-auto">
        <div>
          <h1 class="text-2xl font-semibold text-center">Human Resource Information System</h1>
        </div>
          <h1 class="text-md text-gray-400 text-center">Masuk untuk melanjutkan</h1>
         <div class="divide-y divide-gray-200">
          <div class="text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
            <div class="relative">
              <FieldX :bind="{ readonly: false, style: 'padding-top:20px; padding-bottom:20px; font-size: 11pt'}"
                :value="values.email"
                @input="v=>values.email=v"
                placeholder="Username" fa-icon="user" :check="false"
              />
            </div>
            <div class="relative">
              <FieldX :bind="{ readonly: false, style: 'padding-top:20px; padding-bottom:20px; font-size: 11pt'}"
                :value="values.password" 
                @input="v=>values.password=v" 
                type="password"
                placeholder="Password" fa-icon="lock" :check="false"
              />
            </div>
            <div class="relative">
              <button type="submit" class="bg-cyan-500 w-full text-white rounded-md px-2 py-2 hover:bg-cyan-600">Masuk</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="flex justify-end absolute bottom-0 right-0 p-4">
    <p class="text-white text-sm">©2023 Copyright <a class="underline" href="https://qqltech.com" target="_blank">Quantum Leap</a>. All Rights Reserved</p>
  </div>
</div>
@endverbatim