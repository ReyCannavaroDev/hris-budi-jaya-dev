import { useRouter, useRoute, RouterLink } from 'vue-router'
import { ref, readonly, reactive, inject, onMounted, onBeforeMount,onBeforeUnmount, watchEffect, onActivated } from 'vue'

const currentYear = ref(new Date().getFullYear());
const currentMonth = ref('');
const currentDate = ref(new Date().getDate())
const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
const calendarRows = ref([]);
const dateNow = ref()
var months = [
  "Januari", "Februari", "Maret", "April", "Mei", "Juni",
  "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];

const initializeCalendar = () => {
  const currentDate = new Date();
  currentMonth.value = currentDate.toLocaleString('default', { month: 'long' });
  const daysInMonth = new Date(currentYear.value, currentDate.getMonth() + 1, 0).getDate();
  const firstDay = new Date(currentYear.value, currentDate.getMonth(), 1).getDay();
  let tempCalendarRows = [];
  let dayCounter = 1;

  for (let i = 0; i < 6; i++) {
    let row = [];
    for (let j = 0; j < 7; j++) {
      if (i === 0 && j < firstDay) {
        row.push({ day: '', date: '' });
      } else if (dayCounter > daysInMonth) {
        break;
      } else {
        const date = new Date(currentYear.value, currentDate.getMonth(), dayCounter);
        if(isToday(date)){
          dateNow.value = formatDate(date)
        }
        row.push({ day: dayCounter, date: date, isToday: isToday(date) });
        dayCounter++;
      }
    }
    tempCalendarRows.push(row);
    if (dayCounter > daysInMonth) break;
  }

  calendarRows.value = tempCalendarRows;
};

const isToday = (date) => {
  const today = new Date();
  return date.getFullYear() === today.getFullYear() && date.getMonth() === today.getMonth() && date.getDate() === today.getDate();
};

const formatDate = (date) => {
  const day = date.getDate();
  const month = date.getMonth() + 1;
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

const handleDateClick = (date) => {
  console.log(dateNow.value)
  if(date){
    calendarRows.value.forEach(row => {
      row.forEach(cell => {
        cell.isToday = cell.date && cell.date.toDateString() === date.toDateString(); // Mengatur isToday berdasarkan tanggal yang diklik
      });
    });
    console.log('Tanggal:', formatDate(date));
  }
};

onMounted(() => {
  console.log(store.user,'cok')
  initializeCalendar();
});

const router = useRouter()
const route = useRoute()
const store = inject('store')
const swal = inject('swal')
const dataChart = [
    {
        name: 'Workout',
        data: {
            '2017-01-01 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-02 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-03 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-04 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-05 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-06 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-07 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-08 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-09 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-10 00:00:00 -0800': Math.floor(Math.random() * 10)
        }
    },
    {
        name: 'Call parents',
        data: {
            '2017-01-01 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-02 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-03 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-04 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-05 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-06 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-07 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-08 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-09 00:00:00 -0800': Math.floor(Math.random() * 10),
            '2017-01-10 00:00:00 -0800': Math.floor(Math.random() * 10)
        }
    }
];


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

const endpointApi = '/m_kary'
onBeforeMount(()=>{
  document.title = 'Testing'
})

let initialValues = {}
const changedValues = []
const thisYear = new Date().getFullYear()


const values = reactive({
  is_active: 1,
  bulan_1:months[new Date().getMonth()],
})


const landing = reactive({
  actions: [
  ],
  api: {
    url: `${store.server.url_backend}/operation/m_kary`,
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
    
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
    {
    headerName: 'Nama',
    field: 'nama_lengkap',
    filter: true,
    sortable: true,
    
    filter: 'ColFilter',
    resizable: true,
    cellClass: [ 'border-r', '!border-gray-200', 'justify-start']
  },
{
  headerName: 'AC',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Listrik',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Pipa',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Massage',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Cleaning',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Service',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Kunci',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Atap',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Cat',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Bangunan',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Besi,Las,Canopy ',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
{
  headerName: 'Desain Interior ',
  field: 'is_active',
  filter: true,
  sortable: true,
  cellClass: ['border-r', '!border-gray-200', 'justify-center'],
  cellRenderer: ({ value }) => {
    return value === true
      ? `<span class="text-green-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M0 11l2-2 5 5L18 3l2 2L7 18z" clip-rule="evenodd" />
          </svg>
        </span>`
      : `<span class="text-gray-500 rounded-md text-xs font-medium px-4 py-1 inline-block capitalize">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block align-middle ml-1" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="5" y1="5" x2="15" y2="15" />
            <line x1="15" y1="5" x2="5" y2="15" />
          </svg>
        </span>`;
  }
},
  ]
})


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