<x-layout :doctitle="$post->title">
    <div class="container py-md-5 container--narrow">
      <div class="d-flex justify-content-between">
        <h2>{{$post->title}}</h2>

        {{-- controlling who can edit or delete by user --}}
        @can('delete', $post)
        <span class="pt-2">
          <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
          <form class="delete-post-form d-inline" action="/post/{{$post->id}}" method="POST">
            @csrf
            @method("delete")
            <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
          </form>
        </span>
        @endcan
      </div>

      <p class="text-muted small mb-4">
        <a href="/profile/{{$post->user->username}}"><img class="avatar-tiny" src="{{$post->user->avatar}}" /></a>
        Posted by <a href="/profile/{{$post->user->username}}">{{$post->user->username}}</a> {{$post->created_at->format('n/j/Y')}}
        {{-- we know we access$post->user... because the fuction from the model --}}
      </p>

      <div class="body-content">
        {{-- it is a bit different because we want to accep markdown --}}
        <p>{!!$post->body !!}</p>
      </div>
    </div>

    <!-- footer begins -->
    </x-layout>
