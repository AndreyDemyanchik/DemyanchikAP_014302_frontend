<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Client;
use App\Models\Malfunction;
use App\Models\Ride;
use App\Models\RideRouteData;
use App\Models\Scooter;
use App\Models\ScooterMalfunction;
use Database\Factories\MalfunctionFactory;
use Database\Factories\RideRouteDataFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Client::factory(40)->create();
        Scooter::factory(40)->create();
        Ride::factory(40)->create();
        Malfunction::factory(40)->create();
        RideRouteData::factory(40)->create();
        ScooterMalfunction::factory(40)->create();
    }
}
