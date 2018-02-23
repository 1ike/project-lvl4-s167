@extends('layouts.app')

@php $header = $title ?? 'Tasks' @endphp
@section('title', $header)


@section('content')


<h1>{{ $header }}</h1>

@if ($tasks->isEmpty())
<p>There are no tasks yet.</p>
@else
<div class="d-inline-block">
    <table class="table table-responsive mt-4 mb-5">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">name</th>
                <th scope="col">description</th>
                <th scope="col">status</th>
                <th scope="col">creator</th>
                <th scope="col">assignedTo</th>
                <th scope="col">created_at</th>
                <th scope="col">updated_at</th>
            </tr>
        </thead>
        <tbody class="table-striped">
            @foreach ($tasks as $task)
            <tr>
                <th scope="row">{{ $task->id }}</th>
                <td>
                @if (Auth::user())
                    <a href="{{ route('tasks.edit', ['id' => $task->id]) }}">{{ $task->name }}</a>
                @else
                    {{ $task->name }}
                @endif
                </td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->status->name ?? ''  }}</td>
                <td>{{ $task->creator->name ?? ''  }}</td>
                <td>{{ $task->assignedTo->name ?? '' }}</td>
                <td>{{ $task->created_at }}</td>
                <td>{{ $task->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <nav class="d-flex justify-content-center" aria-label="Page navigation">
        {{ $tasks->links() }}
    </nav>
</div>
@endif
<br>
@if (Auth::user())
<a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Create task</a>
@endif

@endsection
