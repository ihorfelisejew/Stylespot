<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description'];

    public function latestProduct()
    {
        return $this->hasOne(Product::class)->latestOfMany();
    }

    public function latestMaleProduct()
    {
        return $this->hasOne(Product::class)
            ->ofMany(
                ['created_at' => 'max'],
                function ($query) {
                    $query->where('gender', 'male');
                }
            );
    }

    public function latestFemaleProduct()
    {
        return $this->hasOne(Product::class)
            ->ofMany(
                ['created_at' => 'max'],
                function ($query) {
                    $query->where('gender', 'female');
                }
            );
    }




    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
