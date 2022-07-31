@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center h2">
                        {{ __('Resource Manager') }}
                </div>
                <div class="card-body p-2">
                @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                    <div class="row">
                        <div class="col">
                            <a href="{{route('addResource')}} " class="btn btn-primary float-end mb-2">Create Resource <i class="bi bi-plus-lg"></i></a>
                        </div>
                    </div>
                    @if(count($resources) < 1)
                        <div class="alert alert-warning wizard">
                            <i class="bi bi-exclamation-circle-fill"></i> There are no resources to display.                    
                        </div>
                    @else
                        <table class="table table-light">
                            <thead>
                                <tr class="table-primary">
                                    <th>Resource ID</th>
                                    <th>Resource Type</th>
                                    <th>Icon</th>
                                    <th>
                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-2"></div>
                                            Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resources as $resource)
                                <tr>
                                    <td class="align-middle">{{$resource->resource_id}}</td>
                                    <td class="align-middle">{{$resource->resource_type}}</td>
                                    <td class="align-middle h4" style="color: {{$resource->colour}};">{!! $resource->icon !!}</td>
                                    <td>
                                        <a role="button" class="btn btn-danger float-end mx-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{$resource->resource_id}}">
                                                <i class="bi bi-trash3-fill"></i>
                                        </a>
                                        <a href="{{route('editResource', $resource->resource_id)}}" role="button" class="btn btn-secondary float-end">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>
                                </tr>
                                <!-- Delete Modal Start -->
                                <div class="modal fade" id="deleteModal{{$resource->resource_id}}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Delete Resource</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    This Resource is about to be permanently deleted. <br>
                                                    Click Delete to Confirm <br>
                                                    Click Cancel to go back
                                                </p>
                                            </div>
                                            <form action="{{route('resource.destroy', $resource->resource_id)}}" method="POST">
                                                @csrf
                                                {{method_field('DELETE')}}
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete Modal End-->
                                @endforeach
                            </tbody>
                        </table>
                        {{$resources->links()}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
