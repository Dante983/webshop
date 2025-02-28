<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        // Create some customers
        $customerRole = Role::where('name', 'customer')->first();

        $customer = User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
        ]);

        $customer->roles()->attach($customerRole);

        // Create additional customers
        User::factory(5)->create()->each(function ($user) use ($customerRole) {
            $user->roles()->attach($customerRole);
        });
    }
}
