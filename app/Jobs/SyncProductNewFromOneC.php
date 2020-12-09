<?php

namespace App\Jobs;

use App\Models\ProductNew;
use App\Service\OneC\Actions\ProductsAction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SyncProductNewFromOneC implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $product;

    /**
     * SyncProductFromOneC constructor.
     * @param Product $product
     */
    public function __construct(ProductNew $product)
    {
        $this->product = $product;
    }

    /**
     * @param ProductsAction $service
     */
    public function handle(ProductsAction $service)
    {
        $info = $service->getNewProducts($this->product->guid);
        $info = $info->getData();

        $related = ProductNew::whereIn('guid', $info['related_products'])->get();

        $this->product->relatedProducts()->sync($related->pluck('id'));
    }
}
