@extends('partials.app')

@section('content')
    <br><br><br><br><br>
    <h3>My Wallet, Current Balance: Rs. {{ $bal }}</h3>
    <hr>
    <table class="table table-bordered" id="wallet_tbl">
        <thead>
        <tr>
            <th>#</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Order</th>
            <th>Restaurant</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customer->transactions as $index=>$transaction)
            <tr>
                <td>{{ $index+1 }}</td>
                <td style="text-transform: capitalize;">{{ $transaction->type }}</td>
                <td>
                    @if($transaction->type == 'added' || $transaction->type == 'cashback_recieved')
                        (+)
                    @elseif($transaction->type == 'paid_for_order' || $transaction->type == 'removed')
                        (-)
                    @endif
                    Rs. {{ $transaction->amount }}</td>
                @if($transaction->order_id != null)
                    <td>#{{ $transaction->order_id }}</td>
                @else
                    <td></td>
                @endif
                @if($transaction->restaurant_id != null)
                    <td>{{ \App\Restaurant::find($transaction->restaurant_id)->name }}</td>
                @else
                    <td></td>
                @endif
                <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d h:i A') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('script')

@endsection