@extends('layouts.app')

@php $header = $title ?? 'Tags' @endphp
@section('title', $header)


@section('content')


<h1>{{ $header }}</h1>

@if ($tags->isEmpty())
<p>There are no tags yet.</p>
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
            @foreach ($tags as $tag)
            <tr>
                <th scope="row">{{ $tag->id }}</th>
                <td>
                @if (Gate::allows('manage-tag'))
                    <a href="{{ route('tags.edit', ['id' => $tag->id]) }}">{{ $tag->name }}</a>
                @else
                    {{ $tag->name }}
                @endif
                </td>
                <td>{{ $tag->created_at }}</td>
                <td>{{ $tag->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <nav class="d-flex justify-content-center" aria-label="Page navigation">
        {{ $tags->links() }}
    </nav>
</div>
@endif

@endsection
