<?php

namespace App\Console\Commands;

use App\Mail\ExportOrdersMail;
use App\Models\Order;
use App\Models\ProductNew;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class ExportOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:orders {date? : Export orders on date, default is today}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Export today's orders to CSV and send to admin email";

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
     * @return void
     */
    public function handle(): void
    {
        $exportTime = Carbon::now();
        if ($date = $this->argument('date')) {
            $exportTime = Carbon::parse($date);
        }

        $fileName = $this->exportFileName($exportTime);
        $csvFile = fopen($fileName, 'wb');

        $orders = $this->getOrders($exportTime);

        $csvFileheaders = [
            'ID',
            'Время создания',
            'Сумма заказа',
            'Статус заказ',
            'Статус оплаты',
            'Клиент',
            'Продукты',
        ];
        fputcsv($csvFile, $csvFileheaders);

        $ordersRows = [];
        foreach ($orders as $order) {
            $orderRow = [];
            $orderRow['id'] = $order->id;
            $orderRow['created_at'] = $order->created_at->format('d.m.Y H:i:s');
            $orderRow['price'] = $order->price.' руб.';
            $orderRow['status'] = $order->status;
            $orderRow['payment_status'] = $order->payment_status;
            $orderRow['user'] = trim(
                $order->user->first_name.' '.$order->user->last_name.' '.$order->user->company_name.' '.$order->user->phone
            );
            $orderRow['products'] = '';
            foreach ($order->products as $product) {
                $orderRow['products'] .= $this->formatProductToString($product);
            }
            fputcsv($csvFile, $orderRow);
            $ordersRows[] = $orderRow;
        }
        fclose($csvFile);

        Mail::send(new ExportOrdersMail($fileName, $exportTime));
    }

    private function exportFileName(Carbon $exportTime): string
    {
        return '/tmp/export_orders_data_'.$exportTime->timestamp.'.csv';
    }

    private function formatProductToString(ProductNew $product): string
    {
        return $product->name.' - '.$product->price.' руб.'.' - '.$product->pivot->product_count.' шт.'.PHP_EOL;
    }

    private function getOrders(Carbon $onDate = null): Collection
    {
        if ($onDate === null) {
            $onDate = Carbon::now();
        }

        return Order::where('created_at', '>=', $onDate->startOfDay())
            ->where('created_at', '<=', $onDate->copy()->endOfDay())
            ->get();
    }
}
