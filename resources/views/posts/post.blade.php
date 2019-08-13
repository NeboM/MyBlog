@extends('layouts.app')

@section('content')

    <post-component v-bind:post="{{$post}}" class="max"></post-component>

@endsection
