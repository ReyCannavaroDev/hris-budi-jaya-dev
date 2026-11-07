//   javascript//   javascript

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
let trx_dtl = reactive({items: []})
let detailKey = ref(0)
let modalOpen = ref(false)
let detailIdxSelected = ref(0)
let trx_dtl_sub = reactive({items: []})
let _id = ref(0) 
let activeTabIndex = ref(0)

// ------------------------------ PERSIAPAN
const endpointApi = '/t_jadwal_kerja'
onBeforeMount(()=>{
  document.title = 'Jadwal Kerja'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
const tabOpen = ref(1)
const tabs = ref([])

let initialValues = {}
const changedValues = []
const dataAll = reactive({})
const dataActive = reactive({items:[]})

const values = reactive({
   status: 'DRAFT',
})

const removeDetail = (detailItem) => {
  dataActive.items = dataActive.items.filter((e) => e.id != detailItem.id)

  const idxExc = tabs.value.findIndex(a => a.day_num == (activeTabIndex.value));
  const adjIdx = idxExc !== -1 ? idxExc : 0;
  tabs.value[adjIdx]['t_jadwal_kerja_det'] = dataActive.items
}

onBeforeMount(async () => {
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { join: true }
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

      const sortedData = resultJson.data?.t_jadwal_kerja_det_hari.sort((a, b) => a.day_num - b.day_num);
      sortedData?.t_jadwal_kerja_det?.forEach((v)=>{
        v.nama_lengkap = v['m_kary.nama_lengkap']
      })
      tabs.value = sortedData


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
  }

  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
  values?.generate_num_det?.forEach((v,i)=>{
    v._id = i++
  })
})

function mapView(i){
  let data = tabs.value[i]?.t_jadwal_kerja_det ?? []
  
  data.forEach((v)=>{
    v.nama_lengkap = v['m_kary.nama_lengkap'] ?? v.nama_lengkap
  })
  dataActive.items = data
}

onMounted(()=>{
  setTimeout(()=>{
    activeTabIndex.value = 1
    const idxExc = tabs.value.findIndex(a => a.day_num == (activeTabIndex.value));
    const adjIdx = idxExc !== -1 ? idxExc : 0;
    
    let data = tabs.value[adjIdx]?.t_jadwal_kerja_det ?? []
    
    data.forEach((v)=>{
      v.nama_lengkap = v['m_kary.nama_lengkap']
    })
    
    dataActive.items = data
  },1000)
})

let __id = 0
const onDetailAdd = (rows) => {
  const mapped = rows.map((e)=>{
    return{
      _id : __id++,
      m_kary_id : e.id,
      nama_lengkap: e['nama_lengkap'],
      'm_dept.nama' : e['m_dept.nama'],
      'm_divisi.nama': e['m_divisi.nama'],
      'm_dir_id': e['m_dir_id'],
      'm_divisi_id': e['m_divisi_id'],
      'm_dept_id': e['m_dept_id'],
      t_jadwal_kerja_id: route.params.id
    }
  })

  // filter id karyawan yg sudah ada dalam detail
  const mappedIds = mapped.map(e => e.m_kary_id);
  const filteredMapped = mapped.filter(e => !dataActive.items.some(item => item.m_kary_id === e.m_kary_id));

  // gabungkan array detail
  dataActive.items = dataActive.items.concat(filteredMapped);

  // inject dalam object utama
  const idxExc = tabs.value.findIndex(a => a.day_num == (activeTabIndex.value));
  const adjIdx = idxExc !== -1 ? idxExc : 0;
  tabs.value[adjIdx]['t_jadwal_kerja_det'] = dataActive.items
}

async function generate() {
    swal.fire({
      icon: 'warning',
      text: 'Generate semua karyawan, proses ini akan memakan waktu lebih lama?',
      showDenyButton: true
    }).then((res) => {
      if (res.isConfirmed) {
        trx_dtl.items = []
        generate_det_kary()
      }
    })
}


async function getTabs() {
  try {
    const response = await fetch(`${store.server.url_backend}/operation/t_jadwal_kerja/${route.params.id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      }
    });

    if (!response.ok) {
      throw new Error('Coba kembali nanti');
    }

    const data = await response.json();
      
  console.log(data.data)

  } catch (error) {
    console.error('Error fetching tunjangan kemahalan:', error);

  }
}

async function generate_det_kary() {
  try {
    const response = await fetch(`${store.server.url_backend}/operation/t_jadwal_kerja/generate_det_kary`, {
      method: 'GET',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },

      // ajarane sopo get ngirim body
      // body: JSON.stringify({
      //   where: `this.is_active='true' AND this.m_zona_id=${zonaId} AND this.grading_id=${gradingId}`,
      // })
    });
    if (!response.ok) {
      throw new Error('Coba kembali nanti');
    }
    const data = await response.json();
    let allKary = data?.data;

    const mapped = allKary.map((e)=>{
      return{
        _id : __id++,
        m_kary_id : e.m_kary_id,
        nama_lengkap: e['nama_lengkap'],
        'm_dept.nama' : e['m_dept.nama'],
        'm_divisi.nama': e['m_divisi.nama'],
        'm_dir_id': e['m_dir_id'],
        'm_divisi_id': e['m_divisi_id'],
        'm_dept_id': e['m_dept_id'],
        t_jadwal_kerja_id: route.params.id
      }
    })

    // filter id karyawan yg sudah ada dalam detail
    const mappedIds = mapped.map(e => e.m_kary_id);
    const filteredMapped = mapped.filter(e => !dataActive.items.some(item => item.m_kary_id === e.m_kary_id));

    // gabungkan array detail
    dataActive.items = dataActive.items.concat(filteredMapped);

    const idxExc = tabs.value.findIndex(a => a.day_num == (activeTabIndex.value));
    const adjIdx = idxExc !== -1 ? idxExc : 0;
    tabs.value[adjIdx]['t_jadwal_kerja_det'] = dataActive.items

  } catch (error) {
    console.error('Error fetching all kary:', error);

  }
}

function onBack() {
  router.replace('/t_jadwal_kerja/'+route.params.id)
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
  try {
    values.t_jadwal_kerja_det = tabs.value
    const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
    const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}?from=setting_jadwal`
    isRequesting.value = true
    const res = await fetch(dataURL, {
      method: 'PUT',
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
      body: JSON.stringify(values)
    })
    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json()
        formErrors.value = responseJson.errors || {}
        throw (responseJson.errors.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
      } else {
        throw ("Failed when trying to post data")
      }
    }
  } catch (err) {
    isBadForm.value = true
    swal.fire({
      icon: 'error',
      text: err
    })
  }
  isRequesting.value = false
  swal.fire({
    icon: 'success',
    text: "Data berhasil disimpan"
  })
}

//  @else----------------------- LANDING

const landing = reactive({
  actions: [
    {
      icon: 'trash',
      class: 'bg-red-600 text-light-100',
      title: "Hapus",
      show: (row) => row.status?.toUpperCase() !== 'POSTED',
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
      show: (row) => row.status?.toUpperCase() !== 'POSTED',
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
    },
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield:'this.id, this.nama, this.is_active',
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
    cellClass: ['justify-center', 'bg-gray-50', 'border-r', '!border-gray-200']
  },
  {
    field: 'nomor',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Tipe Jam Kerja',
    field: 'tipe_jam_kerja.value',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'keterangan',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'status',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
    return value === 'POSTED'
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">POSTED</span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">DRAFT</span>`
    }
  }]
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