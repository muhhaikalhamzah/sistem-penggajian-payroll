<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'SDM', 'description' => 'Departemen Sumber Daya Manusia'],
            ['name' => 'Keuangan', 'description' => 'Departemen Keuangan'],
            ['name' => 'TI', 'description' => 'Departemen Teknologi Informasi'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
