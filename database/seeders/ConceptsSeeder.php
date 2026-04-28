<?php

namespace Database\Seeders;

use App\Models\Concept;
use Illuminate\Database\Seeder;

class ConceptsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $concepts = [
            ['name' => 'Energía', 'is_billable' => true, 'billing_period_months' => 1],
            ['name' => 'Gas', 'is_billable' => true, 'billing_period_months' => 1],
            ['name' => 'Agua', 'is_billable' => true, 'billing_period_months' => 6],
            ['name' => 'Aseo', 'is_billable' => true, 'billing_period_months' => 6],
            ['name' => 'Personal de limpieza', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Mantenimiento', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Oficina', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Material limpieza', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Imss', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Contador', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Personal de mantenimiento', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Seguridad', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Lic. noe', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Comisiones y devoluciones', 'is_billable' => false, 'billing_period_months' => null],
            ['name' => 'Licencia de alcohol', 'is_billable' => false, 'billing_period_months' => null],
        ];

        foreach ($concepts as $conceptData) {
            Concept::firstOrCreate(
                ['name' => $conceptData['name']],
                [
                    'is_active' => true,
                    'is_billable' => $conceptData['is_billable'],
                    'billing_period_months' => $conceptData['billing_period_months'],
                ]
            );
        }
    }
}
