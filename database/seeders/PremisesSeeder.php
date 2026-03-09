<?php

namespace Database\Seeders;

use App\Models\Premise;
use Illuminate\Database\Seeder;

class PremisesSeeder extends Seeder
{
    public function run(): void
    {
        $premises = [
            [
                'code' => 'LOC-101',
                'square_meters' => 75.5,
                'suggested_rent' => 4200000,
                'status' => Premise::STATUS_AVAILABLE,
            ],
            [
                'code' => 'LOC-102',
                'square_meters' => 62.0,
                'suggested_rent' => 3600000,
                'status' => Premise::STATUS_RENTED,
            ],
            [
                'code' => 'LOC-201',
                'square_meters' => 95.0,
                'suggested_rent' => 5500000,
                'status' => Premise::STATUS_AVAILABLE,
            ],
            [
                'code' => 'LOC-202',
                'square_meters' => 48.3,
                'suggested_rent' => 2800000,
                'status' => Premise::STATUS_MAINTENANCE,
            ],
            [
                'code' => 'LOC-301',
                'square_meters' => 110.0,
                'suggested_rent' => 6700000,
                'status' => Premise::STATUS_RENTED,
            ],
            [
                'code' => 'LOC-302',
                'square_meters' => 54.7,
                'suggested_rent' => 3100000,
                'status' => Premise::STATUS_AVAILABLE,
            ],
        ];

        foreach ($premises as $data) {
            Premise::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
