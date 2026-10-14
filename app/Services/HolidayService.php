<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HolidayService
{
    /**
     * Base URL for the public holiday API.
     */
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.holidays.url', env('PUBLIC_HOLIDAYS_API_URL', 'https://date.nager.at/api/v3/PublicHolidays'));
    }

    /**
     * Retrieve public holidays for a given year and country code.
     * Default: current year, US or IN.
     *
     * @param int|null $year
     * @param string $countryCode
     * @return array
     */
    public function getHolidays(?int $year = null, string $countryCode = 'US'): array
    {
        $year = $year ?? Carbon::now()->year;
        $url = rtrim($this->apiUrl, '/') . "/{$year}/{$countryCode}";

        try {
            $response = Http::timeout(3)->get($url);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            Log::warning('HolidayService API unreachable: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Check if a given date is a known public holiday.
     *
     * @param string|Carbon|null $date
     * @param string $countryCode
     * @return string|null Name of holiday if found, null otherwise.
     */
    public function checkHoliday($date, string $countryCode = 'US'): ?string
    {
        if (!$date) {
            return null;
        }

        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);
        $holidays = $this->getHolidays($carbonDate->year, $countryCode);

        $dateString = $carbonDate->format('Y-m-d');
        foreach ($holidays as $holiday) {
            if (isset($holiday['date']) && $holiday['date'] === $dateString) {
                return $holiday['name'] ?? 'Public Holiday';
            }
        }

        return null;
    }
}
