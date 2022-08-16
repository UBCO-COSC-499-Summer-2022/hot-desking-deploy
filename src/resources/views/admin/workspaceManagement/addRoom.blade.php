@extends('layouts.app')

@section('content')

<script>
    var rowsCount = 0;
    window.onload = function(){ 
        verifyCSS();
    }

    function verifyCSS() {
        if (rowsCount == 0) {
            $('#resourcesList').hide();
            $('#resourcesWarning').show();
        } else {
            $('#resourcesWarning').hide();
            $('#resourcesList').show();
        }
    }

    function addNewResource() {
        rowsCount += 1;
        $('#addResourcesBody').append(` 
            <tr>
                <td class="col-md-3">
                    <select name="resource_ids[]" class="form-select text-md-start">
                        <option disabled selected value="NULL">Please Select a Resource</option>
                        @foreach ($resources as $eachResource)
                            <option value="{{$eachResource->resource_id}}">{{$eachResource->resource_type}}</option>
                        @endforeach
                    </select>
                </td>
                <td class="col-md-6">
                    <input type="text" name="descriptions[]" class="form-control" placeholder="(Optional) Enter a description" maxlength="255" minlength="0">
                </td>
                <td class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger" onclick="deleteResource(this)"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        `);
        verifyCSS();
    }

    function deleteResource(submitter) {
        rowsCount -= 1;
        $(submitter).parents('tr')[0].remove();
        verifyCSS();
    }
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">
                    {{ __('Create Room') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('roomStore')}}" enctype="multipart/form-data">
                        @csrf
                        <fieldset>
                            <div class="mb-3">
                                <label for="name" class="form-label">Room Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Eg. UNC 111" maxlength="30" minlength="1" required>
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="occupancy" class="form-label">Maximum Occupancy</label>
                                <input type="number" min="0" max="9999" id="occupancy" class="form-control @error('occupancy') is-invalid @enderror" value="0" name="occupancy" required>
                                @error('occupancy')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value='FALSE' name="is_closed" id="is_closed">
                                    <label class="form-check-label" for="is_closed">Room Available</label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <button onclick="addNewResource()" type="button" id="addResource" class="btn btn-primary float-start">Add Resource +</button>
                                </div>
                            </div>
                            <div class="alert alert-warning wizard align-items-center" id="resourcesWarning" style="text-align:justify vert">
                                <div class="row">
                                    <div class="col col-md-1 text-center align-middle">
                                        <i class="bi bi-exclamation-circle-fill fs-2"></i>
                                    </div>
                                    <div class="col col-md-11">
                                        This Room has no resources set. If you would like to add a resource then you can select them using the <button disabled type="button" class="btn btn-primary">Add Resource +</button> button
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mb-3" id="resourcesList">
                                <table id="addResourcesTbl" class="table table-light table-borderless">
                                    <thead class="table-primary">
                                        <th class="p-2">Resource Name</th>
                                        <th class="p-2">Optional Description</th>
                                        <th class="text-center p-2">Actions</th>
                                    </thead>
                                    <tbody id="addResourcesBody">
                                    </tbody>
                                </table>
                            </div>
                            <div class=" mb-3">
                                <input name='floor_id' value='{{$floor_id}}' type='hidden' class='form-check-input'>
                                <button type="submit" class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('roomManager',$floor_id)}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection