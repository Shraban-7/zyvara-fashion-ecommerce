@props(['disabled' => false, 'label' => null, 'name' => '', 'type' => 'text'])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            {{ $label }}
        </label>
    @endif
    <input 
        type="{{ $type }}" 
        id="{{ $name }}" 
        name="{{ $name }}"
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge([
            'class' => 'w-full px-4 py-2.5 rounded-lg
            bg-white dark:bg-gray-800
            border border-gray-200 dark:border-gray-700
            text-gray-900 dark:text-white
            placeholder-gray-400 dark:placeholder-gray-500
            transition duration-200 ease-in-out
            focus:outline-none
            focus:ring-2 focus:ring-blue-500
            disabled:opacity-50 disabled:cursor-not-allowed'
        ]) !!}
    >
    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>