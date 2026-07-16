@props(['name', 'label' => null, 'rows' => 3, 'placeholder' => ''])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-secondary-700 mb-1">{{ $label }}</label>
    @endif
    
    <textarea 
        id="{{ $name }}" 
        name="{{ $name }}" 
        rows="{{ $rows }}" 
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 rounded-lg
            bg-surface-elevated 
            border border-secondary-200 
            text-primary 
            placeholder-gray-400
            transition duration-200 ease-in-out
            focus:outline-none
            focus:border-primary
            disabled:opacity-50 disabled:cursor-not-allowed']) }}
    >{{ $slot }}</textarea>

    @error($name)
        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
    @enderror
</div>