# instruksi workspace hris budi jaya

workspace ini bukan aplikasi laravel lengkap dan tidak ditujukan untuk dijalankan.

## struktur
- `Cores/*.php`: source core dari generator lama.
- `Blades/*.blade.php`: source tampilan blade.
- `Javascript/*.js`: source javascript modul.
- `Models/<nama_model>/*.php`: source model per bagian:
  `Migration`, `Alter`, `Basic`, `Custom`, dan `Test`.

## aturan kerja
1. jangan membuat asumsi tentang route, service provider, composer, artisan, atau struktur laravel standar.
2. analisis hanya berdasarkan source yang sudah ditempel.
3. jika dependensi belum tersedia, sebutkan nama file atau model yang dibutuhkan.
4. pertahankan pola, penamaan, dan gaya kode generator lama.
5. sebelum mengubah query atau join, periksa tipe kolom dan relasi pada migration, alter, basic, serta custom.
6. jangan menaruh credential, token, `.env`, data pribadi karyawan, atau akses database ke workspace.
7. setiap perbaikan harus mencantumkan file yang diubah, penyebab, perubahan kode, dampak, dan langkah pengujian.

## konteks awal
salah satu masalah yang sedang ditelusuri adalah error postgresql
`operator does not exist: bigint = json` pada query menu karyawan.
