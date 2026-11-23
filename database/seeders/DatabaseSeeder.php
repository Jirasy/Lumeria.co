<?php


namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call(ProductSeeder::class);
       $this->call(AdminSeeder::class);


        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Admin awal: hanya dibuat bila LUMERIA_ADMIN_EMAIL dan LUMERIA_ADMIN_PASSWORD diisi di .env.
        $email    = config('lumeria.admin.email');
        $password = config('lumeria.admin.password');

        if ($email && $password) {
            $admin = User::firstOrNew(['email' => $email]);
            $admin->forceFill([
                'name'              => config('lumeria.admin.name'),
                'password'          => $password,
                'role'              => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ])->save();
        }
    }
}
