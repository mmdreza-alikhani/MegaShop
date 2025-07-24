<?php

use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

function generateFileName($name): string
{
    $year = Carbon::now()->year;
    $month = Carbon::now()->month;
    $day = Carbon::now()->day;
    $hour = Carbon::now()->hour;
    $minute = Carbon::now()->minute;
    $second = Carbon::now()->second;
    $microsecond = Carbon::now()->microsecond;
    return $year .'_'. $month .'_'. $day .'_'. $hour .'_'. $minute .'_'. $second .'_'. $microsecond .'_'. strtolower($name);
}

function convertToGregorianDate($date): ?string
{
    if ($date == null){
        return null;
    }
    $pattern = "#[/\s]#";
    $splitedSolarDate = preg_split($pattern, $date);
    $gregorianFormat = Verta::jalaliToGregorian($splitedSolarDate[0],$splitedSolarDate[1],$splitedSolarDate[2]);
    return implode("/" , $gregorianFormat) . " " . $splitedSolarDate[3];
}

function removeTimeFromDate($date){
    if ($date == null){
        return null;
    }
    $pattern = "#[\s]#";
    $splitedSolarDate = preg_split($pattern, $date);
    return $splitedSolarDate[0];
}

function convertPersianNumbersToEnglish($input): array|string
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '٤', '۵', '٥', '٦', '۶', '۷', '۸', '۹'];
    $english = [ 0 ,  1 ,  2 ,  3 ,  4 ,  4 ,  5 ,  5 ,  6 ,  6 ,  7 ,  8 ,  9 ];
    return str_replace($english, $persian, $input);
}

function cartTotalSaleAmount(): float|int
{
    $cartTotalSaleAmount = 0;
    foreach (\Cart::getContent() as $item){
        if ($item->attributes->is_sale){
            $cartTotalSaleAmount += $item->quantity * ($item->attributes->price - $item->attributes->sale_price);
        }
    }
    return $cartTotalSaleAmount;
}

function cartTotalDeliveryAmount() {
    $cartTotalDeliveryAmount = 0;
    foreach (\Cart::getContent() as $item){
        $cartTotalDeliveryAmount += $item->associatedModel->delivery_amount;
    }
    return $cartTotalDeliveryAmount;
}

function checkCoupon($code): array
{
    $coupon = Coupon::where('code', $code)->where('expired_at', '>', Carbon::now())->first();
    if ($coupon == null){
        session()->forget('coupon');
        return ['error' => 'کد تخفیف وارد شده صحیح نمیباشد!'];
    }
    if (Order::where('user_id', auth()->id())->where('coupon_id', $coupon->id)->where('payment_status', 1)->exists()){
        session()->forget('coupon');
        return ['error' => 'شما قبلا از این کد تخفیف استفاده کرده اید!'];
    }
    if ($coupon->getRawOriginal('type') == 'amount'){
        session()->put('coupon', ['code' => $coupon->code,'id' => $coupon->id, 'amount' => $coupon->amount]);
    }else{
        $total = \Cart::getTotal();
        $amount = (($total * $coupon->percentage) / 100) > $coupon->max_percentage_amount ? $coupon->max_percentage_amount : (($total * $coupon->percentage) / 100);
        session()->put('coupon', ['code' => $coupon->code,'id' => $coupon->id, 'amount' => $amount]);
    }

    return ['success' => 'کد تخفیف برای شما اعمال شد'];
}

function cartTotalAmount(){
    if (session()->has('coupon')){
        if (session()->get('coupon.amount') > (\Cart::getTotal() + cartTotalDeliveryAmount())){
            return 0;
        }else{
            return (\Cart::getTotal() + cartTotalDeliveryAmount()) - session()->get('coupon.amount');
        }
    }else{
        return \Cart::getTotal() + cartTotalDeliveryAmount();
    }
}
