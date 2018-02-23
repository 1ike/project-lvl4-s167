@extends('layouts.app')

@php $header = $title ?? 'Task Statuses' @endphp
@section('title', $header)


@section('content')


<h1>{{ $header }}</h1>

@if ($taskstatuses->isEmpty())
<p>There are no task statuses yet.</p>
@else
<div class="d-inline-block">
    <table class="table table-responsive mt-4 mb-5">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">name</th>
                <th scope="col">created_at</th>
                <th scope="col">updated_at</th>
            </tr>
        </thead>
        <tbody class="table-striped">
            @foreach ($taskstatuses as $taskstatus)
            <tr>
                <th scope="row">{{ $taskstatus->id }}</th>
                <td>
                @if (Gate::allows('manage-taskstatus'))
                    <a href="{{ route('taskstatuses.edit', ['id' => $taskstatus->id]) }}">{{ $taskstatus->name }}</a>
                @else
                    {{ $taskstatus->name }}
                @endif
                </td>
                <td>{{ $taskstatus->created_at }}</td>
                <td>{{ $taskstatus->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <nav class="d-flex justify-content-center" aria-label="Page navigation">
        {{ $taskstatuses->links() }}
    </nav>
</div>
@endif
<br>
@if (Gate::allows('manage-taskstatus'))
<a href="{{ route('taskstatuses.create') }}" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Create status</a>
@endif

@endsection
