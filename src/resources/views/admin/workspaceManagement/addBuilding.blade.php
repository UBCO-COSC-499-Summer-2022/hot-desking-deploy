@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('Create Building') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('buildingStore')}}">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">Building Name</label>
                                <input type="text" name="name" maxlength="255" id="disabledTextInput"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Building Name"
                                    required>
                                @error('name')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="is_closed"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Building
                                        Availability</label>
                            <div class="mb-3">
                                <input name='campus_id' value='{{$campus_id}}' type='hidden' class='form-check-input'>
                                <button class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('buildingManager',$campus_id)}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection