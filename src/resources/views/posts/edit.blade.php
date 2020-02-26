@extends('posts.master')

@section('body')

<div class="card">
  <div class="card-header">
      <div class="float-right">
          <a href="{{ route('post.index') }}" class="btn btn-primary btn-sm">Back to Posts</a>
      </div>
     Update Post
  </div>
  <div class="card-body">

    @if ($errors->any())
    <div class="alert alert-danger">
        <p>
            <strong>Input Error!</strong>
        </p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('post.update',$post->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                value="{{ $post->name }}"
            />
        </div>

        <div class="form-group">
            <label for="body">Body</label>
            <textarea
                class="form-control"
                id="body"
                name="body"
                rows="5"
                >{{ $post->body }}</textarea
            >
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection