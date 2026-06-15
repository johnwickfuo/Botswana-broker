@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('You are not authorised to access this resource. Please sign in with valid credentials to continue.'))
