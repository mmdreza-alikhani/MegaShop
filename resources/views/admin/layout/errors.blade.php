@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert text-dir-rtl text-right alert-third alert-shade alert-dismissible fade show"
             role="alert">
            {{ $error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach
@endif
