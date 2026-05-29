<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Utility\ElementoC;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use App\Services\OrderService;
use App\Services\InvoiceService;
use App\Mail\OrderCompletedMail;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Support\Facades\Mail;

class AcquistiController extends Controller
{
    public function aggiungi_al_carrello(Request $request, int $id, int $type)
    {
        $carrello = $request->session()->get('carrello', new \App\Http\Utility\Carrello());

        $elemento = new ElementoC($id, $type);
        $carrello->aggiungi($elemento);

        $request->session()->put('carrello', $carrello);

        return match ($type) {
            ElementoC::LEZIONE => redirect()->route('courses.show', Lesson::find($id)->course_id),
            ElementoC::ESERCIZIO => redirect()->route('courses.show', Exercise::find($id)->course_id),
            ElementoC::LEZIONI_CORSO,
            ElementoC::CORSO_COMPLETO => redirect()->route('courses.show', $id),
            ElementoC::LEZIONE_RICHIESTA => redirect()->route('student.direct-requests.show', $id),
            default => redirect()->route('cart.show')
        };
    }

    public function rimuovi_dal_carrello(Request $request, int $id, int $type)
    {
        $carrello = $request->session()->get('carrello');

        if (!$carrello || $carrello->nElementi() === 0) {
            return response()->json(['error' => 'Carrello vuoto'], 400);
        }

        $carrello->rimuovi($id, $type);

        return redirect()->route('cart.show');
    }

    public function process_payment(Request $request)
    {
        $user = $request->user();

        $user->createOrGetStripeCustomer();

        $carrello = $request->session()->get('carrello');

        $tot = $carrello->getTotale() * 100;

        $payment = $user->pay($tot);

        return response()->json([
            'clientSecret' => $payment->client_secret
        ]);
    }

    public function processa_acquisto(
        Request $request,
        OrderService $orderService,
        InvoiceService $invoiceService
    ) {
        DB::beginTransaction();

        try {
            $user = $request->user();

            $studente = Student::where('user_id', $user->id)->first();

            $carrello = $request->session()->get('carrello');

            if (!$carrello || $carrello->nElementi() === 0) {
                throw new \Exception("Carrello vuoto");
            }

            /*
             * 1. CREA ORDINE + RIGHE
             */
            $orderId = $orderService->process($studente, $carrello);

            /*
             * 2. GENERA PDF
             */
            $invoiceService->generatePdf($orderId);

            $invoice = Invoice::where('order_id', $orderId)->first();

            /*
             * 3. INVIO EMAIL
             */
            Mail::to($user->email)->send(
                new OrderCompletedMail(
                    $user,
                    $invoice->path,
                    now()->format('d/m/Y'),
                    Order::find($orderId)->order_products()->sum('price')
                )
            );

            /*
             * 4. SVUOTA CARRELLO
             */
            $carrello->vuotaCarrello();

            DB::commit();

            return redirect()->route('payment.complete');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function prepara_pagamento()
    {
        $prezzo = request('prezzo');
        $qta = request('qta');

        session()->put('descrizione', request('descrizione'));
        session()->put('prezzo', $prezzo);
        session()->put('qta', $qta);

        if (($prezzo * $qta) > 77.47) {
            return back()->withError('Importo superiore a 77.47 € (max consentito)');
        }

        return redirect()->route('payment.pay');
    }

    public function processa_pagamento_individuale(Request $request)
    {
        $user = $request->user();

        $user->createOrGetStripeCustomer();

        $tot = session()->get('prezzo') * session()->get('qta') * 100;

        $payment = $user->pay($tot);

        return response()->json([
            'clientSecret' => $payment->client_secret
        ]);
    }
}
