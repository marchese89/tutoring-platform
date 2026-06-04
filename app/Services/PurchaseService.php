<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;

class PurchaseService
{
    public static function prodotto_acquistato($student_id, $id, $tipo): bool
    {
        $ordini = Order::where('student_id', '=', $student_id)->get();
        foreach ($ordini as $ordine) {
            $prodotti_ordine = OrderItem::where('order_id', '=', $ordine->id)->get();
            foreach ($prodotti_ordine as $prodotto) {
                if ($prodotto->product_id == $id && $prodotto->product_type == $tipo) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function get_totale_ordine($order_id): int
    {
        $tot = 0;

        $prodotti_ordine = OrderItem::where('order_id', '=', $order_id)->get();
        foreach ($prodotti_ordine as $prodotto) {
            $tot += $prodotto->price;
        }

        return $tot;
    }

    public static function stringa_mese($mese)
    {
        switch ($mese) {
            case 1:
                return 'Gennaio';
            case 2:
                return 'Febbraio';
            case 3:
                return 'Marzo';
            case 4:
                return 'Aprile';
            case 5:
                return 'Maggio';
            case 6:
                return 'Giugno';
            case 7:
                return 'Luglio';
            case 8:
                return 'Agosto';
            case 9:
                return 'Settembre';
            case 10:
                return 'Ottobre';
            case 11:
                return 'Novembre';
            case 12:
                return 'Dicembre';
        }
    }
}
