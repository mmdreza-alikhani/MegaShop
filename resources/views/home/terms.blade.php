@extends('home.layout.master')

@section('title')
    قوانین و مقررات
@endsection

@section('meta_description')
    قوانین و شرایط استفاده از فروشگاه مگاشاپ — نسخه آزمایشی
@endsection

@section('content')
    <div class="nk-gap-2"></div>

    <div class="container mk-terms-page">
        <nav class="mk-breadcrumb" aria-label="مسیر صفحه">
            <a href="{{ route('home.index') }}">خانه</a>
            <span class="fa fa-angle-left"></span>
            <span>قوانین و مقررات</span>
        </nav>

        <header class="mk-terms-head">
            <span class="mk-terms-badge">نسخه آزمایشی</span>
            <h1>قوانین و مقررات فروشگاه</h1>
            <p>
                این متن برای تست جریان ثبت‌نام نوشته شده و جنبه حقوقی ندارد. با این حال، برای اینکه
                تجربه صفحه واقعی باشد، ساختار یک صفحه قوانین کامل در آن رعایت شده است.
            </p>
            <div class="mk-terms-meta">
                <span class="fa fa-calendar"></span>
                آخرین به‌روزرسانی: {{ verta(now())->format('%d %B %Y') }}
            </div>
        </header>

        <div class="mk-terms-grid">
            <article class="mk-terms-card">
                <span class="mk-terms-num">۱</span>
                <h2>پذیرش قوانین</h2>
                <p>
                    با ساخت حساب کاربری در مگاشاپ تأیید می‌کنید که این قوانین را خوانده‌اید و می‌پذیرید.
                    اگر با هر بخشی از آن موافق نیستید، لطفاً ثبت‌نام نکنید.
                </p>
                <p>
                    ادامه استفاده از فروشگاه به معنی پذیرش نسخه به‌روزشده قوانین است.
                </p>
            </article>

            <article class="mk-terms-card">
                <span class="mk-terms-num">۲</span>
                <h2>حساب کاربری</h2>
                <ul class="mk-terms-list">
                    <li>اطلاعات ثبت‌شده باید صحیح و به‌روز باشد.</li>
                    <li>مسئولیت حفظ رمز عبور با صاحب حساب است.</li>
                    <li>ساخت حساب برای افراد زیر ۱۸ سال نیاز به تأیید سرپرست دارد.</li>
                    <li>استفاده از حساب برای فعالیت غیرقانونی مجاز نیست.</li>
                </ul>
            </article>

            <article class="mk-terms-card">
                <span class="mk-terms-num">۳</span>
                <h2>سفارش و پرداخت</h2>
                <p>
                    قیمت‌ها در لحظه ثبت سفارش محاسبه می‌شوند. در صورت تغییر قیمت پیش از پرداخت،
                    سفارش با قیمت جدید به‌روزرسانی می‌شود و امکان لغو وجود دارد.
                </p>
                <p>
                    پرداخت‌ها از طریق درگاه‌های بانکی انجام می‌شود و فروشگاه اطلاعات کارت را ذخیره نمی‌کند.
                </p>
            </article>

            <article class="mk-terms-card">
                <span class="mk-terms-num">۴</span>
                <h2>ارسال و تحویل</h2>
                <ul class="mk-terms-list">
                    <li>زمان ارسال بسته به شهر مقصد بین ۱ تا ۵ روز کاری است.</li>
                    <li>هزینه ارسال پیش از پرداخت به‌صورت شفاف نمایش داده می‌شود.</li>
                    <li>در صورت آسیب ظاهری بسته، تحویل را ثبت نکنید و با پشتیبانی تماس بگیرید.</li>
                </ul>
            </article>

            <article class="mk-terms-card">
                <span class="mk-terms-num">۵</span>
                <h2>بازگشت کالا</h2>
                <p>
                    کالاهای سالم تا ۷ روز پس از تحویل، در صورت نداشتن پلمب بازشده، قابل بازگشت هستند.
                    هزینه بازگشت در صورت ایراد کالا با فروشگاه است.
                </p>
            </article>

            <article class="mk-terms-card">
                <span class="mk-terms-num">۶</span>
                <h2>حریم خصوصی</h2>
                <p>
                    اطلاعات شما فقط برای پردازش سفارش، پشتیبانی و بهبود تجربه خرید استفاده می‌شود و
                    در اختیار شخص ثالث قرار نمی‌گیرد، مگر در چارچوب قانون.
                </p>
            </article>

            <article class="mk-terms-card">
                <span class="mk-terms-num">۷</span>
                <h2>تغییر قوانین</h2>
                <p>
                    فروشگاه می‌تواند این قوانین را به‌روزرسانی کند. نسخه معتبر همیشه همین صفحه است و
                    تاریخ به‌روزرسانی در بالای آن نمایش داده می‌شود.
                </p>
            </article>
        </div>

        <div class="mk-terms-note">
            <span class="fa fa-info-circle"></span>
            <div>
                <strong>توجه:</strong>
                این صفحه فقط برای تست است و متن آن نمونه است. برای استفاده واقعی، متن حقوقی نهایی
                باید توسط مشاور حقوقی تهیه شود.
            </div>
        </div>

        <div class="mk-terms-actions">
            <button type="button" class="mk-btn mk-btn-primary" data-toggle="modal" data-target="#modalLogin">
                بازگشت به ثبت‌نام
            </button>
            <a href="{{ route('home.contact') }}" class="mk-btn mk-btn-outline">تماس با پشتیبانی</a>
        </div>
    </div>

    <div class="nk-gap-2"></div>
@endsection
