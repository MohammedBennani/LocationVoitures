<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class ExpireReservations extends Command
{
    protected $signature = 'reservations:expire';

    protected $description = 'Soft delete expired reservations automatically';

    public function handle(): int
    {
        $today = Carbon::today();

        $expired = Reservation::whereDate('date_end', '<', $today)
            ->whereNull('deleted_at')
            ->get();

        foreach ($expired as $reservation) {
            $reservation->delete(); // Soft delete
        }

        $this->info("Expired reservations: " . $expired->count());

        return self::SUCCESS;
    }
}