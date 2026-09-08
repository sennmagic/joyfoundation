@props(['value'])

@php
    $isCountable = (bool) preg_match('/^\d[\d,]*\+?$/', trim($value));

    if ($isCountable) {
        $numeric = (int) str_replace(',', '', rtrim(trim($value), '+'));
        $suffix = str_ends_with(trim($value), '+') ? '+' : '';
    }
@endphp

@if ($isCountable)
    <span data-count-to="{{ $numeric }}" data-count-suffix="{{ $suffix }}">{{ $value }}</span>
@else
    {{ $value }}
@endif
