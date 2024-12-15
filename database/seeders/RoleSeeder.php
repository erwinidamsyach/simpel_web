<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \DB::table('m_role')->insert([
            [
                "id" => 1,
                "role_name" => "Administrator"
            ],
            [
                "id" => 2,
                "role_name" => "Guru"
            ],
            [
                "id" => 3,
                "role_name" => "Siswa"
            ]
        ]);
    }
}
