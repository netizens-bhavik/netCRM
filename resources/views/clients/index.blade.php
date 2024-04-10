@extends('layout.master')
@section('title', 'Client')
@section('breadCrum', 'Clients')
@section('links')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
@endsection
@section('contant')
    <div class="card">
        <div class="card-body">
            <a href="{{url('create/client')}}" class="btn btn-primary" >Add Client</a>
            <div class="table-responsive">
                <table class="table yajra-datatable w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company_name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script>
        $(function() {
            baseUrl = window.location.origin;
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: baseUrl + "/client-list",
                columns: [{
                        data: '#',
                        name: '#'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });

        });
    </script>
@endsection
