<div class="card">
    <div class="card-body">
    @php
        $hasOnlyHidden = count($filter) === 1 && isset($filter['orderBy']);
    @endphp

        <button class="btn btn-outline-secondary" type="button" data-toggle="collapse" data-target="#filterForm" aria-expanded="{{ $hasOnlyHidden ? 'false' : 'true' }}" aria-controls="collapseExample">Filters</button>

        <form method="GET" action="{{ route('tasks.index') }}" id="filterForm" class="collapse{{ $hasOnlyHidden ? '' : ' show' }}" >
            @if (Auth::user())
            <div class="form-group row">
                <div class="col-md-6 offset-md-4">
                    <div class="ml-4">
                        <input type="checkbox" class="form-check-input" id="creator_id" name="creator_id" value="{{ Auth::id() }}" {{ Auth::id() == ($filter['creator_id']['value'] ?? '' ) ? 'checked' : '' }}>
                        <label class="form-check-label" for="creator_id">Only created by me</label>
                    </div>
                </div>
            </div>
            @endif

            <div class="form-group row">
                <label for="status_id" class="col-md-4 col-form-label text-md-right">Task Status</label>

                <div class="col-md-6">
                    <select class="custom-select form-control{{ $errors->has('status_id') ? ' is-invalid' : '' }}" name="status_id">
                    @if ($statuses->isEmpty())
                        <option disabled>There are no statuses created yet. You need to create at least one.</option>
                    @endif
                        <option selected></option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                    </select>

                    @if ($errors->has('status_id'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('status_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="assignedTo_id" class="col-md-4 col-form-label text-md-right">Assigned To</label>

                <div class="col-md-6">
                    <select class="custom-select form-control{{ $errors->has('assignedTo_id') ? ' is-invalid' : '' }}" name="assignedTo_id">
                        <option selected></option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"{{ $user->id == ($filter['assignedTo_id']['value'] ?? '' ) ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                    </select>

                    @if ($errors->has('assignedTo_id'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('assignedTo_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <input name="orderBy" value="{{ $filter['orderBy']['column'] }}:{{ $filter['orderBy']['value'] }}" type="hidden">

            <div class="form-group row">
                <label for="tags" class="col-md-4 col-form-label text-md-right">Tag</label>
                <div class="col-md-6">
                    <input id="tags" type="text" class="form-control{{ $errors->has('tags') ? ' is-invalid' : '' }}" name="tags" value="{{ old('tag') }}">
                </div>
            </div>

            <div class="form-group row mt-4">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-outline-secondary">Apply filters</button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-xs ml-5" role="button" aria-pressed="true">Reset</a>
                </div>
            </div>

        </form>


    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/filter.js') }}"></script>
@endpush
