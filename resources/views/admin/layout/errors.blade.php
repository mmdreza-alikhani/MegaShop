@if($errors instanceof \Illuminate\Support\MessageBag ? $errors->any() : count($errors) > 0)
    <div class="mk-form-errors" role="alert">
        <div class="mk-form-errors-head">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            <strong>ذخیره نشد؛ موارد زیر را اصلاح کنید:</strong>
        </div>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
