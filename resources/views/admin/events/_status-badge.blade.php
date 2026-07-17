@php
    $map = [
        'active'    => ['label' => 'Active',    'class' => 'text-success bg-success-50 border-success-200', 'dot' => 'bg-success'],
        'scheduled' => ['label' => 'Scheduled', 'class' => 'text-info bg-info-50 border-info-200',     'dot' => 'bg-info'],
        'expired'   => ['label' => 'Expired',   'class' => 'text-warning bg-warning-50 border-warning-200', 'dot' => 'bg-warning'],
        'inactive'  => ['label' => 'Inactive',  'class' => 'text-secondary-400 bg-secondary-50 border-secondary-200', 'dot' => 'bg-secondary-400'],
    ];
    $cfg = $map[$key] ?? $map['inactive'];
@endphp

<span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full border {{ $cfg['class'] }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $cfg['dot'] }} mr-1.5"></span>
    {{ $cfg['label'] }}
</span>
