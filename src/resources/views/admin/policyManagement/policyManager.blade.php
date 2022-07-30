@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <!-- Card Header -->
                <div class="card-header text-center h2">
                    {{__('Policy Manager')}}
                </div>
                <!-- End Card Header -->
                <!-- Card Body -->
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <div class="row">
                            <div class="col-md-8 d-md-flex justify-content-md-end">
                            </div>
                            <div class="col-md-4 d-md-flex justify-content-md-end">
                                <a href=" {{route('createPolicy')}}" role="button" class="btn btn-primary">
                                    Create Policy <i class="bi bi-plus-lg"></i>
                                </a>
                            </div>
                        </div>
                        <table class="table table-light table-hover">
                            <thead>
                                <tr class="table-primary">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Policy Name</th>
                                    <th class="text-center">Type</th>
                                    <th>
                                        <div class="row">
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4"></div>
                                            Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="text-center align-middle">1</th>
                                    <td class="text-center align-middle">Booking Window</td>
                                    <td class="text-center align-middle">Time</td>
                                    <td>
                                        <a role="button" class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                        <a href="{{route('editPolicy')}}" role="button" class="btn btn-secondary float-end mx-1">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="{{route('viewPolicy')}}" role="button" class="btn btn-info float-end">
                                            <i class="bi bi-eye-fill text-white p-0"></i>
                                        </a>
                                    </td>
                                </tr>
                                <!-- Delete Modal Start -->
                                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete Modal</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    This Policy is about to be permanently deleted. <br>
                                                    Click Delete to Confirm <br>
                                                    Click Cancel to go back
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-danger">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete Modal End -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- End Card Body -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/test.js') }}"></script>

@endsection