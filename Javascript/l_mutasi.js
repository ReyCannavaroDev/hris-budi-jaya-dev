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
    document.title = 'Laporan Mutasi'
  })

  //  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
  
  //  @else----------------------- LANDING
  let initialValues = {}
  const changedValues = []
  const checkedState = ref()

  const values = reactive({
    tipe: 'HTML'
  })

  const onGenerate = async () => {
    if(values.tipe === null){
      swal.fire({
        icon: 'error',
        text: 'Harap Memilih Tipe Export Dahulu!',
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
    if(values.periode_from){
      let tempYear = values.periode_from.split('/')[2]
      let tempMonth = values.periode_from.split('/')[1]
      let tempDay = values.periode_from.split('/')[0]
      tempGet.push(`periode_from=${tempYear}-${tempMonth}-${tempDay}`)
    }
    if(values.periode_to){
      let tempYear2 = values.periode_to.split('/')[2]
      let tempMonth2 = values.periode_to.split('/')[1]
      let tempDay2 = values.periode_to.split('/')[0]
      tempGet.push(`periode_to=${tempYear2}-${tempMonth2}-${tempDay2}`)
    }
    if(values.m_dir_id){
      tempGet.push(`m_dir_id=${values.m_dir_id}`)
    }
    if(values.m_divisi_id){
      tempGet.push(`m_divisi_id=${values.m_divisi_id}`)
    }
    if(values.m_dept_id){
      tempGet.push(`m_dept_id=${values.m_dept_id}`)
    }
    if(values.m_posisi_id){
      tempGet.push(`m_posisi_id=${values.m_posisi_id}`)
    }
    if(values.m_kary_id){
      tempGet.push(`m_kary_id=${values.m_kary_id}`)
    }
    const paramsGet = tempGet.join("&")
    if(values.tipe?.toLowerCase() !== 'html'){
      exportHtml.value = false
      window.open(`${store.server.url_backend}/web/report_mutasi` + '?' + paramsGet)
    }else{
      await fetch(`${store.server.url_backend}/web/report_mutasi` + '?' + paramsGet, {
        headers: {
            'Content-Type': 'html',
          },
      })
      .then(response => response.text())
      .then(html => {
        exportHtml.value = true
        const tempDiv = document.createElement('div')
        tempDiv.innerHTML = html
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