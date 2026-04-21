<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name'           => 'Cuci Reguler',
                'unit'           => 'kg',
                'price_per_unit' => 6000,
                'estimated_days' => 3,
                'description'    => 'Cuci + kering + lipat. Selesai 2-3 hari kerja.',
                'is_active'      => true,
            ],
            [
                'name'           => 'Cuci Express',
                'unit'           => 'kg',
                'price_per_unit' => 12000,
                'estimated_days' => 1,
                'description'    => 'Selesai dalam 6 jam. Prioritas pengerjaan.',
                'is_active'      => true,
            ],
            [
                'name'           => 'Cuci + Setrika',
                'unit'           => 'kg',
                'price_per_unit' => 9000,
                'estimated_days' => 3,
                'description'    => 'Paket lengkap cuci bersih dan setrika rapi.',
                'is_active'      => true,
            ],
            [
                'name'           => 'Setrika Saja',
                'unit'           => 'kg',
                'price_per_unit' => 4000,
                'estimated_days' => 2,
                'description'    => 'Untuk pakaian yang sudah bersih tapi kusut.',
                'is_active'      => true,
            ],
            [
                'name'           => 'Cuci Sepatu',
                'unit'           => 'pcs',
                'price_per_unit' => 25000,
                'estimated_days' => 3,
                'description'    => 'Cuci sepatu bersih, sikat manual + dijemur.',
                'is_active'      => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}