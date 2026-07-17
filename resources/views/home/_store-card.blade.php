{{-- Store card — used in the homepage showroom carousel and the store locator grid --}}
<div class="bg-light rounded-2xl border border-primary-100 overflow-hidden shadow-sm hover:shadow-md transition h-full flex flex-col">
    <div class="h-40 bg-secondary-100 overflow-hidden">
        @if($store->image)
            <img src="{{ asset('storage/' . $store->image) }}" alt="{{ $store->name }}" class="h-full w-full object-cover">
        @else
            <div class="h-full w-full flex items-center justify-center text-secondary-300">
                <i class="fas fa-store text-4xl"></i>
            </div>
        @endif
    </div>

    <div class="p-5 flex flex-col flex-1">
        <div class="flex items-start justify-between gap-2">
            <h3 class="font-bold text-primary text-base leading-tight">{{ $store->name }}</h3>
            @if($store->is_flagship)
                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold rounded bg-accent-50 text-accent-700 border border-accent-100">
                    <i class="fas fa-star"></i> Flagship
                </span>
            @endif
        </div>

        <p class="text-sm text-secondary mt-1">{{ $store->city }}</p>
        <p class="text-xs text-secondary-500 mt-0.5 line-clamp-2">{{ $store->full_address }}</p>

        {{-- Open now / closed (server-computed) --}}
        <div class="mt-3">
            @if($store->is_open_now)
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-success">
                    <span class="h-2 w-2 rounded-full bg-success animate-pulse"></span> Open now
                    <span class="text-secondary-400 font-normal">({{ $store->today_hours }})</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-secondary-400">
                    <span class="h-2 w-2 rounded-full bg-secondary-300"></span>
                    @if($store->today_hours && strtolower($store->today_hours) !== 'closed')
                        Closed now <span class="text-secondary-400 font-normal">({{ $store->today_hours }})</span>
                    @else
                        Closed today
                    @endif
                </span>
            @endif
        </div>

        <div class="mt-auto pt-4 flex items-center justify-between">
            @if($store->phone)
                <a href="tel:{{ $store->phone }}" class="text-sm text-primary hover:underline"><i class="fas fa-phone mr-1"></i> Call</a>
            @else
                <span></span>
            @endif
            <a href="{{ $store->directions_url }}" target="_blank" rel="noopener"
                class="inline-flex items-center gap-1.5 text-sm font-semibold text-accent hover:text-primary transition">
                <i class="fas fa-directions"></i> Get Directions
            </a>
        </div>
    </div>
</div>
