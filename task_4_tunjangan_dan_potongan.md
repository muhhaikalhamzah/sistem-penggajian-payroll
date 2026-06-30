# Task 4: Pengelolaan Tunjangan dan Potongan

## Deskripsi
Membuat modul untuk mengonfigurasi komponen tunjangan (transport, makan, jabatan) dan potongan (BPJS, asuransi, pinjaman) bagi tiap karyawan.

## Kebutuhan
1. **Struktur Database (Migration):**
   - `allowances`: `id` (PK), `employee_id` (FK), `name` (string), `amount` (decimal 15,2), `type` (enum/string: 'Fixed', 'Variable'), `timestamps`
   - `deductions`: `id` (PK), `employee_id` (FK), `name` (string), `amount` (decimal 15,2), `type` (enum/string: 'Fixed', 'Variable'), `timestamps`
2. **Model & Relasi:** Model `Allowance` dan `Deduction`, keduanya memiliki relasi `belongsTo` ke `Employee`.
3. **Logika CRUD & Otorisasi:** Antarmuka pengelolaan komponen tunjangan dan potongan karyawan. Hak akses menu ini diperuntukkan **hanya untuk role `finance`**.
4. **Data Dummy (Seeder):** 
   - `AllowanceSeeder`: Tunjangan Transport (Fixed, Rp 500.000), Tunjangan Makan (Variable, Rp 40.000/kehadiran).
   - `DeductionSeeder`: Potongan BPJS Kesehatan (Fixed, Rp 150.000).

## Kriteria Selesai (Definition of Done)
- [ ] Tabel dan Model `allowances` dan `deductions` siap digunakan.
- [ ] Manajemen Create, Update, Delete berjalan normal.
- [ ] Filter dan tampilan disesuaikan, dilindungi otorisasi untuk role `finance`.
- [ ] Seeder menyuplai data riil (Tunjangan & Potongan) pada karyawan dummy.
