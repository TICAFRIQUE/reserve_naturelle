<?php
// app/Services/RecueCommandeService.php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RecueCommandeService
{
    public function generate(Order $order): string
    {
        $order->load(['items.product', 'user']);

        $pdf = Pdf::loadView('admin.tickets.recue_commande', ['order' => $order]);
        $path = "tickets/ticket-{$order->num_order}.pdf";

        Storage::disk('local')->put($path, $pdf->output());
        $order->update(['ticket_path' => $path]);

        return $path;
    }
}