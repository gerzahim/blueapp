@extends('posts.master')

@section('body')

<div class="card">
  <div class="card-header">
      <div class="float-right">
          <a href="{{ route('post.index') }}" class="btn btn-primary btn-sm">Back to Posts</a>
      </div>
      Post
  </div>
  <div class="card-body">

    <h2>{{ $post->name }}</h2>

    <p>
        {{ $post->body }}
    </p>
</div>
@endsection