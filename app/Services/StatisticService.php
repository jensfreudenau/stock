<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\Profit;
use App\Models\Transaction;

class StatisticService
{
    public static function calculateProfitForSell(int $sellId, $transactionAt): bool
    {
        // Hole die Verkaufsdetails
        $sell = Transaction::where('id', $sellId)->where('type', 'sell')->first();
        if (!$sell) {
            return false;
        }

        $sellQuantity = $sell->quantity;
        $sellPrice = $sell->price;
        $stockSymbol = $sell->symbol;

        // FIFO: Lade alle vorherigen Käufe, die noch Aktien haben
        $buys = Transaction::where('type', 'buy')
            ->where('symbol', $stockSymbol)
            ->where('quantity', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $profit = 0;

        foreach ($buys as $buy) {
            if ($sellQuantity <= 0) {
                break;
            }
            if ($buy->quantity <= $sellQuantity) {
                // Ganze Kaufposition verkaufen
                $profit += ($sellPrice - $buy->price) * $buy->quantity;
                $sellQuantity -= $buy->quantity;
                // Setze die Kaufmenge auf 0, weil sie verkauft wurde
                Transaction::where('id', $buy->id)->update(['quantity' => 0]);
                Transaction::where('id', $buy->id)->update(['buy_quantity' => $buy->quantity]);
            } else {
                // Teilweise verkaufen
                $profit += ($sellPrice - $buy->price) * $sellQuantity;
                // Reduziere die verbleibende Kaufmenge
                Transaction::where('id', $buy->id)->update(['quantity' => $buy->quantity - $sellQuantity]);
                Transaction::where('id', $buy->id)->update(['buy_quantity' => $buy->quantity]);

                $sellQuantity = 0;
            }
        }

        // Speichere den Gewinn in der Datenbank
        Profit::insert([
            'symbol' => $stockSymbol,
            'sell_id' => $sellId,
            'profit' => $profit,
            'transaction_at' => $transactionAt,
            'created_at' => now(),
        ]);

        return true;
    }

    public static function calculateCurrentValues($currentPrice, $symbol): array
    {
        $buys = Transaction::where('type', 'buy')
            ->where('symbol', $symbol)
            ->orderBy('transaction_at', 'asc')
            ->get();

        // Lade alle Verkäufe in chronologischer Reihenfolge
        $sells = Transaction::where('type', 'sell')
            ->where('symbol', $symbol)
            ->orderBy('transaction_at', 'asc')
            ->get();

        // Berechnung der gekauften Aktien und Investitionssumme
        $totalPurchased = 0;
        $investmentSum = 0;

        foreach ($buys as $buy) {
            $totalPurchased += $buy->buy_quantity;
            $investmentSum += $buy->buy_quantity * $buy->price;
        }

        // Berechnung der verkauften Aktien
        $totalSold = 0;
        foreach ($sells as $sell) {
            $totalSold += $sell->quantity;
        }
        // Verbleibende Aktien
        $remainingShares = $totalPurchased - $totalSold;
        // Aktueller Wert
        $currentValue = $remainingShares * $currentPrice;
        // Durchschnittlicher Kaufpreis pro Aktie
        $averagePurchasePrice = $totalPurchased > 0 ? $investmentSum / $totalPurchased : 0;
        // Gewinn/Verlust Berechnung
        $profitLoss = $currentValue - ($averagePurchasePrice * $remainingShares);

        $currentValues =[];
        //Verbleibende Aktien
        $currentValues['remainingShares'] = $remainingShares;
        //Aktueller Wert
        $currentValues['currentValue'] = $currentValue;
        //Durchschnittlicher Kaufpreis
        $currentValues['averagePurchasePrice'] = $averagePurchasePrice;
        //Gewinn/Verlust
        $currentValues['profitLoss'] = $profitLoss;
        // Ergebnis ausgeben
//        echo "Verbleibende Aktien: $remainingShares\n";
//        echo "Aktueller Wert: " . number_format($currentValue, 2) . "€\n";
//        echo "Durchschnittlicher Kaufpreis: " . number_format($averagePurchasePrice, 2) . "€\n";
//        echo "Gewinn/Verlust: " . number_format($profitLoss, 2) . "€\n";

        return $currentValues;
    }

    public static function calculateStockProfit($stockSymbol)
    {
        // Lade alle Käufe für die Aktie in chronologischer Reihenfolge
        $buys = Transaction::where('type', 'buy')
            ->where('stock_symbol', $stockSymbol)
            ->orderBy('created_at', 'asc')
            ->get();

        // Lade alle Verkäufe in chronologischer Reihenfolge
        $sells = Transaction::where('type', 'sell')
            ->where('stock_symbol', $stockSymbol)
            ->orderBy('created_at', 'asc')
            ->get();

        $profit = 0;
        $buyQueue = [];

        // FIFO: Käufe in eine Warteschlange legen
        foreach ($buys as $buy) {
            $buyQueue[] = [
                'quantity' => $buy->buy_quantity,
                'price' => $buy->price,
            ];
        }

        // Verkäufe durchgehen und FIFO anwenden
        foreach ($sells as $sell) {
            $sellQuantity = $sell->quantity;
            $sellPrice = $sell->price;

            while ($sellQuantity > 0 && !empty($buyQueue)) {
                $firstBuy = &$buyQueue[0];

                if ($firstBuy['quantity'] <= $sellQuantity) {
                    // Gesamte Kaufposition verkaufen
                    $profit += ($sellPrice - $firstBuy['price']) * $firstBuy['quantity'];
                    $sellQuantity -= $firstBuy['quantity'];
                    array_shift($buyQueue);
                } else {
                    // Teilweise verkaufen
                    $profit += ($sellPrice - $firstBuy['price']) * $sellQuantity;
                    $firstBuy['quantity'] -= $sellQuantity;
                    $sellQuantity = 0;
                }
            }
        }
        return $profit;
    }

    public function getLostWin(): float|int
    {
        return $this->lost_win;
    }

    public function save(): void
    {
        Balance::create(get_object_vars($this));
    }

    /**
     * @param $sellAt
     * @return mixed
     */
    public function getSum($sellAt): mixed
    {
        return $sellAt->sum('amount');
    }

}
