@auth
    <a href="{{ route('links.submit') }}" class="btn btn-outline-primary btn-block @isset($submitBtnActive) @if($submitBtnActive) active  disabled @endif @endisset">Submit New</a>
@endauth