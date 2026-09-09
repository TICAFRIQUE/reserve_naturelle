<?php

namespace App\Listeners;

use App\Events\OrderValidated;
use App\Services\RecueCommandeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateRecueCommande
{
    public function __construct(private RecueCommandeService $ticketService) {}

    public function handle(OrderValidated $event): void
    {
        $this->ticketService->generate($event->order);
    }
}