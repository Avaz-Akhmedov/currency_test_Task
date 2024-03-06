<?php

namespace App\Traits;

use App\Models\Currency;

trait HasCurrency
{
    protected function parseCurrencyData($xmlString): \SimpleXMLElement|bool
    {
        $xml = simplexml_load_string($xmlString);
        return $xml->Valute;
    }

    protected function saveCurrencies($currencies): void
    {
        foreach ($currencies as $item) {
            Currency::query()->updateOrCreate([
                'code' => (string)$item->CharCode,
                'number_code' => (int)$item->NumCode,
            ], [
                'name' => (string)$item->Name,
                'code' => (string)$item->CharCode,
                'number_code' => (int)$item->NumCode,
                'value' => (float)str_replace(',', '.', $item->Value),
            ]);
        }
    }
}