<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $pool;
    public $threshold;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param string $pool
     * @param string $threshold
     * @return void
     */
    public function __construct(string $subject, string $pool, string $threshold)
    {
        $this->subject = $subject;
        $this->pool = $pool;
        $this->threshold = $threshold;
        $this->content = "Le seuil de la " . ($threshold == 'temperature' ? 'température' : 'pH') . " est dépassé dans le bassin $pool, veuillez réguler vos niveaux.";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->subject($this->subject)
            ->markdown('emails.alert', [
                'content' => $this->content
            ]);
    }
}
