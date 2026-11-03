@foreach (['success' => 'check-circle', 'error' => 'exclamation-triangle'] as $type => $icon)
    @if (session($type))
        <div class="alert alert-{{ $type === 'error' ? 'danger' : 'success' }} alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">&times;</button>
            <i class="fas fa-{{ $icon }}"></i>
            {{ session($type) }}
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">&times;</button>
        <i class="fas fa-exclamation-triangle"></i>
        لم يتم الحفظ، يرجى تصحيح الأخطاء الموضحة في النموذج.
    </div>
@endif
