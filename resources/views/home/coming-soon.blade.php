<!DOCTYPE html><html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mega Shop | ...به زودی</title>
    <meta name="description" content="GoodGames - Bootstrap template for communities and games store">
    <meta name="keywords" content="game, gaming, template, HTML template, responsive, Bootstrap, premium">
    <meta name="author" content="_nK">
    <link rel="icon" type="image/png" href="assets/images/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('home.sections.links')
</head>
<body>
<div class="nk-main">
<div class="nk-fullscreen-block">
    <div class="nk-fullscreen-block-top">
        <div class="text-center">
            <div class="nk-gap-4"></div>
            <a href="{{ route('home.index') }}"><img src="{{ env('vertical_logo_path') }}" alt="GoodGames"></a>
            <div class="nk-gap-2"></div>
        </div>
    </div>
    <div class="nk-fullscreen-block-middle">
        <div class="container text-center">
            <h1 class="h3" style="direction: rtl">در دست ساخت...</h1>

            <div class="nk-gap-2"></div>
            <div class="nk-countdown nk-countdown-center" data-end="1403-11-26 00:23" data-timezone="GMT+3:30"></div>

            <div class="nk-gap-2"></div>
            <strong class="text-white">میخوای زودتر از همه خبردار بشی؟</strong>
            <div class="nk-gap-2"></div>

            <div class="row">
                <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">
                    <!-- START: MailChimp Signup Form -->
                    <form action="#" method="post" class="nk-mchimp validate" target="_blank">
                        <div class="input-group">
                            <button class="nk-btn nk-btn-rounded nk-btn-color-white ml-20 mr-5">!منو مطلع کن</button>
                            <input type="email" value="" name="EMAIL" class="required email form-control" placeholder="...ایمیلت رو اینجا وارد کن">
                        </div>
                        <div class="nk-form-response-success"></div>
                        <div class="nk-form-response-error"></div>
                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_d433160c0c43dcf8ecd52402f_7eafafe8f0" tabindex="-1" value=""></div>
                    </form>
                    <!-- END: MailChimp Signup Form -->
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

    <div class="nk-page-background-fixed" style="background-image: url('assets/images/bg-fixed-1.jpg');"></div>

    <!-- END: Page Background -->

@include('home.sections.scripts')</body>
</html>
