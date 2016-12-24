@if(Session::has('info'))
    <div class="container">
        <div class="alert alert-{{ session('type') }}">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {!! session('info') !!}
        </div>
    </div>
@endif
