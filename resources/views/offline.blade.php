@extends('home.layout.master')

@section('title')
    آفلاین
@endsection

@section('content')
    <div class="nk-fullscreen-block">
        <div class="nk-fullscreen-block-top">
            <div class="text-center">
                <div class="nk-gap-4"></div>
                <a href="index.html"><img src="assets/images/logo-2.png" alt="GoodGames"></a>
                <div class="nk-gap-2"></div>
            </div>
        </div>
        <div class="nk-fullscreen-block-middle">
            <div class="container text-center">
                <div class="row">
                    <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4">
                        <h1 class="h2"><span class="text-main-1">Site is</span> Offline</h1>

                        <div class="text-white">You gonna have to wait a while. Subscribe now and we'll let you know when we're ready to continue.</div>
                        <div class="nk-gap-2"></div>

                        <!-- START: MailChimp Signup Form -->
                        <form action="//nkdev.us11.list-manage.com/subscribe/post?u=d433160c0c43dcf8ecd52402f&amp;id=7eafafe8f0" method="post" class="nk-mchimp validate" target="_blank">
                            <div class="input-group">
                                <input type="email" value="" name="EMAIL" class="required email form-control" placeholder="Enter your Email">
                                <button class="nk-btn nk-btn-rounded nk-btn-color-white ml-20">Subscribe</button>
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
                <li><a class="nk-social-rss" href="#"><span class="fa fa-rss"></span></a></li>
                <li><a class="nk-social-twitch" href="#"><span class="fab fa-twitch"></span></a></li>
                <li><a class="nk-social-steam" href="#"><span class="fab fa-steam"></span></a></li>
                <li><a class="nk-social-facebook" href="#"><span class="fab fa-facebook"></span></a></li>
                <li><a class="nk-social-google-plus" href="#"><span class="fab fa-google-plus"></span></a></li>
                <li><a class="nk-social-twitter" href="#" target="_blank"><span class="fab fa-twitter"></span></a></li>
                <li><a class="nk-social-pinterest" href="#"><span class="fab fa-pinterest-p"></span></a></li>

                <!-- Additional Social Buttons
                    <li><a class="nk-social-behance" href="#"><span class="fab fa-behance"></span></a></li>
                    <li><a class="nk-social-bitbucket" href="#"><span class="fab fa-bitbucket"></span></a></li>
                    <li><a class="nk-social-dropbox" href="#"><span class="fab fa-dropbox"></span></a></li>
                    <li><a class="nk-social-dribbble" href="#"><span class="fab fa-dribbble"></span></a></li>
                    <li><a class="nk-social-deviantart" href="#"><span class="fab fa-deviantart"></span></a></li>
                    <li><a class="nk-social-flickr" href="#"><span class="fab fa-flickr"></span></a></li>
                    <li><a class="nk-social-foursquare" href="#"><span class="fab fa-foursquare"></span></a></li>
                    <li><a class="nk-social-github" href="#"><span class="fab fa-github"></span></a></li>
                    <li><a class="nk-social-instagram" href="#"><span class="fab fa-instagram"></span></a></li>
                    <li><a class="nk-social-linkedin" href="#"><span class="fab fa-linkedin"></span></a></li>
                    <li><a class="nk-social-medium" href="#"><span class="fab fa-medium"></span></a></li>
                    <li><a class="nk-social-odnoklassniki" href="#"><span class="fab fa-odnoklassniki"></span></a></li>
                    <li><a class="nk-social-paypal" href="#"><span class="fab fa-paypal"></span></a></li>
                    <li><a class="nk-social-reddit" href="#"><span class="fab fa-reddit"></span></a></li>
                    <li><a class="nk-social-skype" href="#"><span class="fab fa-skype"></span></a></li>
                    <li><a class="nk-social-soundcloud" href="#"><span class="fab fa-soundcloud"></span></a></li>
                    <li><a class="nk-social-slack" href="#"><span class="fab fa-slack"></span></a></li>
                    <li><a class="nk-social-tumblr" href="#"><span class="fab fa-tumblr"></span></a></li>
                    <li><a class="nk-social-vimeo" href="#"><span class="fab fa-vimeo"></span></a></li>
                    <li><a class="nk-social-vk" href="#"><span class="fab fa-vk"></span></a></li>
                    <li><a class="nk-social-wordpress" href="#"><span class="fab fa-wordpress"></span></a></li>
                    <li><a class="nk-social-youtube" href="#"><span class="fab fa-youtube"></span></a></li>
                -->
            </ul>
            <div class="nk-gap-2"></div>
        </div>
    </div>
@endsection
