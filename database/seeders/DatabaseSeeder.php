<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\ClientsSeeder;
use Database\Seeders\ExpenseConceptsSeeder;
use Database\Seeders\PremisesSeeder;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin$$2026'),
            ],
        );
        $adminUser->assignRole($adminRole);

        $ownerUser = User::firstOrCreate(
            ['email' => 'owner@owner.com'],
            [
                'name' => 'Dueño',
                'password' => bcrypt('owner__2026'),
            ],
        );
        $ownerUser->assignRole($ownerRole);

        $this->call([
            // ClientsSeeder::class,
            // PremisesSeeder::class,
            ExpenseConceptsSeeder::class,
        ]);
    }
}
