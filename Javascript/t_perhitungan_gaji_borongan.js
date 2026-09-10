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
const detailArr = ref([])
const titleOpen = ref('')
const formErrors = ref({})
const modalOpen = ref(false)
const detailArrOpen = reactive({items: []})
const tsId = `ts=`+(Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = '/t_perhitungan_gaji'
onBeforeMount(()=>{
  document.title = 'Perhitungan Gaji Borongan'
})

function openDetailFromLanding(row) {
  titleOpen.value = row['m_kary.nama_lengkap'] + ' - ' +row['m_kary.nik']
  let dataFormat =  row?.detail_gaji ?? []

  detailArrOpen.items = dataFormat
  modalOpen.value = true
}

function closeModal(i) {
  modalOpen.value = false
}

function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
}


//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []
let _id = 0

const today = new Date();
const day = String(today.getDate()).padStart(2, '0');
const month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
const year = today.getFullYear();
var formattedDate = `${day}/${month}/${year}`;

// Menambahkan 14 hari ke tanggal hari ini
const nextWeek = new Date(today);
nextWeek.setDate(today.getDate() + 14);
const nextDay = String(nextWeek.getDate()).padStart(2, '0');
const nextMonth = String(nextWeek.getMonth() + 1).padStart(2, '0');
const nextYear = nextWeek.getFullYear();
var dayNext = `${nextDay}/${nextMonth}/${nextYear}`;


const values = reactive({
  periode_awal_form: formattedDate,
  periode_akhir_form: dayNext,
  tipe: 'BORONGAN'
})

function openDetail(i) {
  titleOpen.value = detailArr.value[i]?.nama_lengkap ?? '-' +' - ' +detailArr.value[i]?.nama_lengkap ?? ''
  let dataFormat =  detailArr.value[i]?.detail_gaji ?? []

  detailArrOpen.items = dataFormat
  modalOpen.value = true
}

onBeforeMount(async () => {
  console.log(values.periode_awal_form)
  // tampilkan default direktorat dengan store user comp.nama
  values.direktorat = store.user.data?.direktorat

  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { join: false, transform: false }
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
})

function onBack() {
  if (route.query.view_gaji) {
    router.replace('/t_info_gaji')
  }else{
    router.replace('/' + modulPath)
  }
  return
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

// validasi periode awal dan akhir
const checkTglStDate = (v) => {
  if(v!==null){
    values.periode_awal_form=v;
    var sdDate=v;
    if(values.periode_akhir_form){
      var stDtSplit = v.split('/');
      var edDtSplit =values.periode_akhir_form.split('/');
      var startDate = new Date(stDtSplit[1]+"/"+stDtSplit[0]+"/"+stDtSplit[2]);
      var endDate = new Date(edDtSplit[1]+"/"+edDtSplit[0]+"/"+edDtSplit[2]);

      if(endDate<startDate){
        swal.fire({
          icon: 'warning',
          text: 'Tanggal Mulai tidak boleh melebihi Tanggal Selesai',
          showDenyButton: false
        }).then((res) => {
          console.log(res.isConfirmed)
          if (res.isConfirmed) {
            values.periode_awal_form=formattedDate;
            values.periode_akhir_form=dayNext;
          }
        })
      } else {
            const dayNow = values.periode_awal_form;
            const [day, month, year] = dayNow.split('/').map(Number);
            const currentDate = new Date(year, month - 1, day);
            const resultDate = new Date(currentDate);
            resultDate.setDate(currentDate.getDate() + 14);

            const resultDay = String(resultDate.getDate()).padStart(2, '0');
            const resultMonth = String(resultDate.getMonth() + 1).padStart(2, '0');
            const resultYear = resultDate.getFullYear();

            const nextWeekNow = `${resultDay}/${resultMonth}/${resultYear}`;
            
            values.periode_akhir_form=nextWeekNow;
      }
    }
  }
 
}

const checkTglEdDate = (v) => {
  if(v!==null){
    values.periode_akhir_form=v;
    var sdDate=v;
    if(values.periode_awal_form){
      var edDtSplit = v.split('/');
      var stDtSplit = values.periode_awal_form.split('/');
      var startDate = new Date(stDtSplit[1]+"/"+stDtSplit[0]+"/"+stDtSplit[2]);
      var endDate = new Date(edDtSplit[1]+"/"+edDtSplit[0]+"/"+edDtSplit[2]);

      if(endDate<startDate){
        swal.fire({
          icon: 'warning',
          text: 'Tanggal Mulai tidak boleh melebihi Tanggal Selesai',
          showDenyButton: false
        }).then((res) => {
          if (res.isConfirmed) {
            const dayNowVal = values.periode_awal_form;
            const [day, month, year] = dayNowVal.split('/').map(Number);
            const currentDateVal = new Date(year, month - 1, day);
            const resultDateVal = new Date(currentDateVal);
            resultDateVal.setDate(currentDateVal.getDate() + 14);

            const resultDay = String(resultDateVal.getDate()).padStart(2, '0');
            const resultMonth = String(resultDateVal.getMonth() + 1).padStart(2, '0');
            const resultYear = resultDateVal.getFullYear();

            const nextWeekNowVal = `${resultDay}/${resultMonth}/${resultYear}`;
            
            values.periode_akhir_form=nextWeekNowVal;
          }
        })
      }
    }
  }
 
}


async function generatePerhitungan() {
  values.periode_awal = values.periode_awal_form.split('/')[2] + '-' + values.periode_awal_form.split('/')[1] + '-' + values.periode_awal_form.split('/')[0]
  values.periode_akhir = values.periode_akhir_form.split('/')[2] + '-' + values.periode_akhir_form.split('/')[1] + '-' + values.periode_akhir_form.split('/')[0]
  
    detailArr.value = []
      try {
        const dataURL = `${store.server.url_backend}/operation/t_salary_borongan/generate_gaji_borongan`

        const res = await fetch(dataURL, {
          method: 'POST',
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
          } else if(res.status == 500) {
            throw ("Sesuatu yang salah terjadi, silahkan coba kembali nanti atau hubungi administrator")
          } else {
            throw ("Failed when trying to post data")
          }
        }
        
        const result = await res.json()
        const resultData = result.data
        if(!resultData?.length) {
          swal.fire({
            icon: 'warning',
            text: "Tidak ditemukan data Perhitungan Gaji, pastikan karyawan sudah memiliki standar gaji"
          })
        }
        resultData.forEach((item) => {
          item.id           = ++_id
          item.karyawan     = item['nama_lengkap']
          item.deskripsi    = item['desc']
          detailArr.value.push(item)
        })
        
      } catch (err) {
        isBadForm.value = true
        swal.fire({
          icon: 'error',
          text: "Sesuatu yang salah terjadi, silahkan coba kembali nanti atau hubungi administrator"
        })
      }
      
}

async function onSave() {

     if (detailArr.value.length === 0) {
    swal.fire({
      icon: 'error',
      title : 'Oops..',
      text: "Silahkan generate data perhitungan gaji terlebih dahulu sebelum menyimpan."
    });
    return;
  }

  // merging detail data
    values['detail'] = detailArr._value
      try {
        const dataURL = `${store.server.url_backend}/operation/t_perhitungan_gaji/save`
        isRequesting.value = true

        const res = await fetch(dataURL, {
          method: 'POST',
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

//  @else----------------------- LANDING
const landing = reactive({
  actions: [
    // {
    //   icon: 'trash',
    //   class: 'bg-red-600 text-light-100',
    //   title: "Hapus",
    //   // show: () => store.user.data.username==='developer',
    //   click(row) {
    //     swal.fire({
    //       icon: 'warning',
    //       text: 'Hapus Data Terpilih?',
    //       confirmButtonText: 'Yes',
    //       showDenyButton: true,
    //     }).then(async (result) => {
    //       if (result.isConfirmed) {
    //         try {
    //             const dataURL = `${store.server.url_backend}/operation${endpointApi}/${row.id}`
    //             isRequesting.value = true
    //             const res = await fetch(dataURL, {
    //               method: 'DELETE',
    //               headers: {
    //                 'Content-Type': 'Application/json',
    //                 Authorization: `${store.user.token_type} ${store.user.token}`
    //               }
    //             })
    //             if (!res.ok) {
    //             const resultJson = await res.json()
    //             throw (resultJson.message || "Failed when trying to remove data")
    //           }
    //             apiTable.value.reload()
    //             const resultJson = await res.json()
    //           } catch (err) {
    //             isBadForm.value = true
    //             swal.fire({
    //               icon: 'error',
    //               text: err
    //             })
    //           }
    //           isRequesting.value = false
    //       }
    //     })
    //   }
    // },
    {
      icon: 'eye',
      title: "Read",
      class: 'bg-green-600 text-light-100',
      // show: (row) => (currentMenu?.can_read)||store.user.data.username==='developer',
      click(row) {
        openDetailFromLanding(row)
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
      where: `lower(this.type_perhitungan) = 'borongan'`,
      simplest: true,
      searchfield:'this.id, this.nomor, m_kary.nik, m_kary.nama_lengkap, m_kary_dir.nama, m_kary_divisi.nama, m_kary_dept.nama, this.periode, periode.value, this.netto',
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
    headerName: 'Nomor Generate',
    filter: true,
    wrapText: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start','w-[400px]']
  },
  {
    field: 'm_kary.kode',
    headerName: 'NIK',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'm_kary.nama_lengkap',
    headerName: 'Karyawan',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'm_kary_dept.nama',
    headerName: 'Departement',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'periode',
    headerName: 'Tgl Periode',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center','!w-[500px]']
  },
  {
    field: 'periode.value',
    headerName: 'Periode Gaji',
    filter: true,
    wrapText: true,
    sortable: true,
    filter: 'ColFilter',
    flex:1,
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'netto',
    headerName: 'Gaji',
    filter: true,
    wrapText: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-end'],
    cellRenderer: ({ value }) => {
      return formatRupiah(value);
    },
  },
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