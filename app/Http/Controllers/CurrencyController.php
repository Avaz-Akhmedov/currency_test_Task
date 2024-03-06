<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Traits\HasCurrency;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    use HasCurrency;


    public function save(): JsonResponse
    {
        try {
            $response = Http::timeout(10)
                ->retry(3, 100)
                ->get(config('currencies.url'));

            if ($response->successful()) {
                Log::info('Successfully fetched data from XML API');

                $currencies = $this->parseCurrencyData($response);
                $this->saveCurrencies($currencies);

                Log::info('Successfully saved data to database');

            } else {
                Log::error('Failed to fetch data from XML API', ['status' => $response->status()]);
                return response()->json(['error' => 'Failed to fetch data from XML API'], $response->status());
            }
        } catch (\Exception $ex) {
            Log::error('Failed to fetch data: ' . $ex->getMessage());
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function currencies() : ResourceCollection
    {
        $currencies = Currency::all();

        return CurrencyResource::collection($currencies);
    }
}
