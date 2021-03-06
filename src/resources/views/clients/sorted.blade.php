@extends('layouts.master')

@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <div class="col-12 align-self-center">
                    <nav class="breadcrumb">
                        <a class="breadcrumb-item" href="{{ url('/') }}">Home</a>
                        <span class="breadcrumb-item active">Customers</span>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->



    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <!-- Column -->
                            <div class="col-md-6">
                                <h4 class="font-light">Customers List</h4>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6">
                                <div class="float-right">
                                    <a href="{{ route('client.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus-circle"></i>&nbsp;Add</a>
                                </div>
                            </div>
                        </div>

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            @foreach ($clients as $key => $client)
                                <tbody>
                                <tr>
                                    <td class="text-center">
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        <a href="{{ route('client.show',$client->id) }}">
                                            {{ $client->name }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <!-- Edit button -->
                                        <a
                                            class="btn btn-primary btn-sm"
                                            href="{{ route('client.edit',$client->id) }}"
                                        ><i class="far fa-edit"></i>&nbsp;Edit</a>

                                        <!-- Delete button -->
                                        <form
                                            action="{{ route('client.destroy',$client->id) }}"
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
                        {!! $links !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
