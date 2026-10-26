<?php
// app/Notifications/OrderValidatedNotification.php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class OrderValidatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [60, 300];

    public function __construct(public Order $order) {}

    public function via($notifiable): array{
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage{
        $mail = (new MailMessage)
            ->subject("Votre commande {$this->order->num_order} est validée")
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("Bonne nouvelle : votre commande **{$this->order->num_order}** a été validée.")
            ->line('Elle sera livrée dans les 2 à 3 jours suivants. Nous vous contacterons si besoin.')
            ->line('Montant total : ' . number_format($this->order->montant_ttc, 0, ',', ' ') . ' FCFA')
            ->action('Voir mes commandes', route('client.orders.index'))
            ->salutation('L\'équipe La Réserve Naturelle');

        // Reçu PDF (généré par GenerateRecueCommande avant cette notification)
        if ($this->order->ticket_path && Storage::disk('local')->exists($this->order->ticket_path)) {
            $mail->attach(Storage::disk('local')->path($this->order->ticket_path), [
                'as' => "recu-{$this->order->num_order}.pdf",
                'mime' => 'application/pdf',
            ]);
        }
        return $mail;
    }

    public function toArray($notifiable): array{
        return [
            'order_id'  => $this->order->id,
            'num_order' => $this->order->num_order,
            'message'   => "Votre commande {$this->order->num_order} a été validée.",
        ];
    }
}