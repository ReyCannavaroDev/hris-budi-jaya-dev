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
  const exportHtml = ref(false)
  const formErrors = ref({})
  const activeTabIndex = ref(0)
  const tsId = `ts=`+(Date.parse(new Date()))

  // ------------------------------ PERSIAPAN
  onBeforeMount(()=>{
    document.title = 'Laporan Absensi'
  })

  //  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
  
  //  @else----------------------- LANDING
  let initialValues = {}
  const changedValues = []
  const checkedState = ref()
  let thisMonth = new Date().toISOString().split('T')[0]
  let tempYear = thisMonth.split('-')[0]
  let tempMonth = thisMonth.split('-')[1]
  const openDateSelected = ref(null)
  const dataByDate = ref([])
  const dataByDateDetail = ref([])

  const now = new Date();
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0');
  const day = String(now.getDate()).padStart(2, '0');

  const currentDate = `${day}/${month}/${year}`;

  const values = reactive({
    tipe: 'HTML',
    tipe_report : 'Rekap Harian',
    periode_from : tempYear+'-'+tempMonth,
    periode_to : tempYear+'-'+tempMonth,
    periode : tempYear+'-'+tempMonth,
    date: currentDate
  })

  const resetValuesPeriode = () => {
    values.periode_from = tempYear+'-'+tempMonth
    values.periode_to = tempYear+'-'+tempMonth
    values.periode = tempYear+'-'+tempMonth
  }

  const onGenerate = async () => {
    console.log(values)
    if(values.tipe === null || values.tipe_report === null){
      swal.fire({
        icon: 'error',
        text: 'Harap Memilih Tipe Export dan Tipe Report Dahulu!',
      })
      return
    }
    if(values.tipe_report === 'Rekap' && (values.periode_from === null || values.periode_to === null )){
      swal.fire({
        icon: 'error',
        text: 'Harap Memilih Periode Dahulu!',
      })
      return
    }
    if(values.tipe_report === 'Detail' && (values.periode === null)){
      swal.fire({
        icon: 'error',
        text: 'Harap Memilih Periode Dahulu!',
      })
      return
    }
    const tempGet = []
    isRequesting.value = true
    if(values.tipe){
      if(values.tipe?.toLowerCase() === 'excel'){
        tempGet.push(`export=xls`)
      }else if(values.tipe?.toLowerCase() === 'pdf'){
        tempGet.push(`export=pdf`)
      }
    }
    if(values.tipe_report === 'Rekap Harian')
    {
      if(values.date){
        tempGet.push(`date=${values.date}`)
      }
    }
    if(values.tipe_report === 'Rekap')
    {
      if(values.periode_from){
        let tempDay = values.periode_from.split('/')[0]
        tempGet.push(`periode_from=${tempDay}`)
      }
      if(values.periode_to){
        let tempDay2 = values.periode_to.split('/')[0]
        tempGet.push(`periode_to=${tempDay2}`)
      }
    }
    if(values.tipe_report === 'Detail')
    {
      if(values.periode){
        let tempDay = values.periode.split('/')[0]
        tempGet.push(`periode=${tempDay}`)
      }
    }
    
    if(values.tipe_report){
      tempGet.push(`tipe_report=${values.tipe_report}`)
    }
    if(values.m_divisi_id){
      tempGet.push(`m_divisi_id=${values.m_divisi_id}`)
    }
    if(values.m_dept_id){
      tempGet.push(`m_dept_id=${values.m_dept_id}`)
    }

    const paramsGet = tempGet.join("&")
    if(values.tipe?.toLowerCase() !== 'html'){
      exportHtml.value = false
      window.open(`${store.server.url_backend}/web/report_absensi` + '?' + paramsGet)
    }else{
      await fetch(`${store.server.url_backend}/web/report_absensi` + '?' + paramsGet, {
        headers: {
            'Content-Type': 'html',
          },
      })
      .then(response => response.text())
      .then(html => {
        exportHtml.value = true
        const tempDiv = document.createElement('div')
        tempDiv.innerHTML = html
        const firstSpanElement = tempDiv.querySelector('span:first-of-type');
        if (firstSpanElement) {
          firstSpanElement.style.fontSize = '22px'
        }
        const tableElements = tempDiv.querySelectorAll('table')
        if (tableElements) {
          tableElements.forEach(items => {
            items.style.fontSize = '14px',
            items.style.marginBottom = '10px'
          });
        }
        const targetDiv = document.getElementById('exportTable')
        targetDiv.innerHTML = ''
        targetDiv.appendChild(tempDiv)
      })
      .catch(error => {
        swal.fire({
          icon: 'error',
          text: error,
        })
      })
    }
    
    isRequesting.value = false
  }

  //  @endif -------------------------------------------------END
  watchEffect(()=>store.commit('set', ['isRequesting', isRequesting.value]))