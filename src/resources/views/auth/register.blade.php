@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="role_id" class="col-md-4 col-form-label text-md-end">Role</label>
                            <div class="col-md-6">
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
                        </div>

                        <div class="row mb-3">
                            <label for="role_id" class="col-md-4 col-form-label text-md-end">Faculty</label>
                            <div class="col-md-6">
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
                        </div>

                        <div class="row mb-3">
                            <label for="role_id" class="col-md-4 col-form-label text-md-end">Department</label>
                            <div class="col-md-6">
                                <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" disabled required>
                                    <option disabled selected hidden>Select a department</option>
                                </select>
                                @error('department_id')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">
var departments = <?php echo json_encode($departments)?>;
$('#faculty_id').change(function() {
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

</script>
@endsection
