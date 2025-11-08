<?php

namespace App\Notifications;

use App\Models\SparepartRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SparepartRequestCreated extends Notification
{
    use Queueable;

    public function __construct(public SparepartRequest $requestHeader)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $title = 'Permintaan Sparepart Baru';
        $url = route('sparepart-request.show', $this->requestHeader);

        $vehicle = $this->requestHeader->diagnosis?->damageReport?->vehicle;
        $vehicleLabel = $vehicle ? ($vehicle->nomor_lambung . ' - ' . $vehicle->tipe) : '-';
        $requesterName = $this->requestHeader->requester?->name ?? 'Mechanic';

        return (new MailMessage)
            ->subject($title)
            ->greeting('Halo ' . ($notifiable->name ?? ''))
            ->line('Ada permintaan sparepart baru yang diajukan oleh ' . $requesterName . '.')
            ->line('Tanggal: ' . optional($this->requestHeader->tanggal)->format('d M Y'))
            ->line('Kendaraan: ' . $vehicleLabel)
            ->action('Lihat Permintaan', $url);
    }

    public function toArray(object $notifiable): array
    {
        $vehicle = $this->requestHeader->diagnosis?->damageReport?->vehicle;
        return [
            'type' => 'sparepart_request_created',
            'title' => 'Permintaan Sparepart Baru',
            'message' => 'Permintaan sparepart baru diajukan oleh ' . ($this->requestHeader->requester?->name ?? 'mechanic') . '.',
            'request_id' => $this->requestHeader->id,
            'vehicle' => [
                'id' => $vehicle?->id,
                'label' => $vehicle?->nomor_lambung . ' - ' . $vehicle?->tipe,
            ],
            'tanggal' => $this->requestHeader->tanggal?->toDateString(),
            'status' => $this->requestHeader->status,
            'url' => route('sparepart-request.show', $this->requestHeader),
        ];
    }
}

