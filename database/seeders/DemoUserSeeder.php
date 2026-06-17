<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Admin;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = env('SEED_ADMIN_PASSWORD', 'password');
        $studentPassword = env('SEED_STUDENT_PASSWORD', 'password');
        $secondStudentPassword = env('SEED_STUDENT_2_PASSWORD', $studentPassword);

        $adminUser = User::create([
            'name' => env('SEED_ADMIN_NAME', 'Mario'),
            'surname' => env('SEED_ADMIN_SURNAME', 'Rossi'),
            'email' => env('SEED_ADMIN_EMAIL', 'admin@example.com'),
            'email_verified_at' => now(),
            'password' => Hash::make($adminPassword),
            'role' => UserRole::ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
        ]);

        Admin::create([
            'user_id' => $adminUser->id,
            'tax_code' => 'RSSMRA80A01H501U',
            'street' => 'Via Roma',
            'house_number' => '10',
            'city' => 'Roma',
            'province' => 'RM',
            'postal_code' => '00100',
            'vat_number' => '12345678901',
        ]);

        collect([
            [
                'name' => env('SEED_STUDENT_NAME', 'Giulia'),
                'surname' => env('SEED_STUDENT_SURNAME', 'Bianchi'),
                'email' => env('SEED_STUDENT_EMAIL', 'student@example.com'),
                'password' => $studentPassword,
                'tax_code' => env('SEED_STUDENT_TAX_CODE', 'BNCGLI90A41H501K'),
            ],
            [
                'name' => env('SEED_STUDENT_2_NAME', 'Luca'),
                'surname' => env('SEED_STUDENT_2_SURNAME', 'Verdi'),
                'email' => env('SEED_STUDENT_2_EMAIL', 'student2@example.com'),
                'password' => $secondStudentPassword,
                'tax_code' => env('SEED_STUDENT_2_TAX_CODE', 'VRDLCU91B12F205Z'),
            ],
        ])->each(function (array $data) {
            $user = User::create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($data['password']),
                'role' => UserRole::STUDENT->value,
                'status' => UserStatus::ACTIVE->value,
            ]);

            Student::create([
                'user_id' => $user->id,
                'street' => 'Via Garibaldi',
                'house_number' => '5',
                'city' => 'Milano',
                'province' => 'MI',
                'postal_code' => '20100',
                'tax_code' => $data['tax_code'],
            ]);
        });
    }
}
