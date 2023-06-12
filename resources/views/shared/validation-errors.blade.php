@if ($errors->any())
    <hr />
    <div class="alert alert-danger text-center" role="alert">
        <ol class="list-group list-group-flush list-group-numbered">
            @foreach ($errors->all() as $error)
            <li class="list-group-item list-group-item-danger">
                {{ $error }}
            </li>
            @endforeach
        </ol>
    </div>
@endif