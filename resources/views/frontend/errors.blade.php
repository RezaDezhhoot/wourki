<div class="row">
    @if($errors->any())
        <div class="alert alert-danger text-center">
            <ul class="list-unstyled">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>