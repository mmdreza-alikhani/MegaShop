<div class="nk-modal modal fade" id="modalSearch" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h4 class="mb-0 text-right">جستجو</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="ion-android-close"></span>
                </button>
                <div class="nk-gap-1"></div>
                <form id="generalSearch" action="{{ route('home.search') }}" method="GET" class="nk-form nk-form-style-1">
                    <input type="text"
                           name="q"
                           class="form-control"
                           placeholder="جستجو در محصولات..."
                           value="{{ request('q') }}"
                           minlength="2"
                           required
                           autofocus>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript برای بهبود تجربه کاربری --}}
@push('scripts')
    <script>
        let searchModal = $('#modalSearch');
        $(document).ready(function() {
            searchModal.on('shown.bs.modal', function () {
                $(this).find('input[name="q"]').focus();
            });

            searchModal.on('hidden.bs.modal', function () {
                $(this).find('input[name="q"]').val('');
            });

            $('#modalSearch input[name="q"]').on('keypress', function(e) {
                if (e.which === 13) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endpush
