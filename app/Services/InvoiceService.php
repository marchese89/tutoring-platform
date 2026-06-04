<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Student;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generatePdf(int $order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $admin = User::where('role', 'admin')->first();
        $adminData = Admin::where('user_id', $admin->id)->first();
        $student = $order->student;
        $user = $student->user;
        $order_items = $order->orderItems()->get();
        $total = $order->orderItems()->sum('price');

        $number = $this->getNextInvoiceNumber();

        $data = $order->created_at;

        $html = view('invoices.invoice', [
            'user' => $user,
            'studente' => $student,
            'admin' => $admin,
            'adminData' => $adminData,
            'order_items' => $order_items,
            'total' => $total,
            'order' => $order,
            'dataFattura' => $data->format('d/m/Y'),
            'numeroFattura' => $number,
        ])->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdf = $dompdf->output();

        $relativePath = "invoices/invoice_{$number}.pdf";

        Storage::disk('private')->put(
            $relativePath,
            $pdf
        );

        // salva record fattura (solo metadata, NON file)
        Invoice::create([
            'number' => $number,
            'issued_at' => $data,
            'order_id' => $order->id,
            'file_path' => $relativePath,
        ]);

        return $pdf;
    }

    private function getNextInvoiceNumber(): int
    {
        $last = Invoice::latest()->first();

        if (!$last) {
            return 1;
        }

        $currentYear = now()->year;
        $lastYear = Carbon::parse($last->issued_at)->year;

        if ($currentYear === $lastYear) {
            return $last->number + 1;
        }

        return 1;
    }
}
