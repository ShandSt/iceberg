<?php

namespace App\Console\Commands;

use App\Models\Tag;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Class OneCSyncTags
 */
class OneCSyncTags extends Command
{
    /**
     * @var string
     */
    protected $signature = '1c:sync:tags';

    /**
     * @var string
     */
    protected $description = 'Synchronization tags with 1C';

    /**
     * @param Client $client
     */
    public function handle(Client $client): void
    {
        $response = $client->get(config('onec.endpoint').'products/Tags');
        if ($response->getStatusCode() !== 200) {
            Log::error('Failed sync Tags from 1C. Reason: '.$response->getBody()->getContents());

            return;
        }

        $data = json_decode($response->getBody()->getContents());

        $loadedTags = [];

        foreach ($data as $tag) {
            $loadedTags[] = (int)$tag->id;

            Tag::updateOrCreate(
                [
                    'id' => (int)$tag->id,
                    'guid' => $tag->guid,
                ],
                [
                    'name' => $tag->name,
                    'position' => $tag->position,
                ]
            );
        }

        Tag::whereNotIn('id', $loadedTags)->get()->each->delete();
    }
}
