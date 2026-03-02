<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'name' => 'Comercial La Estacion',
                'tax_id' => '900123456-7',
                'email' => 'contacto@laestacion.com',
                'phone' => '+57 320 111 2233',
            ],
            [
                'name' => 'Inversiones Plaza Norte',
                'tax_id' => '901987654-3',
                'email' => 'admin@plazanorte.co',
                'phone' => '+57 315 444 5566',
            ],
        ];

        foreach ($clients as $data) {
            Client::updateOrCreate(
                ['tax_id' => $data['tax_id']],
                $data
            );
        }
    }
}
