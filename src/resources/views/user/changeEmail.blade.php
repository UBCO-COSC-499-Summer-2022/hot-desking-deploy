@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-md-10 offset-2">
            <div class="panel panel-default">
                <h2>Change Email</h2>

                <div class="panel-body">
                    @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('changeEmailPost') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('new-email') ? ' has-error' : '' }}">
                            <label for="new-email" class="col-md-4 control-label">New Email</label>

                            <div class="col-md-6">
                                <input id="new-email" type="email" class="form-control @error('new-email') is-invalid @enderror" name="new-email" required>
                                @error('new-email')
                                    <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new-email-confirm" class="col-md-4 control-label">Confirm New Email</label>

                            <div class="col-md-6 mb-3">
                                <input id="new-email-confirm" type="email" class="form-control @error('new-email-confirm') is-invalid @enderror" name="new-email-confirm" required>
                                @error('new-email-confirm')
                                    <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Change Email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection