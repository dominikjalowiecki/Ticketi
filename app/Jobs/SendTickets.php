<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SendTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ordersIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $ordersIds)
    {
        $this->ordersIds = $ordersIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = DB::table('orders_list')
            ->whereIn('id_order', $this->ordersIds)
            ->get();

        $mail = new OrderMail($orders);

        foreach ($orders as $order) {
            $pdf = Pdf::loadView('pdf.ticket', ['order' => $order]);

            $normalizedName = preg_replace(
                '/[^A-Za-z0-9_.-]/',
                '',
                str_replace(' ', '_', strtolower($order->event_name))
            );

            $filename = $order->id_event . '_' . $normalizedName . '_ticket.pdf';

            $mail->attachData($pdf->output(),  $filename, [
                'mime' => 'application/pdf',
            ]);
        }

        $email = $orders->first()->email;

        Mail::to($email)
            ->send($mail);
    }
}
