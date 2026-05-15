@if (session('status'))
    <div class="p-4 bg-green-50 text-green-800 rounded-md text-sm font-medium">
        {{ session('status') }}
    </div>
@endif

@if ($errors->has('delete'))
    <div class="p-4 bg-red-50 text-red-800 rounded-md text-sm font-medium">
        {{ $errors->first('delete') }}
    </div>
@endif
