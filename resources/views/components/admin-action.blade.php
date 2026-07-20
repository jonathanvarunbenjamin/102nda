@props(['action', 'field', 'value', 'label'])

<form method="POST" action="{{ $action }}">
    @csrf
    @method('patch')
    <input type="hidden" name="{{ $field }}" value="{{ $value }}">
    <button type="submit" {{ $attributes->merge(['class' => 'text-sm hover:underline']) }}>
        {{ $label }}
    </button>
</form>
