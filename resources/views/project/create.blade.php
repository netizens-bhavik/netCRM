@extends('layout.master')
@section('title', 'Projects')
@section('breadCrum', 'Projects')
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
            <form @if (isset($data)) action="{{url('project/'.request()->projectId.'/update')}}" @else action="{{url('project-store')}}" @endif method="post" id="createProjectForm">@csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Name</label>
                        <input type="text" class="form-control" placeholder="Name" name="name"
                            @if (isset($data)) value="{{ $data['name'] }}" @endif />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Currency</label>
                        <input type="text" class="form-control" placeholder="Currency" name="currency"
                            @if (isset($data)) value="{{ $data['currency'] }}" @endif />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Client</label>
                        <select name="client_id" id="">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Manage By</label>
                        <select name="manage_by" id="">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Start Dates</label>
                        <input type="date" class="form-control" name="start_date"
                            @if (isset($data)) value="{{ $data['start_date'] }}" @endif />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Deadline</label>
                        <input type="date" class="form-control" name="deadline"
                            @if (isset($data)) value="{{ $data['deadline'] }}" @endif />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Summary</label>
                        <textarea name="summary" class="form-control">@if (isset($data)){{$data['summary']}}@endif</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Project Members</label>
                        <select name="project_members[]" id="" multiple>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if (isset($data)) {{is_array($data['projectMembers']) && in_array($user->id, $data['projectMembers']) ? 'selected' : '' }} @endif >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
                    manage_by:'required',
                    start_date: 'required',
                    deadline:'required',
                    summary:'required',
                    'project_members[]':'required',
                },
                // messages: {}
            });
    });
</script>
@endsection
