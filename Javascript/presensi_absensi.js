//   javascript

import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const isCreateEdit = route.params.id === 'create' || route.query.action === 'Edit'
const actionText = ref(route.params.id === 'create' ? 'Tambah' : route.query.action)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const showModal = ref(false)
const formErrors = ref({})
const tsId = `ts=`+(Date.parse(new Date()))
const detailDate = ref(null)
const dataDetail = ref([])
const isCheckin = ref()
const isCheckout = ref(false)
const openDateDetail = (e,item,check,data) => {
  if(!item.checkin_foto?.includes(`http`)){
    if(item.checkin_foto){    
      item.checkin_foto = `${store.server.url_backend}/${item.checkin_foto}`
    }
    
  }
  if(!item.checkout_foto?.includes(`http`)){
    if(item.checkout_foto){
      item.checkout_foto = `${store.server.url_backend}/${item.checkout_foto}`
    }
  }
  check === 'checkin' ? isCheckin.value = true : isCheckin.value = false
  dataDetail.value = item
  dataDetail.value = {
    ...dataDetail.value,
    ...data
  }
  showModal.value = e
  console.log(dataDetail.value)
}
// ------------------------------ PERSIAPAN
const endpointApi = '/presensi_absensi'
onBeforeMount(()=>{
  document.title = 'Transaksi Absensi Karyawan'
})

let thisMonth = new Date().toISOString().split('T')[0]
let tempYear = thisMonth.split('-')[0]
let tempMonth = thisMonth.split('-')[1]
const headerValues = reactive({
  month: tempYear+'-'+tempMonth,
  divisi_id: null,
  dept_id: null
})



const formatDate = (year, month, day) => {
    return `${year}-${month}-${String(day).padStart(2, '0')}`;
};

function getWeekNow(tDay, lgth){
  let nowDay = parseInt(thisMonth.split('-')[2])
  let floorAvg = Math.floor(parseFloat(tDay??0)/lgth??4)
  let value
  if(floorAvg >= nowDay){
    value = 0
  }else if((floorAvg*2)>= nowDay && nowDay > floorAvg){
    value = 1
  }else if((floorAvg*3)>= nowDay && nowDay > (floorAvg*2)){
    value = 2
  }else if((floorAvg*4)>= nowDay && nowDay > (floorAvg*3)){
    value = 3
  }else if((floorAvg*5)>= nowDay && nowDay > (floorAvg*4)){
    value = 4
  }else if((floorAvg*6)>= nowDay && nowDay > (floorAvg*5)){
    value = 5
  }else{
    value = 6
  }
  return value
// totaldays % 4 = 7, apakah 7 < tanggal seakarang, kalau lebih kecil = minggu peertama   
// else if ((7*2  <= tanggal seakrang && tanggal sekarang > 7 = week 2))
// else if ((7*3  <= tanggal seakrang && tanggal sekarang > 7*2 = week 3))
// else week 4
}

let weeks = [];
const weeksOptions = ref([])
async function dayPerWeek(paramsFunc){
  const [yearPar,monthPar] = paramsFunc?.split('-')
  weeks = []
  weeksOptions.value = []
  let totalDays = new Date(yearPar, monthPar, 0).getDate();

  const daysPerWeek = 3;

  const fullWeeks = Math.floor(totalDays / daysPerWeek);
  const remainder = totalDays % daysPerWeek;

  for (let i = 0; i < fullWeeks; i++) {
    let startDay = i * daysPerWeek + 1;
    let endDay = startDay + daysPerWeek - 1;
    weeks.push({
        week: `P${i + 1}`,
        startDate: formatDate(yearPar, monthPar, startDay),
        endDate: formatDate(yearPar, monthPar, endDay)
    });
  }

  if (remainder > 0) {
      let lastWeekIndex = weeks.length - 1;
      let startDay = fullWeeks * daysPerWeek + 1;
      let endDay = totalDays;
      weeks[lastWeekIndex].endDate = formatDate(yearPar, monthPar, endDay);
  }

  let indexWeek = monthPar == tempMonth ? await getWeekNow(totalDays, weeks.length) : 0

  let weeksMap = weeks.map(week => ({
    value: week.startDate+'/'+week.endDate,
    text: `${week.week} ${week.startDate}/${week.endDate}`
  }));

  weeksOptions.value = weeksMap
  headerValues.weeks = weeksOptions.value[indexWeek]['value']
  await loadData()
}


const dataByDate = ref([])
const dataByDateDetail = ref([])
onMounted(async ()=>{
  dayPerWeek(tempYear+'-'+tempMonth)
})

const loaderData = ref(false)
const processLoad = ref(0)

const loadData = async () => {
  processLoad.value = 0;
  const interval = setInterval(() => {
    processLoad.value++
  }, 100);

  loaderData.value = true
  if(openDateSelected.value){
    openDate(openDateSelected.value)
    return
  }
  try {
    const dataURL = `${store.server.url_backend}/operation${endpointApi}/get_by_daily`

    const fixedParams = new URLSearchParams(headerValues)
    const res = await fetch(dataURL + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    })
    loaderData.value = false
    const resultJson = await res.json()
    if(resultJson.code == 200){
      dataByDate.value = resultJson.data
    }else{
      return swal.fire({
        icon: 'Perhatian',
        text: resultJson.message,
        allowOutsideClick: false,
      })
    }
    clearInterval(interval);
  } catch (err) {
    isRequesting.value = false
    swal.fire({
      icon: 'Perhatian',
      text: err,
      allowOutsideClick: false,
    })
    clearInterval(interval);
  }
 
}


const openDateSelected = ref(null)
const openDate = async (date) => {
  loaderData.value = true
  openDateSelected.value = date
  const dataURL = `${store.server.url_backend}/operation${endpointApi}/get_by_date`
  isRequesting.value = true

  headerValues.date = date

  const fixedParams = new URLSearchParams(headerValues)
  const res = await fetch(dataURL + '?' + fixedParams, {
    headers: {
      'Content-Type': 'Application/json',
      Authorization: `${store.user.token_type} ${store.user.token}`
    },
  })

  isRequesting.value = false
   loaderData.value = false
  const resultJson = await res.json()
  dataByDateDetail.value = resultJson.data
}


//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []

const values = reactive({
})


onBeforeMount(async () => {
  // tampilkan default direktorat dengan store user comp.nama
  // values.creator_name = store.user.data?.name
 
  // console.log( values.creator_name = response['creator.name'])
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { join: true, transform: true }
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
      const regex = new RegExp(`(${store.server.url_backend})(\/?)(${store.server.url_backend})`, 'g')
      const image_checkin = initialValues.checkin_foto?.replace(regex, '$1')
      const image_checkout = initialValues.checkout_foto?.replace(regex, '$1')
      initialValues.checkin_foto = image_checkin
      const segments = new URL(initialValues.checkout_foto).pathname.split('/').filter(Boolean)
      if(segments.length === 0){
        isCheckout.value=true
        // const params2 = { join: true, transform: true, where: `this.name='default-no-image'` }
        // const fixedParams2 = new URLSearchParams(params2)
        // const res2 = await fetch(`${store.server.url_backend}/operation/m_file` + '?' + fixedParams2, {
        //   headers: {
        //     'Content-Type': 'Application/json',
        //     Authorization: `${store.user.token_type} ${store.user.token}`
        //   },
        // })
        // if (!res2.ok) throw new Error("Failed when trying to read data")
        // const resultJson2 = await res2.json()
        // initialValues.checkout_foto = resultJson2.data[0].filename
      }else{
        initialValues.checkout_foto = image_checkout
      }
      if(initialValues.checkin_lat && initialValues.checkin_long){
        initialValues.geo_checkin = `POINT(${initialValues.checkin_long} ${initialValues.checkin_lat})`
      }
      if(initialValues.checkout_lat && initialValues.checkout_long){
        initialValues.geo_checkout = `POINT(${initialValues.checkout_long} ${initialValues.checkout_lat})`
      }
      // values.absen_checkin_on_scope= resultJson.data['checkin_on_scope'] ? true  : false
      // values.absen_checkout_on_scope = resultJson.data['checkout_on_scope'] ? true  : false
      // console.log( resultJson.data)
      // console.log(resultJson.data['checkin_on_scope'] ? true  : false)
      
      // const tempTanggal = route.query.ts.split('-')
      // initialValues.tanggal = `${tempTanggal[2]}/${tempTanggal[1]}/${tempTanggal[0]}`
      
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
  }else{
    try {
      isRequesting.value = true
      isCheckout.value=true
      initialValues.default_user_id = route.query.user_id
      // const params = { join: true, transform: true, where: `this.name='default-no-image'` }
      // const fixedParams = new URLSearchParams(params)
      // const res = await fetch(`${store.server.url_backend}/operation/m_file` + '?' + fixedParams, {
      //   headers: {
      //     'Content-Type': 'Application/json',
      //     Authorization: `${store.user.token_type} ${store.user.token}`
      //   },
      // })
      // if (!res.ok) throw new Error("Failed when trying to read data")
      // const resultJson = await res.json()
      // initialValues.checkin_foto = resultJson.data[0].filename
      // initialValues.checkout_foto = resultJson.data[0].filename
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
    const tempTanggal = route.query.ts.split('-')
    initialValues.tanggal = `${tempTanggal[2]}/${tempTanggal[1]}/${tempTanggal[0]}`
  }

  for (const key in initialValues) {
    values[key] = initialValues[key]
  }
})

function onBack() {
    router.replace('/' + modulPath+'?ts='+route.query.ts)
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

function onSave() {
  //values.tags = JSON.stringify(values.tags)
  swal.fire({
    icon: 'warning',
    text: 'Save data?',
    showDenyButton: true
  }).then(async (res) => {
    if (res.isConfirmed) {
      try {
        values.checkin_on_scope = values.checkin_on_scope ? 1 : 0
        values.checkout_on_scope = values.checkout_on_scope ? 1 : 0
        if(values.checkin_time && values.checkout_time){
          values.status='ATTEND'
        }else if(values.checkin_time && !values.checkout_time){
          values.status='WORKING'
        }
        const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
        const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`
        isRequesting.value = true
        values.status = 'WORKING'

        const res = await fetch(dataURL, {
          method: isCreating ? 'POST' : 'PUT',
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
            throw new Error(responseJson.message || "Failed when trying to post data")
          } else {
            throw new Error("Failed when trying to post data")
          }
        }
        onBack()
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
function onBackReal() {
    router.replace('/'+modulPath)
    dataByDateDetail.value = []
    openDateSelected.value = null
    headerValues.dept_id = null
    headerValues.divisi_id = null
    loadData()
}
if(route.query.ts){
  openDate(route.query.ts)
}
const landing = reactive({
  actions: [
    // {
    //   icon: 'trash',
    //   class: 'bg-red-600 text-light-100',
    //   title: "Hapus",
      // show: () => store.user.data.username==='developer',
      // click(row) {
      //   swal.fire({
      //     icon: 'warning',
      //     text: 'Hapus Data Terpilih?',
      //     confirmButtonText: 'Yes',
      //     showDenyButton: true,
      //   }).then(async (result) => {
      //     if (result.isConfirmed) {
      //       try {
      //         const dataURL = `${store.server.url_backend}/operation${endpointApi}/${row.id}`
      //         isRequesting.value = true
      //         const res = await fetch(dataURL, {
      //           method: 'DELETE',
      //           headers: {
      //             'Content-Type': 'Application/json',
      //             Authorization: `${store.user.token_type} ${store.user.token}`
      //           }
      //         })
      //         if (!res.ok) throw new Error("Failed when trying to remove data")
      //         apiTable.value.reload()
      //         // const resultJson = await res.json()
      //       } catch (err) {
      //         isBadForm.value = true
      //         swal.fire({
      //           icon: 'error',
      //           text: err
      //         })
      //       }
      //       isRequesting.value = false
      //     }
      //   })
      // }
    // },
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
    // {
    //   icon: 'copy',
    //   title: "Copy",
    //   class: 'bg-gray-600 text-light-100',
    //   click(row) {
    //     router.push(`${route.path}/${row.id}?action=Copy`+tsId)
    //   }
    // }
  ],
  api: {
    url: `${store.server.url_backend}/operation${endpointApi}`,
    headers: {
      'Content-Type': 'Application/json',
      authorization: `${store.user.token_type} ${store.user.token}`
    },
    params: {
      simplest: true,
      searchfield:'tanggal, creator.name, status, checkin_time, checkin_on_scope, checkout_time, checkout_on_scope',
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
    cellClass: ['justify-left', 'bg-gray-50', 'border-r', '!border-gray-200']
  },
  {
    field: 'tanggal',
    headerName: 'Tanggal',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-left']
  },
  {
    field: 'creator.name',
    headerName: 'Karyawan',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'status',
    headerName: 'Status',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-left']
  },
  // Check in
  {
    field: 'checkin_time',
    headerName: 'Waktu Checkin',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-left']
  },

  
  {
    field: 'checkin_on_scope',
    headerName: 'On Scope CheckIn',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Ya</span>`
      : `<span class="text-red-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>`
  }},

    // Checkout
  {
    field: 'checkout_time',
    headerName: 'Waktu Checkout',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-left']
  },
  

  {
    field: 'checkout_on_scope',
    headerName: 'On Scope Checkout',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Ya</span>`
      : `<span class="text-red-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Tidak</span>`
  }},
  ]

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