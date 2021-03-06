<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'description',
        'ingredients',
        'price',
        'rate',
        'stock',
        'types',
        'picturePath',
        'seller_id'
    ];

    public function seller()
    {
        return $this->hasOne(seller::class, 'id', 'seller_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function toArray()
    {
        $toArray = parent::toArray();
        $toArray['picturePath'] = $this->picturePath;
        return $toArray;
    }

    public function getPicturePathAttribute()
    {
        return url('') . Storage::url($this->attributes['picturePath']);
    }
    
}
