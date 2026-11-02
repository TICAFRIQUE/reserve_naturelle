<?php

namespace App\Listeners;

use App\Events\OrderValidated;
use App\Services\RecueCommandeService;

class GenerateRecueCommande
{
    public function __construct(private RecueCommandeService $service) {}

    public function handle(OrderValidated $event): void{
        $this->service->generate($event->order);
    }
}