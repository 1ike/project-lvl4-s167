@extends('layouts.app')

@php $header = $title ?? 'Create Task' @endphp
@section('title', $header)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">{{ $header }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.store') }}">

                        @csrf

                        @include('tasks.inputs')

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary mt-2">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
