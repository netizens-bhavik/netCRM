@extends('layout.master')
@section('title', 'Projects')
@section('breadCrum', 'Projects')
@section('links')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
@endsection
@section('contant')
    <div class="card">
        <div class="card-body">
            <a href="{{url('project/create')}}" class="btn btn-primary" >Add Project</a>
            <div class="table-responsive">
                <table class="table yajra-datatable w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Start Datae</th>
                            <th>End Date</th>
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
                ajax: baseUrl + "/project-list",
                columns: [{
                        data: '#',
                        name: '#'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'startDate',
                        name: 'startDate'
                    },
                    {
                        data: 'deadLine',
                        name: 'deadLine'
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
