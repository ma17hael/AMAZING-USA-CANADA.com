<?php 
class Currency {
    private static array $rates = [
        'EUR' => 1,
        'USD' => 1.08,
        'CAD' => 1.47,
    ];

    public static function convert(float $amountEuro, string $target): float {
        return $amountEuro * self::$rates[$target];
    }

    public static function format(float $amount, string $currency, string $locale): string {
        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, $currency);
    }

    public static function getRate(string $currency): float {
        return self::$rates[$currency];
    }
}
?>