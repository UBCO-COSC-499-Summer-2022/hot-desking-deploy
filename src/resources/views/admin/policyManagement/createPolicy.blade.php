@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    <h2 class="h2">{{ __('Create Policy') }}
                    </h2>
                </div>

                <div class="card-body">
                    <form>
                        <fieldset>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">Policy Title</label>
                                <input type="text" id="disabledTextInput" class="form-control" placeholder="Title">
                            </div>
                            <div class="mb-3">
                                <label for="disabledTextInput" class="form-label">Policy Description</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Policy Description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="disabledSelect" class="form-label">Policy Type</label>
                                <select id="disabledSelect" class="form-select">
                                    <option>Disabled select</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="disabledFieldsetCheck" disabled>
                                    <label class="form-check-label" for="disabledFieldsetCheck">
                                        Send Email Notification
                                    </label>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{route('policyManager')}}" class="btn btn-success" role="button">Save</a>
                            </div>
                        </fieldset>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection