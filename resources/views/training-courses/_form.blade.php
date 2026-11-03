<div class="card-body">
    <div class="form-group">
        <label for="course_id">الكورس</label>
        <select id="course_id" name="course_id" class="form-control @error('course_id') is-invalid @enderror" required>
            <option value="">اختر الكورس</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected((string) old('course_id', $trainingCourse->course_id) === (string) $course->id)>
                    {{ $course->name }}
                </option>
            @endforeach
        </select>
        @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($courses->isEmpty())
            <small class="form-text text-danger">
                لا توجد كورسات فعالة. <a href="{{ route('courses.create') }}">أضف كورساً أولاً</a>.
            </small>
        @endif
    </div>

    <div class="form-group">
        <label for="name">اسم الدورة</label>
        <input type="text" id="name" name="name" value="{{ old('name', $trainingCourse->name) }}"
               class="form-control @error('name') is-invalid @enderror" required maxlength="255">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="description">وصف الدورة</label>
        <textarea id="description" name="description" rows="4"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $trainingCourse->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label for="start_date">تاريخ البداية</label>
            <input type="date" id="start_date" name="start_date"
                   value="{{ old('start_date', $trainingCourse->start_date?->format('Y-m-d')) }}"
                   class="form-control @error('start_date') is-invalid @enderror" required>
            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="end_date">تاريخ النهاية</label>
            <input type="date" id="end_date" name="end_date"
                   value="{{ old('end_date', $trainingCourse->end_date?->format('Y-m-d')) }}"
                   class="form-control @error('end_date') is-invalid @enderror" required>
            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="seats">عدد المقاعد</label>
            <input type="number" id="seats" name="seats" min="1" max="10000" value="{{ old('seats', $trainingCourse->seats) }}"
                   class="form-control @error('seats') is-invalid @enderror" required>
            @error('seats') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('training-courses.index') }}" class="btn btn-secondary">إلغاء</a>
</div>
