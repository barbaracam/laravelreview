<x-layout doctitle="{{$sharedData['username']}}'s Following">
    {{-- <x-profile :avatar='$avatar' :username="$username" :currentlyFollowing="$currentlyFollowing" :postCount="$postCount"> --}}
    <x-profile :sharedData="$sharedData">
      <div class="list-group">
        @foreach($following as $following)
        <a href="/profile/{{$following->userBeingFollowed->username}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$following->userBeingFollowed->avatar}}" />
          {{$following->userBeingFollowed->username}}   
        </a>
        @endforeach
      </div>
  
    </x-profile>
  </x-layout>