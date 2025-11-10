<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * شروع پرداخت و ارسال به درگاه
     */
    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:user_addresses,id',
            'description' => 'nullable|string|min:3',
        ]);

        if ($validator->fails() || !auth()->check()) {
            flash()->error('ادامه فرایند ممکن نیست!');
            return redirect()->back();
        }

        $user = auth()->user();
        $amounts = cartAmounts();
        $amount = $amounts['payingAmount'];

        // ایجاد سفارش قبل از پرداخت
        $orderResult = $this->createOrder($request->address_id, $amounts);

        if (array_key_exists('error', $orderResult)) {
            flash()->error($orderResult['error']);
            return redirect()->back();
        }

        $order = $orderResult['order'];

        // آماده‌سازی داده‌های پرداخت
        $data = [
            "merchant_id" => config('pay.merchant_id'),
            "amount" => $amount,
            "currency" => "IRT", // تومان
            "description" => $request->description ?? 'پرداخت سفارش شماره ' . $order->id,
            "callback_url" => route('home.payment.verify'),
            "metadata" => [
                "mobile" => $user->phone_number,
                "email" => $user->email,
                "order_id" => (string) $order->id // شماره سفارش برای پیگیری
            ]
        ];

        // درخواست به زرین‌پال
        $result = $this->sendRequest(config('pay.request_url'), $data);

        // بررسی پاسخ
        if (isset($result['data']['code']) && $result['data']['code'] == 100) {
            $authority = $result['data']['authority'];

            // ذخیره authority در تراکنش
            Transaction::where('order_id', $order->id)->update([
                'token' => $authority,
            ]);

            // هدایت به درگاه پرداخت (sandbox برای تست)
            $paymentUrl = config('pay.payment_url') . $authority;
            return redirect()->away($paymentUrl);
        }

        // در صورت خطا
        $errorMessage = $result['errors']['message'] ?? 'خطا در اتصال به درگاه پرداخت';
        flash()->error($errorMessage);

        return redirect()->route('home.cart.checkout');
    }

    /**
     * بازگشت از درگاه و تایید پرداخت
     */
    public function verify(Request $request)
    {
        $authority = $request->Authority;
        $status = $request->Status;

        // بررسی وضعیت پرداخت
        if ($status != 'OK') {
            flash()->error('پرداخت توسط کاربر لغو شد یا با خطا مواجه شد.');
            return redirect()->route('home.cart.checkout');
        }

        // یافتن تراکنش
        $transaction = Transaction::where('token', $authority)->first();

        if (!$transaction) {
            flash()->error('تراکنش مورد نظر یافت نشد!');
            return redirect()->route('home.cart.index');
        }

        // بررسی اینکه قبلاً verify نشده باشد
        if ($transaction->status == 1) {
            flash()->info('این تراکنش قبلاً تایید شده است.');
            return redirect()->route('home.cart.index');
        }

        $order = Order::findOrFail($transaction->order_id);

        // آماده‌سازی داده‌های verify
        $data = [
            "merchant_id" => config('pay.merchant_id'),
            "amount" => $transaction->amount,
            "authority" => $authority
        ];

        // ارسال درخواست verify
        $result = $this->sendRequest(config('pay.verify_url'), $data);

        // بررسی نتیجه verify
        if (isset($result['data']['code'])) {
            $code = $result['data']['code'];

            // کد 100 = موفق، کد 101 = قبلاً verify شده
            if ($code == 100 || $code == 101) {
                $refId = $result['data']['ref_id'];
                $cardPan = $result['data']['card_pan'] ?? null;

                // به‌روزرسانی تراکنش
                $updateResult = $this->updateOrder($transaction, $refId, $cardPan);

                if (array_key_exists('error', $updateResult)) {
                    flash()->error($updateResult['error']);
                    return redirect()->back();
                }

                // پاک کردن سبد خرید
                clearCart(auth()->id());

                flash()->success("پرداخت با موفقیت انجام شد. کد پیگیری: {$refId}");
                return redirect()->route('home.profile.orders.index');
            }
        }

        // خطا در verify
        $errorMessage = $result['errors']['message'] ?? 'تایید پرداخت با خطا مواجه شد';
        flash()->error($errorMessage);

        return redirect()->route('home.profile.orders.checkout');
    }

    /**
     * ارسال درخواست CURL به زرین‌پال
     */
    private function sendRequest($url, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);

            \Log::error('Zarinpal CURL Error: ' . $error);

            return [
                'errors' => ['message' => 'خطا در اتصال به درگاه پرداخت']
            ];
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * ایجاد سفارش
     */
    private function createOrder($addressId, $amounts)
    {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $addressId,
                'coupon_id' => session()->has('coupon') ? session()->get('coupon.id') : null,
                'total_amount' => $amounts['totalAmount'],
                'delivery_amount' => $amounts['deliveryAmount'],
                'coupon_amount' => $amounts['couponAmount'],
                'paying_amount' => $amounts['payingAmount'],
                'payment_type' => 'online',
            ]);

            foreach (cartItems() as $item) {
                $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                $variationId = $options['variation_id'] ?? null;

                if (!$variationId) {
                    throw new \Exception('اطلاعات محصول در سبد خرید نامعتبر است');
                }

                $variation = ProductVariation::find($variationId);

                if (!$variation) {
                    throw new \Exception('محصول مورد نظر یافت نشد');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->itemable_id,
                    'product_variation_id' => $variationId,
                    'price' => $variation->best_price,
                    'quantity' => $item->quantity,
                    'subtotal' => ($item->quantity * $variation->best_price),
                ]);
            }

            Transaction::create([
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'amount' => $amounts['payingAmount'],
                'token' => null, // بعداً authority اینجا ذخیره می‌شه
                'gateway_name' => 'zarinpal',
                'status' => 0,
            ]);

            DB::commit();

            return ['success' => true, 'order' => $order];

        } catch (\Exception $ex) {
            DB::rollBack();
            \Log::error('Create Order Error: ' . $ex->getMessage());

            return ['error' => $ex->getMessage()];
        }
    }

    /**
     * به‌روزرسانی سفارش بعد از پرداخت موفق
     */
    private function updateOrder($transaction, $refId, $cardPan = null)
    {
        try {
            DB::beginTransaction();

            // به‌روزرسانی تراکنش
            $transaction->update([
                'status' => 1,
                'ref_id' => $refId,
                'card_pan' => $cardPan,
            ]);

            // به‌روزرسانی سفارش
            $order = Order::findOrFail($transaction->order_id);
            $order->update([
                'payment_status' => 1,
                'status' => 1,
            ]);

            // کسر موجودی محصولات
            foreach (cartItems() as $item) {
                $options = is_string($item->options) ? json_decode($item->options, true) : $item->options;
                $variationId = $options['variation_id'] ?? null;

                if ($variationId) {
                    $variation = ProductVariation::find($variationId);
                    if ($variation) {
                        $variation->decrement('quantity', $item->quantity);
                    }
                }
            }

            DB::commit();

            return ['success' => true];

        } catch (\Exception $ex) {
            DB::rollBack();
            \Log::error('Update Order Error: ' . $ex->getMessage());

            return ['error' => $ex->getMessage()];
        }
    }
}
