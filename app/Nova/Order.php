<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Order';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Boolean::make(
                'Loaded to 1C',
                function () {
                    if ($this->guid == null || $this->guid == '00000000-0000-0000-0000-000000000000') {
                        return false;
                    }
                    return true;
                }
            ),

            Text::make('GUID', 'guid')
                ->onlyOnDetail(),

            BelongsTo::make('User')
                ->searchable()
                ->rules('required'),

            BelongsTo::make('Address')
                ->searchable()
                ->rules('required'),

            Select::make('Payment Method')
                ->options(
                    [
                        'Cash' => 'Cash',
                        'Card' => 'Card',
                    ]
                ),

            Select::make('Payment Status')
                ->options(
                    [
                        \App\Models\Order::STATUS_PAYED => \App\Models\Order::STATUS_PAYED,
                        \App\Models\Order::STATUS_CANCELED => \App\Models\Order::STATUS_CANCELED,
                    ]
                ),

            Select::make('Status')
                ->options(
                    [
                        \App\Models\Order::STATUS_NEW => \App\Models\Order::STATUS_NEW,
                        \App\Models\Order::STATUS_PROCESSING => \App\Models\Order::STATUS_PROCESSING,
                        \App\Models\Order::STATUS_CONFIRMED => \App\Models\Order::STATUS_CONFIRMED,
                        \App\Models\Order::STATUS_IN_TRANSIT => \App\Models\Order::STATUS_IN_TRANSIT,
                        \App\Models\Order::STATUS_COMPLETED => \App\Models\Order::STATUS_COMPLETED,
                        \App\Models\Order::STATUS_CANCELED => \App\Models\Order::STATUS_CANCELED,
                    ]
                )
                ->rules('required'),

            DateTime::make('Created At')
                ->sortable()
                ->exceptOnForms(),

            Text::make('From', 'order_source')
                ->exceptOnForms(),

            Boolean::make('Delivery SMS', 'delivery_sms')
                ->onlyOnDetail(),

            Boolean::make('Back Call', 'back_call')
                ->onlyOnDetail(),

            Boolean::make('Intercom does not work', 'intercom_does_not_work')
                ->onlyOnDetail(),

            Boolean::make('Contactless delivery', 'contactless')
                ->onlyOnDetail(),

            Textarea::make('Comment')
                ->onlyOnDetail(),

            Code::make('Delivery', 'date_of_delivery')
                ->json(JSON_UNESCAPED_UNICODE)
                ->onlyOnDetail(),

            DateTime::make('Sync Failed At', 'sync_failed_at')
                ->onlyOnDetail(),

            Number::make('Sync Failed Attempts Count', 'sync_attempts_count')
                ->onlyOnDetail(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
