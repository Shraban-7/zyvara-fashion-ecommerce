@php
    $hasCells = !empty($cells);
@endphp

<div class="rounded-xl border border-secondary-200 bg-secondary-50/50 p-4">
    @if(!$hasCells)
        <p class="text-sm text-secondary-400 italic">No active events right now — the homepage section is hidden.</p>
    @else
        <div class="grid grid-cols-4 auto-rows-[40px] gap-2">
            @foreach($cells as $cell)
                @if($cell['type'] === 'view-all')
                    <div class="col-span-1 row-span-1 rounded-md border border-dashed border-primary/40 bg-primary/5 flex items-center justify-center text-[10px] font-semibold text-primary">
                        View All
                    </div>
                @else
                    @php
                        $size = $cell['size'];
                        $span = match ($size) {
                            'large'  => 'col-span-2 row-span-2',
                            'medium' => 'col-span-2 row-span-1',
                            default  => 'col-span-1 row-span-1',
                        };
                        $event = $cell['event'];
                    @endphp
                    <div class="{{ $span }} rounded-md overflow-hidden relative bg-primary/10 border border-secondary-200 flex items-end p-2"
                         title="{{ $event->title }} ({{ ucfirst($size) }})">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="">
                        @endif
                        <span class="relative text-[10px] font-semibold text-white drop-shadow line-clamp-2">{{ $event->title }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
