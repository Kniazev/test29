<?php

namespace App\Jobs;

use App\Mail\CarAssignedMail;
use App\Models\Car;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CarAssignedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Car $car;
    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(Car $car, User $user)
    {
        $this->car = $car;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user)->send(new CarAssignedMail($this->car, $this->user));
    }
}
