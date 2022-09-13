@if ($message = session('primary'))
<div class="alert alert-primary alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <strong>{{ $message }}</strong>
    </div>
</div>
@endif
@if ($message = session('seondary'))
<div class="alert alert-secondary alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <strong>{{ $message }}</strong>
    </div>
</div>
@endif
@if ($message = session('success'))
<div class="alert alert-success alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <strong>{{ $message }}</strong>
    </div>
</div>
@endif
@if ($message = session('danger'))
<div class="alert alert-danger alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <strong>{{ $message }}</strong>
    </div>
</div>
@endif
@if ($message = session('warning'))
<div class="alert alert-warning alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <strong>{{ $message }}</strong>
    </div>
</div>
@endif
@if ($message = session('info'))
<div class="alert alert-info alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <strong>{{ $message }}</strong>
    </div>
</div>
@endif
@if ($message = session('light'))
<div class="alert alert-light alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <strong>{{ $message }}</strong>
    </div>
</div>
@endif
@if ($message = session('dark'))
<div class="alert alert-dark alert-dismissible show fade">
    <div class="alert-body">
        <button class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
        <strong>{{ $message }}</strong>
    </div>
</div>
@endif