<?php

/**
 * Class AddClass
 */
class AddClass {
    const IS_EURO = 0.01,
        IS_NOT_EURO = 0.02;

    private $totalLines,
        $curlHandle,
        $exchangeRates,
        $error = true;

    public function __construct($filename)
    {
        if(file_exists($filename)) {
            $this->totalLines = file($filename);
            $this->error = false;

            $this->curlHandle = curl_init();

            $this->exchangeRates = $this->getExchangeRates();
        }
    }

    public function __destruct()
    {
        if($this->curlHandle) {
            curl_close($this->curlHandle);
        }
    }

    public function getError(): bool
    {
        return $this->error;
    }

    public function getData(string $line): float
    {
        $currentLine = json_decode($line);
        $binList = $this->getBinlistResults($currentLine->bin);

        $rate = $this->isEU($binList['country']['alpha2']) ? self::IS_EURO : self::IS_NOT_EURO;

        if($rate === self::IS_EURO) {
            $result = $currentLine->amount*$rate;
        } else {
            $result = ($currentLine->amount / $this->exchangeRates['rates'][$currentLine->currency])*$rate;
        }

        return ceil($result*100)/100;
    }

    protected function getBinlistResults($request): array
    {
        curl_setopt($this->curlHandle, CURLOPT_URL, 'https://lookup.binlist.net/'.$request);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);

        $resultString = curl_exec($this->curlHandle);

        return json_decode($resultString, true);
    }

    protected function getExchangeRates(): array
    {
        curl_setopt($this->curlHandle, CURLOPT_URL, 'https://api.exchangeratesapi.io/latest');
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);

        $resultString = curl_exec($this->curlHandle);

        return json_decode($resultString, true);
    }

    public function isEU($country): bool
    {
        $countries = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES',
            'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU',
            'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
        ];

        return in_array($country, $countries, true);
    }

    public function start() {
        foreach ($this->totalLines as $line) {
            echo number_format($this->getData($line), 2, '.', ''), PHP_EOL;
        }
    }
}
