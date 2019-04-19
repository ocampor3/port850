@extends('layouts.master')

@section('title')
    Appzmate | Home
@endsection

{{--META TAGS--}}
@section('meta-url')
	{{Request::url()}}
@endsection

@section('meta-title')
	Home
@endsection
