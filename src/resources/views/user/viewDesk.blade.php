@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                @if (count($desks) < 1)
                        <div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill"></i> Room currently has no desks. 
                        </div>
                @else
                <div class="card-header">
                    {{ __('Indicate Desk') }}
                </div>
                <div class="card-body">
                <label for="indicateDesk" class="col-md-6 col-form-label text-md-end">Current Date: {{ $date->format('Y-m-d') }}</label>
                <label for="indicateDesk" class="col-md-6 col-form-label text-md-end">Current Location: {{ $desks[0]->room->floor->building->name.' Building, '.$desks[0]->room->floor->floor_num.', '.$desks[0]->room->name }}</label>
                <form action="{{ route('bookings') }}">    
                <div class="row mb-3">
                        <label for="select desk" class="col-md-6 col-form-label text-md-end">{{ __('Select Desk') }}</label> 

                        <div class="col-md-6">                                                  
                                <select id="Desks" class="form-select" name="Desks" required>
                                    <option value="desks" disabled selected>Select Desks</option>
                                    @foreach($desks as $desk)
                                        <option value="{{ $desk->id }}">Desk {{ $desk->id  }}</option>
                                    @endforeach
                                </select>                            
                        </div>
                    </div>    
                    <div class="row mb-3">                                  
                        <button type="submit" class="btn btn-secondary">Book this desk</button>                                                                          
                    </div>
                </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection