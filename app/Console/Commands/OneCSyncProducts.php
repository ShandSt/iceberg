<?php

namespace App\Console\Commands;

use App\Jobs\SyncProductFromOneC;
use App\Jobs\SyncUserFromOneC;
use App\Models\Product;
use App\Service\OneC\Actions\ProductsAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use App\Service\OneC\Server\OneCServer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OneCSyncProducts extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:sync:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync products from 1c to local db.';


    private $server;

    private $service;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OneCServer $server, ProductsAction $service) {
        parent::__construct();

        $this->server = $server;
        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle() {
        $status = json_decode(
            file_get_contents($this->server->getStatusFilePath()),
            true
        );

        if ($status['server_status'] != 200) {
            Log::info('products sync error. 1C server is down.');
            return;
        }

        $products_to_sync = [];

        $page = $this->service->get(null, 1, 50);
        $products = $page->getData();

        if ($page->hasNextPage()) {
            for ($i = 1; $i < $page->total(); $i++) {
                foreach ($this->service->get(null, $i, 50)->getData() as $p) {
                    $products[] = $p;
                }
            }
        }

        $this->info("Products fetch : OK");

        //удаляем старые продукты, которых нет в 1c
        $productsApi = Product::all();
        $productGuids = [];
        foreach ($products as $pro) {
            $productGuids[] = $pro['guid'];
        }

        foreach ($productsApi as $item) {
            if (!in_array($item->guid, $productGuids)) {
                Product::find($item->id)->delete();
                $this->info('Delete product');
            }
        }

        //создаем продукты и картинки
        foreach ($products as $product) {

            //Сейв картинок
            if (!empty($product['preview_picture'])) {
                $file = base64_decode($product['preview_picture']);
                Storage::put('/public/products/' . $product['guid'] . '.jpg', $file);
                unset($file);
                $product['preview_picture'] = '/storage/products/' . $product['guid'] . '.jpg';
                $product['detail_picture'] = '/storage/products/' . $product['guid'] . '.jpg';
            }
            //Пошел сейв продуктов
            if (!Product::whereGuid($product['guid'])->exists()) {
                $product['id'] = substr($product['id'], 3);
                $this->info($product['id']);
                $record = Product::create($product);
                $products_to_sync[] = $record;
                $this->info("Create product");
            }
            else {
                unset($product['id']);
                Product::whereGuid($product['guid'])->update($product);

                $this->info("Update product");
            }
        }

        foreach ($products_to_sync as $k => $sync) {
            dispatch((new SyncProductFromOneC($sync))->delay(5 + $k));
        }

    }
}
