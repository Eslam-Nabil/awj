@extends('layouts.dashboard-default-template')

@section('page-title', 'Home' )
@section('content')
<div class="card-body">
    <form action="{{ route('home.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="inputText1" class="col-form-label">Page Name</label>
            <input id="inputText1" type="text" value="{{ $homedata->page_name }}" name="page_name" class="form-control">
        </div>
        <h2>Page First Section</h2>
        <div class="form-group">
            <label for="inputText2" class="col-form-label">Section title</label>
            <input id="inputText2" type="text" value="{{ $homedata->first_section_title }}" name="first_section_title" class="form-control">
        </div>
        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input" name="first_side_image" id="customFile">
            <label class="custom-file-label" value="" for="customFile">Side Image</label>
        </div>
        <h2>Page Third Section</h2>
        <div class="form-group">
            <label for="inputText1" class="col-form-label">Section Title</label>
            <input id="inputText1" value="{{ $homedata->third_section_title }}" type="text" name="third_section_title" class="form-control">
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Left Description</label>
            <textarea class="form-control" value="{{ $homedata->third_left_description }}" name="third_left_description" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Right Description</label>
            <textarea class="form-control" value="{{ $homedata->third_right_description }}" name="third_right_description" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
        <div class="custom-file mb-3">
            <input type="file" class="custom-file-input" name="third_side_image" id="customFile">
            <label class="custom-file-label" for="customFile">Side Image</label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
@endsection
