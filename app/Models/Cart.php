<?php

namespace App\Models;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

/**
 * Class Cart
 */
class Cart
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var int
     */
    public $count;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var array
     */
    public $products;

    /**
     * @var Collection
     */
    public $items;

    /**
     * @var bool
     */
    public $allowProceedCheckout;

    /**
     * Cart constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;

        $this->count = 0;
        $this->amount = 0;
        $this->products = [];

        if ($this->session->has('cart')) {
            $this->init($this->session->get('cart'));
        }

        $this->allowProceedCheckout = $this->isAllowProceedCheckout();
    }

    /**
     * @param int $productId
     * @param int $count
     */
    public function add(int $productId, int $count): void
    {
        if (isset($this->products[$productId])) {
            $this->products[$productId] += $count;
            if ($productId == 1 && $this->products[$productId] > 2) {
                $this->products[$productId] = 2;
            }

            if ($this->products[$productId] <= 0) {
                unset($this->products[$productId]);
            }
        } else {
            $this->products[$productId] = $count;
            if ($productId == 1 && $count > 2) {
                $this->products[$productId] = 2;
            }
        }

        $this->save();
        $this->calc();
    }

    /**
     * @param ProductNew $product
     * @return bool
     */
    public function has(ProductNew $product): bool
    {
        return isset($this->products[$product->id]);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->allowProceedCheckout = $this->isAllowProceedCheckout();

        return json_encode($this);
    }

    /**
     * @return Collection
     */
    public function items(): Collection
    {
        return collect($this->products)->map(
            function ($qty, $id) {
                $product = ProductNew::find($id);

                return [
                    'id' => $id,
                    'image' => $product->smallPhoto,
                    'name' => $product->name,
                    'price' => $product->price($qty),
                    'old_price' => number_format($product->old_price, 0 ,',', ''),
                    'discount' => number_format($product->discount, 0, '', ''),
                    'qty' => $qty,
                    'amount' => $product->price($qty) * $qty,
                ];
            }
        );
    }

    /**
     * @return bool
     */
    public function isAllowProceedCheckout(): bool
    {
        if ($this->amount >= 500) {
            return true;
        }

        if (isset($this->products[1])) {
            return true;
        }

        if (isset($this->products[460]) && $this->products[460] >= 2) {
            return true;
        }

        if (isset($this->products[2])) {
            return true;
        }

        return false;
    }

    /**
     * Load cart from session
     *
     * @param array $cart
     */
    private function init(array $cart): void
    {
        $this->products = $cart;
        $this->calc();
    }

    /**
     * Save cart to session
     */
    private function save(): void
    {
        $this->session->put('cart', $this->products);
    }

    /**
     * Calculate count and amount
     */
    private function calc(): void
    {
        $count = 0;
        $amount = 0;
        $products = ProductNew::whereIn('id', array_keys($this->products))->get();
        foreach ($this->products as $id => $productCount) {
            $count += $productCount;
            $amount += $products->first(
                    function (ProductNew $product) use ($id) {
                        return $product->id == $id;
                    }
                )->price($productCount) * $productCount;
        }
        $this->count = $count;
        $this->amount = $amount;
        $this->items = $this->items();
    }
}
