<?php
/**
 * Created by PhpStorm.
 * User: catchme
 * Date: 2018/12/19
 * Time: 下午6:16
 */




if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param  string $abstract
     * @param  array $parameters
     * @return mixed|\Illuminate\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return \App\Rocket\Context::getApp();
        }

        return empty($parameters)
            ? \App\Rocket\Context::getApp()->make($abstract)
            : \App\Rocket\Context::getApp()->makeWith($abstract, $parameters);
    }
}

if (!function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string $key
     * @param  mixed $default
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app(\Illuminate\Http\Request::class);
        }

        if (is_array($key)) {
            return app(\Illuminate\Http\Request::class)->only($key);
        }

        return data_get(app(\Illuminate\Http\Request::class)->all(), $key, $default);
    }
}

