<?php

namespace App\Jobs;

use App\Models\Product;
use App\Service\OneC\Actions\ProductsAction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SyncProductFromOneC implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $product;

    /**
     * SyncProductFromOneC constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @param ProductsAction $service
     */
    public function handle(ProductsAction $service)
    {
        $info = $service->get($this->product->guid);
        $info = $info->getData();

        $related = Product::whereIn('guid', $info['related_products'])->get();

        $this->product->relatedProducts()->sync($related->pluck('id'));

    }
}
