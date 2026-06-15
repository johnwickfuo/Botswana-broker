@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('The platform is temporarily unavailable for scheduled maintenance. Please try again shortly.'))
