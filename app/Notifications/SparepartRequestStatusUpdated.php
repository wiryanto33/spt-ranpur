<?php

namespace App\Notifications;

use App\Models\SparepartRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SparepartRequestStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(public SparepartRequest $requestHeader, public string $oldStatus, public string $newStatus)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $title = 'Status Permintaan Sparepart Diperbarui';
        $url = route('sparepart-request.show', $this->requestHeader);

        return (new MailMessage)
            ->subject($title)
            ->greeting('Halo ' . ($notifiable->name ?? ''))
            ->line('Status permintaan sparepart Anda telah diperbarui.')
            ->line('Dari: ' . $this->oldStatus . ' menjadi: ' . $this->newStatus)
            ->action('Lihat Permintaan', $url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'sparepart_request_status_updated',
            'title' => 'Status Permintaan Sparepart Diperbarui',
            'message' => 'Status berubah dari ' . $this->oldStatus . ' menjadi ' . $this->newStatus,
            'request_id' => $this->requestHeader->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'url' => route('sparepart-request.show', $this->requestHeader),
        ];
    }
}

