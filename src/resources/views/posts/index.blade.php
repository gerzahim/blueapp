@extends('posts.master')

@section('body')
<div class="card">
    <div class="card-header">
        <div class="float-right">
            <a href="{{ route('post.create') }}" class="btn btn-primary btn-sm"
                >Add</a
            >
        </div>
        Posts
    </div>
    <div class="card-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            @foreach ($posts as $key => $post)
            <tbody>
                <tr>
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        <a href="{{ route('post.show',$post->id) }}">
                        {{ $post->name }}
                        </a>
                    </td>
                    <td>
                        <!-- Edit button -->
                        <a
                        class="btn btn-primary btn-sm"
                        href="{{ route('post.edit',$post->id) }}"
                        ><i class="far fa-edit"></i>&nbsp;Edit</a>

                        <!-- Delete button -->
                        <form
                            action="{{ route('post.destroy',$post->id) }}"
                            method="POST"
                            style="display: inline;"
                        >
                            @csrf @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="far fa-trash-alt"></i>&nbsp;Delete
                            </button>
                        </form>
                    </td>
                </tr>
            </tbody>
            @endforeach
        </table>
        {!! $posts->links() !!}
    </div>
    @endsection
</div>
