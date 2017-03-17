<?php

namespace App\Http\Controllers;


use App\Area;
use App\Coupon;
use App\Order;
use App\Restaurant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        if(Auth::check()){
            return view('home');
        }else{
            return view('login');
        }

    }

    public function getArea()
    {
        $areas = Area::select(['id', 'name'])->Where([['city_id', $_GET['c']], ['name', 'like', '%'.$_GET['q'].'%']])->get();

        return json_encode($areas);
    }

    public function getRestaurant(Request $request)
    {
        if($request->get('area_id') == '')
            return redirect()->route('home')->with(['type'=>"warning", 'info'=>"Sorry, Currently no restaurant is delivering in this location."]);

        $area = Area::where('id', $request->get('area_id'))->first();
        session(['area_id' => $area->id]);
        $restaurant = json_decode($area->restaurant_id, true);
        $restaurants = [];
        $closed = [];
        if($restaurant != null) {
            foreach ($restaurant as $res) {
                $tmp = Restaurant::where('id', $res)->first();
                if($request->type=='delivery' && $tmp->delivery_time !=null) {
                    if($this->check_hours($tmp->delivery_hours, $tmp)) $restaurants[] = $tmp;
                    else $closed[] = $tmp;
                }elseif($request->type=='pickup' && $tmp->pickup_time !=null) {
                    if($this->check_hours($tmp->pickup_hours, $tmp)) $restaurants[] = $tmp;
                    else $closed[] = $tmp;
                }elseif($request->type=='dinein' && $tmp->dinein_time !=null) {
                    if($this->check_hours($tmp->dinein_hours, $tmp)) $restaurants[] = $tmp;
                    else $closed[] = $tmp;
                }
            }
        }

        if(! count($restaurants)>0 && ! count($closed)>0){
            return redirect()->route('home')->with(['type'=>"warning", 'info'=>"Sorry, Currently no restaurant is delivering in this location."]);
        }

        $restaurants = Collection::make($restaurants);
        $type = $request->type;
        return view('restaurant.index', compact(['restaurants', 'area', 'closed', 'type']));
    }

    public function viewRestaurant($type, $id, $name)
    {
        $area = Area::where('id', session('area_id'))->first();
        $restaurant = Restaurant::where("id", $id)->first();

        if($type=='delivery' && $restaurant->delivery_time!=null) {
            if($this->check_hours($restaurant->delivery_hours, $restaurant)) return view('restaurant.nview', compact(['restaurant', 'area', 'type']));
        }elseif($type=='pickup' && $restaurant->pickup_time!=null) {
            if($this->check_hours($restaurant->pickup_hours, $restaurant)) return view('restaurant.nview', compact(['restaurant', 'area', 'type']));
        }elseif($type=='dinein' && $restaurant->dinein_time!=null) {
            if($this->check_hours($restaurant->dinein_hours, $restaurant)) return view('restaurant.nview', compact(['restaurant', 'area', 'type']));
        }

        return view('restaurant.closed', compact(['restaurant', 'area', 'type']));
    }

    public function ajaxRestaurant(Request $request)
    {
        $q = $request->get('q');
        $search = Restaurant::select('id', 'name', 'cuisines')->where('name', 'LIKE', '%'.$q.'%')->orWhere('cuisines', 'LIKE', '%'.$q.'%')->get();
        return $search;
    }

    public function viewOrder($id)
    {
        $order = Order::find($id);

        return view('cart.confirm', compact('order'));
    }

    public function coupon_check(Request $request)
    {
        $gtotal = $request->gtotal;
        $user = Auth::user();
        $coupon = Coupon::select('min_amt', 'max_amount', 'percent', 'return_type', 'valid_from', 'valid_till', 'new_only')->where('code', $request->code)->first();

        if($coupon != null){
            $c = new CouponController();
            $data = $c->check($request->code, $user, $gtotal);
        }else {
            $data = ["status"=>'error', 'error'=>"invalid"];
        }

        return $data;
    }

    public function check_hours($hours, $restaurant)
    {
        $time = json_decode($hours, true);
        if(! isset($time[strtolower(Carbon::now()->format('D'))])) return false;
        else {
            $today = $time[strtolower(Carbon::now()->format('D'))];
            $open = Carbon::now()->createFromFormat('Hi', $today['open_time']);
            $close = Carbon::now()->createFromFormat('Hi', $today['close_time']);
            return Carbon::now()->between($open, $close);
        }
    }
}
