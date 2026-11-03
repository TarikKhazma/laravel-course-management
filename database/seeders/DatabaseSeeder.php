<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;

/** Sample data for trying the system: php artisan db:seed */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $php = Course::create(['name' => 'أساسيات PHP', 'description' => 'مقدمة في لغة PHP والبرمجة الكائنية.', 'hours' => 30]);
        $laravel = Course::create(['name' => 'تطوير الويب بـ Laravel', 'description' => 'بناء تطبيقات ويب متكاملة باستخدام Laravel.', 'hours' => 45]);
        Course::create(['name' => 'تصميم قواعد البيانات', 'description' => 'مبادئ تصميم قواعد البيانات العلائقية.', 'hours' => 20, 'is_active' => false]);

        $phpTraining = $php->trainingCourses()->create([
            'name' => 'دورة PHP المسائية',
            'description' => 'دورة مسائية للمبتدئين.',
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-20',
            'seats' => 2,
        ]);
        $laravelTraining = $laravel->trainingCourses()->create([
            'name' => 'معسكر Laravel المكثف',
            'description' => 'دورة تطبيقية مكثفة.',
            'start_date' => '2027-01-10',
            'end_date' => '2027-02-10',
            'seats' => 25,
        ]);

        $students = collect([
            ['full_name' => 'طالب تجريبي 1', 'email' => 'student1@example.com', 'phone' => '0500000001'],
            ['full_name' => 'طالب تجريبي 2', 'email' => 'student2@example.com', 'phone' => '0500000002'],
            ['full_name' => 'طالب تجريبي 3', 'email' => 'student3@example.com', 'phone' => '0500000003'],
        ])->map(fn (array $data) => Student::create($data));

        $phpTraining->students()->attach($students->take(2)->pluck('id'));
        $laravelTraining->students()->attach($students->pluck('id'));
    }
}
