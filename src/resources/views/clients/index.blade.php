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
                    <span class="breadcrumb-item active">Customers</span>
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
                                <h4 class="font-light">Customers List</h4>
                            </div>
                            <!-- Column -->
                            <div class="col-md-6">
                                <div class="float-right">
                                    <a href="{{ url('/clients_sorted') }}" class="btn btn-info btn-sm"><i class="fas fa-sort-alpha-up"></i>&nbsp;Sort by Name</a>
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
                        {!! $clients->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    &nbsp;
    <!--  Modal content  -->
    <div class="modal fade" id="bs-example-modal-lg" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Info Header Modal -->
    <div id="info-header-modal" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="info-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-info">
                    <h4 class="modal-title" id="info-header-modalLabel">Modal Heading</h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h5 class="mt-0">Info Background</h5>
                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio,
                        dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta
                        ac consectetur ac, vestibulum at eros.</p>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et.
                        Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor
                        auctor.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
@endsection
