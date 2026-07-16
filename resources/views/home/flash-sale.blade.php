@php
    $flashSales = \Illuminate\Support\Facades\Cache::remember('active_flash_sales', now()->addMinutes(10), function () {
        return \App\Models\FlashSale::current()
            ->ordered()
            ->with(['products' => function ($q) {
                $q->where('is_active', true);
            }, 'products.primaryImage', 'products.variants'])
            ->get();
    });

    $sectionLimit = isset($section) ? (int) $section->item_limit : 10;
@endphp

@if($flashSales->isNotEmpty())
    @foreach($flashSales as $flashSale)
        @php
            $fsProducts = $flashSale->products->take($sectionLimit)->map(function ($product) {
                $salePrice = $product->pivot->sale_price;
                if ($salePrice !== null && $salePrice < $product->price) {
                    $product->compare_price = $product->price;
                    $product->price = $salePrice;
                    $product->is_on_sale = true;
                }
                return $product;
            });
        @endphp

        @continue($fsProducts->isEmpty())

        <section class="home-section home-section--flash">
            <div class="home-wrap">
                <div class="flash-strip">
                    <div class="flash-strip-icon"><i class="fas fa-bolt"></i></div>
                    <div class="flash-strip-text">
                        <span class="flash-strip-label">{{ $flashSale->subtitle ?? 'Flash Sale' }}</span>
                        <span class="flash-strip-value">{{ $flashSale->title }}</span>
                    </div>
                    <div class="flash-strip-timer" data-flash-timer data-ends-at="{{ $flashSale->ends_at->toIso8601String() }}">
                        <span class="timer-block"><span class="timer-num" data-days>00</span><span class="timer-label">Days</span></span>
                        <span class="timer-sep">:</span>
                        <span class="timer-block"><span class="timer-num" data-hours>00</span><span class="timer-label">Hrs</span></span>
                        <span class="timer-sep">:</span>
                        <span class="timer-block"><span class="timer-num" data-minutes>00</span><span class="timer-label">Min</span></span>
                        <span class="timer-sep">:</span>
                        <span class="timer-block"><span class="timer-num" data-seconds>00</span><span class="timer-label">Sec</span></span>
                    </div>
                </div>

                <div class="products-grid products-grid--flash">
                    @foreach($fsProducts as $product)
                        <x-product-card :product="$product" badgeType="SALE" />
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach

    <style>
        .home-section--flash {
            padding: 48px 0;
            background: var(--color-surface-muted);
            width: 100%;
            border-top: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
        }

        @media (min-width: 768px) {
            .home-section--flash { padding: 64px 0; }
        }

        .flash-strip {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--color-primary);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 32px;
            box-shadow: 0 4px 16px rgba(26, 26, 26, 0.18);
            flex-wrap: wrap;
        }

        @media (min-width: 768px) {
            .flash-strip { padding: 16px 24px; gap: 16px; flex-wrap: nowrap; }
        }

        .flash-strip-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(250, 248, 245, 0.1);
            border-radius: 10px;
            flex-shrink: 0;
        }

        .flash-strip-icon i { font-size: 16px; color: var(--color-accent); }

        .flash-strip-text { display: flex; flex-direction: column; gap: 2px; flex: 1; }

        .flash-strip-label {
            font-size: 11px;
            font-weight: 800;
            color: rgba(250, 248, 245, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            line-height: 1;
        }

        .flash-strip-value {
            font-size: 15px;
            font-weight: 700;
            color: var(--color-surface-elevated);
            line-height: 1.3;
            font-family: var(--font-heading);
        }

        .flash-strip-timer {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(250, 248, 245, 0.08);
            padding: 8px 14px;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .home-section--flash .timer-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1px;
            min-width: 32px;
        }

        .home-section--flash .timer-num {
            font-size: 18px;
            font-weight: 900;
            color: var(--color-surface-elevated);
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        .home-section--flash .timer-label {
            font-size: 9px;
            font-weight: 600;
            color: rgba(250, 248, 245, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            line-height: 1;
        }

        .home-section--flash .timer-sep {
            font-size: 16px;
            font-weight: 700;
            color: rgba(250, 248, 245, 0.3);
            line-height: 1;
            margin-top: -8px;
        }

        .products-grid--flash {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            width: 100%;
        }

        @media (min-width: 640px) {
            .products-grid--flash { grid-template-columns: repeat(3, 1fr); gap: 16px; }
        }

        @media (min-width: 1024px) {
            .products-grid--flash { grid-template-columns: repeat(4, 1fr); gap: 20px; }
        }

        @media (min-width: 1280px) {
            .products-grid--flash { grid-template-columns: repeat(5, 1fr); gap: 20px; }
        }
    </style>

    <script>
        (function() {
            const timers = document.querySelectorAll('[data-flash-timer]');
            if (!timers.length) return;

            function pad(n) { return String(n).padStart(2, '0'); }

            function tick() {
                const now = Date.now();
                timers.forEach(function(timer) {
                    const end = new Date(timer.dataset.endsAt).getTime();
                    let diff = Math.max(0, Math.floor((end - now) / 1000));

                    const days = Math.floor(diff / 86400); diff %= 86400;
                    const hours = Math.floor(diff / 3600); diff %= 3600;
                    const minutes = Math.floor(diff / 60);
                    const seconds = diff % 60;

                    timer.querySelector('[data-days]').textContent = pad(days);
                    timer.querySelector('[data-hours]').textContent = pad(hours);
                    timer.querySelector('[data-minutes]').textContent = pad(minutes);
                    timer.querySelector('[data-seconds]').textContent = pad(seconds);
                });
            }

            tick();
            setInterval(tick, 1000);
        })();
    </script>
@endif
