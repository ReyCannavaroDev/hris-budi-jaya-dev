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

  const file_report = ref('')

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

const values = reactive({
  tipe: 'HTML',
  tipe_report: 'Laporan Absensi Karyawan Rekap',
  periode: null,
  date_start: null,
});


const onDateStartChange = (value) => {
   if (!value) {
      values.date_end = null;

    return;
  }


  values.date_start = value;
  if (value) {
    const [day, month, year] = value.split('/').map(Number);
    const startDate = new Date(year, month - 1, day);
    const endDate = new Date(startDate);
    endDate.setDate(startDate.getDate() + 7);

    const formattedEndDate = [
      endDate.getDate().toString().padStart(2, '0'),
      (endDate.getMonth() + 1).toString().padStart(2, '0'),
      endDate.getFullYear()
    ].join('/');

    values.date_end = formattedEndDate;
  } else {
    values.date_end = null;
  }
};

const onDateEndChange = (value) => {
  if (!value) {
    return;
  }

  const [endDay, endMonth, endYear] = value.split('/').map(Number);
  const selectedEndDate = new Date(endYear, endMonth - 1, endDay);

  const [startDay, startMonth, startYear] = values.date_start.split('/').map(Number);
  const currentStartDate = new Date(startYear, startMonth - 1, startDay);

  // Jika tanggal end lebih awal dari tanggal start
  if (selectedEndDate < currentStartDate) {
    const newStartDate = new Date(selectedEndDate);
    newStartDate.setDate(selectedEndDate.getDate() - 7); // Mundur 7 hari dari tanggal end

    const formattedStartDate = [
      newStartDate.getDate().toString().padStart(2, '0'),
      (newStartDate.getMonth() + 1).toString().padStart(2, '0'),
      newStartDate.getFullYear()
    ].join('/');

    values.date_start = formattedStartDate;
  }

  // Jika tanggal end lebih besar dari tanggal start + 7 hari
  const maxEndDate = new Date(currentStartDate);
  maxEndDate.setDate(currentStartDate.getDate() + 7);
  if (selectedEndDate > maxEndDate) {
    const newStartDate = new Date(selectedEndDate);
    newStartDate.setDate(selectedEndDate.getDate() - 7); // Mundur 7 hari dari tanggal end

    const formattedStartDate = [
      newStartDate.getDate().toString().padStart(2, '0'),
      (newStartDate.getMonth() + 1).toString().padStart(2, '0'),
      newStartDate.getFullYear()
    ].join('/');

    values.date_start = formattedStartDate;
  }

  // Tetapkan nilai baru untuk date_end
  values.date_end = value;
};







watchEffect(() => {
  if (values.tipe_report === 'Laporan Absensi Karyawan Rekap Tidak Absen') {
    values.periode = null;
  }
});

  const resetValuesPeriode = () => {
    values.periode = null
    values.date = null
    values.date_start=null
  }

  const onGenerate = async () => {

    if(values.tipe_report == 'Laporan Absensi Karyawan Rekap'){
      file_report.value = 'report_karyawan_absensi_rekap'
    }
    if(values.tipe_report == 'Laporan Absensi Karyawan Rekap Tidak Absen'){
      file_report.value = 'report_karyawan_absensi_rekap_tidak_absen'
    }
    if(values.tipe_report == 'Laporan Absensi Karyawan Detail'){
      file_report.value = 'report_karyawan_absensi_detail'
    }
    if(values.tipe_report == 'Laporan Absensi Karyawan Group'){
      file_report.value = 'report_karyawan_absensi_group_range'
    }
    if(values.tipe_report == 'Laporan Sisa Cuti Karyawan'){
      file_report.value = 'report_sisa_cuti_karyawan'
    }
    
    if(values.tipe_report == 'Laporan Absensi Karyawan Detail' && !values.m_kary_id){
      swal.fire({
        icon: 'error',
        text: 'Harap Memilih Karyawan Terlebih Dahulu!',
      })
      return
    }
    if(values.tipe === null || values.tipe_report === null){
      swal.fire({
        icon: 'error',
        text: 'Harap Memilih Tipe Export dan Tipe Report Dahulu!',
      })
      return
    }
    if(values.tipe_report === 'Laporan Absensi Karyawan Detail' && (values.periode === null)){
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
  
    if(values.periode){
      let tempDay = values.periode.split('/')[0]
      tempGet.push(`periode=${tempDay}`)
    }
    
    if(values.tipe_report){
      tempGet.push(`tipe_report=${values.tipe_report}`)
    }
    if(values.m_kary_id){
      tempGet.push(`kary_id=${values.m_kary_id}`)
    }
    if(values.m_divisi_id){
      tempGet.push(`m_divisi_id=${values.m_divisi_id}`)
    }
    if(values.m_dept_id){
      tempGet.push(`m_dept_id=${values.m_dept_id}`)
    }
    if (values.date) {
  let tempDay = values.date.split('/')[0];
  let tempMonth = values.date.split('/')[1];
  let tempYear = values.date.split('/')[2];
  tempGet.push(`date=${tempYear}-${tempMonth}-${tempDay}`)
  }
  if(values.date_start){
      let tempYear = values.date_start.split('/')[2]
      let tempMonth = values.date_start.split('/')[1]
      let tempDay = values.date_start.split('/')[0]
      tempGet.push(`date_start=${tempYear}-${tempMonth}-${tempDay}`)
    }
    if(values.date_end){
      let tempYear2 = values.date_end.split('/')[2]
      let tempMonth2 = values.date_end.split('/')[1]
      let tempDay2 = values.date_end.split('/')[0]
      tempGet.push(`date_end=${tempYear2}-${tempMonth2}-${tempDay2}`)
    }



    const paramsGet = tempGet.join("&")
    if(values.tipe?.toLowerCase() !== 'html'){
      exportHtml.value = false
      window.open(`${store.server.url_backend}/web/${file_report.value}` + '?' + paramsGet)
    }else{
      await fetch(`${store.server.url_backend}/web/${file_report.value}` + '?' + paramsGet, {
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