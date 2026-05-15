@if (session('status'))
    <x-alert>{{ session('status') }}</x-alert>
@endif

@if ($errors->has('delete'))
    <x-alert variant="error">{{ $errors->first('delete') }}</x-alert>
@endif
