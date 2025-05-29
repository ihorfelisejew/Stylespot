<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'category_id',
        'name',
        'slug',
        'description',
        'color',
        'price',
        'main_image',
        'sku'
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            $product->sku = self::generateSku();
        });
    }

    private static function generateSku(): string
    {
        // Генеруємо формат типу 27C6505
        $part1 = rand(10, 99);
        $part2 = chr(rand(65, 90));
        $part3 = rand(1000, 9999);
        return $part1 . $part2 . $part3;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
