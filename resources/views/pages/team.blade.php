@extends('layouts.dashboard-default-template')
@section('page-title','team')
@section('content')
<button id="addfield" type="button" class="btn btn-success">Add Team member</button>
        <div class="modal-body">
            <form method="POST" action="">
                @csrf
                <div class="row" id="fields">

                    <div class="col-xl-3 form-group">
                        <label for="exampleInputEmail1">Member Name</label>
                        <input type="text" name="member_name0" class="form-control" id="member_name0" placeholder="Member name">
                    </div>
                    <div class="col-xl-3 form-group">
                        <label for="exampleInputEmail1">Job title</label>
                        <input type="text" name="job_title0" class="form-control" id="job_title0" placeholder="Job title">
                    </div>
                    <div class="col-xl-3 form-group">
                        <label for="exampleInputEmail1">Picture</label>
                        <input type="file" name="picture0" class="form-control" id="picture0" placeholder="Picture">
                    </div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Delete</button>

                </div>


            </form>
        </div>
  <div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Basic Table</h5>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Picture</th>
                            <th scope="col">Name</th>
                            <th scope="col">Job title</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td> <img src="assets/images/product-pic.jpg" alt="user" class="rounded" width="100"></td>
                            <td></td>
                            <td>Otto</td>
                        </tr>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
  @endsection
