<?php

namespace App\Console\Commands;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GeneratePrice extends Command
{
    /**
     * @var string
     */
    protected $signature = 'price:generate';

    /**
     * @var string
     */
    protected $description = 'Generate products price';

    public function handle(): void
    {
        $categories = Category::with(
            [
                'products' => function ($query) {
                    $query->orderBy('position');
                },
            ]
        )
            ->has('products')
            ->orderBy('position')->get();

        \PDF::loadView(
            'catalog.price',
            [
                'date' => Carbon::now()->format('d.m.Y'),
                'categories' => $categories,
            ]
        )->save(storage_path('app/public/price.pdf'));
    }
}
