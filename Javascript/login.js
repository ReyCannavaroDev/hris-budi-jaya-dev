import { useRouter, useRoute } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')
const isRequesting = ref(false)

const showPass= ref(true)
const values = reactive({
  email: null, password: null
})
const valuesRo = readonly(values)

const getBasic = ref({})

onBeforeMount(async () => {
  store.isUsingLayout = false
  isRequesting.value = true
    const result = await fetch(store.server.url_backend + `/public/m_general/get_basic`, {
      method: 'GET',
      headers: {
        'Content-Type': 'Application/json',
      }
    })
    if (!result.ok) {
      throw new Error(await result.text())
    }
    const data = await result.json();
    getBasic.value = data?.data
    document.title = getBasic.value?.brand_title

})

async function onLogin(e = null) {
  e?.preventDefault();
  try {
    isRequesting.value = true
    const result = await fetch(store.server.url_backend + "/login", {
      method: 'POST',
      headers: {
        'Content-Type': 'Application/json'
      },
      body: JSON.stringify(valuesRo)
    })

    if (!result.ok) {
      throw new Error(await result.text())
    }

    const userValue = await result.json()
    window.localStorage.user = JSON.stringify(userValue)
    store.commit('set', ['user', userValue])
  } catch (err) {
    isRequesting.value = false
    swal.fire({
      icon: 'warning',
      text: 'Email atau Password Salah!'
    })
    return
  }
  isRequesting.value = false
  await router.push('/dashboard')
}



onMounted(async () => {
  if (localStorage.user) {
    const tempJson = JSON.parse(localStorage.user)
    values.email = tempJson.data?.username

    try {
      isRequesting.value = true
      const result = await fetch(store.server.url_backend + `${store.server.url_user_check}`, {
        method: 'GET',
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${tempJson.token_type} ${tempJson.token}`
        }
      })
      if (!result.ok) {
        throw new Error(await result.text())
      }
      const refreshedUser = await result.json();
      Object.assign(tempJson, { data: refreshedUser })
      store.commit('set', ['user', tempJson])
      store.commit('set', ['userWasRefreshed', true])
      window.localStorage.user = JSON.stringify(tempJson)
      router.replace('/dashboard')
    } catch (err) { }
    isRequesting.value = false
  }
})

const defaultImage = ()=>{
  return store.server.url_backend+'/logo.png'
}
