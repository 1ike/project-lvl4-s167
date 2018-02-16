@extends('layouts.app')

@section('title', $title)


@section('content')


<h1>{{ $title }}</h1>

@if ($users->isEmpty())
<p>There are no users yet.</p>
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
            @foreach ($users as $user)
            <tr>
                <th scope="row">{{ $user->id }}</th>
                <td>
                @if (Auth::user() && Gate::allows('edit-user', $user))
                    <a href="{{ route('users.edit', ['id' => $user->id]) }}">{{ $user->name }}</a>
                @else
                    {{ $user->name }}
                @endif
                </td>
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <nav class="d-flex justify-content-center" aria-label="Page navigation">
        {{ $users->links() }}
    </nav>
</div>
@endif

@endsection
