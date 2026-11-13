<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class PaymentController extends Controller
{
    /**
     * شروع پرداخت و ارسال به درگاه
     */
    public function payment(Request $request): RedirectResponse
    {
        if (! auth()->check()) {
            flash()->error('برای ادامه باید وارد حساب خود شوید!');

            return redirect()->route('home.index');
        }

        $validator = Validator::make($request->all(), [
            // آدرس باید متعلق به همین کاربر باشد (جلوگیری از IDOR)
            'address_id' => [
                'required',
                Rule::exists('user_addresses', 'id')->where('user_id', auth()->id()),
            ],
            'description' => 'nullable|string|min:3',
        ]);

        if ($validator->fails()) {
            flash()->error('اطلاعات ارسال‌شده معتبر نیست!');

            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (isCartEmpty()) {
            flash()->warning('سبد خرید شما خالی است!');

            return redirect()->route('home.cart.index');
        }

        if (cartService()->hasUnavailableItems()) {
            flash()->warning('برخی از محصولات سبد خرید شما موجود نیستند.');

            return redirect()->route('home.cart.index');
        }

        $amounts = cartAmounts();

        if ($amounts['payingAmount'] <= 0) {
            flash()->error('مبلغ قابل پرداخت معتبر نیست!');

            return redirect()->route('home.cart.index');
        }

        $orderResult = $this->createOrder($request->integer('address_id'), $amounts, $request->input('description'));

        if (array_key_exists('error', $orderResult)) {
            flash()->error($orderResult['error']);

            return redirect()->back();
        }

        $order = $orderResult['order'];
        $user = auth()->user();

        // حالت دمو: به‌جای درگاه واقعی زرین‌پال، کاربر به شبیه‌ساز داخلی هدایت می‌شود
        if (config('pay.driver') === 'simulated') {
            return redirect()->route('home.payment.simulator', ['order' => $order->id]);
        }

        $data = [
            'merchant_id' => config('pay.merchant_id'),
            'amount' => $amounts['payingAmount'],
            'currency' => 'IRT', // تومان
            'description' => $request->input('description') ?: 'پرداخت سفارش شماره '.$order->id,
            'callback_url' => route('home.payment.verify'),
            'metadata' => [
                'mobile' => $user->phone_number,
                'email' => $user->email,
                'order_id' => (string) $order->id,
            ],
        ];

        $result = $this->sendRequest(config('pay.request_url'), $data);

        if (isset($result['data']['code']) && $result['data']['code'] == 100) {
            $authority = $result['data']['authority'];

            Transaction::where('order_id', $order->id)->update(['token' => $authority]);

            return redirect()->away(config('pay.payment_url').$authority);
        }

        $errorMessage = $result['errors']['message'] ?? 'خطا در اتصال به درگاه پرداخت';
        flash()->error($errorMessage);

        return redirect()->route('home.cart.checkout');
    }

    /**
     * بازگشت از درگاه و تایید پرداخت
     */
    public function verify(Request $request): RedirectResponse
    {
        $authority = $request->input('Authority');
        $status = $request->input('Status');

        if (! $authority) {
            flash()->error('اطلاعات بازگشتی از درگاه معتبر نیست!');

            return redirect()->route('home.cart.index');
        }

        $transaction = Transaction::where('token', $authority)->first();

        if (! $transaction) {
            flash()->error('تراکنش مورد نظر یافت نشد!');

            return redirect()->route('home.cart.index');
        }

        // تراکنش باید متعلق به کاربر لاگین‌شده باشد (جلوگیری از IDOR)
        if ((int) $transaction->user_id !== (int) auth()->id()) {
            abort(403);
        }

        if ($transaction->status == 1) {
            flash()->info('این تراکنش قبلاً تایید شده است.');

            return redirect()->route('home.profile.orders.index');
        }

        if ($status !== 'OK') {
            $this->markAsFailed($transaction);
            flash()->error('پرداخت توسط کاربر لغو شد یا با خطا مواجه شد.');

            return redirect()->route('home.cart.checkout');
        }

        $result = $this->sendRequest(config('pay.verify_url'), [
            'merchant_id' => config('pay.merchant_id'),
            'amount' => $transaction->amount,
            'authority' => $authority,
        ]);

        if (isset($result['data']['code'])) {
            $code = $result['data']['code'];

            // کد 100 = موفق، کد 101 = قبلاً verify شده
            if ($code == 100 || $code == 101) {
                $refId = $result['data']['ref_id'];
                $cardPan = $result['data']['card_pan'] ?? null;

                $updateResult = $this->updateOrder($transaction, (string) $refId, $cardPan);

                if (array_key_exists('error', $updateResult)) {
                    flash()->error($updateResult['error']);

                    return redirect()->route('home.cart.checkout');
                }

                clearCart(auth()->id());

                flash()->success("پرداخت با موفقیت انجام شد. کد پیگیری: {$refId}");

                return redirect()->route('home.profile.orders.index');
            }
        }

        $this->markAsFailed($transaction);
        $errorMessage = $result['errors']['message'] ?? 'تایید پرداخت با خطا مواجه شد';
        flash()->error($errorMessage);

        return redirect()->route('home.cart.checkout');
    }

    /**
     * نمایش شبیه‌ساز درگاه پرداخت (فقط در حالت دمو)
     */
    public function simulator(Order $order)
    {
        if (config('pay.driver') !== 'simulated') {
            abort(404);
        }

        if ((int) $order->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $transaction = Transaction::where('order_id', $order->id)->firstOrFail();

        if ($transaction->status == 1) {
            flash()->info('این تراکنش قبلاً تایید شده است.');

            return redirect()->route('home.profile.orders.index');
        }

        return view('home.payment.simulator', compact('order', 'transaction'));
    }

    /**
     * تکمیل یا لغو پرداخت در شبیه‌ساز درگاه (فقط در حالت دمو)
     */
    public function simulate(Request $request, Order $order): RedirectResponse
    {
        if (config('pay.driver') !== 'simulated') {
            abort(404);
        }

        if ((int) $order->user_id !== (int) auth()->id()) {
            abort(403);
        }

        $transaction = Transaction::where('order_id', $order->id)->firstOrFail();

        if ($transaction->status == 1) {
            flash()->info('این تراکنش قبلاً تایید شده است.');

            return redirect()->route('home.profile.orders.index');
        }

        // لغو پرداخت توسط کاربر (شبیه‌سازی بازگشت ناموفق از درگاه)
        if ($request->input('action') === 'cancel') {
            $this->markAsFailed($transaction);
            flash()->error('پرداخت توسط کاربر لغو شد یا با خطا مواجه شد. (شبیه‌ساز درگاه)');

            return redirect()->route('home.cart.checkout');
        }

        // شبیه‌سازی پاسخ موفق درگاه (تولید کد پیگیری ساختگی)
        $refId = (string) random_int(100000000, 999999999);

        $updateResult = $this->updateOrder($transaction, $refId);

        if (array_key_exists('error', $updateResult)) {
            flash()->error($updateResult['error']);

            return redirect()->back();
        }

        clearCart(auth()->id());

        flash()->success("پرداخت آزمایشی با موفقیت انجام شد. کد پیگیری: {$refId}");

        return redirect()->route('home.profile.orders.index');
    }

    /**
     * توکن موقت تراکنش تا قبل از دریافت authority از درگاه.
     */
    private function pendingToken(): string
    {
        return 'PENDING-'.Str::upper(Str::random(32));
    }

    /**
     * ارسال درخواست به درگاه پرداخت
     */
    private function sendRequest(string $url, array $data): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            Log::error('Zarinpal CURL Error: '.$error);

            return ['errors' => ['message' => 'خطا در اتصال به درگاه پرداخت']];
        }

        curl_close($curl);

        return json_decode((string) $response, true) ?: [];
    }

    /**
     * ایجاد سفارش و تراکنش.
     *
     * اگر کاربر همین چند دقیقه پیش سفارشی با همین مبلغ ساخته و پرداختش را کامل نکرده باشد،
     * همان سفارش بازاستفاده می‌شود تا با دکمه «بازگشت» یا دوبار کلیک، سفارش تکراری ساخته نشود.
     */
    private function createOrder(int $addressId, array $amounts, ?string $description = null): array
    {
        try {
            return DB::transaction(function () use ($addressId, $amounts, $description) {
                $order = Order::query()
                    ->where('user_id', auth()->id())
                    ->where('payment_status', 0)
                    ->where('status', 0)
                    ->where('paying_amount', $amounts['payingAmount'])
                    ->where('created_at', '>=', now()->subMinutes(30))
                    ->latest()
                    ->lockForUpdate()
                    ->first();

                if ($order) {
                    $order->items()->delete();
                } else {
                    $order = Order::create([
                        'user_id' => auth()->id(),
                        'address_id' => $addressId,
                        'coupon_id' => session()->has('coupon') ? session()->get('coupon.id') : null,
                        'total_amount' => $amounts['totalAmount'],
                        'delivery_amount' => $amounts['deliveryAmount'],
                        'coupon_amount' => $amounts['couponAmount'] ?? 0,
                        'paying_amount' => $amounts['payingAmount'],
                        'payment_type' => 'online',
                        'description' => $description,
                    ]);
                }

                foreach (cartItems() as $item) {
                    $variation = $item->variation;

                    if (! $variation || ! $variation->product_id || $item->quantity < 1) {
                        throw new \Exception('اطلاعات محصول در سبد خرید نامعتبر است');
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $variation->product_id,
                        'product_variation_id' => $variation->id,
                        'price' => $variation->best_price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->quantity * $variation->best_price,
                    ]);
                }

                if (! $order->items()->exists()) {
                    throw new \Exception('سبد خرید شما خالی است');
                }

                if ((int) $order->address_id !== $addressId) {
                    $order->update(['address_id' => $addressId]);
                }

                $transaction = Transaction::firstOrNew(['order_id' => $order->id]);
                $transaction->fill([
                    'user_id' => auth()->id(),
                    'amount' => $amounts['payingAmount'],
                    'gateway_name' => 'zarinpal',
                    'status' => 0,
                ]);

                // توکن موقت تا زمانی که authority واقعی از درگاه برسد.
                if (! $transaction->token) {
                    $transaction->token = $this->pendingToken();
                }

                $transaction->save();

                return ['success' => true, 'order' => $order];
            });
        } catch (Throwable $ex) {
            Log::error('Create Order Error: '.$ex->getMessage());

            return ['error' => 'ثبت سفارش با خطا مواجه شد. لطفا دوباره تلاش کنید.'];
        }
    }

    /**
     * به‌روزرسانی سفارش بعد از پرداخت موفق (کاهش موجودی از روی order_items و به‌صورت idempotent)
     */
    private function updateOrder(Transaction $transaction, string $refId, ?string $cardPan = null): array
    {
        try {
            return DB::transaction(function () use ($transaction, $refId, $cardPan) {
                $transaction = Transaction::query()->lockForUpdate()->findOrFail($transaction->id);

                if ((int) $transaction->status === 1) {
                    return ['success' => true, 'already' => true];
                }

                $order = Order::query()->lockForUpdate()->findOrFail($transaction->order_id);

                if ((int) $order->payment_status === 1) {
                    return ['success' => true, 'already' => true];
                }

                $transaction->update([
                    'status' => 1,
                    'ref_id' => $refId,
                    'card_pan' => $cardPan,
                ]);

                $order->update([
                    'payment_status' => 1,
                    'status' => 1,
                ]);

                // موجودی از روی اقلام سفارش کم می‌شود، نه سبد فعلی کاربر.
                foreach ($order->items()->get() as $orderItem) {
                    ProductVariation::query()
                        ->whereKey($orderItem->product_variation_id)
                        ->where('quantity', '>=', $orderItem->quantity)
                        ->decrement('quantity', $orderItem->quantity);
                }

                return ['success' => true];
            });
        } catch (Throwable $ex) {
            Log::error('Update Order Error: '.$ex->getMessage());

            return ['error' => 'ثبت پرداخت با خطا مواجه شد. در صورت کسر مبلغ با پشتیبانی تماس بگیرید.'];
        }
    }

    /**
     * علامت‌زدن تراکنش و سفارش به‌عنوان ناموفق/لغوشده.
     */
    private function markAsFailed(Transaction $transaction): void
    {
        try {
            DB::transaction(function () use ($transaction) {
                if ((int) $transaction->status === 1) {
                    return;
                }

                $transaction->update(['status' => 2]);

                Order::query()
                    ->whereKey($transaction->order_id)
                    ->where('payment_status', 0)
                    ->update(['status' => -1]);
            });
        } catch (Throwable $ex) {
            Log::error('Mark Failed Transaction Error: '.$ex->getMessage());
        }
    }
}
