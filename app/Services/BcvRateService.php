<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BcvRateService
{
    public const CACHE_KEY = 'bcv_rate';

    public const CACHE_TTL = 3600;

    public const FALLBACK_RATE = 873.86;

    public const ENDPOINT = 'https://rates.dolarvzla.com/bcv/current.json';

    /**
     * Tasa BCV (USD → VES) compartida globalmente con las vistas.
     */
    public static function getRate(): float
    {
        try {
            return (float) Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function (): float {
                return static::fetchFromApi() ?? static::FALLBACK_RATE;
            });
        } catch (\Throwable $e) {
            Log::warning('No fue posible leer caché de tasa BCV: '.$e->getMessage());

            return static::FALLBACK_RATE;
        }
    }

    /**
     * Tasa BCV del día (instancia, cacheada 1 hora).
     */
    public function rate(): float
    {
        return static::getRate();
    }

    /**
     * Convierte un monto en USD a bolívares según la tasa del día.
     */
    public function usdToVes(float $usd): float
    {
        return round($usd * $this->rate(), 2);
    }

    public function formatVes(float $amount): string
    {
        return 'Bs. '.number_format($amount, 2, ',', '.');
    }

    /**
     * Consulta el endpoint oficial DolarVzla y extrae $data['current']['usd'].
     */
    private static function fetchFromApi(): ?float
    {
        try {
            $response = Http::timeout(8)->acceptJson()->get(self::ENDPOINT);

            if (! $response->successful()) {
                Log::warning('No fue posible consultar la tasa BCV: HTTP '.$response->status());

                return null;
            }

            $data = $response->json();
            $usd = $data['current']['usd'] ?? null;

            if (is_numeric($usd) && (float) $usd > 0) {
                return round((float) $usd, 4);
            }
        } catch (\Throwable $e) {
            Log::warning('Error de conexión al consultar la tasa BCV: '.$e->getMessage());
        }

        return null;
    }
}
