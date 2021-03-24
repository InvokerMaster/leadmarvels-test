<div>
    Showing {{$users->count()}} of {{$total}} users

    @foreach ($users as $user)
        {{$user->email}}
    @endforeach
</div>
