# Course Management and Enrollment System

[English](#course-management-and-enrollment-system) | [العربية](#نظام-إدارة-وحجز-الكورسات)

A simple system for managing courses, students and training courses, and for enrolling students in training courses. Built with Laravel 12 and an Arabic admin dashboard based on the AdminLTE 3 template.

## Features

- **Dashboard:** overall statistics and the latest training courses, in an Arabic right-to-left interface.
- **Courses:** create, list, edit and delete (soft delete), with an active or inactive status.
- **Students:** create, list, edit and delete, with profile photo upload and downloadable attached documents.
- **Training courses:** create, list, edit and delete, with a page showing the students enrolled in each one.
- **Enrollment:** enroll a student in one or more training courses, and cancel an enrollment.
- **Flash messages:** success or error messages after every operation, and validation messages in Arabic.

## Business rules

- Each training course belongs to one course, and a new training course cannot be created for an inactive course.
- A course that has training courses cannot be deleted.
- Enrollment is rejected when a training course is full, and a student cannot enroll in the same training course twice.
- A training course's seats cannot be reduced below its number of enrolled students.
- Profile photo: JPG, PNG or WEBP, up to 2 MB.
- Attached documents: PDF, Word or images, up to 3 files at a time, each up to 2 MB.
- Attached documents are not public and can only be downloaded through the application.

## Tech stack

- PHP 8.2 or newer
- Laravel 12
- MySQL or MariaDB
- Blade, AdminLTE 3 and Bootstrap 4

## Installation

You need PHP, Composer and MySQL (XAMPP works).

1. Clone the project and install the dependencies:

   ```bash
   git clone <repository-url>
   cd <folder-name>
   composer install
   ```

2. Create the environment file and the application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Create a database named `course_management` with the `utf8mb4` charset, then set these values in `.env`:

   ```env
   APP_NAME="Course Management"
   APP_LOCALE=ar

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=course_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   The `DB_` lines in the original file are commented out with `#`, so remove the `#` from them.

4. Create the tables with sample data, and link the storage folder so student photos can be displayed:

   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

   Remove `--seed` if you do not want the sample data.

5. Start the project and open http://127.0.0.1:8000 :

   ```bash
   php artisan serve
   ```

## Tests

```bash
php artisan test
```

The tests run on an in-memory SQLite database, so they do not affect your data, but they need the `pdo_sqlite` PHP extension.

## Database

| Table | Description |
|---|---|
| `courses` | Courses |
| `training_courses` | Training courses, each linked to a course through `course_id` |
| `students` | Students |
| `student_documents` | Each student's attached documents |
| `enrollments` | Links students to training courses (many-to-many) |

## Security note

The system has no login, because this training project does not require one. Anyone who can reach the site can change the data, so do not publish it on the internet before adding authentication.

---

# نظام إدارة وحجز الكورسات

نظام بسيط لإدارة الكورسات والطلاب والدورات التدريبية وتسجيل الطلاب في الدورات، مبني بـ Laravel 12 مع لوحة تحكم عربية من قالب AdminLTE 3.

## المميزات

- **لوحة التحكم:** إحصائيات عامة وأحدث الدورات، بواجهة عربية من اليمين إلى اليسار.
- **الكورسات:** إضافة وعرض وتعديل وحذف (Soft Delete)، مع حالة فعال أو غير فعال.
- **الطلاب:** إضافة وعرض وتعديل وحذف، مع رفع صورة شخصية وملفات مرفقة وتحميلها.
- **الدورات التدريبية:** إضافة وعرض وتعديل وحذف، مع صفحة تعرض الطلاب المسجلين في كل دورة.
- **التسجيل في الدورات:** تسجيل الطالب في دورة أو أكثر وإلغاء التسجيل.
- **Flash Messages:** رسائل نجاح أو خطأ بعد كل عملية، ورسائل تحقق بالعربية.

## قواعد النظام

- كل دورة تدريبية تتبع كورسًا واحدًا، ولا يمكن إنشاء دورة جديدة لكورس غير فعال.
- لا يمكن حذف كورس له دورات تدريبية.
- يُمنع التسجيل في دورة اكتملت مقاعدها، ويُمنع تسجيل الطالب في الدورة نفسها مرتين.
- لا يمكن تخفيض عدد مقاعد الدورة إلى أقل من عدد المسجلين فيها.
- الصورة الشخصية: JPG أو PNG أو WEBP، بحد أقصى 2 ميجابايت.
- الملفات المرفقة: PDF أو Word أو صور، حتى 3 ملفات في المرة الواحدة، كل ملف بحد أقصى 2 ميجابايت.
- الملفات المرفقة ليست عامة، وتُحمّل عبر التطبيق فقط.

## التقنيات

- PHP 8.2 أو أحدث
- Laravel 12
- MySQL أو MariaDB
- Blade وAdminLTE 3 وBootstrap 4

## التثبيت

تحتاج إلى PHP وComposer وMySQL (يمكن استخدام XAMPP).

1. انسخ المشروع وثبّت الحزم:

   ```bash
   git clone <رابط-المستودع>
   cd <اسم-المجلد>
   composer install
   ```

2. أنشئ ملف البيئة ومفتاح التطبيق:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. أنشئ قاعدة بيانات باسم `course_management` بترميز `utf8mb4`، ثم عدّل هذه القيم في ملف `.env`:

   ```env
   APP_NAME="نظام إدارة الكورسات"
   APP_LOCALE=ar

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=course_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   أسطر `DB_` في الملف الأصلي معطلة بعلامة `#`، فاحذف العلامة منها.

4. أنشئ الجداول مع بيانات تجريبية، واربط مجلد التخزين لتظهر صور الطلاب:

   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

   إذا لم تكن تريد البيانات التجريبية، فاحذف `--seed` من الأمر.

5. شغّل المشروع وافتح الرابط http://127.0.0.1:8000 :

   ```bash
   php artisan serve
   ```

## الاختبارات

```bash
php artisan test
```

تعمل الاختبارات على قاعدة SQLite في الذاكرة، لذلك لا تؤثر في بياناتك، لكنها تحتاج إلى إضافة `pdo_sqlite` في PHP.

## قاعدة البيانات

| الجدول | الوصف |
|---|---|
| `courses` | الكورسات |
| `training_courses` | الدورات التدريبية، وكل دورة مرتبطة بكورس عبر `course_id` |
| `students` | الطلاب |
| `student_documents` | الملفات المرفقة لكل طالب |
| `enrollments` | ربط الطلاب بالدورات (Many-to-Many) |

## ملاحظة أمنية

النظام لا يحتوي على تسجيل دخول، لأنه غير مطلوب في هذا المشروع التدريبي. أي شخص يصل إلى الموقع يستطيع تعديل البيانات، لذلك لا تنشره على الإنترنت قبل إضافة تسجيل الدخول.
