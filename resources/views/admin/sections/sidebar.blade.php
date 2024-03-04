<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" data-widget="pushmenu">
        <img src="/admin/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="direction: ltr">
        <div style="direction: rtl">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img class="img-circle elevation-2" src="{{ Str::contains(Auth::user()->avatar, 'https://') ? Auth::user()->avatar : env('USER_AVATAR_UPLOAD_PATH') . '/' . Auth::user()->avatar }}" alt="{{ Auth::user()->username }}-image" />
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        @if(Auth::user()->first_name && Auth::user()->last_name != null)
                            {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                        @else
                            {{ Auth::user()->username }}
                        @endif
                    </a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="/admin-panel/management" class="nav-link @if($active_parent == 'index') active @endif ">
                            <i class="nav-icon fa fa-th"></i>
                            <p>
                                داشبورد
                            </p>
                        </a>
                    </li>

                    @can('manage-users')
                    <li class="nav-header">کاربران</li>
                    <li class="nav-item has-treeview @if($active_parent == 'users') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'users') active @endif ">
                            <i class="nav-icon fa fa-user-circle"></i>
                            <p>
                                کاربران
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" class="nav-link @if($active_child == 'showusers') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش کاربران</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}" class="nav-link @if($active_child == 'roles') active @endif">
                                    <i class="fa fa-tasks nav-icon"></i>
                                    <p>گروه های کاربری</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.permissions.index') }}" class="nav-link @if($active_child == 'permissions') active @endif">
                                    <i class="fa fa-lock nav-icon"></i>
                                    <p>دسترسی ها</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    @can('manage-products')
                    <li class="nav-header">محصولات</li>
                    <li class="nav-item has-treeview @if($active_parent == 'brands') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'brands') active @endif ">
                            <i class="nav-icon fa fa-check"></i>
                            <p>
                                برند ها
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.brands.index') }}" class="nav-link @if($active_child == 'showbrands') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش برند ها</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.brands.create') }}" class="nav-link @if($active_child == 'makebrand') active @endif">
                                    <i class="fa fa-plus-circle nav-icon"></i>
                                    <p>ایجاد برند</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview @if($active_parent == 'platforms') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'platforms') active @endif ">
                            <i class="nav-icon fa fa-gamepad"></i>
                            <p>
                                پلتفرم ها
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.platforms.index') }}" class="nav-link @if($active_child == 'showplatforms') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش پلتفرم ها</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.platforms.create') }}" class="nav-link @if($active_child == 'makeplatform') active @endif">
                                    <i class="fa fa-plus-circle nav-icon"></i>
                                    <p>ایجاد پلتفرم</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview @if($active_parent == 'attributes') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'attributes') active @endif ">
                            <i class="nav-icon fa fa-sort"></i>
                            <p>
                                ویژگی ها
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.attributes.index') }}" class="nav-link @if($active_child == 'showattributes') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش ویژگی ها</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.attributes.create') }}" class="nav-link @if($active_child == 'makeattribute') active @endif">
                                    <i class="fa fa-plus-circle nav-icon"></i>
                                    <p>ایجاد ویژگی</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview @if($active_parent == 'categories') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'categories') active @endif ">
                            <i class="nav-icon fa fa-list-ul"></i>
                            <p>
                                دسته بندی ها
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.categories.index') }}" class="nav-link @if($active_child == 'showcategories') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش دسته بندی ها</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.categories.create') }}" class="nav-link @if($active_child == 'makecategory') active @endif">
                                    <i class="fa fa-plus-circle nav-icon"></i>
                                    <p>ایجاد دسته بندی</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview @if($active_parent == 'tags') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'tags') active @endif ">
                            <i class="nav-icon fa fa-tags"></i>
                            <p>
                                تگ ها
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.tags.index') }}" class="nav-link @if($active_child == 'showtags') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش تگ ها</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.tags.create') }}" class="nav-link @if($active_child == 'maketag') active @endif">
                                    <i class="fa fa-plus-circle nav-icon"></i>
                                    <p>ایجاد تگ</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview @if($active_parent == 'products') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'products') active @endif ">
                            <i class="nav-icon fa fa-product-hunt"></i>
                            <p>
                                محصولات
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.products.index') }}" class="nav-link @if($active_child == 'showproducts') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش محصولات</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.products.create') }}" class="nav-link @if($active_child == 'makeproduct') active @endif">
                                    <i class="fa fa-plus-circle nav-icon"></i>
                                    <p>ایجاد محصول</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    @can('manage-comments')
                    <li class="nav-item has-treeview @if($active_parent == 'comments') menu-open @endif ">
                        <a href="{{ route('admin.comments.index') }}" class="nav-link @if($active_parent == 'comments') active @endif ">
                            <i class="nav-icon fa fa-comment"></i>
                            <p>
                                نظرات
                                @if(\App\Models\Comment::where('approved', '>', '0'))
                                    <span class="badge badge-info right" style="transform: rotate(360deg)">
                                        {{ \App\Models\Comment::where('approved', '0')->get()->count() }}
                                    </span>
                                @else
                                    <span class="badge badge-success right" style="transform: rotate(360deg)">
                                        تمامی نظرات بررسی شده اند.
                                    </span>
                                @endif
                            </p>
                        </a>
                    </li>
                    @endcan

                    @can('manage-orders')
                    <li class="nav-item has-treeview @if($active_parent == 'coupons') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'coupons') active @endif ">
                            <i class="nav-icon fa fa-product-hunt"></i>
                            <p>
                                کد های تخفیف
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.coupons.index') }}" class="nav-link @if($active_child == 'showcoupons') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش کد های تخفیف</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.coupons.create') }}" class="nav-link @if($active_child == 'makecoupon') active @endif">
                                    <i class="fa fa-plus-circle nav-icon"></i>
                                    <p>ایجاد کد تخفیف</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-header">سفارشات</li>
                    <li class="nav-item has-treeview @if($active_parent == 'orders') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'orders') active @endif ">
                            <i class="nav-icon fa fa-first-order"></i>
                            <p>
                                سفارشات
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.orders.index') }}" class="nav-link @if($active_child == 'showorders') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش سفارشات</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    @can('manage-general')
                    <li class="nav-header">تنظیمات</li>
                    <li class="nav-item has-treeview @if($active_parent == 'banners') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'banners') active @endif ">
                            <i class="nav-icon fa fa-picture-o"></i>
                            <p>
                                بنر ها
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.banners.index') }}" class="nav-link @if($active_child == 'showbanners') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش بنر ها</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.banners.create') }}" class="nav-link @if($active_child == 'makebanner') active @endif">
                                    <i class="fa fa-plus-circle nav-icon"></i>
                                    <p>ایجاد بنر</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan

                    <li class="nav-header">نوشته ها</li>
                    @can('manage-articles')
                    <li class="nav-item has-treeview @if($active_parent == 'articles') menu-open @endif ">
                        <a href="#" class="nav-link @if($active_parent == 'articles') active @endif ">
                            <i class="nav-icon fa fa-newspaper-o"></i>
                            <p>
                                مقالات
                                <i class="right fa fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.articles.index') }}" class="nav-link @if($active_child == 'showarticles') active @endif">
                                    <i class="fa fa-th nav-icon"></i>
                                    <p>نمایش مقالات</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.articles.create') }}" class="nav-link @if($active_child == 'makearticle') active @endif">
                                    <i class="fa fa-plus-circle nav-icon"></i>
                                    <p>ایجاد مقاله</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @can('manage-news')
                        <li class="nav-item has-treeview @if($active_parent == 'news') menu-open @endif ">
                            <a href="#" class="nav-link @if($active_parent == 'news') active @endif ">
                                <i class="nav-icon fa fa-newspaper"></i>
                                <p>
                                    اخبار
                                    <i class="right fa fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.news.index') }}" class="nav-link @if($active_child == 'shownews') active @endif">
                                        <i class="fa fa-th nav-icon"></i>
                                        <p>نمایش اخبار</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.news.create') }}" class="nav-link @if($active_child == 'makenews') active @endif">
                                        <i class="fa fa-plus-circle nav-icon"></i>
                                        <p>ایجاد خبر</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
    </div>
    <!-- /.sidebar -->
</aside>
