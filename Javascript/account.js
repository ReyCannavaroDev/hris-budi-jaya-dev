//   javascript

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
const endpointApi = '/default_users'
onBeforeMount(()=>{
  document.title = 'Master Pengguna'
})

let initialValues = {}
let initialValues2 = {}
const changedValues = []

const values = reactive({
  is_active: true
})

onBeforeMount(async () => {
  // tampilkan default direktorat dengan store user comp.nama
  route.params.id = store.user.data.id
  values.direktorat = store.user.data?.direktorat
    //  READ DATA
    try {
      const editedId = store.user.data.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}?withKary=true`
      isRequesting.value = true

      const params = { join: true, transform: false }
      const fixedParams = new URLSearchParams(params)
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })
      if (!res.ok) throw new Error("Failed when trying to read data")
      const resultJson = await res.json()
      initialValues = resultJson.data
      initialValues.name = initialValues.nama_lengkap
      initialValues.username = initialValues.username
      initialValues.email = initialValues.email
    } catch (err) {
      isBadForm.value = true
      swal.fire({
        icon: 'error',
        text: err,
        allowOutsideClick: false,
        confirmButtonText: 'Kembali',
      }).then(() => {
        router.back()
      })
    }
    isRequesting.value = false

  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
})

function onBack() {
  let isChanged = false
  for (const key in initialValues) {
    if (values[key] !== initialValues[key]) {
      isChanged = true
      break;
    }
  }

  if (!isChanged) {
    router.replace('/' + modulPath)
    return
  }

      router.replace('/' + modulPath)
}

function redirectKary() {
  router.replace('/m_karyawan/'+values.m_kary_id+'?profile=true&ts='+tsId)
}

function onReset() {
  swal.fire({
    icon: 'warning',
    text: 'Reset this form data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      for (const key in initialValues) {
        values[key] = initialValues[key]
      }
    }
  })
}

async function onSave() {
  //values.tags = JSON.stringify(values.tags)
      let data = {
        username: values.username,
        email: values.email,
        password: values.password,
        password_confirm: values.password_confirm,
      }
      try {
        const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
        const dataURL = `${store.server.url_backend}/operation${endpointApi}/reset_password`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: 'POST',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify(data)
        })
        if (!res.ok) {
          if ([400, 422].includes(res.status)) {
            const responseJson = await res.json();
            formErrors.value = responseJson.errors || {};
            throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data");
          } else {
            throw ("Failed when trying to post data");
          }
        }else{
          swal.fire({
            icon: 'success',
            text: 'Profil diperbarui'
          })
        }
        router.replace('/' + modulPath + '?reload='+(Date.parse(new Date())))
      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          text: err
        })
      }
      isRequesting.value = false
}
watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))