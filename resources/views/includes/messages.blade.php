@if(isset($errors))
    @if (count($errors) > 0)
        @foreach($errors -> all() as $error)
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endforeach
    @endif
@endif

{{-- Success messages and session messages --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Success messages and session messages --}}
@if (isset($validation_failed))
    <div class="alert alert-danger">
        {{ $validation_failed }}
    </div>
@endif