# Task 7: Kalkulasi Pajak PPh 21

## Deskripsi
Membangun algoritma dan tabel untuk perhitungan otomatis beban pajak PPh 21 berdasarkan status PTKP karyawan (dinamis).

## Kebutuhan
1. **Struktur Database (Migration):**
   - `tax_records`: `id` (PK), `payslip_id` (FK opsional / jika ada dependensi), `employee_id` (FK), `taxable_income` (decimal 15,2), `pph21_amount` (decimal 15,2), `period` (string misal '10-2023'), `timestamps`
2. **Model & Relasi:** Model `TaxRecord` berelasi dengan `Employee` dan/atau `Payslip`.
3. **Logika Kalkulasi & Otorisasi:** 
   - Membuat service class (`Pph21Calculator`) untuk menghitung PKP (Penghasilan Kena Pajak) dan memotong tarif pajak progresif.
   - Hak Akses: Modul ini dikelola secara background (otomatis saat penggajian), namun hasil rekapan dapat dilihat oleh **role `finance`**, serta termuat di payslip milik **role `employee`**.
4. **Data Dummy (Seeder):** `TaxRecordSeeder` dengan contoh: Karyawan berpenghasilan taxable Rp 5.000.000 dengan potongan PPh21 sebesar Rp 25.000 untuk bulan '10-2023'.

## Kriteria Selesai (Definition of Done)
- [ ] Tabel historis pajak sudah dimigrasi.
- [ ] Class Kalkulator PPh 21 berjalan sesuai logika PTKP (bisa divalidasi dengan Unit Test jika perlu).
- [ ] Data dummy PPh 21 dihasilkan lewat Seeder.
- [ ] Finance bisa melihat laporan/history pajak per karyawan.
