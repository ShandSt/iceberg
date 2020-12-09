<?php

namespace App\Service\OneC\Server;

use App\Service\OneC\Exception\SaveConfigException;
use GuzzleHttp\Client;

class OneCServer
{

    public function checkServerStatus()
    {
        if (file_exists($path = $this->getStatusFilePath())) {
            $config = $this->parseCacheFile($path);
        } else {
            $config = $this->createDefaulStatusFileConfig();
        }

        if ($config['next_ping'] < time()) {
            $config['server_status'] = $this->pingToServer();
            $config['next_ping'] = time() + config('onec.ping_ttl', 300);
            $this->saveConfig($config);
        }

        return $config;
    }


    private function saveConfig(array $config): bool
    {
        $config = json_encode($config);

        $put = file_put_contents(
            $this->getStatusFilePath(),
            $config
        );

        if (!$put) {
            throw new SaveConfigException("Cannot save 1c server status");
        }

        return true;
    }

    public function getStatusFilePath(): string
    {
        return storage_path('OneCStatus.json');
    }


    private function createDefaulStatusFileConfig(): array
    {
        return [
            'server_status' => false,
            'next_ping' => 0,
        ];
    }


    public function pingToServer(): int
    {
        $config = config('onec');

        $client = new Client();
        $response = $client->get($config['ping_endpoint']);

        return $response->getStatusCode();
    }

    private function parseCacheFile(string $path): array
    {
        return json_decode(
            file_get_contents($path),
            true
        );
    }
}