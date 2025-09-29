<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Seeder;

class BarangaySeeder extends Seeder
{
    public function run(): void
    {
        $barangays = [
            ['name' => 'Aliwekwek', 'code' => 'ALW'],
            ['name' => 'Baay', 'code' => 'BAA'],
            ['name' => 'Balangobong', 'code' => 'BAL'],
            ['name' => 'Balococ', 'code' => 'BCO'],
            ['name' => 'Bantayan', 'code' => 'BAN'],
            ['name' => 'Basing', 'code' => 'BAS'],
            ['name' => 'Capandanan', 'code' => 'CAP'],
            ['name' => 'Domalandan Center', 'code' => 'DMC'],
            ['name' => 'Domalandan East', 'code' => 'DME'],
            ['name' => 'Domalandan West', 'code' => 'DMW'],
            ['name' => 'Dorongan', 'code' => 'DOR'],
            ['name' => 'Dulag', 'code' => 'DUL'],
            ['name' => 'Estanza', 'code' => 'EST'],
            ['name' => 'Lasip', 'code' => 'LAS'],
            ['name' => 'Libsong East', 'code' => 'LSE'],
            ['name' => 'Libsong West', 'code' => 'LSW'],
            ['name' => 'Malawa', 'code' => 'MAL'],
            ['name' => 'Malimpuec', 'code' => 'MLP'],
            ['name' => 'Maniboc', 'code' => 'MAN'],
            ['name' => 'Matalava', 'code' => 'MAT'],
            ['name' => 'Naguelguel', 'code' => 'NAG'],
            ['name' => 'Namolan', 'code' => 'NAM'],
            ['name' => 'Pangapisan North', 'code' => 'PNN'],
            ['name' => 'Pangapisan Sur', 'code' => 'PNS'],
            ['name' => 'Poblacion', 'code' => 'POB'],
            ['name' => 'Quibaol', 'code' => 'QUI'],
            ['name' => 'Rosario', 'code' => 'ROS'],
            ['name' => 'Sabangan', 'code' => 'SAB'],
            ['name' => 'Talogtog', 'code' => 'TAL'],
            ['name' => 'Tonton', 'code' => 'TON'],
            ['name' => 'Tumbar', 'code' => 'TUM'],
            ['name' => 'Wawa', 'code' => 'WAW'],
        ];

        foreach ($barangays as $barangay) {
            Barangay::create([
                'name' => $barangay['name'],
                'code' => $barangay['code'],
                'is_active' => true,
            ]);
        }
    }
}

























