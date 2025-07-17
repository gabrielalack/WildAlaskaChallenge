<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use Sluggable, HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'user_id'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Return the user/author related model
     */
    public function author(): BelongsTo
    {
        // Use select to scope out unnecessary fields on this relationship
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name', 'email');
    }

    /**
     * Return the ingredients model collection
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipes_ingredients');
    }

    /**
     * Return the recipe steps list collection
     */
    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class)->orderBy('order');
    }

}
