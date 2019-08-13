@extends('layouts.app')

@section('content')
        <profile-component v-bind:user="{{$user}}"></profile-component>
@endsection
