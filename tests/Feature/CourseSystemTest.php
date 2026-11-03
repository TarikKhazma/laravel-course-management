<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use App\Models\TrainingCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CourseSystemTest extends TestCase
{
    use RefreshDatabase;

    private function makeTraining(int $seats = 5, array $courseAttributes = []): TrainingCourse
    {
        $course = Course::create(['name' => 'كورس', 'hours' => 10] + $courseAttributes);

        return $course->trainingCourses()->create([
            'name' => 'دورة',
            'start_date' => '2027-01-01',
            'end_date' => '2027-01-31',
            'seats' => $seats,
        ]);
    }

    private function makeStudent(string $email = 'a@example.com'): Student
    {
        return Student::create(['full_name' => 'طالب', 'email' => $email]);
    }

    public function test_all_pages_render(): void
    {
        $training = $this->makeTraining();
        $student = $this->makeStudent();
        $training->students()->attach($student);

        $pages = [
            route('dashboard'),
            route('courses.index'), route('courses.create'), route('courses.edit', $training->course_id),
            route('students.index'), route('students.create'), route('students.edit', $student),
            route('training-courses.index'), route('training-courses.create'),
            route('training-courses.show', $training), route('training-courses.edit', $training),
            route('enrollments.create'),
        ];

        foreach ($pages as $page) {
            $this->get($page)->assertOk();
        }
    }

    public function test_course_crud_shows_flash_messages(): void
    {
        $this->post(route('courses.store'), ['name' => 'PHP', 'hours' => 30, 'is_active' => 1])
            ->assertRedirect(route('courses.index'))
            ->assertSessionHas('success', 'تم إضافة الكورس بنجاح.');
        $course = Course::firstOrFail();

        $this->put(route('courses.update', $course), ['name' => 'Laravel', 'hours' => 40, 'is_active' => 0])
            ->assertSessionHas('success', 'تم تعديل الكورس بنجاح.');
        $this->assertSame('Laravel', $course->fresh()->name);
        $this->assertFalse($course->fresh()->is_active);

        $this->delete(route('courses.destroy', $course))
            ->assertSessionHas('success', 'تم حذف الكورس بنجاح.');
        $this->assertSoftDeleted($course);
    }

    public function test_validation_messages_are_in_arabic(): void
    {
        $this->post(route('courses.store'), ['hours' => 0, 'is_active' => 1])
            ->assertSessionHasErrors([
                'name' => 'حقل الاسم مطلوب.',
                'hours' => 'يجب ألا يقل عدد الساعات عن 1.',
            ]);
    }

    public function test_course_with_trainings_cannot_be_deleted(): void
    {
        $training = $this->makeTraining();

        $this->delete(route('courses.destroy', $training->course_id))->assertSessionHas('error');

        $this->assertNotSoftDeleted($training->course);
    }

    public function test_training_cannot_use_inactive_course_but_can_keep_it_when_editing(): void
    {
        $training = $this->makeTraining(5, ['is_active' => false]);
        $payload = ['course_id' => $training->course_id, 'name' => 'جديدة', 'start_date' => '2027-01-01', 'end_date' => '2027-01-02', 'seats' => 5];

        $this->post(route('training-courses.store'), $payload)->assertSessionHasErrors('course_id');

        $this->put(route('training-courses.update', $training), $payload)->assertSessionHasNoErrors();
        $this->assertSame('جديدة', $training->fresh()->name);
    }

    public function test_training_rejects_end_date_before_start_and_seats_below_enrolled(): void
    {
        $training = $this->makeTraining();
        $training->students()->attach([$this->makeStudent()->id, $this->makeStudent('b@example.com')->id]);

        $this->put(route('training-courses.update', $training), [
            'course_id' => $training->course_id, 'name' => 'x', 'start_date' => '2027-02-01', 'end_date' => '2027-01-01', 'seats' => 1,
        ])->assertSessionHasErrors(['end_date', 'seats']);
    }

    public function test_student_files_are_stored_downloadable_and_removed_on_delete(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $this->post(route('students.store'), [
            'full_name' => 'طالب',
            'email' => 'a@example.com',
            'phone' => '+966 500000000',
            'photo' => UploadedFile::fake()->create('me.jpg', 100, 'image/jpeg'),
            'documents' => [UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf')],
        ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $student = Student::with('documents')->firstOrFail();
        $document = $student->documents->first();
        Storage::disk('public')->assertExists($student->photo);
        Storage::disk('local')->assertExists($document->path);

        $this->get(route('students.documents.download', [$student, $document]))->assertDownload('cv.pdf');

        $this->delete(route('students.destroy', $student))->assertSessionHas('success');
        Storage::disk('public')->assertMissing($student->photo);
        Storage::disk('local')->assertMissing($document->path);
    }

    public function test_uploads_reject_disallowed_types_and_sizes(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $this->post(route('students.store'), [
            'full_name' => 'طالب',
            'email' => 'a@example.com',
            'photo' => UploadedFile::fake()->create('shell.php', 10, 'application/x-php'),
            'documents' => [UploadedFile::fake()->create('big.pdf', 3000, 'application/pdf')],
        ])->assertSessionHasErrors(['photo', 'documents.0']);

        $this->assertSame(0, Student::count());
    }

    public function test_updating_photo_replaces_the_old_file(): void
    {
        Storage::fake('public');
        $student = $this->makeStudent();
        $student->update(['photo' => UploadedFile::fake()->create('old.jpg', 10, 'image/jpeg')->store('students/photos', 'public')]);
        $oldPhoto = $student->photo;

        $this->put(route('students.update', $student), [
            'full_name' => 'طالب',
            'email' => 'a@example.com',
            'photo' => UploadedFile::fake()->create('new.jpg', 10, 'image/jpeg'),
        ])->assertSessionHasNoErrors();

        Storage::disk('public')->assertMissing($oldPhoto);
        Storage::disk('public')->assertExists($student->fresh()->photo);
    }

    public function test_document_of_another_student_is_not_accessible(): void
    {
        Storage::fake('local');
        $owner = $this->makeStudent();
        $other = $this->makeStudent('b@example.com');
        $document = $owner->documents()->create(['original_name' => 'cv.pdf', 'path' => 'x.pdf']);

        $this->get(route('students.documents.download', [$other, $document]))->assertNotFound();
    }

    public function test_enrollment_and_cancellation(): void
    {
        $training = $this->makeTraining();
        $student = $this->makeStudent();

        $this->post(route('enrollments.store'), ['student_id' => $student->id, 'training_course_id' => $training->id])
            ->assertRedirect(route('training-courses.show', $training))
            ->assertSessionHas('success');
        $this->assertTrue($training->students()->whereKey($student->id)->exists());

        $this->get(route('training-courses.show', $training))->assertSee($student->email);

        $this->delete(route('enrollments.destroy', [$training, $student]))->assertSessionHas('success');
        $this->assertFalse($training->students()->whereKey($student->id)->exists());
    }

    public function test_student_can_join_several_trainings(): void
    {
        $student = $this->makeStudent();
        $first = $this->makeTraining();
        $second = $this->makeTraining();

        foreach ([$first, $second] as $training) {
            $this->post(route('enrollments.store'), ['student_id' => $student->id, 'training_course_id' => $training->id])
                ->assertSessionHas('success');
        }

        $this->assertSame(2, $student->trainingCourses()->count());
    }

    public function test_duplicate_enrollment_is_rejected(): void
    {
        $training = $this->makeTraining();
        $student = $this->makeStudent();
        $training->students()->attach($student);

        $this->post(route('enrollments.store'), ['student_id' => $student->id, 'training_course_id' => $training->id])
            ->assertSessionHas('error', 'هذا الطالب مسجل بالفعل في هذه الدورة.');

        $this->assertSame(1, $training->students()->count());
    }

    public function test_full_training_rejects_enrollment(): void
    {
        $training = $this->makeTraining(1);
        $training->students()->attach($this->makeStudent());

        $this->post(route('enrollments.store'), ['student_id' => $this->makeStudent('b@example.com')->id, 'training_course_id' => $training->id])
            ->assertSessionHas('error', 'لا توجد مقاعد متاحة في هذه الدورة.');

        $this->assertSame(1, $training->students()->count());
    }

    public function test_unexpected_failure_shows_generic_flash_error(): void
    {
        Schema::drop('courses');

        $this->from(route('courses.create'))
            ->post(route('courses.store'), ['name' => 'PHP', 'hours' => 30, 'is_active' => 1])
            ->assertRedirect(route('courses.create'))
            ->assertSessionHas('error', 'حدث خطأ أثناء تنفيذ العملية.');
    }
}
