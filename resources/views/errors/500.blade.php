@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('An unexpected error has occurred. Please refresh the page, and contact the platform administrator if the problem persists.'))
