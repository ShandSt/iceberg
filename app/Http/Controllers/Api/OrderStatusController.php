<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Laravel\Telescope\Telescope;

class OrderStatusController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->header('token') !== 'flTf9vXg5ymuyyxSXAGbGnUZrjLwMnIn') {
            abort(401, 'Wrong token');
        }

        Telescope::tag(function () {
            return ['OrdersStatusUpdating'];
        });

        $attributes = $request->validate(['orders' => 'required|array']);
        $orders = $attributes['orders'];

        $updated = 0;

        foreach ($orders as $orderData) {
            $order = Order::find($orderData['id']);
            if (!$order) {
                continue;
            }

            if ($order->status != $orderData['status']) {
                $order->status = $orderData['status'];
                $order->save();
                $updated++;
            }
        }

        return response(
            [
                'orders' => count($orders),
                'updated' => $updated,
            ],
            200
        );
    }
}
