<?php

namespace App\Mail;

use App\Enums\ReturnStatus;
use App\Models\ReturnRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReturnStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public ReturnRequest $returnRequest;
    public ReturnStatus $status;
    public string $message;

    /**
     * @param  ReturnRequest  $returnRequest
     * @param  ReturnStatus  $status
     * @param  string  $message  Pre-rendered customer-facing message
     */
    public function __construct(ReturnRequest $returnRequest, ReturnStatus $status, string $message)
    {
        $this->returnRequest = $returnRequest;
        $this->status = $status;
        $this->message = $message;
    }

    public function build()
    {
        $type = $this->returnRequest->isExchange ? 'Exchange' : 'Return';

        return $this->subject("{$type} Request " . $this->status->label())
            ->html("
                <div style='font-family: Inter, Arial, sans-serif; max-width: 520px; margin: 0 auto;'>
                    <h2 style='color:#1A1A1A;'>{$type} Request Update</h2>
                    <p style='color:#555;'>{$this->message}</p>
                    <hr style='border:none;border-top:1px solid #eee;'>
                    <p style='color:#888;font-size:12px;'>
                        Refunds are processed within 5–7 business days to your original payment method.
                        For exchanges, we'll ship your new item once we receive the original.
                    </p>
                </div>
            ");
    }
}
