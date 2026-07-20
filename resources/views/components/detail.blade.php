@props(['label', 'value' => null])

@if (filled($value))
    <div {{ $attributes->merge(['class' => '']) }}>
        <dt class="text-gray-500">{{ $label }}</dt>
        <dd class="text-gray-900 font-medium whitespace-pre-line">{{ $value }}</dd>
    </div>
@endif
