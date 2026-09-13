<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Helper for computing version_date used in XML reports.
 * version_date = max(company.updated_at, bpic.updated_at, industrial_physician.updated_at, examination.updated_at)
 * Used for optimistic locking when company, BPIC, or industrial physician data changes.
 */
class VersionDateHelper
{
    /**
     * Compute version_date from the latest updated_at of company, bpic, industrial_physician, examination.
     *
     * @param  Model|null  $company
     * @param  Model|null  $bpic  User model
     * @param  Model|null  $industrialPhysician  User model
     * @param  Model|null  $examination
     * @return string|null ISO 8601 string or null if all are null
     */
    public static function compute(?Model $company, ?Model $bpic, ?Model $industrialPhysician, ?Model $examination = null): ?string
    {
        $dates = array_filter([
            $company?->updated_at,
            $bpic?->updated_at,
            $industrialPhysician?->updated_at,
            $examination?->updated_at,
        ]);

        if (empty($dates)) {
            return null;
        }

        $latest = collect($dates)->max(fn (?Carbon $d) => $d?->timestamp ?? 0);

        return collect($dates)->first(fn (?Carbon $d) => $d && $d->timestamp === $latest)?->toISOString();
    }

    /**
     * Get the latest Carbon from company, bpic, industrial_physician, examination for comparison.
     *
     * @param  Model|null  $company
     * @param  Model|null  $bpic
     * @param  Model|null  $industrialPhysician
     * @param  Model|null  $examination
     * @return Carbon|null
     */
    public static function getLatestCarbon(?Model $company, ?Model $bpic, ?Model $industrialPhysician, ?Model $examination = null): ?Carbon
    {
        $dates = array_filter([
            $company?->updated_at,
            $bpic?->updated_at,
            $industrialPhysician?->updated_at,
            $examination?->updated_at,
        ]);

        if (empty($dates)) {
            return null;
        }

        $latest = collect($dates)->max(fn (?Carbon $d) => $d?->timestamp ?? 0);

        return collect($dates)->first(fn (?Carbon $d) => $d && $d->timestamp === $latest);
    }
}
