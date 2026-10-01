<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemParameter extends Model
{
    use HasFactory;

    protected $table = 'system_parameters';

    protected $guarded = [];

    /**
     * Get a parameter value by key.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $param = static::where('param_key', $key)->where('is_active', true)->first();
        return $param ? $param->param_value : $default;
    }

    /**
     * Set (upsert) a parameter value by key.
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public static function setValue(string $key, $value): void
    {
        static::updateOrCreate(
            ['param_key' => $key],
            ['param_value' => $value]
        );
    }
}
