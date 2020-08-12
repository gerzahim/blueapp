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
                    <span class="breadcrumb-item active">RMA</span>
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
                                <h4 class="font-light">RMA List</h4>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6">
                                <div class="float-right">
                                    <a href="{{ route('rma.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus-circle"></i>&nbsp;Add</a>
                                </div>
                            </div>
                        </div>

                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                        @endif
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>RMA Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @foreach ($rmas as $key => $rma)
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        <a href="{{ route('rma.show',$rma->id) }}">
                                        {{ $rma->name }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <!-- Edit button -->
                                        <a
                                        class="btn btn-primary btn-sm"
                                        href="{{ route('rma.edit', $rma->id ) }}"
                                        ><i class="far fa-edit"></i>&nbsp;Edit</a>

                                        <!-- Delete button -->
                                        <form
                                            action="{{ route('rma.destroy', $rma->id) }}"
                                            method="POST"
                                            style="display: inline;"
                                        >
                                            @csrf @method('DELETE')
                                            <input type='hidden' name='id' value='{{ $rma->id }}'>
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="far fa-trash-alt"></i>&nbsp;Delete
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                        {!! $rmas->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
