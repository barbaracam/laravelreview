<x-layout doctitle="{{$sharedData['username']}}'s Followers">
    {{-- <x-profile :avatar='$avatar' :username="$username" :currentlyFollowing="$currentlyFollowing" :postCount="$postCount"> --}}
     <x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Followers">
      
      @include('profile-followers-only') 
  
    </x-profile>
  </x-layout>