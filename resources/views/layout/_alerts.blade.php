@if(session('success'))
<div class="container-xxl mt-3">
    <div class="alert alert-success">{{ session('success') }}</div>
</div>
@endif

@if(session('warning'))
<div class="container-xxl mt-3">
    <div class="alert alert-warning">{{ session('warning') }}</div>
</div>
@endif

@if(session('error'))
<div class="container-xxl mt-3">
    <div class="alert alert-danger">{{ session('error') }}</div>
</div>
@endif

@if($errors->any())
<div class="container-xxl mt-3">
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
