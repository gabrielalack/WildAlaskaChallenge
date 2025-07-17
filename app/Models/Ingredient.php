<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Hide unnecessary pivot element from model data
     * 
     * @var array
     */
    protected $hidden = ['pivot'];

}
