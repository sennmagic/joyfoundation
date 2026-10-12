@props(['contact' => []])

@php
    $values = [];
    foreach (['email', 'phone', 'address', 'hours', 'map_url'] as $key) {
        $values[$key] = is_string($contact[$key] ?? null) ? trim($contact[$key]) : '';
    }
    $email = filter_var($values['email'], FILTER_VALIDATE_EMAIL) ? $values['email'] : '';
    $phone = preg_match('/^\+?[0-9][0-9 ().-]*[0-9]$/', $values['phone']) ? $values['phone'] : '';
    $map = filter_var($values['map_url'], FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $values['map_url']) ? $values['map_url'] : '';
    $details = array_filter([
        'Email' => $email,
        'Phone' => $phone,
        'Address' => $values['address'],
        'Office hours' => $values['hours'],
        'Directions' => $map ? 'Get directions' : '',
    ], fn (string $value): bool => $value !== '');
@endphp

@if ($details)
    <dl {{ $attributes->class(['grid gap-3']) }}>
        @foreach ($details as $label => $value)
            @php
                $href = match ($label) {
                    'Email' => 'mailto:'.str_replace('%40', '@', rawurlencode($email)),
                    'Phone' => 'tel:'.preg_replace('/[^0-9+]/', '', $phone),
                    'Directions' => $map,
                    default => '',
                };
                $icon = match ($label) {
                    'Email' => 'envelope',
                    'Phone' => 'phone',
                    'Address' => 'map-pin',
                    'Office hours' => 'clock',
                    'Directions' => 'map',
                };
            @endphp
            <div class="min-w-0">
                <dt class="sr-only">{{ $label }}</dt>
                <dd class="flex items-start gap-4 text-base text-body">
                    @svg('heroicon-o-'.$icon, 'mt-2.5 size-6 shrink-0 text-primary', ['aria-hidden' => 'true', 'focusable' => 'false', 'data-contact-icon' => $icon])
                    <div class="min-w-0 flex-1 break-words [overflow-wrap:anywhere]">
                        @if ($href !== '')
                            <a href="{{ $href }}" class="block min-h-11 rounded-sm py-2 font-medium decoration-primary underline-offset-4 hover:text-navy hover:underline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary-ink">{{ $value }}</a>
                        @else
                            <span class="block min-h-11 py-2 whitespace-pre-line">{{ $value }}</span>
                        @endif
                    </div>
                </dd>
            </div>
        @endforeach
    </dl>
@else
    <p {{ $attributes->class(['text-sm leading-relaxed text-muted']) }}>Contact details will be available here soon.</p>
@endif
