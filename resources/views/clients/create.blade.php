@extends('layout.master')
@section('title', 'Client')
@section('breadCrum', 'Clients')
@section('links')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
@endsection
@section('contant')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <li class="text-danger">{{ $error }}</li>
                        @endforeach
            @endif
            <form @if(isset($client)) action="{{ url('client/update/'.$client->id) }}" @else action="{{ url('client/store') }}" @endif method="post" @if(isset($client)) @else id="createClientForm" @endif enctype="multipart/form-data">@csrf
                <div class="row">
                    <div class="col-md-6  mb-3">
                        <label for="defaultFormControlInput" class="form-label">Name</label>
                        <input type="text" class="form-control" placeholder="Name" name="name" @if (isset($client)) value="{{$client->name}}" @endif />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="Email" name="email" @if (isset($client)) value="{{$client->email}}" @endif />
                    </div>
                    <div class="col-md-6  mb-3">
                        <label for="defaultFormControlInput" class="form-label">Avtar</label>
                        <input type="file" class="form-control" placeholder="Avtar" name="avtar" accept="image/*" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Phone no</label>
                        <input type="tel" class="form-control" placeholder="Phone no" name="phone_no" @if (isset($client)) value="{{$client->phone_no}}" @endif />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="defaultFormControlInput" class="form-label">Country</label>
                        <select name="country_id" id="countrySelect" class="form-control">
                            @if ($countries)
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" @isset($client) @if ($client->country_id == $country->id) selected @endif @endisset>{{ $country->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="defaultFormControlInput" class="form-label">State</label>
                        <select name="state_id" id="stateDropDown" class="form-control">@if (isset($client)) <option value="{{$state->id}}">{{$state->name}}</option> @endif</select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="defaultFormControlInput" class="form-label">City</label>
                        <select name="city_id" id="cityDropDown" class="form-control">@if (isset($client)) <option value="{{$city->id}}">{{$city->name}}</option> @endif</select>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="defaultFormControlInput" class="form-label">Zipcode</label>
                        <input type="text" name="zipcode" class="form-control" placeholder="Zipcode" @if (isset($client)) value="{{$client->zipcode}}" @endif />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="defaultFormControlInput" class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="Company Name" @if (isset($client)) value="{{$client->company_name}}" @endif />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="defaultFormControlInput" class="form-label">Company Website</label>
                        <input type="url" name="company_website" class="form-control" placeholder="Company Website" @if (isset($client)) value="{{$client->company_website}}" @endif />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="defaultFormControlInput" class="form-label">Company Address</label>
                        <textarea name="company_address" id="" cols="" rows="" class="form-control" placeholder="Address">@if (isset($client)) {{$client->company_address}} @endif </textarea>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="defaultFormControlInput" class="form-label">Company Logo</label>
                        <input type="file" name="company_logo" class="form-control" id="" accept="image/*">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="defaultFormControlInput" class="form-label">Tax</label>
                        <input type="text" name="tax" class="form-control" id="" placeholder="Tax" @if (isset($client)) value="{{$client->tax}}" @endif />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="defaultFormControlInput" class="form-label">GST/WAT</label>
                        <input type="text" name="gst_vat" class="form-control" id="" @if (isset($client)) value="{{$client->gst_vat}}" @endif />
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="defaultFormControlInput" class="form-label">Office Mobile</label>
                        <input type="tel" name="office_mobile" class="form-control" id="" placeholder="Office Mobile" @if (isset($client)) value="{{$client->office_mobile}}" @endif />
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="defaultFormControlInput" class="form-label">Address</label>
                        <textarea name="address" id="" cols="" rows="" class="form-control" placeholder="Address">@if (isset($client)) {{$client->address}} @endif </textarea>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label for="defaultFormControlInput" class="form-label">Note</label>
                        <textarea name="note" id="" cols="" rows="" class="form-control" placeholder="Note">@if (isset($client)) {{$client->note}} @endif </textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(function() {
            baseUrl = window.location.origin;
            $('#countrySelect').on('change', function() {
                countryId = $('#countrySelect').val();
                $.ajax({
                    url: baseUrl + "/get-states/" + countryId,
                    success: function(response) {
                        $('#stateDropDown').empty();
                        response.data.forEach(state => {
                            $('#stateDropDown').append('<option value="' + state.id +
                                '" >' + state.name + '</option>')
                        });
                    },
                    error: function(error) {}
                });
            });
            $('#stateDropDown').on('change', function() {
                stateId = $('#stateDropDown').val();
                $.ajax({
                    url: baseUrl + '/get-cities/' + stateId,
                    success: function(response) {
                        $('#cityDropDown').empty();
                        response.data.forEach(city => {
                            $('#cityDropDown').append('<option value="' + city.id +
                                '" >' + city.name + '</option>')
                        });
                    },
                    error: function(error) {}
                })
            })
        })

        $().ready(function() {
            $('#createClientForm').validate({
                rules: {
                    name: 'required',
                    avtar: {
                        extension: "jpg|jpeg|png|ico|bmp",
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone_no: 'required',
                    country_id: 'required',
                    state_id: 'required',
                    city_id: 'required',
                    zipcode: 'required',
                    company_name: 'required',
                    company_address: 'required',
                    company_logo: {
                        required: true,
                        extension: "jpg|jpeg|png|ico|bmp",
                    },
                    tax: 'required',
                    gst_vat: 'required',
                    office_mobile: 'required',
                    address: 'required',
                    note: 'required',
                },
                messages: {
                    name: 'This field is required',
                    avtar: {
                        extension: "Please upload file in these format only (jpg, jpeg, png, ico, bmp)."
                    },
                    email: {
                        required: "This Field is Required.",
                        email: "Please enter Valid Email",
                    },
                    phone_no: 'Phone No is required',
                    country_id: 'country_id is Required',
                    state_id: 'state_id is Required',
                    city_id: 'city_id is Required',
                    zipcode: 'zipcode is Required',
                    company_name: 'company_name is Required',
                    company_website: 'company_website is Required',
                    company_address: 'company_address is Required',
                    company_logo: {
                        required: "Please upload file.",
                        extension: "Please upload file in these format only (jpg, jpeg, png, ico, bmp)."
                    },
                    tax: 'tax is Required',
                    gst_vat: 'gst_vat is Required',
                    office_mobile: 'office_mobile is Required',
                    address: 'address is Required',
                    note: 'note is Required',
                },
            });
        });
    </script>
@endsection
