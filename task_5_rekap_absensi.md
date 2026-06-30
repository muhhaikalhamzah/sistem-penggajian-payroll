# Task 5: Rekap Absensi (Attendance Records)

## Deskripsi
Membangun modul pencatatan kehadiran, keterlambatan, dan perhitungan jam lembur untuk kalkulasi gaji.

## Kebutuhan
1. **Struktur Database (Migration):**
   - `attendance_records`: `id` (PK), `employee_id` (FK), `record_date` (date), `check_in` (time, nullable), `check_out` (time, nullable), `overtime_hours` (integer, default 0), `status` (enum: 'Present', 'Absent', 'Leave'), `timestamps`
2. **Model & Relasi:** Model `AttendanceRecord` berelasi dengan `Employee`.
3. **Logika Fitur & Otorisasi:** 
   - Fitur upload data absensi massal atau input manual (Admin/HR).
   - Perhitungan otomatis keterlambatan dan `overtime_hours` dari check-in/out.
   - Otorisasi: Role `hr` dapat **mengelola seluruh data absensi**, sedangkan role `employee` hanya bisa **melihat riwayat absensi pribadinya**.
4. **Data Dummy (Seeder):** `AttendanceRecordSeeder` dengan contoh: EMP-001 pada 2023-10-01, check_in '08:00', check_out '17:00', overtime 0, status 'Present'.

## Kriteria Selesai (Definition of Done)
- [ ] Database tabel absen terbentuk dengan field yang tepat.
- [ ] View absensi bisa dilihat spesifik per user (`employee`) atau keseluruhan (`hr`).
- [ ] Ada fitur untuk HR memasukkan data absensi.
- [ ] Seeder sukses mengisi setidaknya 1 bulan catatan kehadiran untuk simulasi gaji.
