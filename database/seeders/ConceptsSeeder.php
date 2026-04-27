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
            'Personal de limpieza',
            'Mantenimiento',
            'Oficina',
            'Material limpieza',
            'Gas',
            'Imss',
            'Contador',
            'Personal de mantenimiento',
            'Seguridad',
            'Lic. noe',
            'Comisiones y devoluciones',
            'Agua',
            'Luz',
            'Licencia de alcohol',
        ];

        foreach ($concepts as $concept) {
            Concept::firstOrCreate(
                ['name' => $concept],
                ['is_active' => true]
            );
        }
    }
}
