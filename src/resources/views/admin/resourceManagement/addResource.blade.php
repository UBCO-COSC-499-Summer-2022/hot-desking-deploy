@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center h2">
                    Create Resource
                </div>
                <div class="card-body p-2">
                    <form action="{{route('resource.store')}}" method="POST">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                            <div class="row mb-3">
                                <label for="resource_type" class="form-label col-md-4 text-md-end">Resource</label>
                                <div class="col-md-6">
                                    <input type="text" maxlength="60" id="resource_type" class="form-control @error('resource_type') is-invalid @enderror" placeholder="Resource type" name="resource_type" required>
                                    @error('resource_type')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="icon" class="form-label col-md-4 text-md-end">Icon</label>
                                <div class="col-md-6">
                                    <input type="text" maxlength="255" id="icon" class="form-control @error('icon') is-invalid @enderror" placeholder="<i class='bi bi-123'></i>" name="icon" required>
                                    @error('icon')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <small>
                                        <a target="_blank" href="https://icons.getbootstrap.com/">Search for Icon Here</a>
                                    </small> 
                                </div>   
                                
                            </div>
                            <div class="row mb-3">
                                <label for="colour" class="form-label col-md-4 text-md-end">Color</label>
                                <div class="col-md-2">
                                    <input type="color" value="" id="colour" class="form-control form-control-color @error('colour') is-invalid @enderror" name="colour" required>
                                    @error('color')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <button type="submit" class="btn btn-success float-end">Submit</button>
                                    <a href="{{route('resourceManager')}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection