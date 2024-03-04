<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GoodGames | 404</title>
    <meta name="description" content="GoodGames - Bootstrap template for communities and games store">
    <meta name="keywords" content="game, gaming, template, HTML template, responsive, Bootstrap, premium">
    <meta name="author" content="_nK">
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('home.sections.links')
</head>

<body>
<div class="nk-main" style="height: 100vh!important">
<div class="nk-fullscreen-block">
    <div class="nk-fullscreen-block-top">
        <div class="text-center">
            <div class="nk-gap-4"></div>
            <a href="index.html"><img src="/assets/images/logo-2.png" alt="GoodGames"></a>
            <div class="nk-gap-2"></div>
        </div>
    </div>
    <div class="nk-fullscreen-block-middle">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">
                    <h1 class="text-main-1" style="font-size: 150px;">404</h1>

                    <div class="nk-gap"></div>
                    <h2 class="h4">!آدرسو اشتباه اومدی رفیق</h2>

                    <div>یا چنین صفحه ای وجود نداره <br> میخوای برگردی صفحه اصلی؟</div>
                    <div class="nk-gap-3"></div>

                    <a href="{{ route('home.index') }}" class="nk-btn nk-btn-rounded nk-btn-color-white">بازگشت به صفحه اصلی</a>
                </div>
            </div>
            <div class="nk-gap-3"></div>
        </div>
    </div>
    <div class="nk-fullscreen-block-bottom">
        <div class="nk-gap-2"></div>
        <ul class="nk-social-links-2 nk-social-links-center">
            @include('home.sections.contact-accounts')
        </ul>
        <div class="nk-gap-2"></div>
    </div>
</div>

    </div>

        <!-- START: Page Background -->

    <div class="nk-page-background-fixed" style="background-image: url('/assets/images/bg-fixed-2.jpg');"></div>

<!-- END: Page Background -->

@include('home.sections.scripts')
</body>
</html>
