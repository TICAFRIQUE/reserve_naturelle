<?php

namespace App\Listeners;

use App\Events\OrderValidated;
use App\Services\RecueCommandeService;
use Illuminate\Support\Facades\Log;

class GenerateRecueCommande
{
    public function __construct(private RecueCommandeService $ticketService) {}

    public function handle(OrderValidated $event): void
    {
        try {
            $this->ticketService->generate($event->order);
        } catch (\Throwable $e) {
            // La commande est déjà validée et le stock débité : un échec de PDF ne doit pas
            // la faire échouer. Le ticket est régénéré au premier téléchargement (voir downloadTicket).
            Log::error('Échec de génération du ticket PDF', [
                'order_id'  => $event->order->id,
                'num_order' => $event->order->num_order,
                'erreur'    => $e->getMessage(),
            ]);
        }
    }
}