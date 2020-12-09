<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OneCSyncCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:sync:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronization category with 1C';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $res = $client->get(config('onec.endpoint').'categories');
        if ($res->getStatusCode() == '200') {
            $categories = json_decode($res->getBody()->getContents());
            $curr_categories_id = [];
            foreach ($categories as $category) {
                $save = Category::updateOrCreate([
                    'name' => $category->name,
                    'cid' => (int)$category->id,
                    'position' => $category->position,
                ]);
                $curr_categories_id[] = $save->id;
            }
            // Delete category
            $all_cat = Category::all()->pluck('id')->diff($curr_categories_id)->toArray();
            if (!empty($all_cat)) Category::destroy($all_cat);
            $this->info("Categories synchronization successfully.");
        }
    }
}
