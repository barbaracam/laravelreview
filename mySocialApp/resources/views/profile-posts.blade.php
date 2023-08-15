<x-layout doctitle="{{$sharedData['username']}}'s Profile" >
  {{-- <x-profile :avatar='$avatar' :username="$username" :currentlyFollowing="$currentlyFollowing" :postCount="$postCount"> --}}
    <x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Profile" >   
      @include('profile-post-only');
  </x-profile>
</x-layout>

