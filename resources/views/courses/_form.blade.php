<div class="card-body">
    <div class="form-group">
        <label for="name">اسم الكورس</label>
        <input type="text" id="name" name="name" value="{{ old('name', $course->name) }}"
               class="form-control @error('name') is-invalid @enderror" required maxlength="255">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="description">وصف الكورس</label>
        <textarea id="description" name="description" rows="4"
                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="hours">عدد الساعات</label>
        <input type="number" id="hours" name="hours" min="1" max="10000" value="{{ old('hours', $course->hours) }}"
               class="form-control @error('hours') is-invalid @enderror" required>
        @error('hours') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="is_active">حالة الكورس</label>
        @php($isActive = (string) old('is_active', (int) $course->is_active))
        <select id="is_active" name="is_active" class="form-control @error('is_active') is-invalid @enderror" required>
            <option value="1" @selected($isActive === '1')>فعال</option>
            <option value="0" @selected($isActive === '0')>غير فعال</option>
        </select>
        @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('courses.index') }}" class="btn btn-secondary">إلغاء</a>
</div>
