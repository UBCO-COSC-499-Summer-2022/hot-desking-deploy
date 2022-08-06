@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Create Floor') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('floorStore')}}">
                        @csrf
                        <fieldset>
                            <div class="mb-3">
                                <label for="floor_num" class="form-label">Floor Number</label>
                                <input type="number" name="floor_num" id="floor_num" class="form-control @error('floor_num') is-invalid @enderror" placeholder="Floor Number" max="10" min="0" required>
                                @error('floor_num')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="is_closed" id="is_closed">
                                    <label class="form-check-label" for="is_closed">Floor Availability</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input name='building_id' value='{{$building_id}}' type='hidden' class='form-check-input'>
                                <button class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('floorManager',$building_id)}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection