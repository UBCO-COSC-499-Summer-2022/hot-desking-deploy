@extends('layouts.app')

@section('content')

<script>
    var rowsCount = <?php echo json_encode($room->roles->count())?>;
    window.onload = function(){ 
        verifyCSS();
    }

    function verifyCSS() {
        if (rowsCount == 0) {
            $('#rolesList').hide();
            $('#rolesWarning').show();
        } else {
            $('#rolesWarning').empty();
            $('#rolesWarning').append(`
                <div class="row">
                    <div class="col col-md-1 text-center align-middle">
                        <i class="bi bi-exclamation-circle-fill fs-2"></i>
                    </div>
                    <div class="col col-md-11">
                        Add specific roles to this room using the <button disabled type="button" class="btn btn-primary">Add Restricted Role +</button> button.
                        <br>Click the <button disabled type="button" class="btn btn-success">Submit</button> button to save your changes.
                    </div>
                </div>`
            );
            $('#rolesList').show();
        }
    }

    function addNewRole() {
        rowsCount += 1;
        $('#addRolesBody').append(` 
            <tr>
                <td>
                    <select name="role_ids[]" class="form-select text-md-start">
                        <option disabled selected value="NULL">Please Select a Role</option>
                        @foreach ($roles as $eachRole)
                            @if($eachRole->role_id != 1)
                            <option value="{{$eachRole->role_id}}">{{$eachRole->role}}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
                <td class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger" onclick="deleteRole(this)"><i class="bi bi-trash3-fill"></i></button>
                </td>
            </tr>
        `);
        verifyCSS();
    }

    function deleteRole(submitter) {
        rowsCount -= 1;
        $(submitter).parents('tr')[0].remove();
        verifyCSS();
    }
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">
                    Edit Role Restrictions For: {{ $room->name }}
                </div>

                <div class="card-body">
                    <form action="{{ route('editRestrictionsPolicy', $room->id) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col">
                                <button onclick="addNewRole()" type="button" id="addRole" class="btn btn-primary float-start">Add Restricted Role +</button>
                            </div>
                        </div>
                        <div class="alert alert-warning wizard align-items-center" id="rolesWarning" style="text-align:justify vert">
                            <div class="row">
                                <div class="col col-md-1 text-center align-middle">
                                    <i class="bi bi-exclamation-circle-fill fs-2"></i>
                                </div>
                                <div class="col col-md-11">
                                    This Room is available to all users. <br>To specify room access by roles, add roles using the <button disabled type="button" class="btn btn-primary">Add Restricted Role +</button> button.
                                    <br>Click the <button disabled type="button" class="btn btn-success">Submit</button> button to save your changes.
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mb-3" id="rolesList">
                            <table id="addRolesTbl" class="table table-light table-borderless">
                                <thead class="table-primary">
                                    <th class="p-2">Role Name</th>
                                    <th class="text-center p-2">Actions</th>
                                </thead>
                                <tbody id="addRolesBody">
                                    @if ($room->roles->count() > 0)
                                        @foreach ($room->roles as $index => $role)
                                            <tr>
                                                <td>
                                                    <select name="role_ids[]" class="form-select text-md-start">
                                                        <option value="{{$role->role_id}}">{{$role->role}}</option>
                                                        @foreach ($roles as $eachRole)
                                                            @if ($role->role_id != $eachRole->role_id)
                                                                @if($eachRole->role_id != 1)
                                                                <option value="{{$eachRole->role_id}}">{{$eachRole->role}}</option>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="col-md-1 text-center">
                                                    <button type="button" class="btn btn-danger" onclick="deleteRole(this)"><i class="bi bi-trash3-fill"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success float-end" role="button">Submit</button>
                    </form>
                            <form action="{{ route('cancelRestrictionsPolicy', $room->id) }}">
                                <button type="submit" class="mx-2 btn btn-secondary float-end" role="button">Cancel</button>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection