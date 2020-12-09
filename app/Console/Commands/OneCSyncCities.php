<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Street;
use App\Service\OneC\Actions\AddressAction;
use App\Service\OneC\Server\OneCServer;
use Illuminate\Console\Command;

class OneCSyncCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:sync:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync citie from 1c server';


    /**
     * @var OneCServer
     */
    private $server;

    /**
     * Create a new command instance.
     *
     * @param OneCServer $server
     */
    public function __construct(OneCServer $server)
    {
        parent::__construct();

        $this->server = $server;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(AddressAction $service)
    {
        $status = json_decode(
            file_get_contents($this->server->getStatusFilePath()),
            true
        );

        if ($status['server_status'] != 200) {
            $this->error("Cannot start sync cities. 1C server is down.");
            Log::error(static::class,[
                'status' =>  $status,
            ]);

            return;
        }

        $oneCCities = collect(json_decode($service->getCities()->getBody()->getContents()));
        $oneCStreets = collect(json_decode($service->getStreets()->getBody()->getContents()));
        $dbCities = City::all();
        $dbStreets = Street::all();

        $this->info('Start sync cities');

        foreach ($oneCCities as $oneCCity) {
            // fix index value ("      ")
            if (empty(trim($oneCCity->index))) {
                $oneCCity->index = null;
            }

            $dbCity = $dbCities
                ->where('city', '=', $oneCCity->city)
                ->where('district', '=', $oneCCity->district)
                ->first();

            if (! $dbCity) {
                $this->info("Adding city {$oneCCity->city} ({$oneCCity->district})");
                City::create((array) $oneCCity);
            } elseif ($dbCity->index != $oneCCity->index) {
                $this->info("Updating city {$oneCCity->city} ({$oneCCity->district})");
                $dbCity->fill((array) $oneCCity)->save();
            }
        }

        foreach ($oneCStreets as $oneCStreet) {
            // fix index value ("      ")
            if (empty(trim($oneCStreet->index))) {
                $oneCStreet->index = null;
            }

            $dbCity = $dbCities
                ->where('city', '=', $oneCStreet->City)
                ->first();

            if (!$dbCity) {
                $this->error("City {$oneCStreet->City} not found");
                continue;
            }

            $dbStreet = $dbStreets
                ->where('street', '=', $oneCStreet->street)
                ->where('city_id', '=', $dbCity->id)
                ->first();

            if (! $dbStreet) {
                $this->info("Adding street {$oneCStreet->street} ({$oneCStreet->City})");
                Street::create([
                    'street' => $oneCStreet->street,
                    'city_id' => $dbCity->id,
                    'index' => $oneCStreet->index
                ]);
            } elseif ($dbStreet->index != $oneCStreet->index) {
                $this->info("Updating street {$oneCStreet->street} ({$oneCStreet->City})");
                $dbStreet->street = $oneCStreet->street;
                $dbStreet->index = $oneCStreet->index;
                $dbStreet->save();
            }
        }

        $this->info("All cities syncronized.");
    }
}
