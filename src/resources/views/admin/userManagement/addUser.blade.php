@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">
                    {{ __('Create User') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{route('userStore')}}">
                        @csrf
                        {{method_field('POST')}}
                        <fieldset>
                        <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" maxlength="255" id="first_name"
                                    class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name"
                                    required>
                                @error('first_name')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" maxlength="255" id="last_name"
                                    class="form-control @error('last_name') is-invalid @enderror" placeholder="Last Name"
                                    required>
                                @error('last_name')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">User Email</label>
                                <input type="text" name="email" maxlength="255" id="email"
                                    class="form-control @error('email') is-invalid @enderror" placeholder="User Email"
                                    required>
                                @error('email')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">User Password</label>
                                <input type="text" name="password" minlength='8' maxlength="255" id="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="User Password"
                                    required>
                                @error('password')
                                <div class='alert alert-danger'>{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role_id" class="form-label">Role</label>
                                <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                    <option disabled selected value="">Select a role</option>
                                    @foreach ($roles as $role)
                                        @if($role->role_id != 1)
                                        <option value="{{$role->role_id}}">{{$role->role}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('role_id')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="faculty_id" class="form-label">Faculty</label>
                                <select name="faculty_id" id="faculty_id" class="form-select @error('faculty_id') is-invalid @enderror" required>
                                    <option disabled selected value="">Select a faculty</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{$faculty->faculty_id}}">{{$faculty->faculty}}</option>
                                    @endforeach
                                </select>
                                @error('faculty_id')
                                    <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="department_id" class="form-label">Department</label>
                                <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" disabled required>
                                    <option disabled selected hidden>Select a department</option>
                                </select>
                                @error('department_id')
                                    <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" id="is_admin" type="checkbox" value='TRUE' name="is_admin"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="is_admin">Is Admin</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" id="is_suspended" type="checkbox" value='TRUE' name="is_suspended"
                                        id="flexSwitchCheckDefault">
                                    <label class="form-check-label" for="is_suspended">Is Suspended</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-success float-end" role="button">Submit</button>
                                <a href="{{route('userManager')}}" class="mx-2 btn btn-secondary float-end" role="button">Cancel</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('js/alert.js') }}"></script>
<script type="application/javascript">
var departments = <?php echo json_encode($departments)?>;

$("#faculty_id").change( function() {
    $("#department_id").empty();

    var facultyId = parseInt($('#faculty_id').find(':selected').attr('value'));
    var filteredDepts = departments.filter(item => {
        return item.faculty_id === facultyId;
    });

    if(filteredDepts.length < 1) {
        if(!$('#department_id').is(':disabled')) {
            $('#department_id').prop('disabled', true);
        }
        $('#department_id').append($('<option disabled selected hidden>There are no departments for this faculty</option>'));
        
    } else {
        if($('#department_id').is(':disabled')) {
            $('#department_id').prop('disabled', false);
        }

        $('#department_id').append($('<option disabled selected hidden>Select a department</option>'));
            filteredDepts.forEach (department => $('#department_id').append($('<option/>').val(department.department_id).text(department.department)));
            
    }
});

$("#department_id").change( function() {
    var departmentId = parseInt($('#department_id').find(':selected').attr('value'));
    console.log("departmendID: ", departmentId);
})

$(window).on('load', function() {
    
    $('#faculty_id').append($('<option disabled selected hidden>Select a faculty</option>'));
    $('#department_id').prop("hidden", false);
    $('#department_id').prop("disabled", true);
    $('#department_id').append($('<option>Select a department</option>'));
    
})
</script>
@endsection