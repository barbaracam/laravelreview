<x-layout doctitle="{{$sharedData['username']}}'s Profile" >
  {{-- <x-profile :avatar='$avatar' :username="$username" :currentlyFollowing="$currentlyFollowing" :postCount="$postCount"> --}}
    <x-profile :sharedData="$sharedData" >
    <div class="list-group">
      @foreach($posts as $post)
       <x-post :post="$post" hideAuthor />
      @endforeach
    </div>

  </x-profile>
</x-layout>

