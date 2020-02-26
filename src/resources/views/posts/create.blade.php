@extends('posts.master')

@section('body')

<div class="card">
  <div class="card-header">
      <div class="float-right">
          <a href="{{ route('post.index') }}" class="btn btn-primary btn-sm">Back to Posts</a>
      </div>
     Create Post
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

      <form method="POST" action="{{ route('post.store') }}">
            @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
        </div>

         <div class="form-group">
            <label for="body">Body</label>
            <textarea class="form-control" id="body" name="body" rows="5">{{ old('body') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>

      </form>
  </div>
@endsection
