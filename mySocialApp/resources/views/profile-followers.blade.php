<x-layout doctitle="{{$sharedData['username']}}'s Followers">
    {{-- <x-profile :avatar='$avatar' :username="$username" :currentlyFollowing="$currentlyFollowing" :postCount="$postCount"> --}}
     <x-profile :sharedData="$sharedData">
  
      <div class="list-group">
        @foreach($followers as $follower)
        <a href="/profile/{{$follower->userDoingTheFollowing->username}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$follower->userDoingTheFollowing->avatar}}" />  
          {{$follower->userDoingTheFollowing->username}}       
        </a>
        @endforeach
      </div>
  
    </x-profile>
  </x-layout>