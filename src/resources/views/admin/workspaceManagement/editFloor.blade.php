@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">
                    {{ __('Edit Floor') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('floorUpdate', $floor->id)}}">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="mb-3">
                                <label for="floor_num" class="form-label">Floor Number</label>
                                <input type="number" name="floor_num" id="floor_num" class="form-control @error('floor_num') is-invalid @enderror" max="10" min="0" value="{{$floor->floor_num}}" required>
                                @error('floor_num')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($floor->is_closed)
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='FALSE' name="is_closed" id="is_closed">
                                    <label class="form-check-label" for="is_closed">Floor Available</label>
                                </div>
                            </div>
                            @else
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='FALSE' name="is_closed" id="is_closed" checked>
                                    <label class="form-check-label" for="is_closed">Floor Available</label>
                                </div>
                            </div>
                            @endif
                            <div class="mb-3">
                                <input name="building_id" value="{{$floor->building_id}}" type="hidden" class="form-check-input">
                                <button type="submit" class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('floorManager',$floor->building_id)}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection