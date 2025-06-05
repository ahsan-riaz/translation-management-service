<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = ['key', 'group'];

    public function values()
    {
        return $this->hasMany(TranslationValue::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
