@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
            <div class="card-header">
                    {{ __('Profile') }}
            </div>
            <div class="card-body">
                @if(Session::has('message'))
                    <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show">{{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{route('profileUpdate')}}" method="POST">
                    @csrf
                <table class="table table-bordered table-hover align-middle mb-3">
                    <tbody>
                        <tr>
                            <th colspan="2" >First Name: </th>
                            <td colspan="2"><input min="1" max="255" type="text" value="{{$user->first_name}}" name="first_name" class="form-control @error('first_name') is-invalid @enderror" required></td>
                            @error('first_name')
                                <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </tr>
                        <tr>
                            <th colspan="2" >Last Name:</th>
                            <td colspan="2"><input min="1" max="255" type="text" value="{{$user->last_name}}" name="last_name" class="form-control @error('last_name') is-invalid @enderror" required></td>
                            @error('last_name')
                                <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </tr>
                        <tr>
                            <th colspan="2" >User Role:</th>
                            <td colspan="2">
                                <select onchange="checkGraduate()" name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                <option value="{{$user->role_id}}">{{$user->role->role}}</option>
                                    @foreach ($roles as $role)
                                        @if ($role->role_id != $user->role_id)
                                            @if ($role->role_id != 1)
                                                <option value="{{$role->role_id}}">{{$role->role}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>    
                                @error('role_id')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                        </tr>
                    @if ($user->role_id==4|$user->role_id==5)       
                        <tr id="supervisorRow">
                            <th colspan="2">User Supervisor:</th>
                                @if ($user->role_id==4)
                                    <td colspan="2"><input id="supervisor" min="1" max="255" type="text" value="{{$user->supervisor}}" name="supervisor"  class="form-control @error('last_name') is-invalid @enderror" required></td>
                                @else
                                    <td colspan="2"><input id="supervisor" min="1" max="255" type="text" value="{{$user->supervisor}}" name="supervisor"  class="form-control @error('last_name') is-invalid @enderror"></td>
                                @endif
                            @error('supervisor')
                                <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </tr>
                    @else
                        <tr id="supervisorRow" style="display:none;">
                            <th colspan="2">User Supervisor:</th>
                                @if ($user->role_id==4)
                                    <td colspan="2"><input id="supervisor" min="1" max="255" type="text" value="{{$user->supervisor}}" name="supervisor"  class="form-control @error('last_name') is-invalid @enderror" required></td>
                                @else
                                    <td colspan="2"><input id="supervisor" min="1" max="255" type="text" value="{{$user->supervisor}}" name="supervisor"  class="form-control @error('last_name') is-invalid @enderror"></td>
                                @endif
                            @error('supervisor')
                                <div class="alert alert-danger">{{$message}}</div>
                            @enderror
                        </tr>
                    @endif
                    </tbody>
                </table>
                <div class="mb-3">
                    <button type="submit" class="btn btn-success float-end mb-3">Submit</button>
                </div>
                </form>
                <table class="table table-bordered table-hover align-middle mt-3">
                    <tbody>
                        <tr>
                            <th colspan="2" >Email Address:</th>
                            <td colspan="2">{{Auth::user()->email}}</td>
                            <td><a href="{{ route('changeEmailGet') }}"><button type="button" class="btn btn-secondary float-end">Change Email</button></a></td>  
                        </tr>
                        <tr>
                            <!-- <th colspan="2" >Password:</th> -->
                            <!-- <a href="{{ route('changePasswordGet') }}"><button type="button" class="btn btn-secondary">Change Password</button></a> -->
                            <th colspan="2" >Password:</th>
                            <td>********</td>
                            <td colspan="2"><a href="{{ route('changePasswordGet') }}"><button type="button" class="btn btn-secondary float-end">Change Password</button></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function checkGraduate(){
        if ($('#role_id').val()==='4'|$('#role_id').val()==='5'){
            //if role equal to graduate, display supervisor text
            if ($('#role_id').val()==='4'){
                $('#supervisor').prop('required',true)
            }else{
                $('#supervisor').removeAttr('required')
            }
            $('#supervisorRow').show()
        }
        else{
            //if roles not equal to graduate, check if supervisor text is visible and hide it if true
            $('#supervisor').removeAttr('required')
            $('#supervisorRow').hide()
            
        }
    }
</script>
@endsection
