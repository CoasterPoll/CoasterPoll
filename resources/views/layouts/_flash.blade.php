@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('danger'))
    <div class="alert alert-danger">
        {{ session('danger') }}
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info">
        {{ session('info') }}
    </div>
@endif
@if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif
@if (count($errors) > 0)
    <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}&nbsp;
            @endforeach
    </div>
@endif