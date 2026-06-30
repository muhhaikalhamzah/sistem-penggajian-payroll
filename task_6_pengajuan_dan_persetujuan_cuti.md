# Task 6: Pengajuan dan Persetujuan Cuti

## Deskripsi
Membuat fitur manajemen cuti tahunan, sakit, dan izin khusus (termasuk unpaid leave yang berdampak pada gaji).

## Kebutuhan
1. **Struktur Database (Migration):**
   - `leave_requests`: `id` (PK), `employee_id` (FK), `start_date` (date), `end_date` (date), `leave_type` (enum/string: 'Annual', 'Sick', 'Unpaid'), `status` (enum/string: 'Pending', 'Approved', 'Rejected'), `timestamps`
2. **Model & Relasi:** Model `LeaveRequest` yang berelasi ke `Employee`.
3. **Logika Fitur & Otorisasi:** 
   - **Role `employee`**: Dapat mengajukan cuti (Create) dan melihat status cutinya (Read).
   - **Role `hr`**: Dapat melihat semua pengajuan cuti dan memproses persetujuan (Approve/Reject).
   - Opsional: Validasi logika mencegah overlapping tanggal cuti.
4. **Data Dummy (Seeder):** `LeaveRequestSeeder` dengan contoh: EMP-001 mengajukan 'Annual' leave dari 2023-11-01 s/d 2023-11-03, status 'Pending' (dan beberapa ada yang sudah 'Approved').

## Kriteria Selesai (Definition of Done)
- [ ] Migration & Model cuti selesai dibuat.
- [ ] Karyawan bisa mengajukan form cuti dari dashboard mereka sendiri.
- [ ] HR memiliki view khusus untuk merespon (Approve/Reject) cuti karyawan.
- [ ] Seeder men-generate history pengajuan cuti dummy.
