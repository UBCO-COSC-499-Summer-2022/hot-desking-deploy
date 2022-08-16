@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center h2">
                    Email Logs
                </div>
                <div class="card-body p-2">
                    @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col text-center">
                            <p>
                                Click "Download Logs" to download a csv file of emails that have been logged 
                                when actions have affected users such as deleting workspaces or suspending users 
                            </p>
                            <a href="{{route('downloadLogs')}}" class="btn btn-primary" >Download Email Logs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
@endsection