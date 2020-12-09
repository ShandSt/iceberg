<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\User';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'first_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'first_name',
        'last_name',
        'phone',
        'guid',
        'email',
        'company_name',
        'inn',
    ];

    public function title()
    {
        return $this->name;
    }

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
                ->sortable()
                ->onlyOnDetail(),

            Text::make(
                'Name',
                function () {
                    return $this->name;
                }
            )
                ->onlyOnIndex(),

            Text::make('First Name')
                ->hideFromIndex(),

            Text::make('Last Name')
                ->hideFromIndex(),

            Text::make('Email')
                ->sortable()
                ->rules('nullable', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Text::make('Phone')
                ->sortable()
                ->rules('required', 'regex:/\+79\d{2}\d{3}\d{2}\d{2}/')
                ->creationRules('unique:users,phone')
                ->updateRules('unique:users,phone,{{resourceId}}'),

            Boolean::make('Is Admin')
                ->sortable()
                ->rules('required', 'boolean'),

            Select::make('Status')
                ->options(['not active' => 'not active', 'active' => 'active']),

            Text::make('Company Name')
                ->hideFromIndex(),

            Text::make('INN', 'inn')
                ->hideFromIndex(),

            Select::make('Type')
                ->options(['individual' => 'individual', 'legal' => 'legal'])
                ->onlyOnDetail(),

            Number::make('Balance')
                ->onlyOnDetail(),

            Number::make('Bottles')
                ->onlyOnDetail(),

            Boolean::make('Has Debt')
                ->onlyOnDetail(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),

            DateTime::make('Created At')
                ->onlyOnDetail(),

            DateTime::make('Updated At')
                ->onlyOnDetail(),

            BelongsToMany::make('Addresses'),

            HasMany::make('ConfirmCodes'),

            HasMany::make('Orders'),
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
