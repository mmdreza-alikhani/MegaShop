<script src="{{ asset('/admin/js/modernizr.js') }}"></script>
<script src="{{ asset('/admin/js/popper.js') }}"></script>
<script src="{{ asset('/admin/js/toastr.min.js') }}"></script>
<script src="{{ asset('/admin/js/bootstrap-material-design.js') }}"></script>
<script src="{{ asset('/admin/js/persianumber.min.js') }}"></script>
<script src="{{ asset('/admin/js/main.js') }}"></script>
<script>
    window.jQuery || document.write('<script src="{{ asset('/admin/js/jquery-3.2.1.min.js') }}"><\/script>')
    $(document).ready(function () {
        $('body').bootstrapMaterialDesign();
        $('.persianumber').persiaNumber();

    });
</script>
<script src="{{ asset('/admin/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('/admin/js/jalalidatepicker.min.js') }}"></script>
{{--<script src="{{ asset('/admin/js/ckeditor.js') }}"></script>--}}
{{--<script src="{{ asset('/admin/js/summernote.min.js') }}"></script>--}}
<script src="{{ asset('/admin/js/jquery-ui.min.js') }}"></script>
{{--<script src="{{ asset('/admin/js/jquery.czMore-latest.js') }}"></script>--}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
{{--<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>--}}
<script src="{{ asset('/admin/js/ckeditor.js') }}"></script>
