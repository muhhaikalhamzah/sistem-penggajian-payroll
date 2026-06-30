# Task 1: User Management & Autentikasi

## Deskripsi
Menyesuaikan sistem autentikasi dan manajemen pengguna (user management) yang sudah ada di Laravel agar selaras dengan PRD, khususnya terkait peran (role) pengguna: HR, Finance/Payroll, dan Karyawan.

## Kebutuhan
1. **Struktur Tabel:** Menyesuaikan tabel `users` (atau struktur role/permission eksisting). Jika menggunakan kolom bawaan, pastikan terdapat kolom `role` (tipe `string` atau `enum` berisi `hr`, `finance`, `employee`). Jika menggunakan package (misal Spatie), pastikan konvensi relasinya valid. Wajib mengikuti standar struktur yang sudah ada pada modul user saat ini tanpa membuat pola baru.
2. **Data Dummy (Seeder):** Memperbarui seeder (`UserSeeder`) untuk menghasilkan data berikut:
   - HR: email `hr@example.com`, role `hr`
   - Finance: email `finance@example.com`, role `finance`
   - Employee: email `employee@example.com`, role `employee`
   *(Gunakan password default `password` untuk semua user).*
3. **Logika CRUD & Otorisasi:** Menyesuaikan Controller, Model, Request Validation, dan view/API untuk fitur CRUD User agar mengenali dan memproses pembagian role baru ini secara selaras dengan sistem role/permission yang eksis. Otorisasi untuk manajemen `users` ini hanya diperbolehkan untuk role `hr` (dan admin jika ada).
4. **Konsistensi:** Mengikuti standar coding style, pola arsitektur, dan konvensi penamaan (naming convention) yang ada saat ini. Dilarang merusak konvensi yang sudah ada.

## Kriteria Selesai (Definition of Done)
- [ ] Struktur tabel `users` atau tabel roles berhasil disesuaikan untuk mendukung ketiga role.
- [ ] `UserSeeder` berhasil berjalan dan membuat minimal 3 user dengan role berbeda.
- [ ] Fitur CRUD User memvalidasi dan memproses role dengan benar.
- [ ] Middleware / Otorisasi diterapkan, di mana hanya role `hr` yang bisa mengakses menu pengelolaan pengguna.
