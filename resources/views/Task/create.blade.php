@extends('layout.master')
@section('title', 'Task')
@section('breadCrum', 'Task')
@section('links')
@endsection
@section('contant')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <li class="text-danger">{{ $error }}</li>
                @endforeach
            @endif
            <form action="" method="post">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Name</label>
                        <input type="text" class="form-control" placeholder="Name" name="name"
                            @if (isset($data)) value="{{ $data['name'] }}" @endif />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Project</label>
                        <select name="project_id" id="" class="form-control"></select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Manage By</label>
                        <select name="manage_by" id="" class="form-control"></select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Start Dates</label>
                        <input type="date" class="form-control" name="start_date"
                            @if (isset($data)) value="{{ $data['start_date'] }}" @endif />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Deadline</label>
                        <input type="date" class="form-control" name="due_date"
                            @if (isset($data)) value="{{ $data['deadline'] }}" @endif />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Priority</label>
                        <select name="" id="" class="form-control"></select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Status</label>
                        <select name="" id="" class="form-control"></select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Voice Memo</label>
                        <input type="file" name="voice_memo" class="form-control" id="">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Description</label>
                        <textarea name="description" id="" class="form-control"></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $().ready(function() {
            $('#createProjectForm').validate({
                rules: {
                    name: 'required',
                    currency: 'required',
                    client_id: 'required',
                    manage_by: 'required',
                    start_date: 'required',
                    deadline: 'required',
                    summary: 'required',
                    'project_members[]': 'required',
                },
                // messages: {}
            });
        });
    </script>
@endsection
