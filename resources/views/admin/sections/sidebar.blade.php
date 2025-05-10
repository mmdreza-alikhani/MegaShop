<div id="dw-s1" class="bmd-layout-drawer bg-faded ">
    <div class="container-fluid side-bar-container ">
        <header class="pb-0">
            <a class="navbar-brand ">
                <object class="side-logo" style="margin: -23px 0" data="{{ asset('assets/svg/logo-white.png') }}">
                </object>
            </a>
        </header>
        <li class="side a-collapse short m-2 pr-1 pl-1">
            <a href="{{ route('admin.panel') }}" class="side-item {{ request()->routeIs('management') ? 'selected' : '' }}">
                <i class="fas fa-tachometer-alt mr-1"></i>صفحه اصلی
            </a>
        </li>
{{--        @can('admin')--}}
            <p class="side-comment fnt-mxs">پنل مدیریت</p>
{{--            @can('users')--}}
            <ul class="side a-collapse {{ request()->is('management/users') || request()->is('management/roles') || request()->is('management/permissions') ? '' : 'short' }}">
                <a class="ul-text fnt-mxs"><i class="fas fa-user-friends mr-1"></i> کاربران
                    <i class="fas fa-chevron-up arrow"></i>
                </a>
                <div class="side-item-container {{ request()->is('management/users') || request()->is('management/roles') || request()->is('management/permissions') ? '' : 'hide animated' }}">
                    <li class="side-item {{ request()->is('management/users') ? 'selected' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>لیست کاربران
                        </a>
                    </li>
                    <li class="side-item {{ request()->is('management/roles') ? 'selected' : '' }}">
                        <a href="{{ route('admin.roles.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>گروه های  کاربری
                        </a>
                    </li>
                    <li class="side-item {{ request()->is('management/permissions') ? 'selected' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>دسترسی ها
                        </a>
                    </li>
                </div>
            </ul>
{{--            @endcan--}}
{{--            @can('Attributes')--}}
{{--            <ul class="side a-collapse {{ $active_parent == 'attributes' ? '' : 'short' }}">--}}
{{--                <a class="ul-text fnt-mxs"><i class="fas fa-list-alt mr-1"></i> ویژگی ها--}}
{{--                    <i class="fas fa-chevron-up arrow"></i>--}}
{{--                </a>--}}
{{--                <div class="side-item-container {{ $active_parent == 'attributes' ? '' : 'hide animated' }}">--}}
{{--                    <li class="side-item {{ $active_child == 'showAttributes' ? 'selected' : '' }}">--}}
{{--                        <a href="{{ route('admin.attributes.index') }}" class="fnt-mxs">--}}
{{--                            <i class="fas fa-angle-right mr-2"></i>لیست ویژگی ها--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </div>--}}
{{--            </ul>--}}
{{--            @endcan--}}
{{--            @can('articles')--}}
{{--            <ul class="side a-collapse {{ $active_parent == 'articles' ? '' : 'short' }}">--}}
{{--                <a class="ul-text fnt-mxs"><i class="fas fa-grip-lines mr-1"></i> ماده ها--}}
{{--                    <i class="fas fa-chevron-up arrow"></i>--}}
{{--                </a>--}}
{{--                <div class="side-item-container {{ $active_parent == 'articles' ? '' : 'hide animated' }}">--}}
{{--                    <li class="side-item {{ $active_child == 'showArticles' ? 'selected' : '' }}">--}}
{{--                        <a href="{{ route('admin.articles.index') }}" class="fnt-mxs">--}}
{{--                            <i class="fas fa-angle-right mr-2"></i>لیست ماده ها--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </div>--}}
{{--            </ul>--}}
{{--            @endcan--}}
{{--            @can('contracts')--}}
{{--            <ul class="side a-collapse {{ $active_parent == 'contracts' ? '' : 'short' }}">--}}
{{--                <a class="ul-text fnt-mxs">--}}
{{--                    <i class="fas fa-file-contract mr-1"></i>--}}
{{--                    قرارداد ها--}}
{{--                    <i class="fas fas fa-chevron-up arrow"></i>--}}
{{--                </a>--}}
{{--                <div class="side-item-container {{ $active_parent == 'contracts' ? '' : 'hide animated' }}">--}}
{{--                    <li class="side-item {{ $active_child == 'types' ? 'selected' : '' }}">--}}
{{--                        <a href="{{ route('admin.types.index') }}" class="fnt-mxs">--}}
{{--                            <i class="fas fa-angle-right mr-2"></i>--}}
{{--                            انواع قرارداد--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="side-item {{ $active_child == 'allContracts' ? 'selected' : '' }}">--}}
{{--                        <a href="{{ route('admin.contracts.index') }}" class="fnt-mxs">--}}
{{--                            <i class="fas fa-angle-right mr-2"></i>--}}
{{--                            لیست قرارداد ها--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="side-item {{ $active_child == 'awaitingContracts' ? 'selected' : '' }}">--}}
{{--                        <a href="{{ route('admin.contracts.awaiting') }}" class="fnt-mxs">--}}
{{--                            <i class="fas fa-angle-right mr-2"></i>--}}
{{--                            در انتظار انتشار--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="side-item {{ $active_child == 'revokedContracts' ? 'selected' : '' }}">--}}
{{--                        <a href="{{ route('admin.contracts.revoked') }}" class="fnt-mxs">--}}
{{--                            <i class="fas fa-angle-right mr-2"></i>--}}
{{--                            قرارداد های حذف شده--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                </div>--}}
{{--            </ul>--}}
{{--            @endcan--}}
{{--        @endcan--}}

{{--        @can('user')--}}
{{--        <p class="side-comment fnt-mxs">کارتابل</p>--}}
{{--        <ul class="side a-collapse {{ $active_parent == 'panelContracts' ? '' : 'short' }}">--}}
{{--            <a class="ul-text fnt-mxs">--}}
{{--                <i class="fas fa-file-contract mr-1"></i> قرارداد ها--}}
{{--                <i class="fas fas fa-chevron-up arrow"></i>--}}
{{--            </a>--}}
{{--            <div class="side-item-container {{ $active_parent == 'panelContracts' ? '' : 'hide animated' }}">--}}
{{--                <li class="side-item {{ $active_child == 'panelCreateContract' ? 'selected' : '' }}">--}}
{{--                    <a href="{{ route('panel.contracts.create') }}" class="fnt-mxs">--}}
{{--                        <i class="fas fa-angle-right mr-2"></i>--}}
{{--                        ایجاد قرارداد جدید--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="side-item {{ $active_child == 'panelIndexContract' ? 'selected' : '' }}">--}}
{{--                    <a href="{{ route('panel.contracts.index') }}" class="fnt-mxs">--}}
{{--                        <i class="fas fa-angle-right mr-2"></i>--}}
{{--                        قرارداد های مورد بررسی--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="side-item {{ $active_child == 'panelRelevantContracts' ? 'selected' : '' }}">--}}
{{--                    <a href="{{ route('panel.contracts.relevant_contracts') }}" class="fnt-mxs">--}}
{{--                        <i class="fas fa-angle-right mr-2"></i>--}}
{{--                        قرارداد های مربوطه--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </div>--}}
{{--        </ul>--}}
{{--        @endcan--}}
    </div>
</div>
