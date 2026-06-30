# Task 2: Master Data Karyawan, Departemen, dan Jabatan

## Deskripsi
Membuat fitur (CRUD) untuk mengelola data struktur organisasi dan profil karyawan berdasarkan PRD.

## Kebutuhan
1. **Struktur Database (Migration):**
   - `departments`: `id` (PK), `name` (string), `description` (text), `timestamps`
   - `positions`: `id` (PK), `title` (string), `min_salary` (decimal 15,2), `max_salary` (decimal 15,2), `timestamps`
   - `employees`: `id` (PK), `employee_number` (string, unique), `first_name` (string), `last_name` (string), `ptkp_status` (enum/string misal: 'TK/0', 'K/1'), `join_date` (date), `department_id` (FK), `position_id` (FK), `user_id` (FK opsional untuk relasi login), `timestamps`
2. **Model & Relasi:** Membuat model `Department`, `Position`, dan `Employee` beserta relasinya (`Employee belongsTo Department`, dll).
3. **Logika CRUD & Otorisasi:** Mengelola data Departemen, Jabatan, dan Karyawan. Hak akses (otorisasi) untuk seluruh fitur CRUD Master Data ini **hanya untuk role `hr`**.
4. **Data Dummy (Seeder):** 
   - `DepartmentSeeder`: HR, Finance, IT.
   - `PositionSeeder`: Manager (min 10jt, max 20jt), Staff (min 5jt, max 10jt).
   - `EmployeeSeeder`: Misal NIK 'EMP-001', 'Budi Santoso', status 'TK/0', jabatan Staff di departemen IT.

## Kriteria Selesai (Definition of Done)
- [ ] Migration dan Model untuk departments, positions, dan employees sudah dibuat dengan relasi yang benar.
- [ ] Seeder sukses men-generate data contoh secara otomatis dan masuk akal.
- [ ] CRUD untuk ketiga entitas berjalan lancar (Create, Read, Update, Delete).
- [ ] Hak akses halaman hanya bisa diakses oleh role `hr`.
