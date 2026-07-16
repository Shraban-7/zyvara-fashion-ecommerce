@props(['label' => null, 'name' => ''])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-primary dark:text-surface-elevated">
            {{ $label }}
        </label>
    @endif
    <select 
        id="{{ $name }}" 
        name="{{ $name }}"
        {!! $attributes->merge(['class' => 'bg-secondary-50 border border-secondary-300 text-primary text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5 dark:bg-secondary-700 dark:border-secondary-600 dark:text-surface-elevated']) !!}
    >
        {{ $slot }}
    </select>
</div>