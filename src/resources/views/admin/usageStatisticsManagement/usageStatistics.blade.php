@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center h2">
                    {{ __('Usage Statistics') }}
                </div>
                <div class='card-body'>
                    <table class="table table-light ">
                        <thead>
                            <tr class="table-primary">
                                <th class="text-center">Type of Statistics</th>
                                <th>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-3"></div>
                                        Actions
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center align-middle">General Booking Statistics</td>
                                <td class='text-center'>
                                    <a href="{{route('viewBookingStatistics')}}" class="btn btn-info"><i class="bi bi-eye-fill text-white"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle">Resources Statistics</td>
                                <td class='text-center'>
                                    <a href="{{route('viewResourcesStatistics')}}" class="btn btn-info"><i class="bi bi-eye-fill text-white"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle">User Roles Statistics</td>
                                <td class='text-center'>
                                    <a href="{{route('viewRolesStatistics')}}" class="btn btn-info"><i class="bi bi-eye-fill text-white"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle">User Faculty Statistics</td>
                                <td class='text-center'>
                                    <a href="{{route('viewFacultyStatistics')}}" class="btn btn-info"><i class="bi bi-eye-fill text-white"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle">Booking Times Statistics</td>
                                <td class='text-center'>
                                    <a href="{{route('viewBookingTimeStatistics')}}" class="btn btn-info"><i class="bi bi-eye-fill text-white"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection