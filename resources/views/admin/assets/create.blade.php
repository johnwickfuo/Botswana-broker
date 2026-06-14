<?php
if (Auth('admin')->User()->dashboard_style == 'light') {
    $text = 'dark';
} else {
    $text = 'light';
}
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">

                <p>
                    <a href="{{ route('admin.assets.index') }}">
                        <i class="p-2 rounded-lg fa fa-arrow-circle-left fa-2x bg-light"></i>
                    </a>
                </p>

                <div class="mt-2 mb-4">
                    <h1 class="title1 text-{{ $text }}">Add Asset</h1>
                </div>

                @include('admin.assets.partials.flash')

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.assets.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @include('admin.assets.partials.form', ['asset' => null])

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Save Asset
                                </button>
                                <a href="{{ route('admin.assets.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
