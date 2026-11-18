<?php

namespace App\Services;

use InvalidArgumentException;

class PaylaterCalculator
{
    public function tenors(): array
    {
        return config('lumeria.paylater.tenors');
    }

    public function rate(): float
    {
        return (float) config('lumeria.paylater.margin_rate');
    }

    /**
     * Margin flat = pokok × persen/bulan × jumlah bulan.
     * Cicilan dibulatkan ke atas ke rupiah penuh, lalu total = cicilan × tenor,
     * sehingga angka yang ditampilkan selalu konsisten.
     */
    public function calculate(int $principal, int $tenor, ?float $rate = null): array
    {
        if ($tenor < 1) {
            throw new InvalidArgumentException('Tenor minimal 1 bulan.');
        }

        $rate ??= $this->rate();

        $margin      = (int) round($principal * ($rate / 100) * $tenor);
        $installment = (int) ceil(($principal + $margin) / $tenor);
        $total       = $installment * $tenor;

        return [
            'tenor'       => $tenor,
            'rate'        => $rate,
            'principal'   => $principal,
            'margin'      => $total - $principal,
            'installment' => $installment,
            'total'       => $total,
        ];
    }

    public function plans(int $principal): array
    {
        return array_map(
            fn (int $tenor) => $this->calculate($principal, $tenor),
            $this->tenors()
        );
    }
}
