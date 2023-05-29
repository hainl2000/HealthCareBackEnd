<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PushLatestPatientEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $doctorId;
    private $patientName;
    private $bookingDate;

    public function __construct(int $bookingId, string $patientName, string $bookingDate)
    {
        $this->doctorId = $bookingId;
        $this->patientName = $patientName;
        $this->bookingDate = $bookingDate;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $soonestBooking = [
            'id' => $this->doctorId,
            'name' => $this->patientName,
            'date' => $this->bookingDate
        ];
        return new PrivateChannel('doctor-channel.' . $this->doctorId);
    }

    public function broadcastAs()
    {
        return 'push-latest-patient';
    }

}
