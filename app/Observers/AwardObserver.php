<?php

namespace App\Observers;

use App\Models\Award;
use Illuminate\Support\Facades\Cache;

/**
 * Award Observer
 * 
 * Automatically clears cache when awards are created, updated, or deleted.
 */
class AwardObserver
{
    /**
     * Clear awards cache.
     */
    protected function clearCache(): void
    {
        Cache::forget('awards.active');
        Cache::forget('awards.featured');
    }

    /**
     * Handle the Award "created" event.
     */
    public function created(Award $award): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Award "updated" event.
     */
    public function updated(Award $award): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Award "deleted" event.
     */
    public function deleted(Award $award): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Award "restored" event.
     */
    public function restored(Award $award): void
    {
        $this->clearCache();
    }

    /**
     * Handle the Award "force deleted" event.
     */
    public function forceDeleted(Award $award): void
    {
        $this->clearCache();
    }
}
