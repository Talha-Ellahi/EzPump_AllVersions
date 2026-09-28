@extends('layouts.app')
@section('content')
<div id="app">
    <div class="row">
        <all-reports />
        <all-reports  :data='@json($data)'></all-reports>
</div>
    
</div>
@endsection