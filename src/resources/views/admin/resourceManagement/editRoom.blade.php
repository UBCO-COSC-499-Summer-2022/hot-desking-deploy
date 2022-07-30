@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('Edit Room') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('roomUpdate', $room->id)}}" enctype="multipart/form-data">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="mb-3">
                                <label for="name" class="form-label">Room Name</label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" maxlength="30" value="{{$room->name}}" required />
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($room->has_printer)
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="has_printer" id="has_printer" checked>
                                    <label class="form-check-label" for="has_printer">Room Has a Printer</label>
                                </div>
                            </div>
                            @else
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="has_printer" id="has_printer">
                                    <label class="form-check-label" for="has_printer">Room Has a Printer</label>
                                </div>
                            </div>
                            @endif
                            @if ($room->has_projector)
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="has_projector" id="has_projector" checked>
                                    <label class="form-check-label" for="has_projector">Room Has a Projector</label>
                                </div>
                            </div>
                            @else
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="has_projector" id="has_projector">
                                    <label class="form-check-label" for="has_projector">Room Has a Projector</label>
                                </div>
                            </div>
                            @endif
                            @if ($room->is_closed)
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="is_closed" id="is_closed" checked>
                                    <label class="form-check-label" for="is_closed">Room Availability</label>
                                </div>
                            </div>
                            @else<div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='TRUE' name="is_closed" id="is_closed">
                                    <label class="form-check-label" for="is_closed">Room Availability</label>
                                </div>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Add New Room Image</label>
                                <input class="form-control" name="room_image" type="file" accept="image/*/">
                            </div>
                            <div class="w-100 text-center">
                                <img src="{{asset('images/rooms/' .strval($room->id) . '.png')}}" class="img-fluid" alt="">
                            </div>
                            <div class="mb-3">
                                <input name='floor_id' value='{{$room->floor_id}}' type='hidden' class='form-check-input'>
                                <button type="submit" class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('roomManager',$room->floor_id)}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection