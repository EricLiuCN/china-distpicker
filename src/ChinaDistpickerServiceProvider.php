<?php

namespace Encore\ChinaDistpicker;

use Encore\Admin\Admin;
use Encore\Admin\Assets;
use Encore\Admin\Form;
use Encore\Admin\Table\Filter;
use Illuminate\Support\ServiceProvider;

class ChinaDistpickerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(ChinaDistpicker $extension)
    {
        if (! ChinaDistpicker::boot()) {
            return ;
        }

        $this->loadViewsFrom($extension->views(), 'laravel-admin-china-distpicker');

        Assets::define('distpicker', [
            'deps' => 'jquery',
            'js' => 'vendor/laravel-admin-ext/china-distpicker/dist/distpicker.min.js',
        ]);

        $this->publishes(
            [$assets => public_path('vendor/laravel-admin-ext/china-distpicker')],
            'laravel-admin-china-distpicker'
        );

        Admin::booting(function () {
            Form::extend('distpicker', Distpicker::class);
            Filter::extend('distpicker', DistpickerFilter::class);
        });
    }
}
