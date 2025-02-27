<?php

namespace Encore\ChinaDistpicker;

use Encore\Admin\Form\Field;
use Illuminate\Support\Arr;

class Distpicker extends Field
{
    /**
     * @var string
     */
    protected $view = 'laravel-admin-china-distpicker::select';

    /**
     * @var array
     */
    protected $columnKeys = ['province', 'city', 'district'];

    /**
     * @var array
     */
    protected $placeholder = [];

    /**
     * Distpicker constructor.
     *
     * @param array $column
     * @param array $arguments
     */
    public function __construct($column, $arguments)
    {
        if (!Arr::isAssoc($column)) {
            $this->column = array_combine($this->columnKeys, $column);
        } else {
            $this->column      = array_combine($this->columnKeys, array_keys($column));
            $this->placeholder = array_combine($this->columnKeys, $column);
        }

        $this->label = empty($arguments) ? '地区选择' : current($arguments);
    }

    public function getValidator(array $input)
    {
        if ($this->validator) {
            return $this->validator->call($this, $input);
        }

        $rules = $attributes = [];

        if (!$fieldRules = $this->getRules()) {
            return false;
        }

        foreach ($this->column as $key => $column) {
            if (!Arr::has($input, $column)) {
                continue;
            }
            $input[$column] = Arr::get($input, $column);
            $rules[$column] = $fieldRules;
            $attributes[$column] = $this->label."[$column]";
        }

        return \validator($input, $rules, $this->getValidationMessages(), $attributes);
    }

    /**
     * @param int $count
     * @return $this
     */
    public function autoselect($count = 0)
    {
        return $this->attribute('data-autoselect', $count);
    }

    /**
     * @param int $count
     * @return $this
     */
    public function valuetype($type = 'code')
    {
        return $this->attribute('data-value-type', $type);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->id = uniqid('distpicker-');

        $this->addVariables([
            'province' => Arr::get($this->value(), 'province') ?: Arr::get($this->placeholder, 'province'),
            'city'     => Arr::get($this->value(), 'city')     ?: Arr::get($this->placeholder, 'city'),
            'district' => Arr::get($this->value(), 'district') ?: Arr::get($this->placeholder, 'district'),
        ]);

        return parent::render();
    }
}
