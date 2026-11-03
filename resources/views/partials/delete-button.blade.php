<form action="{{ $action }}" method="POST" data-confirm="{{ $confirm }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">
        <i class="fas fa-trash"></i> {{ $label ?? 'حذف' }}
    </button>
</form>
