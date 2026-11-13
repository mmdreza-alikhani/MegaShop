<footer class="mk-footer">
    <div class="container">
        <div class="mk-footer-grid">
            <div class="mk-footer-col mk-footer-about">
                <a href="{{ route('home.index') }}" class="mk-footer-logo">
                    <img src="{{ asset(ltrim(config('brand.logo'), '/')) }}" alt="{{ config('brand.name') }}">
                </a>
                <p>
                    {{ config('brand.name') }} — {{ config('brand.slogan') }}. خرید گوشی، لپ‌تاپ و لوازم جانبی
                    با ضمانت اصالت کالا و ارسال سریع.
                </p>
                <ul class="mk-social">
                    @if(config('brand.social.instagram'))
                        <li><a href="{{ config('brand.social.instagram') }}" aria-label="اینستاگرام" target="_blank" rel="noopener"><span class="fa fa-instagram"></span></a></li>
                    @endif
                    @if(config('brand.social.telegram'))
                        <li><a href="{{ config('brand.social.telegram') }}" aria-label="تلگرام" target="_blank" rel="noopener"><span class="fa fa-paper-plane"></span></a></li>
                    @endif
                    <li><a href="tel:{{ config('brand.phone') }}" aria-label="تماس تلفنی"><span class="fa fa-phone"></span></a></li>
                    <li><a href="mailto:{{ config('brand.email') }}" aria-label="ایمیل"><span class="fa fa-envelope-o"></span></a></li>
                </ul>
            </div>

            <div class="mk-footer-col">
                <h4>دسترسی سریع</h4>
                <ul>
                    <li><a href="{{ route('home.products.index') }}">همه محصولات</a></li>
                    <li><a href="{{ route('home.posts.index') }}">مقالات</a></li>
                    <li><a href="{{ route('home.aboutus') }}">درباره ما</a></li>
                    <li><a href="{{ route('home.contact') }}">تماس با ما</a></li>
                </ul>
            </div>

            <div class="mk-footer-col">
                <h4>حساب کاربری</h4>
                <ul>
                    @auth
                        <li><a href="{{ route('home.profile.info') }}">پروفایل من</a></li>
                        <li><a href="{{ route('home.profile.orders.index') }}">سفارش‌های من</a></li>
                        <li><a href="{{ route('home.profile.wishlist') }}">علاقه‌مندی‌ها</a></li>
                    @else
                        <li><a href="#" data-toggle="modal" data-target="#modalLogin">ورود</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#modalLogin">ثبت‌نام</a></li>
                    @endauth
                </ul>
            </div>

            <div class="mk-footer-col mk-footer-contact">
                <h4>ارتباط با ما</h4>
                <p>
                    <span class="fa fa-phone"></span>
                    {{ config('brand.phone') }}
                </p>
                <p>
                    <span class="fa fa-envelope-o"></span>
                    {{ config('brand.email') }}
                </p>
                <p>
                    <span class="fa fa-map-marker"></span>
                    {{ config('brand.address') }}
                </p>
            </div>
        </div>

        <div class="mk-footer-bottom">
            <span>تمامی حقوق این سایت محفوظ است © {{ verta()->format('%Y') }} {{ config('brand.name') }}</span>
            <span class="mk-footer-sep">|</span>
            <span>طراحی و توسعه با Laravel</span>
        </div>
    </div>
</footer>
