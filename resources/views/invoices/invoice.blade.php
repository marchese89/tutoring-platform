<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Fattura</title>
</head>

<body>

    <table width="100%" cellspacing="0" cellpadding="0" align="center" style="border-collapse: collapse;" rules="none"
        frame="none" border="0">

        <tr style="height:100px">
            <td align="center" colspan="3">
                <h1>Fattura</h1>
            </td>
        </tr>

        {{-- Sender --}}
        <tr style="height:30px">
            <td align="left">
                <font size="4">{{ $admin->name }} {{ $admin->surname }}</font>
            </td>
            <td></td>
        </tr>

        <tr style="height:30px">
            <td align="left">
                <font size="4">{{ $adminData->street }}, {{ $adminData->house_number }}</font>
            </td>
            <td></td>
        </tr>

        <tr style="height:30px">
            <td align="left">
                <font size="4">
                    {{ $adminData->postal_code }} - {{ $adminData->city }} ({{ $adminData->province }})
                </font>
            </td>
            <td></td>
        </tr>

        <tr style="height:30px">
            <td align="left">
                <font size="4">PARTITA IVA: {{ $adminData->vat_number }}</font>
            </td>
            <td></td>
        </tr>

        <tr style="height:30px">
            <td align="left">
                <font size="4">COD. FISC: {{ $adminData->tax_code }}</font>
            </td>
            <td></td>
        </tr>

        {{-- Customer --}}
        <tr style="height:30px">
            <td></td>
            <td align="right">
                <h2>Cliente</h2>
            </td>
        </tr>

        <tr style="height:30px">
            <td></td>
            <td align="right">
                <font size="4">{{ $user->name }} {{ $user->surname }}</font>
            </td>
        </tr>

        <tr style="height:30px">
            <td></td>
            <td align="right">
                <font size="4">{{ $customer->street }}, {{ $customer->house_number }}</font>
            </td>
        </tr>

        <tr style="height:30px">
            <td></td>
            <td align="right">
                <font size="4">
                    {{ $customer->postal_code }} - {{ $customer->city }} ({{ $customer->province }})
                </font>
            </td>
        </tr>

        <tr style="height:30px">
            <td></td>
            <td align="right">
                <font size="4">CF: {{ $customer->tax_code }}</font>
            </td>
        </tr>

        {{-- Date and number --}}
        <tr style="height:30px">
            <td align="left">
                <font size="4"><b>DATA:</b></font> {{ $invoiceDate }}
            </td>
            <td></td>
        </tr>

        <tr style="height:100px">
            <td align="left" style="vertical-align:top">
                <font size="4"><b>FATTURA:</b></font> {{ $invoiceNumber }}
            </td>
            <td></td>
        </tr>

        {{-- Items --}}
        <tr>
            <td colspan="2">

                <table rules="all" border="1" style="width:100%">

                    <tr style="height:50px">
                        <td align="center"><b>DESCRIZIONE</b></td>
                        <td align="center"><b>PREZZO</b></td>
                        <td align="center"><b>QTA</b></td>
                        <td align="center"><b>IMPORTO</b></td>
                    </tr>

                    @foreach ($orderItems as $row)
                        <tr>
                            <td align="center">{{ $row['description'] }}</td>
                            <td align="center">{{ $row['price'] }} €</td>
                            <td align="center">{{ $row['quantity'] ?? 1 }}</td>
                            <td align="center"><b>{{ $row['total'] ?? $row['price'] }} &euro;</b></td>
                        </tr>
                    @endforeach

                </table>

            </td>
        </tr>

        {{-- Totals --}}
        <tr style="height:50px">
            <td></td>
            <td align="right">
                <font size="3">
                    IMPONIBILE: {{ number_format($total / 1.04, 2, '.', '') }} €
                </font>
            </td>
        </tr>

        <tr style="height:50px">
            <td></td>
            <td align="right">
                <font size="3">
                    Rivalsa Inps 4%:
                    {{ number_format((($total / 1.04) * 4) / 100, 2, '.', '') }} €
                </font>
            </td>
        </tr>

        <tr style="height:50px">
            <td></td>
            <td align="right">
                <font size="3"><b>TOTALE {{ $total }} €</b></font>
            </td>
        </tr>

        {{-- Notes --}}
        <tr>
            <td>
                <font size="3">Imposta di bollo € 2,00 su originale</font>
            </td>
        </tr>

        <tr>
            <td>
                <font size="3">su Importi superiori ad € 77,47</font>
            </td>
        </tr>

        <tr>
            <td>
                <font size="3"><b>NOTE</b></font>
            </td>
        </tr>

        <tr>
            <td>
                <font size="3">{{ $note ?? '' }}</font>
            </td>
        </tr>

        {{-- Footer --}}
        <tr align="center" style="height:200px">
            <td colspan="2"></td>
        </tr>

        <tr align="center">
            <td colspan="2">
                <b>Operazione in franchigia da Iva art. 1 cc. 54-89 L. 190/2014</b>
            </td>
        </tr>

        <tr align="center">
            <td colspan="2">
                <b>Non soggetta a ritenuta d’acconto ai sensi del c. 67 L. 190/2014</b>
            </td>
        </tr>

    </table>

</body>

</html>
