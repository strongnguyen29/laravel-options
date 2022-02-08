<?php

namespace StrongNguyen\Options;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Option extends Model
{
    const CACHE_KEY = 'app_options';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Casts.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'json',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var [type]
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Determine if the given option value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function exists($key)
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Get the specified option value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        // Lấy từ cache
        $options = Cache::remember(self::CACHE_KEY, 3600, function () {
            return self::all()->pluck('value', 'key')->toArray();
        });

        if (isset($options[$key])) return $options[$key];

        if ($option = self::where('key', $key)->first()) {
            return $option->value;
        }

        return $default ?? config('options.' . $key . '.value');
    }

    /**
     * Set a given option value.
     *
     * @param  array|string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            self::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget(self::CACHE_KEY);
        // @todo: return the option
    }

    /**
     * Remove/delete the specified option value.
     *
     * @param  string  $key
     * @return bool
     */
    public function remove($key)
    {
        $success = self::where('key', $key)->delete();

        if ($success) Cache::forget(self::CACHE_KEY);

        return (bool) $success;
    }
}
