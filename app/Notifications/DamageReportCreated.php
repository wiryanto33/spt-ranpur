<?php

namespace App\Notifications;

use App\Models\LaporanKerusakan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DamageReportCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public LaporanKerusakan $report)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $vehicle = $this->report->vehicle;
        $title = 'Laporan Kerusakan Baru';
        $url = route('laporan-kerusakan.show', $this->report);

        return (new MailMessage)
            ->subject($title)
            ->greeting('Halo ' . ($notifiable->name ?? ''))
            ->line('Ada laporan kerusakan baru yang dibuat oleh kru.')
            ->line('Tanggal: ' . $this->report->tanggal->format('d M Y'))
            ->line('Kendaraan: ' . ($vehicle?->nomor_lambung . ' - ' . $vehicle?->tipe))
            ->line('Judul: ' . $this->report->judul)
            ->action('Lihat Laporan', $url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'damage_report_created',
            'title' => 'Laporan Kerusakan Baru',
            'message' => 'Laporan kerusakan baru telah dibuat oleh kru.',
            'report_id' => $this->report->id,
            'vehicle' => [
                'id' => $this->report->ranpur_id,
                'label' => $this->report->vehicle?->nomor_lambung . ' - ' . $this->report->vehicle?->tipe,
            ],
            'tanggal' => $this->report->tanggal?->toDateString(),
            'judul' => $this->report->judul,
            'url' => route('laporan-kerusakan.show', $this->report),
        ];
    }
}
