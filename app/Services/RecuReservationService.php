<?php

namespace App\Services;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;


class RecuReservationService
{
    public function make(Reservation $reservation): \Barryvdh\DomPDF\PDF{
        $reservation->load(['user', 'variant.product']);
        return Pdf::loadView('admin.tickets.recu_reservation', ['reservation' => $reservation]);
    }
}