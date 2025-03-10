<?php

namespace App\Services;

use App\Models\Portfolio;
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

        // FIFO: Lade alle vorherigen Käufe, die noch Aktien haben
        $buys = Transaction::buys( $sell->portfolio_id)->where('quantity', '>', 0)->get();
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
            } else {
                // Teilweise verkaufen
                $profit += ($sellPrice - $buy->price) * $sellQuantity;
                // Reduziere die verbleibende Kaufmenge
                Transaction::where('id', $buy->id)->update(['quantity' => $buy->quantity - $sellQuantity]);
                $sellQuantity = 0;
            }
        }
        // Speichere den Gewinn in der Datenbank
        Profit::insert([
            'portfolio_id' => $sell->portfolio_id,
            'symbol' => $sell->symbol,
            'sell_id' => $sellId,
            'profit' => $profit,
            'transaction_at' => $transactionAt,
            'created_at' => now(),
        ]);
        return true;
    }

    public static function calculateCurrentValues($currentPrice, $portfolioId): false|array
    {
        if ($currentPrice === 0) {
            $currentPrice = 1;
        }
        $buys = Transaction::buys($portfolioId)->get();
        // Lade alle Verkäufe in chronologischer Reihenfolge
        $sells = Transaction::sells($portfolioId)->get();

        // Berechnung der gekauften Aktien und Investitionssumme
        $totalPurchased = 0;
        $investmentSum = 0;
        $symbol = '';
        foreach ($buys as $buy) {
            $totalPurchased += $buy->buy_quantity;
            $investmentSum += $buy->buy_quantity * $buy->price;
            $symbol = $buy->symbol;
        }

        // Berechnung der verkauften Aktien
        $totalSold = 0;
        foreach ($sells as $sell) {
            $totalSold += $sell->quantity;
        }

        // Verbleibende Aktien
        $remainingShares = $totalPurchased - $totalSold;
        if($remainingShares <= 0) return false;
        // Aktueller Wert
        $currentValue = $remainingShares * $currentPrice;
        // Durchschnittlicher Kaufpreis pro Aktie
        $averagePurchasePrice = $totalPurchased > 0 ? $investmentSum / $totalPurchased : 0;
        // Gewinn/Verlust Berechnung
        $profitLoss = $currentValue - ($averagePurchasePrice * $remainingShares);
        $buyingValue = $averagePurchasePrice * $remainingShares;
        $percent = $profitLoss * 100 / $buyingValue;
        $currentValues = [];
        //Verbleibende Aktien
        $currentValues['remainingShares'] = $remainingShares;
        //Aktueller Wert
        $currentValues['currentValue'] = $currentValue;
        //Durchschnittlicher Kaufpreis
        $currentValues['averagePurchasePrice'] = $averagePurchasePrice;
        $currentValues['formatAvgPurchasePrice'] = number_format($averagePurchasePrice, 2);
        //Gewinn/Verlust
        $currentValues['profitLoss'] = $profitLoss;
        //kaufwert
        $currentValues['percent'] = number_format($percent, 2);
        $currentValues['currentPrice'] = $currentPrice;
        //symbol
        $currentValues['symbol'] = $symbol;

        // Ergebnis ausgeben
//        echo "Verbleibende Aktien: $remainingShares\n";
//        echo "Aktueller Wert: " . number_format($currentValue, 2) . "€\n";
//        echo "Durchschnittlicher Kaufpreis: " . number_format($averagePurchasePrice, 2) . "€\n";
//        echo "Gewinn/Verlust: " . number_format($profitLoss, 2) . "€\n";

        return $currentValues;
    }


    public static function calculatePastValues($pastPrice, $portfolioId): array
    {
        $buys = Transaction::buys($portfolioId)->get();
        // Lade alle Verkäufe in chronologischer Reihenfolge
        $sells = Transaction::sells($portfolioId)->get();

        // Berechnung der gekauften Aktien und Investitionssumme
        $totalPurchased = 0;
        $investmentSum = 0;
        $symbol = '';
        foreach ($buys as $buy) {
            $totalPurchased += $buy->buy_quantity;
            $investmentSum += $buy->buy_quantity * $buy->price;
            $symbol = $buy->symbol;
        }

        $profit = 0;
        $totalSells = 0;
        $sellSum = 0;
        foreach ($sells as $sell) {
            $totalSells += $sell->quantity;
            $sellSum += $sell->quantity * $sell->price;
            $profitModel = Profit::where('sell_id', $sell->id)->first();
            $profit += $profitModel->profit;
        }

        $averagePurchasePrice = $investmentSum / $totalPurchased;
        $averageSellPrice = $sellSum / $totalSells;

        // Gewinn/Verlust Berechnung

        $buyingValue = $averagePurchasePrice * $totalPurchased;
        $percent = $profit * 100 / $buyingValue;
        $currentValues = [];
        //Verbleibende Aktien
        $currentValues['remainingShares'] = 0;

        //Durchschnittlicher Kaufpreis
        $currentValues['averagePurchasePrice'] = $averagePurchasePrice;

        //Gewinn/Verlust
        $currentValues['profitLoss'] = $profit;
        //kaufwert
        $currentValues['percent'] = number_format($percent, 2);
        $currentValues['averageSellPrice'] = $averageSellPrice;
        //symbol
        $currentValues['symbol'] = $symbol;

        return $currentValues;
    }
}
