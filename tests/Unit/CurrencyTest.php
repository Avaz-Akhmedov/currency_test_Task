<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_to_save_currencies()
    {
        Http::fake([
            config('currencies.url') => Http::response($this->getSampleXml(), 200)
        ]);

        Log::shouldReceive('info')->once()->with('Successfully fetched data from XML API');
        Log::shouldReceive('info')->once()->with('Successfully saved data to database');

        $response = $this->getJson(route('currencies.save'));

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('currencies', [
            'code' => 'USD',
            'number_code' => 840,
            'name' => 'US Dollar',
            'value' => 90,
        ]);

    }


    private function getSampleXml(): string
    {
        return '<?xml version="1.0"?>
            <ValCurs Date="03.06.2024" name="Foreign Currency Market">
                <Valute ID="R01235">
                    <NumCode>840</NumCode>
                    <CharCode>USD</CharCode>
                    <Name>US Dollar</Name>
                    <Value>90</Value>
                </Valute>
            </ValCurs>';
    }
}
