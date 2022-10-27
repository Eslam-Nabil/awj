@extends('layouts.dashboard-default-template')
@section('page-title', 'Home')
@section('content')
    <div class="card-body">
        <form action="{{ route('home.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('layouts.page-main-part')
            <button type="submit">Submit</button>
        </form>
    </div>
@endsection
