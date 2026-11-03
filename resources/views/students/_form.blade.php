<div class="card-body">
    <div class="form-group">
        <label for="full_name">الاسم الكامل</label>
        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $student->full_name) }}"
               class="form-control @error('full_name') is-invalid @enderror" required maxlength="255">
        @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="email">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" dir="ltr" value="{{ old('email', $student->email) }}"
               class="form-control @error('email') is-invalid @enderror" required maxlength="255">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="phone">رقم الهاتف</label>
        <input type="text" id="phone" name="phone" dir="ltr" value="{{ old('phone', $student->phone) }}"
               class="form-control @error('phone') is-invalid @enderror" maxlength="20">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="photo">الصورة الشخصية</label>
        @if ($student->photo)
            <div class="mb-2">
                <img src="{{ $student->photoUrl() }}" alt="{{ $student->full_name }}" class="student-photo">
            </div>
        @endif
        <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.webp"
               class="form-control-file @error('photo') is-invalid @enderror">
        <small class="form-text text-muted">JPG أو PNG أو WEBP، بحد أقصى 2 ميجابايت.</small>
        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label for="documents">الملفات المرفقة</label>
        <input type="file" id="documents" name="documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
               class="form-control-file @error('documents') is-invalid @enderror @error('documents.*') is-invalid @enderror">
        <small class="form-text text-muted">PDF أو Word أو صور، حتى 3 ملفات في المرة الواحدة، كل ملف بحد أقصى 2 ميجابايت.</small>
        @error('documents') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @foreach ($errors->get('documents.*') as $messages)
            <div class="invalid-feedback">{{ $messages[0] }}</div>
        @endforeach
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">إلغاء</a>
</div>
