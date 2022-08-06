@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Edit Desk') }}
                </div>
                <div class="card-body">
                    <form method="POST" action="{{route('deskUpdate')}}">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="mb-3">
                                <label for="pos_x" class="form-label">X Position</label>
                                <input type="number" min="0" max="9999" id="pos_x" class="form-control @error('pos_x') is-invalid @enderror" name="pos_x" value="{{$desk->pos_x}}" required>
                                @error('pos_x')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="pos_y" class="form-label">Y Position</label>
                                <input type="number" min="0" max="9999" id="pos_y" class="form-control @error('pos_y') is-invalid @enderror" name="pos_y" value="{{$desk->pos_y}}" required>
                                @error('pos_y')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($desk->is_closed)
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value='TRUE' name="is_closed" id="is_closed" checked>
                                        <label class="form-check-label" for="is_closed">Close Desk</label>
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value='TRUE' name="is_closed" id="is_closed">
                                        <label class="form-check-label" for="is_closed">Close Desk</label>
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <input name='room_id' value='{{$desk->room_id}}' type='hidden' class='form-check-input'>
                                <button class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('deskManager',$desk->room_id)}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection