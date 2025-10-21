<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ ساخت کاربر Super Admin
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@megashop.com',
            'password' => Hash::make('password'),
            'first_name' => 'مدیر',
            'last_name' => 'سیستم',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('super-admin');

        $this->command->info('✅ کاربر Admin ایجاد شد');
        $this->command->info("   Username: admin");
        $this->command->info("   Password: password");
    }
}
