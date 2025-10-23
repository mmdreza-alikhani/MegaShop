@php use App\Models\Comment; @endphp
<div id="dw-s1" class="bmd-layout-drawer bg-faded">
    <div class="container-fluid side-bar-container">
        <header class="pb-0">
            <a class="navbar-brand">
                <object class="side-logo" data="{{ asset('/assets/images/logo.png') }}">
                </object>
            </a>
        </header>
        <li class="side a-collapse short m-2 pr-1 pl-1">
            <a href="{{ route('admin.panel') }}"
               class="side-item {{ request()->routeIs('management') ? 'selected' : '' }}">
                <i class="fas fa-tachometer-alt mr-1"></i>صفحه اصلی
            </a>
        </li>
        <p class="side-comment fnt-mxs">کاربران</p>
        @can('users-index')
            <ul class="side a-collapse {{ request()->is('management/users*') || request()->is('management/roles*') || request()->is('management/permissions*') ? '' : 'short' }}">
                <a class="ul-text fnt-mxs"><i class="fas fa-user-friends mr-1"></i> کاربران
                    <i class="fas fa-chevron-up arrow"></i>
                </a>
                <div
                    class="side-item-container {{ request()->is('management/users*') || request()->is('management/roles*') || request()->is('management/permissions*') ? '' : 'hide animated' }}">
                    <li class="side-item {{ request()->is('management/users') ? 'selected' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>لیست کاربران
                        </a>
                    </li>
                    <li class="side-item {{ request()->is('management/roles') ? 'selected' : '' }}">
                        <a href="{{ route('admin.roles.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>گروه های کاربری
                        </a>
                    </li>
                    <li class="side-item {{ request()->is('management/permissions') ? 'selected' : '' }}">
                        <a href="{{ route('admin.permissions.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>دسترسی ها
                        </a>
                    </li>
                </div>
            </ul>
        @endcan
        <p class="side-comment fnt-mxs">محصولات</p>
        @can('attributes-index')
            <ul class="side a-collapse {{ request()->is('management/attributes*') ? '' : 'short' }}">
            <a class="ul-text fnt-mxs"><i class="fas fa-star mr-1"></i> ویژگی ها
                <i class="fas fa-chevron-up arrow"></i>
            </a>
            <div class="side-item-container {{ request()->is('management/attributes*') ? '' : 'hide animated' }}">
                <li class="side-item {{ request()->is('management/attributes') ? 'selected' : '' }}">
                    <a href="{{ route('admin.attributes.index') }}" class="fnt-mxs">
                        <i class="fas fa-angle-right mr-2"></i>لیست ویژگی ها
                    </a>
                </li>
            </div>
        </ul>
        @endcan
        @can('categories-index')
            <ul class="side a-collapse {{ request()->is('management/categories*') ? '' : 'short' }}">
            <a class="ul-text fnt-mxs"><i class="fas fa-box-open mr-1"></i> دسته بندی ها
                <i class="fas fa-chevron-up arrow"></i>
            </a>
            <div class="side-item-container {{ request()->is('management/categories*') ? '' : 'hide animated' }}">
                <li class="side-item {{ request()->is('management/categories') ? 'selected' : '' }}">
                    <a href="{{ route('admin.categories.index') }}" class="fnt-mxs">
                        <i class="fas fa-angle-right mr-2"></i>لیست دسته بندی ها
                    </a>
                </li>
                <li class="side-item {{ request()->is('management/categories/create') ? 'selected' : '' }}">
                    <a href="{{ route('admin.categories.create') }}" class="fnt-mxs">
                        <i class="fas fa-angle-right mr-2"></i>ایجاد دسته بندی جدید
                    </a>
                </li>
            </div>
        </ul>
        @endcan
        @can('brands-index')
            <ul class="side a-collapse {{ request()->is('management/brands*') ? '' : 'short' }}">
            <a class="ul-text fnt-mxs"><i class="fas fa-tags mr-1"></i> برند ها
                <i class="fas fa-chevron-up arrow"></i>
            </a>
            <div class="side-item-container {{ request()->is('management/brands*') ? '' : 'hide animated' }}">
                <li class="side-item {{ request()->is('management/brands') ? 'selected' : '' }}">
                    <a href="{{ route('admin.brands.index') }}" class="fnt-mxs">
                        <i class="fas fa-angle-right mr-2"></i>لیست برند ها
                    </a>
                </li>
            </div>
        </ul>
        @endcan
        @can('platforms-index')
            <ul class="side a-collapse {{ request()->is('management/platforms*') ? '' : 'short' }}">
                <a class="ul-text fnt-mxs"><i class="fas fa-gamepad mr-1"></i> پلتفرم ها
                    <i class="fas fa-chevron-up arrow"></i>
                </a>
                <div class="side-item-container {{ request()->is('management/platforms*') ? '' : 'hide animated' }}">
                    <li class="side-item {{ request()->is('management/platforms') ? 'selected' : '' }}">
                        <a href="{{ route('admin.platforms.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>لیست پلتفرم ها
                        </a>
                    </li>
                </div>
            </ul>
        @endcan
        @can('products-index')
            <ul class="side a-collapse {{ request()->is('management/products*') ? '' : 'short' }}">
                <a class="ul-text fnt-mxs"><i class="fa fa-box mr-1"></i> محصولات
                    <i class="fas fa-chevron-up arrow"></i>
                </a>
                <div class="side-item-container {{ request()->is('management/products*') ? '' : 'hide animated' }}">
                    <li class="side-item {{ request()->is('management/products') ? 'selected' : '' }}">
                        <a href="{{ route('admin.products.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>لیست محصولات
                        </a>
                    </li>
                    <li class="side-item {{ request()->is('management/products/create') ? 'selected' : '' }}">
                        <a href="{{ route('admin.products.create') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>ایجاد محصول جدید
                        </a>
                    </li>
                </div>
            </ul>
        @endcan
        <p class="side-comment fnt-mxs">سفارشات</p>
        @can('coupons-index')
            <ul class="side a-collapse {{ request()->is('management/coupons*') ? '' : 'short' }}">
            <a class="ul-text fnt-mxs"><i class="fa fa-ticket-alt mr-1"></i> کد های تخفیف
                <i class="fas fa-chevron-up arrow"></i>
            </a>
            <div class="side-item-container {{ request()->is('management/coupons*') ? '' : 'hide animated' }}">
                <li class="side-item {{ request()->is('management/coupons') ? 'selected' : '' }}">
                    <a href="{{ route('admin.coupons.index') }}" class="fnt-mxs">
                        <i class="fas fa-angle-right mr-2"></i>لیست کد های تخفیف
                    </a>
                </li>
            </div>
        </ul>
        @endcan
        @can('orders-index')
            <ul class="side a-collapse {{ request()->is('management/orders*') ? '' : 'short' }}">
                <a class="ul-text fnt-mxs"><i class="fa fa-truck-loading mr-1"></i> سفارشات
                    <i class="fas fa-chevron-up arrow"></i>
                </a>
                <div class="side-item-container {{ request()->is('management/orders*') ? '' : 'hide animated' }}">
                    <li class="side-item {{ request()->is('management/orders') ? 'selected' : '' }}">
                        <a href="{{ route('admin.orders.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>لیست سفارشات
                        </a>
                    </li>
                </div>
            </ul>
        @endcan
        <p class="side-comment fnt-mxs">محتوا</p>
        @can('posts-index')
            <ul class="side a-collapse {{ request()->is('management/posts*') ? '' : 'short' }}">
                <a class="ul-text fnt-mxs"><i class="fa fa-newspaper mr-1"></i>پست ها
                    <i class="fas fa-chevron-up arrow"></i>
                </a>
                <div class="side-item-container {{ request()->is('management/posts*') ? '' : 'hide animated' }}">
                    <li class="side-item {{ request()->is('management/posts') ? 'selected' : '' }}">
                        <a href="{{ route('admin.posts.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>لیست پست ها
                        </a>
                    </li>
                    <li class="side-item {{ request()->is('management/posts/create') ? 'selected' : '' }}">
                        <a href="{{ route('admin.posts.create') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>ایجاد پست جدید
                        </a>
                    </li>
                </div>
            </ul>
        @endcan
        <p class="side-comment fnt-mxs">عمومی</p>
        @can('tags-index')
            <ul class="side a-collapse {{ request()->is('management/tags*') ? '' : 'short' }}">
                <a class="ul-text fnt-mxs"><i class="fa fa-ticket-alt mr-1"></i> برچسب ها
                    <i class="fas fa-chevron-up arrow"></i>
                </a>
                <div class="side-item-container {{ request()->is('management/tags*') ? '' : 'hide animated' }}">
                    <li class="side-item {{ request()->is('management/tags') ? 'selected' : '' }}">
                        <a href="{{ route('admin.tags.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>لیست برچسب ها
                        </a>
                    </li>
                </div>
            </ul>
        @endcan
        @can('banners-index')
            <ul class="side a-collapse {{ request()->is('management/banners*') ? '' : 'short' }}">
                <a class="ul-text fnt-mxs"><i class="fa fa-box mr-1"></i> بنر ها
                    <i class="fas fa-chevron-up arrow"></i>
                </a>
                <div class="side-item-container {{ request()->is('management/banners*') ? '' : 'hide animated' }}">
                    <li class="side-item {{ request()->is('management/banners') ? 'selected' : '' }}">
                        <a href="{{ route('admin.banners.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>لیست بنر ها
                        </a>
                    </li>
                    <li class="side-item {{ request()->is('management/banners/create') ? 'selected' : '' }}">
                        <a href="{{ route('admin.banners.create') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>ایجاد بنر ها جدید
                        </a>
                    </li>
                </div>
            </ul>
        @endcan
        @can('comments-index')
            <ul class="side a-collapse {{ request()->is('management/comments*') ? '' : 'short' }}">
                <a class="ul-text fnt-mxs"><i class="fa fa-comments mr-1"></i>نظرات
                    <i class="fas fa-chevron-up arrow"></i>
                    <span class="badge badge-danger">{{ Comment::where('status' ,'1')->count() }}</span>
                </a>
                <div class="side-item-container {{ request()->is('management/comments*') ? '' : 'hide animated' }}">
                    <li class="side-item {{ request()->is('management/comments') ? 'selected' : '' }}">
                        <a href="{{ route('admin.comments.index') }}" class="fnt-mxs">
                            <i class="fas fa-angle-right mr-2"></i>لیست نظرات
                        </a>
                    </li>
                </div>
            </ul>
        @endcan
    </div>
</div>
