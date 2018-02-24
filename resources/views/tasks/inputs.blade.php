<div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

    <div class="col-md-6">
        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') ?: $task->name }}" required autofocus>

        @if ($errors->has('name'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>

    <div class="col-md-6">
        <input id="description" type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" value="{{ old('description') ?: $task->description  }}">

        @if ($errors->has('description'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label for="status_id" class="col-md-4 col-form-label text-md-right">Task Status</label>

    <div class="col-md-6">
        <select class="custom-select form-control{{ $errors->has('status_id') ? ' is-invalid' : '' }}" name="status_id" required>
        @if ($statuses->isEmpty())
            <option disabled>There are no statuses created yet. You need to create at least one.</option>
        @endif
        @foreach ($statuses as $status)
            <option value="{{ $status->id }}"{{ $status->id === ($task->status->id ?? '' ) ? 'selected' : '' }}>{{ $status->name }}</option>
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
    <label for="assignedTo_id" class="col-md-4 col-form-label text-md-right">Assigned To </label>

    <div class="col-md-6">
        <select class="custom-select form-control{{ $errors->has('assignedTo_id') ? ' is-invalid' : '' }}" name="assignedTo_id">
        @foreach ($users as $user)
            <option value="{{ $user->id }}"{{ $user->id === ($task->assignedTo->id ?? '' ) ? 'selected' : '' }}>{{ $user->name }}</option>
        @endforeach
        </select>

        @if ($errors->has('assignedTo_id'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('assignedTo_id') }}</strong>
            </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label for="tags" class="col-md-4 col-form-label text-md-right">Tags</label>

    <div class="col-md-6">
        <input id="tags" type="text" class="form-control{{ $errors->has('tags') ? ' is-invalid' : '' }}" name="tags" value="{{ old('tags') ?: tagsList($task) }}">

        @if ($errors->has('tags'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('tags') }}</strong>
            </span>
        @endif
    </div>
</div>