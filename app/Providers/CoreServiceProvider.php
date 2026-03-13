<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use mysqli;

// Import all Repositories
use App\Repositories\PersonRepository;
use App\Repositories\LicenseRepository;
use App\Repositories\VehicleRepository;
use App\Repositories\TicketRepository;
use App\Repositories\ViolationRepository;
use App\Repositories\UserRepository;

// Import all Services
use App\Services\LicenseService;
use App\Services\VehicleService;
use App\Services\TicketService;
use App\Services\UserService;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // 1. Register the shared mysqli connection (Singleton)
        $this->app->singleton(mysqli::class, function ($app) {
            $conn = new mysqli(
                config('database.connections.mysql.host'),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database')
            );

            if ($conn->connect_error) {
                // Return a JSON error if the connection fails
                header('Content-Type: application/json');
                die(json_encode(["error" => "Database connection failed"]));
            }

            return $conn;
        });

        // 2. Register Repositories
        $this->app->singleton(PersonRepository::class, fn($app) => new PersonRepository($app->make(mysqli::class)));
        
        $this->app->singleton(LicenseRepository::class, fn($app) => new LicenseRepository(
            $app->make(mysqli::class), 
            $app->make(PersonRepository::class)
        ));

        $this->app->singleton(VehicleRepository::class, fn($app) => new VehicleRepository(
            $app->make(mysqli::class), 
            $app->make(LicenseRepository::class)
        ));

        $this->app->singleton(TicketRepository::class, fn($app) => new TicketRepository(
            $app->make(mysqli::class), 
            $app->make(LicenseRepository::class)
        ));

        $this->app->singleton(ViolationRepository::class, fn($app) => new ViolationRepository($app->make(mysqli::class)));
        
        $this->app->singleton(UserRepository::class, fn($app) => new UserRepository($app->make(mysqli::class)));

        // 3. Register Services
        $this->app->singleton(LicenseService::class, function ($app) {
            return new LicenseService(
                $app->make(mysqli::class),
                $app->make(PersonRepository::class),
                $app->make(LicenseRepository::class),
                $app->make(TicketRepository::class)
            );
        });

        $this->app->singleton(VehicleService::class, function ($app) {
            return new VehicleService(
                $app->make(mysqli::class),
                $app->make(VehicleRepository::class),
                $app->make(LicenseRepository::class)
            );
        });

        $this->app->singleton(TicketService::class, function ($app) {
            return new TicketService(
                $app->make(mysqli::class),
                $app->make(LicenseRepository::class),
                $app->make(TicketRepository::class),
                $app->make(ViolationRepository::class)
            );
        });

        $this->app->singleton(UserService::class, function ($app) {
            return new UserService(
                $app->make(mysqli::class),
                $app->make(UserRepository::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}