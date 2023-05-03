<?php

namespace App\Builders;

use App\Models\Service;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceBuilder
{
    protected string $name;

    protected int $parent_id = 0;

    protected int $position = 0;

    protected string $url = '';

    protected ?string $alias = '';

    protected string $type = 'default';

    protected bool $is_active = true;

    protected bool $is_bestseller = false;

    protected bool $is_sale = false;

    protected bool $is_popular = false;

    protected bool $is_recommendation = false;

    protected $items = [];

    protected $options = [];


    public static function builder(): self
    {
        return resolve(self::class);
    }

    public function setRoot()
    {
        $this->parent_id = 1;
        return $this;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function setAlias(string $alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function setParent(string $alias)
    {
        $service = \App\Models\Service::query()->where('alias', $alias)->first();
        $this->parent_id = $service->id;
        return $this;
    }

    public function setPosition(int $position)
    {
        $this->position = $position;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setActive($value)
    {
        $this->is_active = $value;
        return $this;
    }

    public function setBestseller(bool $value)
    {
        $this->is_bestseller = $value;
        return $this;
    }

    public function setPopular(bool $value)
    {
        $this->is_popular = $value;
        return $this;
    }

    public function setSale($value)
    {
        $this->is_sale = $value;
        return $this;
    }

    public function setRecommendation(bool $value)
    {
        $this->is_recommendation = $value;
        return $this;
    }

    public function setPackageType()
    {
        $this->type = Service::TYPE_PACKAGE;
        return $this;
    }

    public function setProductType()
    {
        $this->type = Service::TYPE_PRODUCT;
        return $this;
    }

    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    public function create()
    {
        if ($this->type != 'default' && ($this->items == [] && $this->options == [] )) {

            return throw new \Exception('Необходимо заполнить items или options');
        }

        $model = new \App\Models\Service;
        $model->alias = $this->alias ?: Str::slug($this->name);
        $model->parent_id = $this->parent_id;
        $model->url = $this->url ?: Str::slug($this->name);
        $model->is_active = $this->is_active;
        $model->is_sale = $this->is_sale;
        $model->is_recommendation = $this->is_recommendation;
        $model->is_bestseller = $this->is_bestseller;
        $model->is_popular = $this->is_popular;
        $model->type = $this->type;
        $model->name = $this->name;
        $model->position = $this->position ?: Service::where('parent_id', $this->parent_id)->max('position') + 1;

        $model->save();

        if ($this->options) {
                $model->options()->create($this->options);
        }

        if ($this->items) {
            foreach ($this->items as $k => $item) {
                $model->items()->create($item);
            }
        }

    }

    public function createIfNotExists()
    {
        if (!DB::table('services')->where([
            'name' => $this->name,
        ])->exists()) {
            $this->create();
        }
    }
}
