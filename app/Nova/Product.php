<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\ProductNew';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
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

            Text::make('GUID', 'guid')
                ->onlyOnDetail(),

            Image::make('Photo')
                ->thumbnail(
                    function () {
                        return $this->smallPhoto;
                    }
                )
                ->preview(
                    function () {
                        return $this->detailsPhoto;
                    }
                ),

            BelongsTo::make('Category'),

            Text::make('Name')
                ->sortable(),

            Textarea::make('Description'),

            Currency::make('Price')
                ->format('%.0n руб'),

            Currency::make('Old Price')
                ->displayUsing(function ($oldPrice) {
                    return $oldPrice <= 0 ? null : $oldPrice;
                })
                ->format('%.0n руб'),

            Number::make('Position')
                ->sortable(),

            BelongsToMany::make('Tags'),

            BelongsToMany::make('Related Products', 'relatedProducts', Product::class),
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
