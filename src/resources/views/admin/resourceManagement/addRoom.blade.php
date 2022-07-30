@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('Create Room') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('roomStore')}}" enctype="multipart/form-data">
                        @csrf
                        <fieldset>
                            <div class="mb-3">
                                <label for="name" class="form-label">Room Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Eg. UNC 111" maxlength="30" minlength="1" required>
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="has_printer" id="has_printer">
                                    <label class="form-check-label" for="has_printer">Room Has a Printer</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="has_projector" id="has_projector">
                                    <label class="form-check-label" for="has_projector">Room Has a Projector</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="is_closed" id="is_closed">
                                    <label class="form-check-label" for="is_closed">Room Availability</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="room_image" class="form-label">Add Room Image</label>
                                <input class="form-control " type="file" id="room_image" name="room_image" accept="image/*" required>
                            </div>
                            <div class=" mb-3">
                                <input name='floor_id' value='{{$floor_id}}' type='hidden' class='form-check-input'>
                                <button type="submit" class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('roomManager',$floor_id)}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection