# Task 8: Generate Slip Gaji Otomatis (Payslips)

## Deskripsi
Fitur inti yang mengakumulasi gaji pokok, tunjangan, potongan absensi, dan pajak, untuk generate slip gaji akhir.

## Kebutuhan
1. **Struktur Database (Migration):**
   - `payslips`: `id` (PK), `employee_id` (FK), `period` (string 'MM-YYYY'), `gross_salary` (decimal 15,2), `total_deductions` (decimal 15,2), `net_salary` (decimal 15,2), `payment_date` (date), `timestamps`
2. **Model & Relasi:** Model `Payslip` dengan relasi `belongsTo` ke `Employee`. `TaxRecord` dan lainnya bisa di-link melalui `payslips`.
3. **Logika Generate & Otorisasi:**
   - **Role `finance`**: Berhak me-trigger/mengeksekusi kalkulasi generate payslip (proses batch) di akhir bulan.
   - **Role `employee`**: Hanya dapat melihat dan mengunduh (download PDF) slip gajinya sendiri.
4. **Data Dummy (Seeder):** `PayslipSeeder` mensimulasikan slip gaji bulan '10-2023' dengan contoh `gross_salary` Rp 8.500.000, `total_deductions` Rp 150.000, dan `net_salary` Rp 8.350.000.

## Kriteria Selesai (Definition of Done)
- [x] Struktur data payslip telah ter-create di database.
- [x] Terdapat interface untuk role Finance membuat / mem-publish slip gaji bulanan.
- [x] Karyawan bisa melihat detail slip gaji.
- [x] Karyawan bisa mendownload slip gaji dalam bentuk PDF (atau print page).
- [x] Seeder berisi history dummy penggajian untuk di-review.
