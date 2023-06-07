<?php

namespace Ericliucn\Distpicker;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid\Filter;
use Illuminate\Support\ServiceProvider;

class DistpickerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(ChinaDistpicker $extension)
    {
        if (! ChinaDistpicker::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-distpicker');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/ericliucn/distpicker')],
                'laravel-admin-distpicker'
            );
        }

        Admin::booting(function () {
            Form::extend('distpicker', Distpicker::class);
            Filter::extend('distpicker', DistpickerFilter::class);
        });
    }
}