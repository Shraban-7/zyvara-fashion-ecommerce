@props(['name', 'label', 'value', 'selected' => null])

<div class="flex items-center">
    <input 
        id="radio_{{ $name . '_' . $value }}" 
        name="{{ $name }}" 
        type="radio" 
        value="{{ $value }}"
        {{ $value == $selected ? 'checked' : '' }}
        {{ $attributes->merge(['class' => 'h-4 w-4 text-primary border-secondary-300 focus:ring-primary transition duration-150']) }}
    >
    <label for="radio_{{ $name . '_' . $value }}" class="ml-3 block text-sm font-medium text-secondary-700 cursor-pointer">
        {{ $label }}
    </label>
</div>