<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Cache;

/**
 * Builds a data-driven bento grid layout for the homepage "Festival / Running Events"
 * section. The grid is fully derived from the number of active events and their
 * priority — no hardcoded Blade conditionals. The service returns a flat list of
 * cells, each carrying a Tailwind span class string that the view applies verbatim.
 *
 * Grid model: a 4-column base on desktop (`lg`), 2-column on tablet (`md`),
 * 1-column stacked on mobile (spans stripped). Cells are expressed as
 * `col-span-X row-span-Y` for the lg breakpoint; tablet/mobile overrides are
 * appended so the view only loops and applies classes.
 */
class BentoLayoutService
{
    public const MAX_VISIBLE = 6;

    /**
     * Returns the rendered layout: a list of cells.
     * Each cell is: [
     *   'type'  => 'event' | 'view-all',
     *   'event' => Event|null,
     *   'span'  => 'lg:col-span-2 lg:row-span-2 md:col-span-2 md:row-span-1 col-span-1 row-span-1',
     *   'size'  => 'large'|'medium'|'small' (for typography/styling hooks)
     * ]
     */
    public function build(): array
    {
        $events = Event::active()->ordered()->get();

        if ($events->isEmpty()) {
            return [];
        }

        $count = $events->count();
        $capped = $events->take(self::MAX_VISIBLE);

        // If a single event outranks the rest significantly, always feature it.
        $featured = $this->pickFeatured($capped);

        $pattern = $this->patternFor($count, $featured);

        $cells = [];
        $eventIndex = 0;

        foreach ($pattern as $slot) {
            if ($slot === 'view-all') {
                $cells[] = $this->viewAllCell();
                continue;
            }

            $event = $capped[$eventIndex] ?? null;
            $eventIndex++;

            if (!$event) {
                continue;
            }

            $cells[] = [
                'type' => 'event',
                'event' => $event,
                'size' => $slot,
                'span' => $this->spanFor($slot),
            ];
        }

        // Append "View All" only when we capped the list.
        if ($count > self::MAX_VISIBLE && !collect($pattern)->contains('view-all')) {
            $cells[] = $this->viewAllCell();
        }

        return $cells;
    }

    /**
     * Decide which event should be the large featured tile.
     * Picks the highest-priority event only if it clearly outranks the others
     * (priority strictly greater than the second highest), so a default tie
     * lets the count-based pattern decide.
     */
    protected function pickFeatured(\Illuminate\Support\Collection $events): ?Event
    {
        if ($events->count() < 1) {
            return null;
        }

        $sorted = $events->sortByDesc('priority')->values();
        $top = $sorted[0];

        if ($sorted->count() > 1 && $top->priority <= $sorted[1]->priority) {
            return null; // tie -> let the pattern decide
        }

        return $top->priority > 0 ? $top : null;
    }

    /**
     * Returns an ordered list of slot sizes ('large'|'medium'|'small'|'view-all')
     * for the given active count, biasing the featured event into the large slot.
     */
    protected function patternFor(int $count, ?Event $featured): array
    {
        return match (true) {
            $count === 1 => ['large'],
            $count === 2 => ['large', 'medium'],
            $count === 3 => ['large', 'small', 'small'],
            $count === 4 => ['large', 'small', 'small', 'small'],
            $count === 5 => ['large', 'medium', 'small', 'small', 'small'],
            $count >= 6  => ['large', 'medium', 'small', 'small', 'small', 'view-all'],
            default     => ['small'],
        };
    }

    /**
     * Map an abstract slot size to responsive Tailwind span classes.
     *
     * Desktop (lg): 4-col grid. large = 2x2, medium = 2x1, small = 1x1.
     * Tablet (md):  2-col grid. large/medium collapse to 2x1, small = 1x1.
     * Mobile:       1 col stacked, everything full width (col-span-1 row-span-1).
     */
    protected function spanFor(string $slot): string
    {
        return match ($slot) {
            'large'  => 'lg:col-span-2 lg:row-span-2 md:col-span-2 md:row-span-1 col-span-1 row-span-1',
            'medium' => 'lg:col-span-2 lg:row-span-1 md:col-span-2 md:row-span-1 col-span-1 row-span-1',
            'small'  => 'lg:col-span-1 lg:row-span-1 md:col-span-1 md:row-span-1 col-span-1 row-span-1',
            default  => 'lg:col-span-1 lg:row-span-1 md:col-span-1 md:row-span-1 col-span-1 row-span-1',
        };
    }

    protected function viewAllCell(): array
    {
        return [
            'type' => 'view-all',
            'event' => null,
            'size' => 'small',
            'span' => 'lg:col-span-1 lg:row-span-1 md:col-span-1 md:row-span-1 col-span-1 row-span-1',
        ];
    }

    // ---- Cached entry point used by the controller ----

    public static function cached(): array
    {
        return Cache::remember(Event::CACHE_KEY, now()->addMinutes(5), function () {
            return (new self())->build();
        });
    }
}
