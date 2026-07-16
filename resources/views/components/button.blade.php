@props([
    'variant' => 'primary',
    'type' => 'button',
    'size' => 'md',
    'pill' => false,
    'outline' => false,
])

<?php
    $baseClasses = "inline-flex items-center justify-center font-medium transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2";

    // Size Definitions
    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm leading-4',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base',
        'xl' => 'px-6 py-3 text-base',
    ];

    // Solid Variants
    $solidColors = [
        'primary'   => 'bg-primary text-surface-elevated hover:bg-primary focus:ring-primary border-transparent',
        'secondary' => 'bg-secondary text-surface-elevated hover:bg-secondary focus:ring-secondary border-transparent',
        'danger'    => 'bg-danger-600 text-surface-elevated hover:bg-danger-700 focus:ring-danger border-transparent',
        'success'   => 'bg-success-600 text-surface-elevated hover:bg-success-700 focus:ring-success border-transparent',
        'warning'   => 'bg-warning-500 text-surface-elevated hover:bg-warning-600 focus:ring-warning border-transparent',
        'info'      => 'bg-accent-500 text-surface-elevated hover:bg-accent-600 focus:ring-accent border-transparent',
        'dark'      => 'bg-primary text-surface-elevated hover:bg-primary focus:ring-primary border-transparent',
    ];

    // Outline Variants
    $outlineColors = [
        'primary'   => 'border-primary text-primary hover:bg-primary focus:ring-primary',
        'secondary' => 'border-secondary text-secondary hover:bg-secondary focus:ring-secondary',
        'danger'    => 'border-danger-600 text-danger-600 hover:bg-danger-50 focus:ring-danger',
        'success'   => 'border-success-600 text-success-600 hover:bg-success-50 focus:ring-success',
        'warning'   => 'border-warning-500 text-warning-600 hover:bg-warning-50 focus:ring-warning',
        'info'      => 'border-accent-500 text-accent-600 hover:bg-accent-50 focus:ring-accent',
        'dark'      => 'border-primary text-primary hover:bg-secondary-100 focus:ring-primary',
    ];

    // Select the correct style based on the 'outline' prop
    $colorClass = $outline 
        ? ($outlineColors[$variant] ?? $outlineColors['primary']) . " border"
        : ($solidColors[$variant] ?? $solidColors['primary']) . " border shadow-sm";

    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $rounding = $pill ? 'rounded-full' : 'rounded-md';
?>

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $colorClass $sizeClass $rounding"]) }}>
    {{ $slot }}
</button>