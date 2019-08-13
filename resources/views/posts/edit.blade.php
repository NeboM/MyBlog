@extends('layouts.app')

@section('content')
    <edit-post-component v-bind:post="{{$post}}"></edit-post-component>
@endsection
