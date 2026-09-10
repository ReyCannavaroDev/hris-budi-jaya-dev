import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount,onBeforeUnmount, watchEffect, onActivated ,onUnmounted } from 'vue'

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
const content = ref()

const tsId = `ts=`+(Date.parse(new Date()))

// ------------------------------ PERSIAPAN
const endpointApi = '/m_kary'
onBeforeMount(()=>{
  document.title = 'Absen Online'
})

//  @if( $id )------------------- VALUES FORM ! PENTING JANGAN DIHAPUS


//  @else----------------------- LANDING

//  @endif -------------------------------------------------END
const tempDate = new Date();
const listTahun = [];
const tempmonth = tempDate.getMonth() + 1;
const tempyear = tempDate.getFullYear();

const form = reactive({
  month: tempmonth,
  year: tempyear,
  currentTime: "",
  tanggal: "",
  day: "",
  attending: "",
  address: "",
  distance_check: null,
});


const videoElement = ref(null);
const capturedImage = ref(null);
const coordsLocation = ref();
const listDetail = ref([]);
const isImage = ref(false);
const formData = new FormData();
const dataDetail = ref();
const showModal = ref(false);
const resetTimer = ref(null);

const listMonths = [
  { id: 1, name: "Januari" },
  { id: 2, name: "Februari" },
  { id: 3, name: "Maret" },
  { id: 4, name: "April" },
  { id: 5, name: "Mei" },
  { id: 6, name: "Juni" },
  { id: 7, name: "Juli" },
  { id: 8, name: "Agustus" },
  { id: 9, name: "September" },
  { id: 10, name: "Oktober" },
  { id: 11, name: "November" },
  { id: 12, name: "Desember" }
];

onMounted(() => {
  // Update currentTime every second
  setInterval(() => {
    const now = new Date();
    form.currentTime = now.toLocaleTimeString(); // atau format waktu lain yang kamu inginkan
  }, 1000); // update setiap 1 detik
});

onMounted(async () => {
  isRequesting.value = true;
  await getLocation();
  await checkLastStatus();
  await getDetailAbsen(form.year, form.month);
  const tempDate = new Date();
  const day = tempDate.getDate();
  const monthName = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
  ][tempDate.getMonth()];
  const year = tempDate.getFullYear();
  form.tanggal = `${day} ${monthName} ${year}`;
  form.day = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][tempDate.getDay()];
  isRequesting.value = false;
});

async function tampilkanModal(data){
  showModal.value = true
  dataDetail.value = data
  console.log(data,'cok')
}

async function getDetailAbsen(year,month){
  try{
      const params = `${year}-${month}`
      const res = await fetch(`${store.server.url_backend}/operation/presensi_absensi/get_absen?periode=${params}`, {
        headers: {
          'Content-Type': 'Application/json',
          Authorization: `${store.user.token_type} ${store.user.token}`
        },
      })  
      if (!res.ok) {
        if ([400, 422].includes(res.status)) {
          const responseJson = await res.json()
          throw (responseJson.message || "Failed when trying to post data")
        } else {
          throw ("Failed when trying to post data")
        }
      }
      const resultJson = await res?.json()
      const data = resultJson.data
      listDetail.value = data
    }catch(err){
      swal.fire({
        icon: 'error',
        text: err
      })
    }
}




let captureTimer = null;  // Menyimpan timer capture
async function capture() {
  try {
    const canvas = document.createElement('canvas');
    canvas.width = videoElement.value.videoWidth;
    canvas.height = videoElement.value.videoHeight;
    canvas.getContext('2d').translate(canvas.width, 0);
    canvas.getContext('2d').scale(-1, 1);
    canvas.getContext('2d').drawImage(videoElement.value, 0, 0, canvas.width, canvas.height);
    const captredImageSrc = canvas.toDataURL('image/jpeg');
    let imgElem = document.getElementById('imgElem');
    imgElem.setAttribute('src', captredImageSrc);
    isImage.value=true
    const stream = videoElement.value.srcObject;
    const capturedImageBlob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg'));
    formData.append('foto', capturedImageBlob, 'captured_image.jpg');
    // formData.forEach((value, key) => {
    //   if (value instanceof File) {
    //     console.log(`${key}: ${value.name} (${value.type}), ${value.size} bytes`);
    //   } else {
    //     console.log(`${key}: ${value}`);
    //   }
    // });
    // capturedImage.value = formData
    stream.getTracks()?.forEach((track)=>{
      track.stop()
    })
    getLocation()
  } catch (error) {
    console.log(error)
    alert("Oh maaf, sepertinyßa kami tidak mendapatkan akses kamera anda");
  }
}

async function recapture(){
  mountCam()
  isImage.value = false
}
function getLocation() {
  console.log(navigator.geolocation);
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(setPosition, showError);
  } else {
    swal.fire({
      icon: 'error',
      text: "Geolocation is not supported by this browser."
    });
  }
}

function showError(error) {
  switch (error.code) {
    case error.PERMISSION_DENIED:
      swal.fire({
        icon: 'error',
        text: "Please enable location permissions to access your current location.",
      });
      break;
    case error.POSITION_UNAVAILABLE:
      swal.fire({
        icon: 'error',
        text: "Location information is unavailable."
      });
      break;
    case error.TIMEOUT:
      swal.fire({
        icon: 'error',
        text: "The request to get user location timed out."
      });
      break;
    case error.UNKNOWN_ERROR:
      swal.fire({
        icon: 'error',
        text: "An unknown error occurred."
      });
      break;
  }
}





function removeStrip(data){
  const tempTest = data?.split('-')
  const monthName = ["Januari", "Februari", "Maret", "April","Mei", "Juni", "Juli", "Agustus","September", "Oktober", "November", "Desemeber"][tempTest[1]-1]
  return `${tempTest[0]} ${monthName} ${tempTest[2]}`
  // console.log(data)
  // return data?.replace(/-/g,' ')
}

let positionInterval = null;

// Fungsi untuk membersihkan interval dan menghentikan proses
function cleanupPositionTracking() {
  if (positionInterval) {
    clearInterval(positionInterval); // Hentikan interval
    positionInterval = null; // Reset variabel interval
  }
}

// Watcher untuk mendeteksi perubahan token atau status login
watchEffect(() => {
  if (!store.user.token) {
    // Jika token tidak ada (logout), bersihkan interval dan hentikan tracking
    cleanupPositionTracking();
  }
});

// Lifecycle hook untuk membersihkan interval saat komponen di-unmount
onUnmounted(() => {
  cleanupPositionTracking();
});

// Fungsi setPosition dengan penyesuaian
async function setPosition(position) {
  coordsLocation.value = position.coords;
  form.loadingAddress = true; // Mulai loading alamat
  try {
    const res = await fetch(
      `https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`
    );
    const resultJson = await res?.json();
    form.address = resultJson?.display_name;
    await getDistance(position.coords.latitude, position.coords.longitude);
  } catch (err) {
    swal.fire({
      icon: 'error',
      text: err,
    });
  } finally {
    form.loadingAddress = false; // Selesai loading alamat
  }
}

// Watcher untuk memulai interval hanya jika token ada
watchEffect(() => {
  if (form.address && store.user.token) {
    cleanupPositionTracking(); // Pastikan interval sebelumnya dibersihkan

    // Set interval baru untuk memanggil setPosition setiap 4 detik
    positionInterval = setInterval(() => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(setPosition, showError);
      }
    }, 4000); // Setiap 4 detik
  } else {
    cleanupPositionTracking(); // Bersihkan interval jika token tidak ada
  }
});

async function getDistance(lat, long) {
  try {
    const res = await fetch(`${store.server.url_backend}/operation/presensi_absensi/distance_check?lat=${lat}&long=${long}`, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`,
      },
    });
    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json();
        throw (responseJson.message || "Failed when trying to post data");
      } else {
        throw ("Failed when trying to post data");
      }
    }
    const resultJson = await res?.json();
    const data = resultJson.data;
    form.distance_check = data?.on_scope;
  } catch (err) {
    swal.fire({
      icon: 'error',
      text: err
    });
  }
}

async function mountCam() {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
    videoElement.value.srcObject = stream;
  } catch (error) {
    alert("Oh maaf, sepertinya kami tidak mendapatkan akses kamera anda");
  }
}

async function checkLastStatus(){
  try{
    const res = await fetch(`${store.server.url_backend}/operation/presensi_absensi/status`, {
      headers: {
        'Content-Type': 'Application/json',
        Authorization: `${store.user.token_type} ${store.user.token}`
      },
    })  
    if (!res.ok) {
      const responseJson = await res.json()
      if ([400, 422].includes(res.status)) {
        throw (responseJson.message || "Failed when trying to post data")
      } else {
        throw (responseJson.message || "Failed when trying to post data")
      }
    }
    const resultJson = await res?.json()
    const data = resultJson.data
    form.attending = data.status
    if(form.attending?.toLowerCase() === 'attend'){
      // const stream = videoElement.value.srcObject
      // stream?.getTracks()?.forEach((track)=>{
      //   track.stop()
      // })
    }else{
      mountCam()
    }
  }catch(err){
    swal.fire({
      icon: 'error',
      text: err
    })
  }
}

async function postAttend() {
  try {
    // Validasi lokasi
    if (!coordsLocation.value?.latitude || !coordsLocation.value?.longitude || !form.address) {
      const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

      swal.fire({
        icon: 'warning',
        title: 'GPS Tidak Aktif',
        text: 'Jika belum tahu cara menyalakan Lokasi / GPS, silakan klik tombol di bawah ini:',
        iconColor: '#FFA500',
        confirmButtonColor: '#FFA500',
        confirmButtonText: 'OK',
        footer: `
          <div style="display: flex; justify-content: center; gap: 10px;">
            ${isMobile ? `
              <a href="https://www.youtube.com/watch?v=iHvBn1Kx0hI" target="_blank" style="display: inline-block; padding: 8px 15px; background-color: #FFA500; color: white; font-weight: bold; border-radius: 5px; text-decoration: none;">ANDROID</a>
              <a href="https://www.youtube.com/watch?v=ZAeCVhYTCjU" target="_blank" style="display: inline-block; padding: 8px 15px; background-color: #FFA500; color: white; font-weight: bold; border-radius: 5px; text-decoration: none;">IOS</a>
            ` : `
              <a href="https://www.youtube.com/watch?v=aCc_oYT0g5c" target="_blank" style="display: inline-block; padding: 8px 15px; background-color: #FFA500; color: white; font-weight: bold; border-radius: 5px; text-decoration: none;">TUTORIAL</a>
            `}
          </div>
        `
      });
      return;
    }

    // Persiapkan data untuk dikirim
    formData.append('lat', coordsLocation.value.latitude);
    formData.append('long', coordsLocation.value.longitude);
    formData.append('address', form.address);

    let postData = {
      foto: capturedImage.value,
      lat: coordsLocation.value.latitude,
      long: coordsLocation.value.longitude,
      address: form.address,
    };

    // Kirim data ke server
    const res = await fetch(
      `${store.server.url_backend}/operation/presensi_absensi/${form.attending?.toLowerCase() === 'not attend' ? 'checkin' : 'checkout'}`,
      {
        method: 'POST',
        headers: {
          Authorization: `${store.user.token_type} ${store.user.token}`,
        },
        body: formData,
      }
    );

    // Handle error jika ada
    if (!res.ok) {
      if ([400, 422].includes(res.status)) {
        const responseJson = await res.json();
        throw responseJson.message || 'Failed when trying to post data';
      } else {
        throw 'Failed when trying to post data';
      }
    }

    // Tangani respons sukses
    const resultJson = await res.json();
    swal.fire({
      icon: 'success',
      text: resultJson.message,
      iconColor: '#1469AE',
      confirmButtonColor: '#1469AE',
    }).then(async (res) => {
      if (res.isConfirmed) {
        await getLocation();
        await checkLastStatus();
        await getDetailAbsen(form.year, form.month);
        isImage.value = false;

        // Hentikan interval setPosition setelah absensi berhasil
        cleanupPositionTracking();
      }
    });
  } catch (err) {
    swal.fire({
      icon: 'error',
      text: err,
    });
  }
}


watchEffect(() => {
  if (form.address) {
    // Bisa melakukan aksi lain jika diperlukan ketika alamat sudah ada
    console.log('Alamat telah diperbarui:', form.address);
  }
});



