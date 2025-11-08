<?php

namespace App\Notifications;

use App\Models\LaporanKerusakan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DamageReportStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public LaporanKerusakan $report, public string $oldStatus, public string $newStatus)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $title = 'Status Laporan Kerusakan Diperbarui';
        $url = route('laporan-kerusakan.show', $this->report);

        return (new MailMessage)
            ->subject($title)
            ->greeting('Halo ' . ($notifiable->name ?? ''))
            ->line('Status laporan kerusakan Anda telah diperbarui.')
            ->line('Judul: ' . $this->report->judul)
            ->line('Dari: ' . $this->oldStatus . ' menjadi: ' . $this->newStatus)
            ->action('Lihat Laporan', $url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'damage_report_status_updated',
            'title' => 'Status Laporan Kerusakan Diperbarui',
            'message' => 'Status berubah dari ' . $this->oldStatus . ' menjadi ' . $this->newStatus,
            'report_id' => $this->report->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'url' => route('laporan-kerusakan.show', $this->report),
        ];
    }
}
