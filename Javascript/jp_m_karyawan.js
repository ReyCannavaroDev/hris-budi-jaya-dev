import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount, watchEffect, onActivated } from 'vue'

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')

const isRead = route.params.id && route.params.id !== 'create'
const actionText = ref(route.params.id === 'create' ? 'Tambah' : route.query.action)
const isProfile = ref(route.query.profile ? true : false)
const isBadForm = ref(false)
const isRequesting = ref(false)
const modulPath = route.params.modul
const currentMenu = store.currentMenu
const apiTable = ref(null)
const formErrors = ref({})
const formErrorsPend = ref({})
const formErrorsKel = ref({})
const formErrorsPel = ref({})
const formErrorsPres = ref({})
const formErrorsOrg = ref({})
const formErrorsBhs = ref({})
const formErrorsPK = ref({})
const activeTabIndex = ref(0)
const tsId = `ts=`+(Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = '/m_kary'
onBeforeMount(()=>{
  document.title = 'Master Karyawan'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS
let initialValues = {}
const changedValues = []
const thisYear = new Date().getFullYear()
let tempKTP = ''
let tempBPJS = ''
let tempNPWP = ''
let tempKK = ''
let tempPasfoto = ''

const setStandartGaji = async () => {
  if(values.m_zona_id && values.grading_id){
    const fixedParams = new URLSearchParams({simplest: true , where: `this.is_active='true' AND this.m_zona_id=${values.m_zona_id ?? 0} AND this.grading_id=${values.grading_id ?? 0}`})
    const res = await fetch(`${store.server.url_backend}/operation/m_standart_gaji` + '?' + fixedParams, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    })
    if (!res.ok) throw new Error("Failed when trying to read data")
    const resultJson = await res.json()
    const data = resultJson.data
    console.log(data,'halo')
    if(initialValues.m_standart_gaji_id === values.m_standart_gaji_id){
      values.m_standart_gaji_id = initialValues.m_standart_gaji_id
    }else{    
      values.m_standart_gaji_id = data[0]?.id
    }
  }
  
}

const values = reactive({
  is_active: 1,
  direktorat: store.user.data?.direktorat,
  cuti_p24: 0,
  cuti_reguler: 0,
  cuti_masa_kerja:0,
  cuti_p24_terpakai:0,
  sisa_cuti_reguler:0,
  sisa_cuti_masa_kerja:0,

})

const valuesPendidikan = reactive({
  tingkat_id:null,
  thn_masuk: thisYear,
  nama_sekolah:null,
  thn_lulus:thisYear,
  kota_id:null,
  nilai:null,
  jurusan:null,
  is_pend_terakhir:null,
  desc:null,
  ijazah_foto:null
})

const valuesKeluarga = reactive({
  keluarga_id: null,
  nama:null,
  pend_terakhir_id:null,
  pekerjaan_id:null,
  jk_id:null,
  usia:null,
  desc:null
})

const valuesPelatihan = reactive({
  nama_pel: null,
  tahun:thisYear,
  nama_lem:null,
  kota_id:null
})

const valuesPrestasi = reactive({
  tingkat_pres_id: null,
  tahun:thisYear,
  nama_pres:null
})

const valuesOrganisasi = reactive({
  nama: null,
  tahun:thisYear,
  jenis_org_id:null,
  kota_id:null,
  posisi:null
})

const valuesBahasa = reactive({
  bhs_dikuasai: null,
  nilai_lisan:null,
  nilai_tertulis:null
})

const valuesPengalaman = reactive({
  instansi: null,
  thn_masuk:thisYear,
  thn_keluar:thisYear,
  kota_id:null,
  alamat_kantor:null,
  surat_referensi:null,
  bidang_usaha:null,
  no_tlp:null,
  posisi:null
})


onBeforeMount(async () => {
  if (isRead) {
    //  READ DATA
    try {
      const editedId = route.params.id
      const dataURL = `${store.server.url_backend}/operation${endpointApi}/${editedId}`
      isRequesting.value = true

      const params = { transform: false, detail: true }
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
      if(initialValues.is_active){
        initialValues.is_active = 'true' ? initialValues.is_active = 1 : initialValues.is_active = 0
      }
      if(initialValues['tipe_jam_kerja.value'] == 'OFFICE'){
        getJadwalKerjaOffice()
      }
      initialValues['m_kary_det_pend']?.forEach(async (item) => {
        const res = await fetch(`${store.server.url_backend}/operation/m_general/${item.tingkat_id}`,  {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!res.ok) throw new Error("Failed when trying to read data")
        const resultJson = await res.json()
        const tempinitialValues = resultJson.data
        item.tingkat = tempinitialValues.value
        item._id = ++_idPend
        item.is_pend_terakhir ? item.is_pend_terakhir = 1 : item.is_pend_terakhir = 0
        detailPendidikan.value.push(item)
      })
      initialValues['m_kary_det_bhs']?.forEach((item) => {
        item._id = ++_idBhs
        detailBahasa.value.push(item)
      })
      initialValues['m_kary_det_kel']?.forEach(async (item) => {
        const res = await fetch(`${store.server.url_backend}/operation/m_general/${item.keluarga_id}`,  {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!res.ok) throw new Error("Failed when trying to read data")
        const resultJson = await res.json()
        const tempinitialValues = resultJson.data
        const res2 = await fetch(`${store.server.url_backend}/operation/m_general/${item.pend_terakhir_id}`,  {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!res2.ok) throw new Error("Failed when trying to read data")
        const resultJson2 = await res2.json()
        const tempinitialValues2 = resultJson2.data
        const res3 = await fetch(`${store.server.url_backend}/operation/m_general/${item.jk_id}`,  {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!res3.ok) throw new Error("Failed when trying to read data")
        const resultJson3 = await res3.json()
        const tempinitialValues3 = resultJson3.data
        const res4 = await fetch(`${store.server.url_backend}/operation/m_general/${item.pekerjaan_id}`,  {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!res4.ok) throw new Error("Failed when trying to read data")
        const resultJson4 = await res4.json()
        const tempinitialValues4 = resultJson4.data
        item.jk = tempinitialValues3.value
        item.keluarga = tempinitialValues.value
        item.pendidikan = tempinitialValues2.value
        item.pekerjaan = tempinitialValues4.value
        item._id = ++_idKel
        detailKeluarga.value.push(item)
      })
      
      initialValues['m_kary_det_pk']?.forEach((item) => {
        item._id = ++_idPk
        detailPengalaman.value.push(item)
      })
      initialValues['m_kary_det_org']?.forEach(async (item) => {
        const res = await fetch(`${store.server.url_backend}/operation/m_general/${item.jenis_org_id}`,  {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!res.ok) throw new Error("Failed when trying to read data")
        const resultJson = await res.json()
        const tempinitialValues = resultJson.data
        const res2 = await fetch(`${store.server.url_backend}/operation/m_general/${item.kota_id}`,  {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!res2.ok) throw new Error("Failed when trying to read data")
        const resultJson2 = await res2.json()
        const tempinitialValues2 = resultJson2.data
        item.kota = tempinitialValues2.value
        item.jenis = tempinitialValues.value
        item._id = ++_idOrg
        detailOrganisasi.value.push(item)
      })
      initialValues['m_kary_det_pres']?.forEach(async (item) => {
        const res = await fetch(`${store.server.url_backend}/operation/m_general/${item.tingkat_pres_id}`,  {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!res.ok) throw new Error("Failed when trying to read data")
        const resultJson = await res.json()
        const tempinitialValues = resultJson.data
        item.tingkat = tempinitialValues.value
        item._id = ++_idPres
        detailPrestasi.value.push(item)
      })
      initialValues['m_kary_det_pel']?.forEach(async (item) => {
        const res = await fetch(`${store.server.url_backend}/operation/m_general/${item.kota_id}`,  {
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
        })
        if (!res.ok) throw new Error("Failed when trying to read data")
        const resultJson = await res.json()
        const tempinitialValues = resultJson.data
        item.kota = tempinitialValues.value
        item._id = ++_idPel
        detailPelatihan.value.push(item)
      })
      if(initialValues.info_cuti){
        for (let key in initialValues.info_cuti) {
            if (initialValues.info_cuti.hasOwnProperty(key) && initialValues.info_cuti[key] === null) {
                initialValues.info_cuti[key] = 0;
            }
        }
      }
      initialValues = {...initialValues, ...initialValues.info_cuti}
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
  if(values.m_kary_det_pemb?.length > 0){
    values.periode_gaji_id = values.m_kary_det_pemb[0].periode_gaji_id
    values.metode_id = values.m_kary_det_pemb[0].metode_id
    values.tipe_id = values.m_kary_det_pemb[0].tipe_id
    values.bank_id = values.m_kary_det_pemb[0].bank_id
    values.atas_nama_rek = values.m_kary_det_pemb[0].atas_nama_rek
    values.no_rek = values.m_kary_det_pemb[0].no_rek
  }
  if(values.m_kary_det_kartu?.length > 0){
    values.ktp_no = values.m_kary_det_kartu[0].ktp_no
    values.kk_no = values.m_kary_det_kartu[0].kk_no
    values.npwp_no = values.m_kary_det_kartu[0].npwp_no
    values.npwp_tgl_berlaku = values.m_kary_det_kartu[0].npwp_tgl_berlaku
    values.bpjs_tipe_id = values.m_kary_det_kartu[0].bpjs_tipe_id
    // values.bpjs_no = values.m_kary_det_kartu[0].bpjs_no
    values.bpjs_no_kesehatan = values.m_kary_det_kartu[0].bpjs_no_kesehatan
    values.bpjs_no_ketenagakerjaan = values.m_kary_det_kartu[0].bpjs_no_ketenagakerjaan
    values.desc_file = values.m_kary_det_kartu[0].desc_file
    values.berkas_lain = values.m_kary_det_kartu[0].berkas_lain
    // urlBPJSFoto.value = values.m_kary_det_kartu[0].bpjs_foto
    urlPasFoto.value = values.m_kary_det_kartu[0].pas_foto
    urlKKFoto.value = values.m_kary_det_kartu[0].kk_foto
    urlKTPFoto.value = values.m_kary_det_kartu[0].ktp_foto
    urlNPWPFoto.value = values.m_kary_det_kartu[0].npwp_foto
   
    tempBPJS = values.m_kary_det_kartu[0].bpjs_foto
    tempNPWP = values.m_kary_det_kartu[0].npwp_foto
    tempPasfoto = values.m_kary_det_kartu[0].pas_foto
    tempKTP = values.m_kary_det_kartu[0].ktp_foto
    tempKK = values.m_kary_det_kartu[0].kk_foto
  }
})

async function getJadwalKerjaOffice() {
  try {
    const response = await fetch(`${store.server.url_backend}/operation/t_jadwal_kerja/get_jadwal_office`, {
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
    console.log(data)
    console.log(data?.data?.id)
    values.t_jadwal_kerja_id = data?.data?.id
    values.t_jadwal_kerja_ket = data?.data?.keterangan
   } catch (error) {
    console.error('Error fetching tunjangan kemahalan:', error);

  }
}
const changeTipeJamKerja = (v) => {
  console.log(v)
  console.log("OK", v.value)
  values['tipe_jam_kerja.value'] = v.value
  if(v.value?.toLowerCase() == 'office'){
    if(initialValues.jadwal_kerja?.id){
      values.t_jadwal_kerja_id = initialValues.jadwal_kerja?.id
    }else{
      getJadwalKerjaOffice()
    }
    
  }
}

// preview image
const refPasFoto = ref()
const urlPasFoto = ref('')
const refKTPFoto = ref()
const urlKTPFoto = ref('')
const urlKKFoto = ref('')
const urlNPWPFoto = ref('')
const urlBPJSFoto = ref('')
const urlImg = ref('')
async function imageChange(e){
  const file = e.target.files
  // console.log(e.target.id)
  if(file[0]){
    const maxAllowedSize = 1 * 1024 * 1024;
    if (file[0].size >= maxAllowedSize) {
      swal.fire({
        icon: 'error',
        text: 'Error: File terlalu besar, max 1MB'
      })
      e.target.value = null
      return
    }
    if(e.target.id === 'inputPasFoto')
    {
      var formData = new FormData()
      formData.append('file', file[0])
      // console.log(file[0])
      const res = await fetch(`${store.server.url_backend}/operation/m_kary_det_kartu/upload` + '?' + `field=pas_foto`, {
        method: 'POST',
        headers: {
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: formData
      })
      if(res.ok){
        tempPasfoto = file[0].name
        urlPasFoto.value = URL.createObjectURL(file[0])
      }
    }
    else if(e.target.id === 'inputKTPFoto'){
      var formData = new FormData()
      formData.append('file', file[0])
      // console.log(file[0])
      const res = await fetch(`${store.server.url_backend}/operation/m_kary_det_kartu/upload` + '?' + `field=ktp_foto`, {
        method: 'POST',
        headers: {
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: formData
      })
      if(res.ok){
        tempKTP = file[0].name
        urlKTPFoto.value = URL.createObjectURL(file[0])
      }
    }
    else if(e.target.id === 'inputKKFoto'){
      var formData = new FormData()
      formData.append('file', file[0])
      // console.log(file[0])
      const res = await fetch(`${store.server.url_backend}/operation/m_kary_det_kartu/upload` + '?' + `field=kk_foto`, {
        method: 'POST',
        headers: {
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: formData
      })
      if(res.ok){
        tempKK = file[0].name
        urlKKFoto.value = URL.createObjectURL(file[0])
      }
    }
    else if(e.target.id === 'inputNPWPFoto'){
      var formData = new FormData()
      formData.append('file', file[0])
      // console.log(file[0])
      const res = await fetch(`${store.server.url_backend}/operation/m_kary_det_kartu/upload` + '?' + `field=npwp_foto`, {
        method: 'POST',
        headers: {
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: formData
      })
      if(res.ok){
        tempNPWP = file[0].name
        urlNPWPFoto.value = URL.createObjectURL(file[0])
      }
    }
    else if(e.target.id === 'inputBPJSFoto'){
      var formData = new FormData()
      formData.append('file', file[0])
      // console.log(file[0])
      const res = await fetch(`${store.server.url_backend}/operation/m_kary_det_kartu/upload` + '?' + `field=bpjs_foto`, {
        method: 'POST',
        headers: {
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: formData
      })
      if(res.ok){
        tempBPJS = file[0].name
        urlBPJSFoto.value = URL.createObjectURL(file[0])
      }
    }
  }
}

let ArrTahun = []
for(let i = thisYear; i >= 1973; i--) {
  ArrTahun.push(i)
}

// Pendidikan
let _idPend = 0
const detailPendidikan = ref([])
const fileIjz = ref(null)
async function fileIjazah(e){
  const file = e.target.files
  if(file[0]){
    const maxAllowedSize = 1 * 1024 * 1024;
    if (file[0].size >= maxAllowedSize) {
      swal.fire({
        icon: 'error',
        text: 'File terlalu besar, max 1MB'
      })
      e.target.value = null
      return
    }
    valuesPendidikan.ijazah_foto = file[0].name
    var formData = new FormData()
    formData.append('file', file[0])
      const res = await fetch(`${store.server.url_backend}/operation/m_kary_det_pend/upload` + '?' + `field=ijazah_foto`, {
        method: 'POST',
        headers: {
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: formData
      })
    //   if(res.ok){
    //     const resData = await res.json()
    //     const data = resData.key
    //     const _data = data.split("m_kelas_d_gambar_foto_1_")[1]
    //     // const _data = data.split('_')[data.split('_').length-1]
    //     // addImage(resData.key)
    //     // addImage(_data)
    //   }
  }
}
const addPendidikan = async () => {
  var tempObj = {}
  valuesPendidikan._id = ++_idPend
  for (const key in valuesPendidikan) {
    if(key !== 'desc'){
      if (valuesPendidikan[key] == null) {
        tempObj[key] = ['Bidang ini wajib diisi']
      }
    }
  }
  if(Object.keys(tempObj).length >= 1){
    formErrorsPend.value = tempObj
    swal.fire({
      icon: 'error',
      text: 'Masih ada field yang belum terisi'
    })
    return
  }
  detailPendidikan.value = [...detailPendidikan.value, {...valuesPendidikan}]
  Object.keys(valuesPendidikan).forEach(key => valuesPendidikan[key] = null)
  fileIjz.value.value = null
  formErrorsPend.value = {}
  valuesPendidikan.thn_masuk = thisYear
  valuesPendidikan.thn_lulus = thisYear
}

// Keluarga
let _idKel = 0
const detailKeluarga = ref([])

const addKeluarga = async () => {
  var tempObj = {}
  valuesKeluarga._id = ++_idKel
  for (const key in valuesKeluarga) {
    if(key !== 'desc'){
      if (valuesKeluarga[key] == null) {
        tempObj[key] = ['Bidang ini wajib diisi']
      }
    }
  }
  if(Object.keys(tempObj).length >= 1){
    formErrorsKel.value = tempObj
    swal.fire({
      icon: 'error',
      text: 'Masih ada field yang belum terisi'
    })
    return
  }
  detailKeluarga.value = [...detailKeluarga.value, {...valuesKeluarga}]
  Object.keys(valuesKeluarga).forEach(key => valuesKeluarga[key] = null)
  // gambarKK.value.value = null
  formErrorsKel.value = {}
}

// Pelatihan
let _idPel = 0
const detailPelatihan = ref([])
const addPelatihan = async () => {
  var tempObj = {}
  valuesPelatihan._id = ++_idPel
  for (const key in valuesPelatihan) {
    if(key !== 'catatan'){
      if (valuesPelatihan[key] == null) {
        tempObj[key] = ['Bidang ini wajib diisi']
      }
    }
  }
  if(Object.keys(tempObj).length >= 1){
    formErrorsPel.value = tempObj
    swal.fire({
      icon: 'error',
      text: 'Masih ada field yang belum terisi'
    })
    return
  }
  detailPelatihan.value = [...detailPelatihan.value, {...valuesPelatihan}]
  Object.keys(valuesPelatihan).forEach(key => valuesPelatihan[key] = null)
  formErrorsPel.value = {}
  valuesPelatihan.tahun = thisYear
}

// Prestasi
let _idPres = 0
const detailPrestasi = ref([])
const addPrestasi = async () => {
  var tempObj = {}
  valuesPrestasi._id = ++_idPres
  for (const key in valuesPrestasi) {
    if(key !== 'catatan'){
      if (valuesPrestasi[key] == null) {
        tempObj[key] = ['Bidang ini wajib diisi']
      }
    }
  }
  if(Object.keys(tempObj).length >= 1){
    formErrorsPres.value = tempObj
    swal.fire({
      icon: 'error',
      text: 'Masih ada field yang belum terisi'
    })
    return
  }
  detailPrestasi.value = [...detailPrestasi.value, {...valuesPrestasi}]
  Object.keys(valuesPrestasi).forEach(key => valuesPrestasi[key] = null)
  formErrorsPres.value = {}
  valuesPrestasi.tahun = thisYear
}

// Organisasi
let _idOrg = 0
const detailOrganisasi = ref([])
const addOrganisasi = async () => {
  var tempObj = {}
  valuesOrganisasi._id = ++_idOrg
  for (const key in valuesOrganisasi) {
    if(key !== 'catatan'){
      if (valuesOrganisasi[key] == null) {
        tempObj[key] = ['Bidang ini wajib diisi']
      }
    }
  }
  if(Object.keys(tempObj).length >= 1){
    formErrorsOrg.value = tempObj
    swal.fire({
      icon: 'error',
      text: 'Masih ada field yang belum terisi'
    })
    return
  }
  detailOrganisasi.value = [...detailOrganisasi.value, {...valuesOrganisasi}]
  Object.keys(valuesOrganisasi).forEach(key => valuesOrganisasi[key] = null)
  formErrorsOrg.value = {}
  valuesOrganisasi.tahun = thisYear
}

// Bahasa
let _idBhs = 0
const detailBahasa = ref([])
const addBahasa = async () => {
  var tempObj = {}
  valuesBahasa._id = ++_idBhs
  for (const key in valuesBahasa) {
    if(key !== 'catatan'){
      if (valuesBahasa[key] == null) {
        tempObj[key] = ['Bidang ini wajib diisi']
      }
    }
  }
  if(Object.keys(tempObj).length >= 1){
    formErrorsBhs.value = tempObj
    swal.fire({
      icon: 'error',
      text: 'Masih ada field yang belum terisi'
    })
    return
  }
  detailBahasa.value = [...detailBahasa.value, {...valuesBahasa}]
  Object.keys(valuesBahasa).forEach(key => valuesBahasa[key] = null)
  formErrorsBhs.value = {}
}

// Pengalaman Kerja
let _idPk = 0
const detailPengalaman = ref([])
const fileSurat = ref(null)
async function fileSrtRef(e){
  const file = e.target.files
  if(file[0]){
    const maxAllowedSize = 1 * 1024 * 1024;
    if (file[0].size >= maxAllowedSize) {
      swal.fire({
        icon: 'error',
        text: 'File terlalu besar, max 1MB'
      })
      e.target.value = null
      return
    }
    valuesPengalaman.surat_referensi = file[0].name
    var formData = new FormData()
    formData.append('file', file[0])
      const res = await fetch(`${store.server.url_backend}/operation/m_kary_det_pk/upload` + '?' + `field=surat_referensi`, {
        method: 'POST',
        headers: {
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
        body: formData
      })
  }
}
const addPengalaman = async () => {
  var tempObj = {}
  valuesPengalaman._id = ++_idPk
  for (const key in valuesPengalaman) {
    if(key !== 'catatan'){
      if (valuesPengalaman[key] == null) {
        tempObj[key] = ['Bidang ini wajib diisi']
      }
    }
  }
  if(Object.keys(tempObj)?.length >= 1){
    formErrorsPK.value = tempObj
    swal.fire({
      icon: 'error',
      text: 'Masih ada field yang belum terisi'
    })
    return
  }
  detailPengalaman.value = [...detailPengalaman.value, {...valuesPengalaman}]
  Object.keys(valuesPengalaman).forEach(key => valuesPengalaman[key] = null)
  fileSurat.value.value = null
  formErrorsPK.value = {}
  valuesPengalaman.thn_masuk = thisYear
  valuesPengalaman.thn_keluar = thisYear
}


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
      try {
        
        values.nama_lengkap = values.nama_depan + ' ' +values.nama_belakang
        values.m_kary_det_pres = detailPrestasi.value
        if(values.periode_gaji_id){
          values.m_kary_det_pemb = [{
            periode_gaji_id: values.periode_gaji_id,
            metode_id: values.metode_id,
            tipe_id: values.tipe_id,
            bank_id: values.bank_id,
            no_rek: values.no_rek,
            atas_nama_rek: values.atas_nama_rek,
            desc: values.desc,
            is_active: values.periode_gaji_idtrue,
          }]
        }
        // if(detailKeluarga.value.length === 0){
        //   throw ("Tab Keluarga Tidak Boleh Kosong")
        // }
        // if(detailPendidikan.value.length === 0){
        //   throw ("Tab Pendidikan Tidak Boleh Kosong")
        // }
        values.m_kary_det_kel = detailKeluarga.value
        values.m_kary_det_org = detailOrganisasi.value
        values.m_kary_det_bhs = detailBahasa.value
        values.m_kary_det_pend = detailPendidikan.value
        values.m_kary_det_pel = detailPelatihan.value
        values.m_kary_det_pk = detailPengalaman.value
        const isCreating = ['Create','Copy','Tambah'].includes(actionText.value)
        // console.log(values.m_kary_det_kartu.length)
        if(isCreating || values.m_kary_det_kartu?.length === 0){

          values.m_kary_det_kartu = [{
            ktp_no: values.ktp_no,
            ktp_foto: tempKTP,
            pas_foto: tempPasfoto,
            kk_no: values.kk_no,
            kk_foto: tempKK,
            npwp_no: values.npwp_no,
            npwp_foto: tempNPWP,
            npwp_tgl_berlaku: values.npwp_tgl_berlaku,
            bpjs_tipe_id: values.bpjs_tipe_id,
            bpjs_no_kesehatan: values.bpjs_no_kesehatan,
            bpjs_no_ketenagakerjaan: values.bpjs_no_ketenagakerjaan,
            // bpjs_no: values.bpjs_no,
            // bpjs_foto: tempBPJS,
            berkas_lain: values.berkas_lain,
            desc_file: values.desc_file,
            is_active: true
          }]
        }else{   
          console.log(values.m_kary_det_kartu)
          values.m_kary_det_kartu[0].ktp_no = values.ktp_no
          if(initialValues.m_kary_det_kartu[0]?.ktp_foto !== tempKTP){
            values.m_kary_det_kartu[0].ktp_foto = tempKTP
          }
          if(initialValues.m_kary_det_kartu[0]?.pas_foto !== tempPasfoto){
            values.m_kary_det_kartu[0].pas_foto = tempPasfoto
          }
          values.m_kary_det_kartu[0].kk_no = values.kk_no
          if(initialValues.m_kary_det_kartu[0]?.kk_foto !== tempKK){
            values.m_kary_det_kartu[0].kk_foto = tempKK
          }
          values.m_kary_det_kartu[0].npwp_no = values.npwp_no
          values.m_kary_det_kartu[0].npwp_tgl_berlaku = values.npwp_tgl_berlaku
          if(initialValues.m_kary_det_kartu[0]?.npwp_foto !== tempNPWP){
            values.m_kary_det_kartu[0].npwp_foto = tempNPWP
          }
          values.m_kary_det_kartu[0].bpjs_tipe_id = values.bpjs_tipe_id
          // values.m_kary_det_kartu[0].bpjs_no = values.bpjs_no
          values.m_kary_det_kartu[0].bpjs_no_kesehatan = values.bpjs_no_kesehatan
          values.m_kary_det_kartu[0].bpjs_no_ketenagakerjaan = values.bpjs_no_ketenagakerjaan
          values.m_kary_det_kartu[0].berkas_lain = values.berkas_lain
          values.m_kary_det_kartu[0].desc_file = values.desc_file
          // if(initialValues.m_kary_det_kartu[0].bpjs_foto !== tempBPJS){
          //   values.m_kary_det_kartu[0].bpjs_foto = tempBPJS
          // }

        }
        const dataURL = `${store.server.url_backend}/operation${endpointApi}${isCreating ? '' : ('/' + route.params.id)}`
        isRequesting.value = true
        const res = await fetch(dataURL, {
          method: isCreating ? 'POST' : 'PUT',
          headers: {
            'Content-Type': 'Application/json',
            Authorization: `${store.user.token_type} ${store.user.token}`
          },
          body: JSON.stringify(values)
        })
        if (!res.ok) {
          // values.m_kary_det_kartu = []
          if ([400, 422].includes(res.status)) {
            const responseJson = await res.json()
            formErrors.value = responseJson.errors || {}
            throw (responseJson.errors?.length ? responseJson.errors[0] : responseJson.message || "Failed when trying to post data")
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
console.log(route.path)

const activeBtn = ref()

function filterShowData(params,noBtn){
  if(activeBtn.value === noBtn){
    activeBtn.value = null
  }else{
    activeBtn.value = noBtn
  }
  if(params){
    landing.api.params.where = `this.is_active=true`
  }else if(activeBtn.value == null){
    // clear params filter
    landing.api.params.where = null
  }else{
    landing.api.params.where = `this.is_active=false`
  }

  apiTable.value.reload()
}

const landing = reactive({
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
                const resultJson = await res.json()
                throw (resultJson.message || "Failed when trying to remove data")
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
    },
    {
      icon: 'database',
      title: "Adjusment Cuti",
      class: 'bg-yellow-600 text-light-100',
      click(row) {
        router.replace(`/adj_cuti/${row.id}?action=Adjusment&`+tsId)
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
      searchfield:'this.id, this.nik, this.kode, atasan.nama_lengkap, this.nama_lengkap, m_dir.nama, m_dept.nama, this.alamat_domisili, this.no_tlp',
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
    field: 'kode',
    headerName:'NIK',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'nomor_ktp',
    headerName:'No KTP',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Nama',
    field: 'nama_lengkap',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    field: 'atasan.nama_lengkap',
    headerName:'Atasan',
    filter: true,
    sortable: true,
    flex:1,
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Departemen',
    field: 'm_dept.nama',
    filter: true,
    sortable: true,
    filter: 'ColFilter',
    resizable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
  {
    headerName: 'Status',
    field: 'is_active',
    filter: true,
    // resizable: true,
    // valueGetter: (p) => p.node.data['status'].toLowerCase()==='active'? 'Aktif':'Tidak Aktif',
    sortable: true,
    flex:1,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-center'],
    cellRenderer: ({ value }) => {
      return value === true
        ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Active</span>`
        : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">Inactive</span>`
    }
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