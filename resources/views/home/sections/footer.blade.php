@php
    $domain = request()->getHost(); // "example.com"
    $parts = explode('.', $domain);

    $name = $parts[0];       // "example"
//    $tld  = $parts[1] ? '.' . $parts[1] : ''; // ".com"
@endphp
<footer class="nk-footer">
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <h2>
                    <a href="{{ route('home.index') }}">
                        {{ $name }}
                        <span class="text-light">
{{--                            {{ $tld }}--}}
                        </span>
                    </a>
                </h2>
                <div>
                    <ul class="nk-social-links justify-content-center">
                        @include('home.sections.contact-accounts')
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12 text-center">
                !تمامی حقوق این سایت محفوظ است
                <p>
                    Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
                </p>
            </div>
        </div>
    </div>
</footer>
