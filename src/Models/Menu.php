<?php

namespace Tjslash\CtoMenuManager\Models;

use Illuminate\Support\Str;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};
use Cviebrock\EloquentSluggable\Sluggable;
use Tjslash\CtoPageManager\Models\Page;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    BelongsToMany
};

class Menu extends Model
{
    use CrudTrait, Sluggable, SoftDeletes, HasFactory;

    /**
     * Table name
     * 
     * @var string
     */
    protected $table = 'menu';

    /**
     * Guarded attributes
     * 
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        '_blank' => 'boolean',
        'active' => 'boolean'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'unique' => true
            ]
        ];
    }

    /**
     * Get page
     * 
     * @return BelongsTo
     */
    public function page() : BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get parent
     * 
     * @return BelongsTo
     */
    public function parent() : BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get parents
     * 
     * @return BelongToMany
     */
    public function parents() : BelongsToMany
    {
        return $this->belongsToMany(
            Menu::class, 
            'menu_parent', 
            'menu_id', 
            'parent_id'
        );
    }

    /**
     * Get childs
     * 
     * @return BelongToMany
     */
    public function childs() : BelongsToMany
    {
        return $this->belongsToMany(
            Menu::class, 
            'menu_parent', 
            'parent_id', 
            'menu_id'
        );
    }

    /**
     * Scope active menu items
     *
     * @param Builder $query
     * 
     * @return Builder
     */
    public function scopeActive(Builder $query) : Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope order by priority menu items
     * 
     * @param Builder $query
     * 
     * @return Builder
     */
    public function scopeOrder(Builder $query) : Builder
    {
        return $query->orderBy('priority');
    }

    /**
     * Scope menu items by parent slug
     *
     * @param Builder $query
     * 
     * @return Builder
     */
    public function scopePosition(Builder $query, string $position) : Builder
    {
        return $query->whereHas('parents', fn($q) => $q->where('slug', $position));
    }

    /**
     * Set url attribute
     *
     * @param string $value
     * 
     * @return void
     */
    public function setUrlAttribute(?string $value = '') : void
    {
        if ($value) {
            $this->attributes['url'] = !$this->isHttp($value) ? 
                Str::start($value, '/') : 
                $value;
        }
    }

    /**
     * Get url attribute
     *
     * @return ?string
     */
    public function getUrlAttribute() : ?string
    {
        $url = $this->page ? $this->page->url : $this->attributes['url'];

        if (!$this->isHttp($url) && !Str::startsWith($url, '/') && $url !== null) {
            $url = '/' . $url;
        }

        $this->attributes['url'] = $url;

        return $url;
    }

    /**
     * Check is http/https URL
     * 
     * @param ?string $url
     * 
     * @return bool
     */
    private function isHttp(?string $url = '') : bool
    {
        return Str::startsWith($url, 'http');
    }
}
