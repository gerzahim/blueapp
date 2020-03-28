@extends('layouts.master')

@section('content')

    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <nav class="breadcrumb">
                    <a class="breadcrumb-item" href="{{ url('/') }}">Home</a>
                    <span class="breadcrumb-item active">Suppliers</span>
                </nav>
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
                                <h4 class="font-light">Suppliers List</h4>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6">
                                <div class="float-right">
                                    <a href="{{ url('/vendors_sorted') }}" class="btn btn-info btn-sm">Sort by Name</a>
                                    &nbsp;
                                    <a href="{{ route('vendor.create') }}" class="btn btn-success btn-sm">Add</a>
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
                            @foreach ($vendors as $key => $vendor)
                                <tbody>
                                <tr>
                                    <td class="text-center">
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        <a href="{{ route('vendor.show',$vendor->id) }}">
                                            {{ $vendor->name }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <!-- Edit button -->
                                        <a
                                            class="btn btn-primary btn-sm"
                                            href="{{ route('vendor.edit',$vendor->id) }}"
                                        >Edit</a>

                                        <!-- Delete button -->
                                        <form
                                            action="{{ route('vendor.destroy',$vendor->id) }}"
                                            method="POST"
                                            style="display: inline;"
                                        >
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                        {!! $vendors->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
