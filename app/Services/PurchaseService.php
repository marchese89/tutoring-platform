<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;

class PurchaseService
{
    public static function prodotto_acquistato($id_studente, $id, $tipo): bool
    {
        $ordini = Order::where('student_id', '=', $id_studente)->get();
        foreach ($ordini as $ordine) {
            $prodotti_ordine = OrderProduct::where('id_ordine', '=', $ordine->id)->get();
            foreach ($prodotti_ordine as $prodotto) {
                if ($prodotto->id_prodotto == $id && $prodotto->tipo_prodotto == $tipo) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function get_totale_ordine($id_ordine): int
    {
        $tot = 0;

        $prodotti_ordine = OrderProduct::where('id_ordine', '=', $id_ordine)->get();
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
