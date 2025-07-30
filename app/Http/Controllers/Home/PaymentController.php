<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function checkCart()
    {
        if (\Cart::isEmpty()) {
            return ['error' => 'سبد خرید شما خالی است!'];
        }
        foreach (\Cart::getContent() as $item) {
            $variation = ProductVariation::find($item->attributes->id);

            $price = $variation->sale_price != null && $variation->date_on_sale_from < Carbon::now() && $variation->date_on_sale_to > Carbon::now() ? $variation->sale_price : $variation->price;

            if ($item->price != $price) {
                \Cart::clear();

                return ['error' => 'قیمت محصول تغییر کرده است!'];
            }

            if ($item->quantity > $variation->quantity) {
                \Cart::clear();

                return ['error' => 'تعداد محصول تغییر کرده است!'];
            }

            return ['success' => 'success!'];
        }
    }

    public function getAmount()
    {

        if (session()->has('coupon')) {
            $checkCoupon = checkCoupon(session()->get('coupon.code'));
            if (array_key_exists('error', $checkCoupon)) {
                return $checkCoupon;
            }
        }

        return [
            'total_amount' => \Cart::getTotal() + cartTotalSaleAmount(),
            'delivery_amount' => cartTotalDeliveryAmount(),
            'coupon_amount' => session()->has('coupon') ? session()->get('coupon.amount') : 0,
            'paying_amount' => cartTotalAmount(),
        ];
    }

    public function send($api, $amount, $redirect, $mobile = null, $factorNumber = null, $description = null)
    {
        return $this->curl_post('https://pay.ir/pg/send', [
            'api' => $api,
            'amount' => $amount,
            'redirect' => $redirect,
            'mobile' => $mobile,
            'factorNumber' => $factorNumber,
            'description' => $description,
        ]);
    }

    public function verify($api, $token)
    {
        return $this->curl_post('https://pay.ir/pg/verify', [
            'api' => $api,
            'token' => $token,
        ]);
    }

    public function curl_post($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    public function paymentVerification(Request $request)
    {

        $api = 'test';
        $token = $request->token;
        $result = json_decode($this->verify($api, $token));
        if (isset($result->status)) {
            if ($result->status == 1) {
                $updateOrder = $this->updateOrder($token, $result->transId);
                if (array_key_exists('error', $updateOrder)) {
                    toastr()->error($updateOrder['error']);

                    return redirect()->route('home.index');
                }
                \Cart::clear();
                toastr()->success('تراکنش با موفقیت انجام شد!');

                return redirect()->back();
            } else {
                toastr()->error('پرداخت با خطا مواجه شد!');

                return redirect()->back();
            }
        } else {
            if ($request->status == 0) {
                toastr()->error('پرداخت با خطا مواجه شد!');

                return redirect()->back();
            }
        }
    }

    public function createOrder($addressId, $amounts, $token, $getaway_name)
    {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $addressId,
                'coupon_id' => session()->has('coupon') ? session()->get('coupon.id') : null,
                'total_amount' => $amounts['total_amount'],
                'delivery_amount' => $amounts['delivery_amount'],
                'coupon_amount' => $amounts['coupon_amount'],
                'paying_amount' => $amounts['paying_amount'],
                'payment_type' => 'online',
            ]);

            foreach (\Cart::getContent() as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->associatedModel->id,
                    'product_variation_id' => $item->attributes->id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => ($item->quantity * $item->price),
                ]);
            }

            Transaction::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'amount' => $amounts['paying_amount'],
                'token' => $token,
                'getaway_name' => $getaway_name,
            ]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            return ['error' => $ex->getMessage()];
        }

        return ['success' => 'success'];
    }

    public function updateOrder($token, $transId)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::where('token', $token)->firstOrFail();
            $transaction->update([
                'status' => 1,
                'ref_id' => $transId,
            ]);

            $order = Order::findOrFail($transaction->order_id);
            $order->update([
                'payment_status' => 1,
                'status' => 1,
            ]);

            foreach (\Cart::getContent() as $item) {
                $variation = ProductVariation::find($item->attributes->id);
                $variation->update([
                    'quantity' => $variation->quantity - $item->quantity,
                ]);
            }

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();

            return ['error' => $ex->getMessage()];
        }

        return ['success' => 'success'];
    }

    public function payment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
        ]);
        if ($validator->fails()) {
            toastr()->error('!ادامه فرایند ممکن نیست');

            return redirect()->route('home.index');
        }

        $checkCart = $this->checkCart();
        if (array_key_exists('error', $checkCart)) {
            toastr()->error($checkCart['error']);

            return redirect()->route('home.index');
        }

        $amounts = $this->getAmount();
        if (array_key_exists('error', $amounts)) {
            toastr()->error($amounts['error']);

            return redirect()->route('home.cart.index');
        }

        $api = 'test';
        $amount = $amounts['paying_amount'];
        //        $mobile = "شماره موبایل";
        //        $factorNumber = "شماره فاکتور";
        //        $description = "توضیحات";
        $redirect = route('home.cart.payment.verification');
        $result = $this->send($api, $amount, $redirect);
        $result = json_decode($result);
        if ($result->status) {
            $createOrder = $this->createOrder($request->address_id, $amounts, $result->token, 'pay');
            if (array_key_exists('error', $createOrder)) {
                toastr()->error($createOrder['error']);

                return redirect()->route('home.index');
            }
            $go = "https://pay.ir/pg/$result->token";

            return redirect()->to($go);
        } else {
            echo $result->errorMessage;
        }
    }
}
