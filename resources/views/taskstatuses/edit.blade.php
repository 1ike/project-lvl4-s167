@extends('layouts.app')

@php $header = $title ?? 'Edit Task Status' @endphp
@section('title', $header)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">{{ $header }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('taskstatuses.update', $taskstatus->id) }}">

                        @csrf

                        @method('PUT')

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') ?: $taskstatus->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Save changes
                                </button>
                            </div>
                        </div>
                    </form>


                    <form method="POST" class="danger-form" action="{{ route('taskstatuses.destroy', $taskstatus->id) }}">

                        @csrf

                        @method('DELETE')

                        <div class="form-group row mt-5 mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete task status
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
