<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('invoice.title') }}</title>
</head>

<body>

    <table width="100%" cellspacing="0" cellpadding="0" align="center" style="border-collapse: collapse;" rules="none"
        frame="none" border="0">

        <tr style="height:100px">
            <td align="center" colspan="3">
                <h1>{{ __('invoice.title') }}</h1>
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
                <font size="4">{{ __('invoice.vat_number') }}: {{ $adminData->vat_number }}</font>
            </td>
            <td></td>
        </tr>

        <tr style="height:30px">
            <td align="left">
                <font size="4">{{ __('invoice.tax_code') }}: {{ $adminData->tax_code }}</font>
            </td>
            <td></td>
        </tr>

        {{-- Customer --}}
        <tr style="height:30px">
            <td></td>
            <td align="right">
                <h2>{{ __('invoice.customer') }}</h2>
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
                <font size="4">{{ __('invoice.customer_tax_code') }}: {{ $customer->tax_code }}</font>
            </td>
        </tr>

        {{-- Date and number --}}
        <tr style="height:30px">
            <td align="left">
                <font size="4"><b>{{ __('invoice.date') }}:</b></font> {{ $invoiceDate }}
            </td>
            <td></td>
        </tr>

        <tr style="height:100px">
            <td align="left" style="vertical-align:top">
                <font size="4"><b>{{ __('invoice.invoice_number') }}:</b></font> {{ $invoiceNumber }}
            </td>
            <td></td>
        </tr>

        {{-- Items --}}
        <tr>
            <td colspan="2">

                <table rules="all" border="1" style="width:100%">

                    <tr style="height:50px">
                        <td align="center"><b>{{ __('invoice.description') }}</b></td>
                        <td align="center"><b>{{ __('invoice.price') }}</b></td>
                        <td align="center"><b>{{ __('invoice.quantity') }}</b></td>
                        <td align="center"><b>{{ __('invoice.amount') }}</b></td>
                    </tr>

                    @foreach ($orderItems as $row)
                        <tr>
                            <td align="center">{{ $row['description'] }}</td>
                            <td align="center">{{ $row['price'] }} &euro;</td>
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
                    {{ __('invoice.taxable_amount') }}: {{ number_format($total / 1.04, 2, '.', '') }} &euro;
                </font>
            </td>
        </tr>

        <tr style="height:50px">
            <td></td>
            <td align="right">
                <font size="3">
                    {{ __('invoice.inps') }}:
                    {{ number_format((($total / 1.04) * 4) / 100, 2, '.', '') }} &euro;
                </font>
            </td>
        </tr>

        <tr style="height:50px">
            <td></td>
            <td align="right">
                <font size="3"><b>{{ __('invoice.total') }} {{ $total }} &euro;</b></font>
            </td>
        </tr>

        {{-- Notes --}}
        <tr>
            <td>
                <font size="3">{{ __('invoice.stamp_duty') }}</font>
            </td>
        </tr>

        <tr>
            <td>
                <font size="3">{{ __('invoice.stamp_duty_threshold') }}</font>
            </td>
        </tr>

        <tr>
            <td>
                <font size="3"><b>{{ __('invoice.notes') }}</b></font>
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
                <b>{{ __('invoice.vat_exemption') }}</b>
            </td>
        </tr>

        <tr align="center">
            <td colspan="2">
                <b>{{ __('invoice.withholding_exemption') }}</b>
            </td>
        </tr>

    </table>

</body>

</html>
