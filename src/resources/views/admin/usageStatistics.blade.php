@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header text-center h2">
                    {{ __('Usage Statistics') }}
                </div>

                <div class="card-body">
                    <nav id="stats-views">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-users-tab" data-bs-toggle="tab" data-bs-target="#nav-users" type="button" role="tab" aria-controls="nav-users" aria-selected="true">By Users</button>
                            <button class="nav-link" id="nav-roles-tab" data-bs-toggle="tab" data-bs-target="#nav-roles" type="button" role="tab" aria-controls="nav-roles" aria-selected="false">By Roles</button>
                            <button class="nav-link" id="nav-departments-tab" data-bs-toggle="tab" data-bs-target="#nav-departments" type="button" role="tab" aria-controls="nav-departments" aria-selected="true">By Departments</button>
                            <button class="nav-link" id="nav-resources-tab" data-bs-toggle="tab" data-bs-target="#nav-resources" type="button" role="tab" aria-controls="nav-resources" aria-selected="false">By Resources</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">
                            <p>This is where user statistics are displayed</p>
                            <table class="table table-light">
                                <thead>
                                    <tr class="table-primary">
                                        <th scope="col">#</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Reports</th>
                                        <!-- <th scope="col">Role</th>
                                        <th scope="col">Department</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="vertical-align:middle;" scope="row">1</th>
                                        <td style="vertical-align:middle;">Bob Dylan</td>
                                        <td>
                                            <button id="show-user-stat" type="button" class="btn btn-info" onclick="myFunction()">View Statistic</button>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="vertical-align:middle;" scope="row">2</th>
                                        <td style="vertical-align:middle;">Resource Misuse</td>
                                        <td>
                                            <button type="button" class="btn btn-info">View Statistic</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="user-stats" style="display: none;">
                                <img id="user-stats-img" src="{{ asset('img/user-stats-dummy.png') }}" class="rounded mx-auto d-block w-100">

                            </div>
                        </div>

                        <div class=" tab-pane fade" id="nav-roles" role="tabpanel" aria-labelledby="nav-roles-tab">
                            <p>This is where role statistics are displayed</p>
                        </div>
                        <div class="tab-pane fade" id="nav-departments" role="tabpanel" aria-labelledby="nav-departments-tab">
                            <p>This is where departments statistics are displayed</p>
                        </div>
                        <div class="tab-pane fade" id="nav-resources" role="tabpanel" aria-labelledby="nav-resources-tab">
                            <p>This is where resources statistics are displayed</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function myFunction() {
        var x = document.getElementById("user-stats");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>