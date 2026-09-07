@props(['segments' => []])

<p {{ $attributes }}>
    @foreach ($segments as $segment)
        @if ($segment['break'] ?? false)
            <br class="hidden xl:inline">
        @elseif ($segment['highlight'])
            <span class="text-primary">{{ $segment['text'] }}</span>
        @else
            {{ $segment['text'] }}
        @endif
    @endforeach
</p>