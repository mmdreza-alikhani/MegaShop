<style>
    .custom-dropdown {
        width: 400px; /* اندازه پیش‌فرض برای دسکتاپ */
        font-size: 16px; /* اندازه متن برای دسکتاپ */
    }

    @media (max-width: 768px) { /* برای حالت گوشی */
        .custom-dropdown {
            width: 50%; /* عرض کوچکتر برای موبایل */
            font-size: 14px; /* اندازه متن برای موبایل */
            margin: 0 auto; /* محتوای نوتیفیکیشن را وسط‌چین می‌کند */
        }
    }

</style>
<header class="bmd-layout-header ">
    <div class="navbar navbar-light bg-faded animate__animated animate__fadeInDown">
        <button class="navbar-toggler animate__animated animate__wobble animate__delay-2s" type="button"
                data-toggle="drawer" data-target="#dw-s1">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="nav navbar-nav p-0">
            <li class="nav-item">
                <div class="dropdown">
                    <button class="btn  dropdown-toggle m-0" type="button" id="dropdownMenu3" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-bell fa-lg"></i>
{{--                        @if($awaitingContracts + $pendingContracts > 0)--}}
{{--                            <span--}}
{{--                                class="badge badge-pill badge-warning animate__animated animate__flash animate__repeat-3 animate__slower animate__delay-2s">--}}
{{--                                {{ $awaitingContracts + $pendingContracts }}--}}
{{--                            </span>--}}
{{--                        @endif--}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right custom-dropdown">
{{--                        @if($pendingContracts + $awaitingContracts > 0)--}}
{{--                            <span class="dropdown-item dropdown-header persianumber">در انتظار شما</span>--}}
{{--                            <div class="dropdown-divider"></div>--}}
{{--                            @if($pendingContracts)--}}
{{--                                <a class="dropdown-item" href="{{ route('panel.contracts.index') }}">--}}
{{--                                    <i class="far fa-envelope c-main mr-2"></i> {{ $pendingContracts }} قرارداد منتظر تایید شما--}}
{{--                                </a>--}}
{{--                                <div class="dropdown-divider"></div>--}}
{{--                            @endif--}}
{{--                            @if($user->hasPermissionTo('contracts') && $awaitingContracts > 0)--}}
{{--                                <a class="dropdown-item" href="{{ route('admin.contracts.awaiting') }}">--}}
{{--                                    <i class="far fa-envelope c-main mr-2"></i> {{ $awaitingContracts }} قرارداد در انتظار انتشار--}}
{{--                                </a>--}}
{{--                                <div class="dropdown-divider"></div>--}}
{{--                            @endif--}}
{{--                        @else--}}
{{--                            <p class="text-center">هیچ قراردادی برای شما وجود ندارد.</p>--}}
{{--                        @endif--}}
                    </div>
                </div>
            </li>
            <li class="nav-item"><img src="{{ asset('assets/img/user.png') }}" alt="..."
                                      class="rounded-circle screen-user-profile"></li>
            <li class="nav-item">
                <div class="dropdown">
                    <button class="btn  dropdown-toggle m-0 d-none d-md-block" type="button" id="dropdownMenu4"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->username . ', خوش اومدی!' }}
                    </button>
                    <div class="dropdown-menu  pl-3 dropdown-menu-right dropdown-menu dropdown-menu-right">
                        <button onclick="dark()" class="dropdown-item" type="button">
                            <i class="fas fa-moon fa-sm c-main mr-2"></i>
                            حالت شب
                        </button>
{{--                        <form method="post" action="{{ route('toggleActive') }}">--}}
{{--                            @csrf--}}
{{--                            <button class="dropdown-item" type="submit">--}}
{{--                                <i class="fas fa-sm c-main mr-2 {{ auth()->user()->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>--}}
{{--                                {{ auth()->user()->is_active ? 'فعال' : 'غیرفعال' }}--}}
{{--                            </button>--}}
{{--                        </form>--}}
{{--                        <form method="post" action="{{ route('logout') }}">--}}
{{--                            @csrf--}}
{{--                            <button class="dropdown-item" type="submit">--}}
{{--                                <i class="fas fa-sign-out-alt c-main fa-sm mr-2"></i>--}}
{{--                                خروج--}}
{{--                            </button>--}}
{{--                        </form>--}}
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
{{--<script>--}}
{{--    function toggleButtonText() { --}}
{{--      const button = document.getElementById('toggleButton'); --}}
{{--      var icon = document.getElementById('toggleIcon');--}}
{{--      if (button.textContent.includes('غیرفعال')) { --}}
{{--        button.innerHTML = '<i id="toggleIcon" class="fas fa-check-circle fa-sm c-main mr-2"></i> فعال';--}}
{{--      } else { --}}
{{--        const icon = document.getElementById('toggleIcon');--}}
{{--        button.innerHTML = '<i id="toggleIcon" class="fas fa-times-circle fa-sm c-main mr-2"></i> غیرفعال';--}}
{{--       }--}}
{{--    }--}}
{{--</script>--}}
{{-- fe --}}
