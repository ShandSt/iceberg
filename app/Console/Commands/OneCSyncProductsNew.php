<?php

namespace App\Console\Commands;
use App\Models\ProductNew;
use App\Service\OneC\Actions\ProductsAction;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Service\OneC\Server\OneCServer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class OneCSyncProductsNew extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:sync:productsnew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronization products with 1C';

    /**
     * @param Client $client
     */
    public function handle(Client $client): void
    {
        $response = $client->get(config('onec.endpoint').'products/GetProducts');
        if ($response->getStatusCode() !== 200) {
            Log::error('Failed sync Products from 1C. Reason: '.$response->getBody()->getContents());

            return;
        }

        $data = json_decode($response->getBody()->getContents());

        $loadedProducts = [];

        Schema::disableForeignKeyConstraints();
        foreach ($data as $product) {
            $productId = (int)substr($product->id, 3);
            $loadedProducts[] = $productId;

            $picturePath = null;

            if (!empty($product->preview_picture)) {
                $file = base64_decode($product->preview_picture);
                Storage::put('/public/productsnew/'.$product->guid.'.jpg', $file);
                unset($file);
                $picturePath = '/storage/productsnew/'.$product->guid.'.jpg';
                $this->saveImageVariants($product->guid);
            }

            /** @var ProductNew $localProduct */
            $localProduct = ProductNew::updateOrCreate(
                [
                    'id' => $productId,
                    'guid' => $product->guid,
                ],
                [
                    'name' => $product->name,
                    'description' => $product->description,
                    'category_id' => (int)$product->category_id,
                    'status' => $product->status,
                    'position' => (int)$product->position,
                    'preview_picture' => $picturePath,
                    'detail_picture' => $picturePath,
                    'price' => (int)$product->price,
                    'old_price' => (int)$product->old_price,
                ]
            );

            $tags = collect($product->tags)->filter(
                function ($tag) {
                    return !empty($tag);
                }
            )->map(
                function ($tag) {
                    return (int)$tag;
                }
            )->toArray();

            $localProduct->tags()->sync([]);
            $localProduct->tags()->sync($tags);

            $relatedProducts = collect($product->related_products)->filter(
                function ($relatedProduct) {
                    return !empty($relatedProduct);
                }
            )->map(
                function ($relatedProduct) {
                    return (int)substr($relatedProduct,3);
                }
            )->toArray();

            $localProduct->relatedProducts()->sync([]);
            $localProduct->relatedProducts()->sync($relatedProducts);
        }
        Schema::enableForeignKeyConstraints();

        ProductNew::whereNotIn('id', $loadedProducts)->delete();
    }

    /**
     * @param string $name
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    private function saveImageVariants(string $name): void
    {
        $picturePath = storage_path('app/public/productsnew/'.$name.'.jpg');
        Image::load($picturePath)
            ->fit(Manipulations::FIT_FILL, 148, 176)
            ->background('ffffff')
            ->save(storage_path('app/public/productsnew/'.$name.'-small.jpg'));
        Image::load($picturePath)
            ->fit(Manipulations::FIT_FILL, 400, 380)
            ->background('ffffff')
            ->save(storage_path('app/public/productsnew/'.$name.'-details.jpg'));
    }
}
