<x-layout doctitle="{{$sharedData['username']}}'s Following">
    {{-- <x-profile :avatar='$avatar' :username="$username" :currentlyFollowing="$currentlyFollowing" :postCount="$postCount"> --}}
    <x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Following">
      
      @include('profile-following-only') 
  
    </x-profile>
  </x-layout>