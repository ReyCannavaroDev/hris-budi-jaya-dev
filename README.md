# HRIS Budi Jaya — Antigravity Offline Source Workspace

## 1. Tentang repository ini

Repository ini merupakan **workspace dokumentasi, analisis, dan penyusunan perubahan source code HRIS Budi Jaya** yang dipindahkan secara manual dari generator lama ke Antigravity.

Workspace ini dibuat agar programmer dapat menggunakan bantuan AI untuk:

- membaca source code dari generator lama;
- memahami hubungan antara core, blade, javascript, dan model;
- menelusuri penyebab bug;
- menyusun usulan perbaikan kode;
- membandingkan perubahan sebelum dan sesudah revisi;
- membuat catatan teknis dan skenario pengujian;
- menyiapkan potongan source code yang nantinya dimasukkan kembali oleh programmer ke generator atau server yang sebenarnya.

Repository ini **bukan salinan aplikasi Laravel lengkap**, bukan repository deployment, dan bukan environment runtime HRIS Budi Jaya.

---

## 2. Status koneksi dan batasan environment

Repository ini bersifat **offline dan terisolasi**.

Repository ini tidak terhubung secara langsung maupun otomatis ke:

- server development HRIS Budi Jaya;
- server production HRIS Budi Jaya;
- server QL;
- database development;
- database production;
- API internal HRIS;
- storage atau file server perusahaan;
- repository Git utama perusahaan;
- pipeline CI/CD;
- terminal server;
- container aplikasi;
- web server;
- queue worker;
- scheduler;
- cache server;
- layanan autentikasi perusahaan;
- layanan pihak ketiga yang digunakan oleh aplikasi.

Tidak ada proses sinkronisasi otomatis dari workspace ini ke server mana pun.

Perubahan yang dibuat di dalam repository ini **tidak akan langsung mengubah aplikasi HRIS Budi Jaya**. Semua penerapan perubahan harus dilakukan secara sadar dan manual oleh programmer yang memiliki kewenangan pada generator, repository, database, atau server tujuan.

---

## 3. Prinsip utama: seluruh eksekusi dilakukan oleh programmer

Semua tindakan terhadap sistem nyata merupakan tanggung jawab dan eksekusi programmer.

AI di dalam Antigravity hanya berfungsi sebagai alat bantu untuk:

- menganalisis source code yang tersedia;
- mencari kemungkinan penyebab masalah;
- mengusulkan perubahan;
- membuat contoh kode;
- menuliskan langkah pengujian;
- membantu mendokumentasikan hasil pekerjaan.

AI tidak memiliki akses langsung untuk:

- membuka server HRIS;
- menjalankan query pada database asli;
- mengubah struktur tabel;
- mengedit source code pada generator kantor;
- melakukan commit ke repository utama;
- melakukan merge;
- melakukan deploy;
- melakukan rollback;
- mengubah konfigurasi environment;
- mengakses data karyawan;
- mengaktifkan atau menonaktifkan fitur pada sistem nyata.

Setiap kode yang dihasilkan atau diubah harus ditinjau kembali oleh programmer sebelum digunakan.

Programmer wajib memeriksa:

1. kesesuaian kode dengan pola generator lama;
2. nama tabel dan kolom yang sebenarnya;
3. tipe data database;
4. relasi antartabel;
5. dampak terhadap modul lain;
6. kompatibilitas dengan versi PHP, Laravel, PostgreSQL, dan library yang digunakan;
7. keamanan data dan otorisasi;
8. hasil pengujian pada environment yang benar;
9. kebutuhan backup sebelum perubahan diterapkan.

---

## 4. Repository ini bukan aplikasi Laravel lengkap

Walaupun source code HRIS Budi Jaya memiliki pola yang menyerupai Laravel, repository ini tidak dibangun sebagai project Laravel standar.

Repository ini tidak menjamin tersedianya komponen seperti:

```text
app/
bootstrap/
config/
database/
public/
resources/
routes/
storage/
tests/
vendor/
artisan
composer.json
composer.lock
.env
```

Ketiadaan file tersebut merupakan kondisi yang disengaja karena source code diambil dari generator lama berdasarkan bagian yang tersedia bagi programmer.

Repository ini tidak ditujukan untuk menjalankan perintah berikut:

```bash
php artisan serve
php artisan migrate
php artisan test
composer install
npm install
npm run dev
npm run build
```

Perintah tersebut hanya dapat digunakan apabila suatu saat seluruh kebutuhan runtime telah dilengkapi dan sudah dipastikan sesuai dengan aplikasi asli. Secara default, anggap repository ini **tidak dapat dijalankan**.

---

## 5. Tujuan repository

Tujuan utama repository ini adalah menyediakan peta source code yang rapi agar proses pemeliharaan HRIS Budi Jaya dapat dilakukan dengan lebih terstruktur.

Repository ini digunakan untuk:

- menyimpan salinan kerja source code yang diambil manual;
- mengelompokkan source berdasarkan jenisnya;
- membantu pencarian dependensi antarmodul;
- mendokumentasikan bug dan hasil investigasi;
- menyiapkan revisi tanpa menyentuh server secara langsung;
- membantu programmer memahami kode generator lama;
- menjadi tempat review sebelum perubahan dipindahkan ke sistem nyata.

Repository ini tidak digunakan sebagai:

- source of truth aplikasi;
- backup resmi server;
- backup database;
- repository deployment;
- pengganti generator kantor;
- pengganti pengujian pada server development;
- bukti bahwa suatu perubahan sudah berhasil di production.

---

## 6. Struktur folder

```text
hris-Budi Jaya-antigravity/
├── Cores/
├── Blades/
├── Javascript/
├── Models/
├── docs/
├── AGENTS.md
├── README.md
└── manifest.json
```

### 6.1. `Cores/`

Folder ini menyimpan source code core yang berasal dari generator lama.

Format file:

```text
Cores/<nama_file>.php
```

Contoh:

```text
Cores/Approval.php
Cores/Bootstrap.php
Cores/Frontend.php
Cores/Helper.php
Cores/Middleware.php
Cores/Respo.php
Cores/Response.php
```

Isi file dapat berupa controller-like logic, helper, bootstrap, middleware, response handler, atau komponen inti lain sesuai pola generator lama.

Jangan berasumsi bahwa file di dalam folder ini memiliki namespace, inheritance, lifecycle, atau lokasi yang sama dengan Laravel standar. Gunakan source yang tersedia sebagai acuan utama.

### 6.2. `Blades/`

Folder ini menyimpan source tampilan Blade.

Format file:

```text
Blades/<nama_modul>.blade.php
```

Contoh:

```text
Blades/l_perjalanan_dinas.blade.php
Blades/l_karyawan_aktif.blade.php
Blades/m_approval.blade.php
```

File Blade dapat berisi:

- struktur HTML;
- komponen form;
- tabel;
- modal;
- directive Blade;
- binding data;
- pemanggilan route atau endpoint;
- script inline;
- referensi terhadap javascript modul.

Saat menganalisis Blade, jangan mengubah nama field, id elemen, class, atau data attribute tanpa memeriksa penggunaan yang sama pada file Javascript dan Core.

### 6.3. `Javascript/`

Folder ini menyimpan source Javascript modul.

Format file:

```text
Javascript/<nama_modul>.js
```

Contoh:

```text
Javascript/l_perjalanan_dinas.js
Javascript/l_karyawan_aktif.js
Javascript/m_approval.js
```

Javascript dapat menangani:

- request AJAX;
- inisialisasi tabel;
- validasi form;
- pengambilan data dropdown;
- event handler;
- submit data;
- filter;
- rendering frontend;
- notifikasi;
- komunikasi dengan endpoint dari Core.

Sebelum mengubah endpoint, parameter, atau nama response, periksa file Blade, Core, dan Model terkait.

### 6.4. `Models/`

Setiap model memiliki satu folder tersendiri.

Format struktur:

```text
Models/<nama_model>/
├── Migration.php
├── Alter.php
├── Basic.php
├── Custom.php
└── Test.php
```

Contoh:

```text
Models/m_approval/
├── Migration.php
├── Alter.php
├── Basic.php
├── Custom.php
└── Test.php
```

#### `Migration.php`

Digunakan untuk menyimpan source awal pembentukan tabel atau struktur utama model dari generator lama.

File ini dapat membantu mengetahui:

- nama tabel;
- nama kolom;
- tipe data;
- primary key;
- foreign key;
- nullable atau required;
- default value;
- index;
- struktur awal tabel.

#### `Alter.php`

Digunakan untuk menyimpan perubahan struktur tabel setelah migration awal.

File ini penting karena struktur database aktif mungkin berbeda dari struktur pada `Migration.php`.

Saat mencari tipe data atau kolom, selalu periksa `Migration.php` dan `Alter.php` secara bersamaan.

#### `Basic.php`

Digunakan untuk menyimpan konfigurasi atau source model dasar hasil generator.

Bagian ini dapat memuat:

- daftar field;
- konfigurasi tabel;
- query dasar;
- join dasar;
- filter;
- relasi;
- konfigurasi listing;
- metadata generator.

#### `Custom.php`

Digunakan untuk menyimpan penyesuaian yang dibuat programmer di luar konfigurasi dasar generator.

Perubahan bisnis dan query khusus biasanya lebih aman ditempatkan atau diperiksa di bagian ini, tergantung pola aplikasi asli.

Jangan memindahkan logika dari `Basic.php` ke `Custom.php`, atau sebaliknya, tanpa memahami bagaimana generator melakukan regenerasi file.

#### `Test.php`

Digunakan untuk menyimpan source test, eksperimen, query uji, atau bagian pengujian yang tersedia dari generator lama.

Keberadaan file ini tidak berarti test dapat dijalankan di workspace ini karena runtime dan dependensinya belum tentu tersedia.

### 6.5. `docs/`

Folder ini digunakan untuk dokumentasi pendukung, misalnya:

- daftar model lama atau model yang dicoret;
- catatan database;
- peta dependensi;
- daftar bug;
- hasil investigasi;
- langkah pengujian;
- perbandingan dev dan production;
- catatan deployment manual.

### 6.6. `AGENTS.md`

File ini berisi instruksi kerja bagi AI di Antigravity.

AI harus mengikuti aturan pada file tersebut, terutama:

- tidak menganggap workspace sebagai Laravel lengkap;
- tidak mengarang dependency yang belum tersedia;
- meminta file terkait apabila konteks belum cukup;
- mempertahankan pola generator lama;
- menjelaskan alasan setiap perubahan.

### 6.7. `manifest.json`

File ini berisi daftar file dan model yang berhasil dipetakan saat scaffold dibuat.

Manifest digunakan sebagai daftar awal, bukan jaminan bahwa seluruh source aplikasi sudah tersedia.

Apabila ditemukan file baru dari generator, file tersebut dapat ditambahkan ke struktur repository dan manifest dapat diperbarui secara manual.

---

## 7. Sumber kode

Seluruh source di repository ini berasal dari proses penyalinan manual oleh programmer dari generator lama atau sumber internal yang memang diizinkan untuk digunakan.

Karena prosesnya manual, kondisi berikut mungkin terjadi:

- source belum lengkap;
- file masih berupa placeholder;
- versi file berbeda dengan server development;
- versi file berbeda dengan production;
- ada dependency yang belum disalin;
- ada perubahan database yang belum tercatat;
- nama file pada generator berbeda dengan nama modul di frontend;
- source di dev belum sinkron dengan production;
- generator menghasilkan ulang bagian tertentu dan menimpa perubahan manual.

Oleh karena itu, jangan menyimpulkan bahwa source di repository ini selalu sama dengan source aktif pada server.

Sebelum menerapkan perbaikan, programmer harus membandingkan source workspace dengan source pada environment tujuan.

---

## 8. Alur kerja yang direkomendasikan

### Tahap 1 — Tentukan masalah

Catat masalah secara jelas:

- menu yang bermasalah;
- langkah untuk memunculkan masalah;
- hasil yang diharapkan;
- hasil aktual;
- pesan error;
- environment tempat error terjadi;
- apakah error juga terjadi di production;
- data atau role yang digunakan saat pengujian.

### Tahap 2 — Ambil source dari generator

Salin hanya source yang relevan terlebih dahulu:

1. Core modul;
2. Blade modul;
3. Javascript modul;
4. Model utama;
5. Model relasi;
6. Migration;
7. Alter;
8. Basic;
9. Custom;
10. Test apabila tersedia.

### Tahap 3 — Tempel source ke workspace

Tempel source ke file yang sesuai tanpa mengubah nama file atau struktur aslinya.

Contoh untuk modul karyawan:

```text
Cores/<core_karyawan>.php
Blades/<blade_karyawan>.blade.php
Javascript/<javascript_karyawan>.js
Models/m_kary/Migration.php
Models/m_kary/Alter.php
Models/m_kary/Basic.php
Models/m_kary/Custom.php
Models/m_kary/Test.php
```

Tambahkan juga model yang digunakan dalam join, misalnya:

```text
Models/m_divisi/
Models/m_general/
Models/m_jam_kerja/
Models/m_posisi/
Models/m_branch/
Models/m_subcomp/
```

### Tahap 4 — Analisis dengan AI

Minta AI untuk:

- memetakan alur data;
- menunjukkan file yang terlibat;
- menemukan query atau event yang bermasalah;
- membandingkan tipe data;
- menjelaskan akar masalah;
- membuat usulan patch;
- menyusun risiko perubahan;
- membuat skenario pengujian.

AI harus menyebutkan asumsi apabila source belum lengkap.

### Tahap 5 — Review programmer

Programmer memeriksa hasil AI dan memastikan:

- syntax sesuai versi PHP dan framework;
- nama tabel benar;
- nama kolom benar;
- tipe data benar;
- join benar;
- response cocok dengan frontend;
- tidak ada data sensitif yang bocor;
- tidak ada bypass otorisasi;
- perubahan tidak merusak modul lain.

### Tahap 6 — Terapkan manual pada sistem tujuan

Programmer menyalin perubahan yang sudah disetujui ke generator, repository utama, atau server development sesuai prosedur internal.

Workspace ini tidak melakukan proses tersebut secara otomatis.

### Tahap 7 — Pengujian

Lakukan pengujian pada environment tujuan.

Minimal lakukan:

- uji skenario normal;
- uji data kosong;
- uji data tidak valid;
- uji role berbeda;
- uji cabang atau company berbeda;
- uji filter;
- uji create, read, update, delete sesuai kebutuhan;
- uji query listing dan pagination;
- uji dampak pada modul terkait;
- uji error log;
- uji kesesuaian hasil dengan production apabila diperlukan.

### Tahap 8 — Dokumentasikan hasil

Catat:

- tanggal perubahan;
- nama programmer;
- masalah yang diperbaiki;
- file yang diubah;
- query yang diubah;
- hasil pengujian;
- environment pengujian;
- status deploy;
- rencana rollback.

---

## 9. Aturan perubahan source code

Setiap perubahan sebaiknya dibuat sekecil mungkin dan fokus pada satu masalah.

Hindari:

- refactor besar tanpa kebutuhan;
- mengganti pola generator secara menyeluruh;
- mengubah nama tabel atau kolom tanpa migration resmi;
- mengubah response API tanpa memeriksa Javascript;
- menghapus field yang terlihat tidak digunakan;
- menambahkan cast secara sembarang;
- menggabungkan banyak bug dalam satu patch;
- menyalin kode langsung ke production tanpa pengujian.

Setiap usulan patch harus menjelaskan:

```text
masalah:
penyebab:
file terkait:
perubahan:
risiko:
langkah pengujian:
rollback:
```

---

## 10. Penanganan masalah database

Workspace ini tidak memiliki koneksi database.

AI hanya dapat menganalisis:

- migration yang tersedia;
- alter yang tersedia;
- query pada Basic atau Custom;
- pesan error yang diberikan programmer;
- contoh struktur data yang sudah disamarkan.

AI tidak dapat memastikan struktur database aktual tanpa informasi dari programmer.

Sebelum mengubah query, programmer harus memeriksa struktur sebenarnya pada database tujuan.

Contoh pemeriksaan PostgreSQL:

```sql
select
    table_schema,
    table_name,
    column_name,
    data_type,
    udt_name
from information_schema.columns
where table_name in ('m_kary', 'm_divisi', 'm_general')
order by table_name, ordinal_position;
```

Query pemeriksaan hanya contoh. Programmer harus menjalankannya sendiri pada environment yang diizinkan.

Jangan memasukkan connection string, username, password, host database, token, atau credential lain ke repository ini.

---

## 11. Masalah awal yang sedang ditelusuri

Salah satu masalah awal HRIS Budi Jaya yang sedang dianalisis adalah error PostgreSQL:

```text
operator does not exist: bigint = json
```

Error muncul pada query listing karyawan yang memiliki banyak `left join`.

Salah satu bagian yang perlu diperiksa adalah perbandingan kolom dengan tipe berbeda, termasuk kemungkinan join seperti:

```sql
m_divisi.name = m_general.id
```

Kemungkinan masalah:

- kolom JSON dibandingkan dengan bigint;
- nama atau object JSON dibandingkan dengan ID;
- foreign key memiliki tipe yang tidak sesuai;
- struktur database dev berbeda dengan production;
- source dev belum sinkron dengan source production;
- konfigurasi join dari generator salah.

Kesimpulan final tidak boleh dibuat hanya dari pesan error. Programmer harus menyediakan source `Basic.php`, `Custom.php`, `Migration.php`, dan `Alter.php` dari model terkait serta memeriksa tipe data aktual di database.

---

## 12. Environment dan perbedaan versi

Setiap temuan harus menyebutkan environment:

```text
workspace lokal
server ql
server development Budi Jaya
server production Budi Jaya
```

Status pada satu environment tidak otomatis berlaku pada environment lain.

Contoh:

- fitur berhasil di server QL tetapi error di server Budi Jaya;
- source production lebih baru daripada dev;
- tabel production memiliki kolom tambahan;
- config dev belum tersedia;
- cache dev belum diperbarui;
- frontend dan backend menggunakan versi berbeda.

Repository ini tidak dapat mendeteksi perbedaan tersebut secara otomatis.

Programmer harus melakukan perbandingan manual terhadap:

- source code;
- migration dan alter;
- struktur tabel;
- data konfigurasi;
- environment variable;
- dependency;
- cache;
- endpoint;
- response API.

---

## 13. Keamanan dan kerahasiaan data

Jangan memasukkan data berikut ke repository:

- `.env` asli;
- password;
- token akses;
- private key;
- API key;
- credential database;
- credential server;
- cookie autentikasi;
- session aktif;
- data pribadi karyawan;
- NIK;
- nomor rekening;
- data gaji;
- data pajak;
- data BPJS;
- alamat rumah;
- nomor telepon pribadi;
- dokumen pelamar;
- data kesehatan;
- data absensi yang dapat mengidentifikasi individu;
- dump database production.

Saat membagikan contoh data, gunakan data dummy atau samarkan nilai sensitif.

Contoh aman:

```json
{
  "employee_id": 123,
  "employee_name": "dummy employee",
  "branch_id": 15
}
```

---

## 14. Placeholder dan kelengkapan source

Sebagian file pada scaffold awal masih berupa placeholder.

Contoh placeholder PHP:

```php
<?php

/**
 * placeholder source.
 * tempel source code dari generator lama di file ini.
 */
```

Placeholder berarti source asli belum dimasukkan. AI tidak boleh menganggap placeholder sebagai implementasi sebenarnya.

Apabila analisis membutuhkan file yang masih placeholder, programmer harus mengambil source terkait dari generator lama.

---

## 15. Penamaan file

Pertahankan nama dari generator lama semaksimal mungkin.

Aturan ekstensi:

```text
Cores      -> .php
Blades     -> .blade.php
Javascript -> .js
Models     -> .php
```

Struktur model:

```text
Models/<nama_model>/Migration.php
Models/<nama_model>/Alter.php
Models/<nama_model>/Basic.php
Models/<nama_model>/Custom.php
Models/<nama_model>/Test.php
```

Jangan melakukan perubahan kapitalisasi nama file tanpa memastikan sistem tujuan bersifat case-insensitive. Server Linux umumnya membedakan huruf besar dan kecil.

---

## 16. Cara meminta bantuan AI

Gunakan permintaan yang spesifik dan sertakan konteks.

Contoh:

```text
analisis error listing karyawan berikut.

environment: server development Budi Jaya
error: operator does not exist: bigint = json
hasil yang diharapkan: halaman daftar karyawan tampil
hasil aktual: request mengembalikan status 500

periksa file:
- models/m_kary/basic.php
- models/m_kary/custom.php
- models/m_kary/migration.php
- models/m_kary/alter.php
- models/m_divisi/migration.php
- models/m_divisi/alter.php
- models/m_general/migration.php
- models/m_general/alter.php

jelaskan akar masalah, file yang harus diubah, patch minimal, risiko, dan langkah pengujian.
```

Hindari permintaan terlalu umum seperti:

```text
perbaiki semua bug hris
```

AI hanya dapat memberikan hasil sebaik konteks dan source yang tersedia.

---

## 17. Checklist sebelum mengambil source dari generator

- pastikan file memang terkait dengan masalah;
- pastikan versi source berasal dari environment yang sedang dianalisis;
- hapus credential dan data sensitif;
- jangan menyalin `.env`;
- jangan menyalin dump database;
- pertahankan nama file;
- catat tanggal pengambilan source;
- catat sumber environment;
- tandai apakah source berasal dari dev, QL, atau production;
- bandingkan dengan file yang sudah ada sebelum menimpa.

---

## 18. Checklist sebelum menerapkan perubahan

- patch sudah direview programmer;
- syntax sudah diperiksa;
- dependency tersedia;
- tipe data database sudah diverifikasi;
- relasi tabel sudah diverifikasi;
- authorization tidak terlewati;
- validasi input tetap berjalan;
- response sesuai frontend;
- backup tersedia;
- rollback plan tersedia;
- perubahan diuji di development;
- hasil pengujian didokumentasikan;
- persetujuan penerapan sudah diperoleh sesuai prosedur internal.

---

## 19. Checklist setelah menerapkan perubahan

- halaman dapat dibuka;
- request tidak menghasilkan error 500;
- tidak ada error baru pada log;
- data create tersimpan dengan benar;
- data list tampil dengan benar;
- data edit dan update berjalan;
- filter dan pagination berjalan;
- role dan permission tetap benar;
- modul terkait tidak terganggu;
- hasil dibandingkan dengan kebutuhan user;
- status spreadsheet trial diperbarui;
- source hasil final disimpan pada repository resmi sesuai prosedur perusahaan.

---

## 20. Deployment dan rollback

Repository ini tidak menyediakan deployment otomatis.

Deployment dilakukan sepenuhnya oleh programmer atau tim yang berwenang.

Sebelum deployment:

- lakukan backup source;
- lakukan backup database apabila ada perubahan struktur atau data;
- catat file yang akan diganti;
- siapkan salinan file lama;
- tentukan langkah rollback;
- lakukan deployment sesuai prosedur perusahaan.

Apabila terjadi masalah, rollback dilakukan secara manual berdasarkan backup dan catatan perubahan.

AI tidak dapat melakukan rollback pada server nyata.

---

## 21. Tanggung jawab

Programmer bertanggung jawab atas:

- validasi hasil analisis AI;
- keputusan perubahan kode;
- keamanan source;
- keamanan data;
- penerapan perubahan;
- pengujian;
- deployment;
- rollback;
- dokumentasi;
- kepatuhan terhadap prosedur perusahaan dan client.

Setiap output AI dianggap sebagai **usulan teknis**, bukan instruksi deployment yang otomatis benar.

---

## 22. Ringkasan penting

> Repository ini adalah workspace source code offline untuk membantu programmer menganalisis dan menyiapkan perbaikan HRIS Budi Jaya menggunakan Antigravity.
>
> Repository ini tidak terhubung dengan server development, production, database, API, repository utama, maupun pipeline deployment.
>
> Tidak ada perubahan yang diterapkan otomatis ke sistem HRIS Budi Jaya.
>
> Seluruh pengambilan source, review, penerapan kode, pengujian, deployment, dan rollback dilakukan secara manual oleh programmer yang berwenang.
