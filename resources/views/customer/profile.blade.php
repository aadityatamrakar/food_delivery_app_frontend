@extends('partials.app')

@section('header')
    <div style="margin-top: 100px;"></div>
@endsection

@section('content')
    <form class="form-horizontal" action="{{ route('profile') }}" method="post">
        <fieldset>
            <legend>Update Profile</legend>

            {!! csrf_field() !!}
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="name">Name</label>
                <div class="col-md-6">
                    <input id="name" name="name" type="text" value="{{ old('name')?:Auth::user()->name }}" class="form-control input-md" required="">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="email">Email</label>
                <div class="col-md-6">
                    <input id="email" name="email" type="text" value="{{ old('email')?:Auth::user()->email }}" class="form-control input-md" required="">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="mobile">Mobile</label>
                <div class="col-md-6">
                    <input id="mobile" name="mobile" type="text" value="{{ Auth::user()->mobile }}" disabled class="form-control input-md" required="">

                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="city">City</label>
                <div class="col-md-6">
                    <input readonly id="city" name="city" type="text" value="{{ old('city')?:\App\City::find(Auth::user()->city)->name }}" class="form-control input-md">
                </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="address">Address</label>
                <div class="col-md-4">
                    <textarea class="form-control" id="address" name="address">{{ old('address')?:Auth::user()->address }}</textarea>
                </div>
            </div>

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="Save"></label>
                <div class="col-md-4">
                    <button id="Save" name="Save" class="btn btn-primary">Save</button>
                </div>
            </div>
        </fieldset>
    </form>
@endsection