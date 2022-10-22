@extends('layouts.dashboard-default-template')
@section('page-title', 'About' )
@section('content')
<div class="card-body">
    <form action="{{ route('about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
       @include('layouts.page-main-part')
        <button type="submit">Submit</button>
    </form>
</div>
@endsection
