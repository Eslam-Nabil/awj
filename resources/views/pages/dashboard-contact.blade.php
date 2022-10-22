@extends('layouts.dashboard-default-template')
@section('page-title', 'Contact Us' )
@section('content')
<div class="card-body">
    <form action="{{ route('contact.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
       @include('layouts.page-main-part')
       
        <button type="submit">Submit</button>
    </form>
</div>
@endsection
