<?php
// database/seeders/RoleSeeder.php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'admin', 'label' => 'Administrator']);
        Role::create(['name' => 'customer', 'label' => 'Customer']);
    }
}
