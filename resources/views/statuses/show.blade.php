@extends('layouts.app')

@section('content')
    <div class="container" style="height: 2000px">
        <status-list-item 
            :status="{{ json_encode($status) }}">
        </status-list-item>
    </div>
@endsection