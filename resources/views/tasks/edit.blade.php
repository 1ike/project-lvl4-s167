@extends('layouts.app')

@php $header = $title ?? 'Edit Task' @endphp
@section('title', $header)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">{{ $header }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.update', $task->id) }}">

                        @csrf

                        @method('PUT')

                        @include('tasks.mix.inputs')

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Save changes
                                </button>
                            </div>
                        </div>
                    </form>


                    <form method="POST" class="danger-form" action="{{ route('tasks.destroy', $task->id) }}">

                        @csrf

                        @method('DELETE')

                        <div class="form-group row mt-5 mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete task
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
