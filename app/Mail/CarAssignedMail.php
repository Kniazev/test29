<?php

namespace App\Mail;

use App\Models\Car;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Car $car;
    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Car $car, User $user)
    {
        $this->car = $car;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Car Assigned',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cars.assigned',
        );
    }
}
