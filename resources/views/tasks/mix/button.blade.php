@php
    $column = $columnName ?? $text;
    $isActualFilter = $filter['orderBy']['column'] === $column;
    if ($isActualFilter) {
        $order = $filter['orderBy']['value'];
    }
@endphp

<th scope="col" class="filter-btn{{ $isActualFilter ? ' filter-btn--pressed-'.$order : '' }}" data-column="{{ $column }}" data-order="{{ $order ?? 'asc' }}" role="button" aria-pressed="true">{{ $text }}</th>