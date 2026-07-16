@props(['disabled' => false, 'label' => null, 'name' => '', 'type' => 'text', 'id' => null])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-primary ">
            {{ $label }}
        </label>
    @endif
    <input 
        type="{{ $type }}" 
        id="{{ $id == null ? $name : $id }}" 
        name="{{ $name }}"
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge([
            'class' => 'w-full px-4 py-2.5 rounded-lg
            bg-surface-elevated
            border border-secondary-200
            text-primary
            placeholder-gray-400 
            transition duration-200 ease-in-out
            focus:outline-none
            focus:border-primary
            disabled:opacity-50 disabled:cursor-not-allowed'
        ]) !!}
    >
    @error($name)
        <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
    @enderror
</div>