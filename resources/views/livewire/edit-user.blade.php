<div class="flex flex-col justify-center items-center" x-data="{animate: false, timer: null, verified: true, email: ''}">
    <div class="w-full h-100 flex justify-center items-center">
    <div x-show="animate" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-1000" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90" 
        class="absolute rounded shadow-md p-4 w-1/4 text-center bg-gray-700 text-white m-auto">
        <span x-text="(verified ? 'Unverified ' : 'Unverified ') + email"></span>
    </div>
    </div>

    <div class="w-full p-4 flex justify-between">
        <div class="flex items-center">Showing {{($page - 1) * $pageSize + 1}} - {{($page - 1) * $pageSize + $users->count()}} of {{$total}} users</div>
        <div class="flex items-center">{{'Total (' . $total . ') : Verified (' . $totalVerified . ') => ' . $verifiedPercentage . '%'}}</div>
        <div x-data="{timer: null}">
            <input type="text" placeholder="Search" @keyup="clearTimeout(timer); setTimeout(() => {$wire.set('search', $event.target.value)}, 1000);"/>
        </div>
        <div>
            <button wire:click="prevPage" class="m-2">Prev</button>
            <span>{{$page}} / {{ceil($total / $pageSize)}}</span>
            <button wire:click="nextPage" class="m-2">Next</button>
        </div>
    </div>

    <table class="w-4/5 table-auto p-4 m-4">
        <thead>
            <tr class="text-left">
                <th class="cursor-pointer" wire:click="sort('name')">Name<span>
                @if ($orderBy == 'name')
                    {{$orderDirection == 'asc' ? '🔻' : '🔺'}}
                @endif
                </span></th>
                <th class="cursor-pointer" wire:click="sort('email')">Email<span>
                @if ($orderBy == 'email')
                    {{$orderDirection == 'asc' ? '🔻' : '🔺'}}
                @endif
                </span></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>
                <div>
                    <button 
                        x-on:click="$wire.toggleVerified({{$user->id}});clearTimeout(timer); email = '{{$user->email}}'; verified = {{$user->email_verified_at ? 'true' : 'false'}}; animate = true; timer = setTimeout(() => {animate = false;}, 1000)" 
                        class="{{$user->email_verified_at ? 'bg-blue-500 hover:bg-blue-700' : 'bg-green-500 hover:bg-green-700'}} text-white font-bold py-2 px-4 rounded mt-4 ml-4"
                    >
                        <span>{{$user->email_verified_at ? 'Verify' : 'UnVerify'}}</span>
                    </button>
                </div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>