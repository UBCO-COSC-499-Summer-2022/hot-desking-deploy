@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">
                    {{ __('Edit Building') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('buildingUpdate',$building->id)}}">
                        @csrf
                        {{method_field('POST')}}
                            <fieldset>
                                <div class="mb-3">
                                <label for="name" class="form-label">Building Name</label>
                                <input type="text" value="{{$building->name}}" name="name" maxlength="255"
                                    id="name" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Building Name" required>
                                @error('name')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    @if($building->is_closed)
                                    <input class="form-check-input" type="checkbox" value='FALSE' name="is_closed"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Building
                                        Available</label>
                                    @else
                                    <input class="form-check-input" type="checkbox" value='FALSE' name="is_closed"
                                        id="flexSwitchCheckDefault" checked>
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Building
                                        Available</label>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <input name="campus_id" value="{{$building->campus_id}}" type="hidden" class="form-check-input">                                 
                                <button type="submit" class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('buildingManager',$building->campus_id)}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection