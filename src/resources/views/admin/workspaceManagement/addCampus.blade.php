@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">
                    {{ __('Create Campus') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('campusStore')}}">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">Campus Name</label>
                                <input type="text" name="name" maxlength="255" id="disabledTextInput"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Campus Name"
                                    required>
                                @error('name')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value="FALSE" name="is_closed"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Campus
                                        Available</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('campusManager')}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection