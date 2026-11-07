import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Create' : route.query.action)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const currentUser = store.user.data
const apiTable = ref(null)
const formErrors = ref({})
const tsId = `ts=`+(Date.parse(new Date()))
// ------------------------------ PERSIAPAN

const endpointApi = '/m_approval'
onBeforeMount(()=>{
  document.title = 'Master Approval'
})

//  @if( $id )------------------- VALUES FORM
let initialValues = {}
const changedValues = []

const values = reactive({
  is_active: true
})
const detailArr = ref([])

function clearDetailArr() {
  swal.fire({
    icon: 'warning',
    text: 'Clear Details?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      detailArr.value=[]
      detailArr.value.push({tipe: 'MENGAJUKAN',level: 1})
    }
  })
}


function onDetailAdd() {
  const data = [...detailArr.value]
  const row = {
    level: data.length+1,
    is_full_approve: false,
    is_skippable:false
  }
  data.push(row)
  detailArr.value = data
}

onBeforeMount(async () => {
  if(route.params.id === 'create'){
    // index pertama tambahkan data default dengan tipe MENGAJUKAN
    detailArr.value.push({tipe: 'MENGAJUKAN',level: 1})
  }
  if (isRead) {
    //  READ DATA KE SERVER
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = {  }
      const fixedParams = new URLSearchParams(params)
      const res = await fetch(dataURL + '?' + fixedParams, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })
      if (!res.ok) throw new Error("Failed when trying to read data")
      const resultJson = await res.json()
      resultJson.data.m_approval_det = resultJson.data?.m_approval_det.sort((a, b) => a.level - b.level);
     
      initialValues = resultJson.data
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
    // const details = [...detailArr.value]
    // details.forEach( (dt,idx)=>{
    //   dt.default_user_id = parseInt(dt.default_user_id);
      
    // })
    

    isRequesting.value = false
  }
  
//  PENGISIAN DATA KE FORM DAN DETAILS
  const initialData = JSON.parse(JSON.stringify(initialValues))
  for (const key in initialData) {
    if(['Create', 'Copy'].includes(actionText.value) ) continue;
    if(key === 'm_approval_det'){
      detailArr.value = [...initialData[key]]
      continue;
    }
    values[key] = initialData[key]
  }
  if(!detailArr.value.length) {
    detailArr.value=[]
    detailArr.value.push({tipe: 'MENGAJUKAN',level: 1})
  }
  if(values.is_active===true){
      values.is_active="1";
    }else{
      values.is_active="0";
    }
})

function onBack() {
  if( !route.query.action ){
    router.replace('/' + modulPath)
    return
  }
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

  swal.fire({
    icon: 'warning',
    text: 'Buang semua perubahan dan kembali ke list data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      router.replace('/' + modulPath)
    }
  })
}

function onReset() {
  swal.fire({
    icon: 'warning',
    text: 'Reset this form data?',
    showDenyButton: true
  }).then((res) => {
    if (res.isConfirmed) {
      const initialData = JSON.parse(JSON.stringify(initialValues))
      for (const key in initialData) {
        if(key === 'm_approval_d'){
          detailArr.value = [...initialData[key]]
          continue;
        }
        values[key] = initialData[key]
      }
    }
  })
}

function onSave() {

  //    validasi
  const details = [...detailArr.value]
  const uniqueNess = []
  let invalid = false;
  details.forEach( (dt,idx)=>{
    //dt.default_user_id = dt.default_user_id.toString();
    if( !dt.level || dt.level<0 ){
      swal.fire({ title: 'Invalid Detail', icon: 'warning',
        text: `Data baris ke ${idx-(-1)} : Level harus benar!`
      })
      invalid=true; return false
    }
    dt.level = idx+1
  })
  values.is_active = values.is_active ? 1 : 0
  if(invalid) return;

  swal.fire({
    icon: 'warning',
    text: 'Save data?',
    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        const isCreating = ['Create', 'Copy'].includes(actionText.value)
        const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify( Object.assign(values, {
            'm_approval_det' : detailArr.value
          }))
        })
        if (!res.ok) {
          if ([400, 422].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw new Error(responseJson.message || "Failed when trying to post data")
          } else {
            throw new Error("Failed when trying to post data")
          }
        }
        router.replace('/' + modulPath + '?reload=' + (Date.parse(new Date())))
      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          text: err
        })
      }
      isRequesting.value = false
    }
  })
}

//  @else----------------------- LANDING

const landing = reactive({
  //  ACTIONS sesuai priviledge user dan data
   actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      // show: () => store.user.data.username==='developer',
      click(row) {
        swal.fire({
          icon: 'warning',
          text: 'Hapus Data Terpilih?',
          confirmButtonText: 'Yes',
          showDenyButton: true,
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const dataURL = `${store.server.url_backend}/operation${endpointApi}/${row.id}`
              isRequesting.value = true
              const res = await fetch(dataURL, {
                method: 'DELETE',
                headers: {
                  'Content-Type': 'Application/json',
                  Authorization: `${store.user.token_type} ${store.user.token}`
                }
              })
              if (!res.ok) {
                if ([400, 422].includes(res.status)) {
                  const responseJson = await res.json()
                  formErrors.value = responseJson.errors || {}
                  throw new Error(responseJson.message || "Failed when trying to post data")
                } else {
                  throw new Error("Failed when trying to post data")
                }
              }
              apiTable.value.reload()
              // const resultJson = await res.json()
            } catch (err) {
              isBadForm.value = true
              swal.fire({
                icon: 'error',
                text: err
              })
            }
            isRequesting.value = false
          }
        })
      }
    },
    {
      icon: 'eye',
      title: "Read",
      class: 'bg-green-600 text-light-100',
      // show: (row) => (currentMenu?.can_read)||store.user.data.username==='developer',
      click(row) {
        router.push(`${route.path}/${row.id}?`+tsId)
      }
    },
    {
      icon: 'edit',
      title: "Edit",
      class: 'bg-blue-600 text-light-100',
      // show: (row) => (currentMenu?.can_update)||store.user.data.username==='developer',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Edit&`+tsId)
      }
    },
    {
      icon: 'copy',
      title: "Copy",
      class: 'bg-gray-600 text-light-100',
      click(row) {
        router.push(`${route.path}/${row.id}?action=Copy&`+tsId)
      }
    }
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield:  'this.nama, m_menu.menu, this.desc, this.is_active'
    },
    onsuccess(response) {
      response.page = response.current_page
      response.hasNext = response.has_next
      return response
    }
  },
  columns: [{
    headerName: 'No',
    valueGetter: (params) => params.node.rowIndex + 1,
    width: 60,
    sortable: true,
    resizable: true,
    filter: true,
    cellClass: ['justify-center', 'bg-gray-50']
  },
  {
    flex: 1,
    field: 'nama',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: "Menu",
    flex: 1,
    field: 'm_menu.menu',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    cellClass: [ 'border-r', '!border-gray-200']
  },
  {
    headerName: 'Keterangan',
    field: 'desc',
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    sortable: true,
    filter: true,
    filter: 'ColFilter',
  },
  {
    headerName: 'Status',
    field: 'is_active',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>`
  }}]
})

onActivated(() => {
  //  reload table api landing
  if (apiTable.value) {
    if (route.query.reload) {
      apiTable.value.reload()
    }
  }
})


//  @endif -------------------------------------------------END
watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))