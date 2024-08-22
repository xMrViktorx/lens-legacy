<?php

namespace Modules\Admin\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        User::updateOrCreate(
            ['name' => 'admin'],
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
            ]
        );
    }
}
