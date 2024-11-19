<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{

    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'number',
        'unit_price',
        'status',
        'shipping_price',
        'notes',
    ];

    public function customer():BelongsTo{
        return $this->belongsTo(Customer::class);
    }
    //

    public function items():HasMany{
        return $this->hasMany(order_item::class);
    }
}
