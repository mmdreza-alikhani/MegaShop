@if(count($errors) > 0)
    <div class="alert alert-danger text-right">
        <ul class="mb-0">
           @foreach($errors->all() as $error)
               <li class="alert-text mx-3">{{ $error }}</li>
           @endforeach
        </ul>
    </div>
@endif
