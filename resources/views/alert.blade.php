@error('email')
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <span class="fw-semibold">Info:</span> {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@enderror

@error('password')
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <span class="fw-semibold">Info:</span> {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@enderror

@error('name')
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <span class="fw-semibold">Info:</span> {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@enderror

@error('phone')
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <span class="fw-semibold">Info:</span> {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@enderror
