<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price',
        'stock', 'image', 'is_featured', 'is_active'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
