@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('Info for your Bookings') }}
                </div>

                <div class="card-body">
                <table class="table  table-primary table-bordered table-hover align-middle">
                    <tbody>
                        <tr>
                            <th colspan="2" >Bookings Status: </th>
                            <td colspan="2">Confirmed</td>  
                        </tr>
                        <tr>
                            <th colspan="2" >Campus:</th>
                            <td colspan="2">{{$booking->room->floor->building->campus->name}}</td>  
                        </tr>
                        <tr>
                            <th colspan="2" >Building:</th>
                            <td colspan="2">{{$booking->room->floor->building->name}}</td>  
                        </tr>
                        <tr>
                            <th colspan="2" >Floor:</th>
                            <td colspan="2">{{$booking->room->floor->floor_num}}</td>  
                        </tr>
                        <tr>
                            <th colspan="2" >Room:</th>
                            <td colspan="2">{{$booking->room->name}}</td>  
                        </tr>
                        <tr>
                            <th colspan="2" >Desk:</th>
                            <td colspan="2">{{$booking->id}}</td>  
                        </tr>
                        <tr>
                            <th colspan="2" >Time:</th>
                            <td colspan="2">{{date('Y, F d',strtotime($booking->pivot->book_time_start))}}: {{date('g:ia',strtotime($booking->pivot->book_time_start))}} - {{date('g:ia',strtotime($booking->pivot->book_time_end))}}</td>  
                        </tr>
                    </tbody>
                </table>

                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            <div>
                            Note: 
                                    <li> If the room is locked, please contact non-emergency Campus Security 250-807-9236 </li>
                                    <li>If the room is damaged, please contact Facilities 250-807-9272.</li>
                            </div>
                        </div>
                <hr/>
                <a href="{{ route('bookings') }}" type="button" class="btn btn-primary">Previous Page</a>
                </div>

                

            </div>
        </div>
    </div>
</div>


@endsection