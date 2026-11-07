import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Tambah' : route.query.action)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=`+(Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = '/dashboard'
onBeforeMount(()=>{
  document.title = 'Dashboard'
})
const is_superadmin = ref(false)
const beforeLoad = ref(false)
onMounted(async ()=>{
  beforeLoad.value = true
   try {
      const dataURL = `${store.server.url_backend}/me`
      isRequesting.value = true
      const res = await fetch(dataURL, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })
      const data = await res.json()
      is_superadmin.value = data?.is_superadmin ?? false
      if(data?.is_superadmin == false){
        router.replace('/presensi_absen_online')
      }
    } catch (err) {
      beforeLoad.value = false
    }
    beforeLoad.value = false
})
