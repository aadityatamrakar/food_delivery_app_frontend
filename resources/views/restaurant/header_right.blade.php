<form class="navbar-form navbar-left" method="post" action="{{ route('restaurant.index') }}">
    <div class="form-group input-group">
        <div class="input-group">
            <span class="input-group-addon" style="background-color: white;"><b>Change Delivery Location</b></span>
            <select class="form-control my-control" name="city" id="city">
                <option>City</option>
                @foreach(\App\City::all() as $city)
                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
            <script>
                document.getElementById('city').value = '{{ $area->city_id }}';
            </script>
        </div>
        <div class="input-group">
            <input type="text" class="form-control my-control" autocomplete="off" placeholder="Area" value="{{ $area->name }}" name="area" id="area">
            <input type="hidden" name="area_id" id="area_id" value="{{ $area->id }}" />
            {!! csrf_field() !!}
            <div class="input-group-btn">
                <button type="submit" class="btn btn-primary">Change</button>
            </div>
        </div>
    </div>
</form>
<form class="navbar-form navbar-right" action="{{ route('wallet') }}" method="get">
    <button type="submit" class="btn btn-default"><i class="fa fa-folder-open" style="font-size: 18px;"></i> Wallet (<i class="fa fa-inr"></i> {{ \App\Http\Controllers\WalletController::balance() }})</button>
</form>

@section('area_script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    <script>
        var $area = $('#area');
        $area.typeahead({
            highlight: true,
            name: 'area',
            display: 'name',
            afterSelect: function (item){ $("#area_id").val(item.id); },
            source: function (query, process) {
                var ajaxResponse, city_id= $("#city").val();
                $.ajax({
                    url: '{{ route('area.get') }}?q=' + query+'&c='+city_id,
                    type: "GET",
                    cache: false,
                    async: false,
                    success : function (response) {
                        ajaxResponse = JSON.parse(response);
                        console.log(ajaxResponse);
                        process(ajaxResponse);
                    },
                });
            },
            autoSelect: true
        })
    </script>
@endsection