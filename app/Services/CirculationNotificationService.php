<?php

namespace App\Services;

use App\Models\CirculationNotification;
use App\Models\Loan;
use Carbon\Carbon;

class CirculationNotificationService
{
    /**
     * Send simulated WhatsApp message
     */
    public static function sendWhatsApp(Loan $loan, string $type, string $customMessage = null): CirculationNotification
    {
        $user = $loan->user;
        $phone = $user->phone ?? '081234567890';
        $bookTitle = $loan->book->title;
        $dueDate = $loan->due_date ? $loan->due_date->format('d M Y') : '-';

        if (!$customMessage) {
            $customMessage = match ($type) {
                'due_reminder' => "Halo Kak {$user->name}, pengingat dari Perpustakaan Aksaraya: Buku \"{$bookTitle}\" (Kode: {$loan->loan_code}) akan jatuh tempo pada {$dueDate}. Harap kembalikan tepat waktu untuk menghindari denda.",
                'overdue_alert' => "Pemberitahuan: Peminjaman buku \"{$bookTitle}\" oleh Kak {$user->name} telah melewati batas jatuh tempo ({$dueDate}). Denda keterlambatan berjalan: Rp " . number_format($loan->fine_amount, 0, ',', '.') . ". Mohon segera kembalikan ke perpustakaan.",
                'loan_approved' => "Kabar Baik Kak {$user->name}! Permohonan peminjaman buku \"{$bookTitle}\" telah DISETUJUI oleh petugas. Silakan ambil buku di rak {$loan->book->shelf_location} dalam waktu 1x24 jam.",
                default => "Halo Kak {$user->name}, notifikasi peminjaman buku Aksaraya.",
            };
        }

        return CirculationNotification::create([
            'loan_id' => $loan->id,
            'user_id' => $user->id,
            'channel' => 'whatsapp',
            'type' => $type,
            'recipient' => $phone,
            'message' => $customMessage,
            'status' => 'sent',
        ]);
    }

    /**
     * Send simulated Email
     */
    public static function sendEmail(Loan $loan, string $type, string $customMessage = null): CirculationNotification
    {
        $user = $loan->user;
        $email = $user->email;
        $bookTitle = $loan->book->title;
        $dueDate = $loan->due_date ? $loan->due_date->format('d M Y') : '-';

        if (!$customMessage) {
            $customMessage = match ($type) {
                'due_reminder' => "Yth. Anggota Aksaraya ({$user->name}), masa peminjaman buku \"{$bookTitle}\" akan berakhir pada tanggal {$dueDate}. Silakan lakukan perpanjangan atau pengembalian di perpustakaan.",
                'overdue_alert' => "Pemberitahuan Keterlambatan: Buku \"{$bookTitle}\" telah melewati batas tempo pengembalian. Silakan segera menyelesaikan pengembalian di meja sirkulasi.",
                'loan_approved' => "Reservasi Disetujui: Buku \"{$bookTitle}\" sudah disiapkan oleh petugas dan siap Anda ambil di meja sirkulasi.",
                default => "Notifikasi status sirkulasi Aksaraya.",
            };
        }

        return CirculationNotification::create([
            'loan_id' => $loan->id,
            'user_id' => $user->id,
            'channel' => 'email',
            'type' => $type,
            'recipient' => $email,
            'message' => $customMessage,
            'status' => 'sent',
        ]);
    }
}

