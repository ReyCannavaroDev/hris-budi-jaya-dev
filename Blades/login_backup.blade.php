<div class="overflow-hidden">
  @verbatim
  <div class="grid grid-cols-2 <md:grid-cols-1 relative">
    <div class="h-screen bg-[#FCFCFC] flex items-center justify-center <md:hidden">
        <img style="object-fit: cover; object-position: right; width: 50%; height: 100%;"src="https://images.pexels.com/photos/1634187/pexels-photo-1634187.jpeg?auto=compress&cs=tinysrgb" class="w-screen z-0 h-full absolute ">
    </div>
    <form @submit="onLogin" class="flex z-10 flex-col h-[100%] space-y-6 bg-gray-200 px-[126px] justify-center w-full bg-opacity-80 <md:h-full mt-300s px-[56px]">
      <img src="https://server.qqltech.com:7005/logo-login.png" @error="defaultImage" class="w-[100px] mb-1">
      <h2 class="text-[22px] md:text-[28px] font-700">Welcome Back</h2>
      <h3 class="text-[16px] text-[#8F8F8F]">Silahkan masuk ke akun anda</h3>
        <FieldX :bind="{ readonly: false }"
          :value="values.email"
          @input="v=>values.email=v"
          placeholder="Username" fa-icon="user" :check="false"
        />
        <FieldX :bind="{ readonly: false }" type="password"
          :value="values.password" 
          @input="v=>values.password=v" 
          placeholder="Password" fa-icon="lock" :check="false"
        />
      <button type="submit" class="bg-[#387CEE] text-white py-[11px] rounded-[10px]">
        Masuk
      </button>
  </form>
  </div>
  @endverbatim
</div>
