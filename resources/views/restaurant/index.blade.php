@extends('partials.app')

@section('style')
    @include('restaurant.style')
@endsection

@section('header_right')
    @include('restaurant.header_right')
@endsection

@section('header')
    <div class="container" style="margin-top: 80px;">
        <div class="row">
            <div class="col-md-9">
                <div class="carousel slide" id="carousel-example-generic" data-interval="1000" data-ride="carousel" style="border: 1px solid #000;">
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <img src="https://res.cloudinary.com/swiggy/image/upload/f_auto,fl_lossy,q_80/qenyr5dssgyhg2xnmhs4" width="100%" height="300px" alt="test1">
                        </div>
                        <div class="item">
                            <img src="https://res.cloudinary.com/swiggy/image/upload/f_auto,fl_lossy,q_80/yakreg841lcpl6tmolld" width="100%" height="300px" alt="test1">
                        </div>
                        <div class="item">
                            <img src="https://res.cloudinary.com/swiggy/image/upload/f_auto,fl_lossy,q_80/b9evyqkivxfmyko7voa2" width="100%" height="300px" alt="test1">
                        </div>
                    </div>

                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=400%C3%97280&w=350&h=280" width="100%" />
            </div>
        </div>
    </div>
    <br>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-9" style="margin-bottom: 10px">
            <div class="box">
                <div class="row">
                    @foreach($restaurants as $restaurant)
                        @include('restaurant.partials.list_restaurant_item', ['type'=>Request::get('type'), 'restaurant'=>$restaurant, 'link'=>true])
                    @endforeach
                    @foreach($closed as $restaurant)
                        @include('restaurant.partials.list_restaurant_item', ['type'=>Request::get('type'), 'restaurant'=>$restaurant, 'link'=>false])
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="nav">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    </script>
    @yield('area_script')
@endsection