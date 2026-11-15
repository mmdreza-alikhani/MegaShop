<script src="{{ asset('/admin/js/modernizr.js') }}"></script>
<script src="{{ asset('/admin/js/toastr.min.js') }}"></script>
<script src="{{ asset('/admin/js/persianumber.min.js') }}"></script>
{{-- ترتیب اسکریپت‌ها مهم است: bootstrap باید قبل از افزونه‌های وابسته (bootstrap-select) بارگذاری شود. --}}
<script src="{{ asset('/admin/js/popper.js') }}"></script>
<script src="{{ asset('/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/admin/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('/admin/js/bootstrap-material-design.js') }}"></script>
<script src="{{ asset('/admin/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('/admin/js/jalalidatepicker.min.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.czMore-latest.js') }}"></script>
<script src="{{ asset('/admin/js/form-repeater.js') }}"></script>
<script src="{{ asset('/admin/js/main.js') }}"></script>
<script src="{{ asset('/admin/js/shell.js') }}"></script>
<script>
    jQuery(function () {
        if (jQuery.fn.bootstrapMaterialDesign) {
            jQuery('body').bootstrapMaterialDesign();
        }
    });
</script>
