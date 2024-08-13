<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $division = Division::firstOrCreate(
            ['name' => 'Backend'],
            ['id' => Str::uuid()]
        );

        Employee::create([
            'id' => Str::uuid(),
            'name' => 'John Doe',
            'phone' => '08123456789',
            'division_id' => $division->id,
            'position' => 'Backend Developer',
        ]);
    }
}
