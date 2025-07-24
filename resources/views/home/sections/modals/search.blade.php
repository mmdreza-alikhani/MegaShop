<div class="nk-modal modal fade" id="modalSearch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="mb-0 text-right">جستجو</h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="ion-android-close"></span>
                </button>

                <div class="nk-gap-1"></div>
                <form id="generalSearch" action="{{ route('home.global-search') }}" method="GET" class="nk-form nk-form-style-1">
                    <input type="text" value="{{ request()->has('keyword') ? request()->keyword : '' }}" name="keyword" class="form-control" placeholder="...اینجا بنویسید" autofocus>
                </form>
            </div>
        </div>
    </div>
</div>
