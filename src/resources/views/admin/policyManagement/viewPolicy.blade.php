@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    {{ __('Create Policy') }}
                </div>

                <div class="card-body">
                    <div id="view-policy-info">
                        <p>
                            Policy Name: Lorem Ipsum
                            <br>
                            Policy Description: Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            <br>
                            Policy Type: Lorem ipsum
                        </p>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{route('policyManager')}}" class="btn btn-primary" role="button">All Policies</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection