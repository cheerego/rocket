<?php
/**
 * Created by PhpStorm.
 * User: catchme
 * Date: 2018/12/21
 * Time: 下午4:14
 */

namespace App\Rocket;


class Context
{
    public static $idMaps = [];


    public static $apps = [];

    public static $datas = [];

    public static function setParentId()
    {
        $id = static::getCoId();
        static::$idMaps[$id] = $id;
        return $id;
    }

    public static function getPid($id = null)
    {
        if ($id === null) {
            $id = static::getCoId();
        }
        return static::$idMaps[$id];
    }

    public static function coroutine(\Closure $cb)
    {
        $parent_id = static::getCoId();
        $app = clone Context::getApp();
        return go(function () use ($cb, $parent_id, $app) {
            $child_id = static::getCoId();
            static::$apps[$child_id] = $app;
            static::$idMaps[$child_id] = static::$idMaps[$parent_id];
            $cb();
            unset(static::$idMaps[$child_id]);
            unset(static::$apps[$child_id]);
        });
    }


    public static function setApp($app)
    {
        static::$apps[static::getCoId()] = $app;
    }

    public static function getApp()
    {
        return static::$apps[static::getCoId()];
    }


    public static function getCoId()
    {
        return \co::getCid();
    }

    /**
     * 不传 默认清除当前协程
     * @param null $id
     */
    public static function clearIdMaps($id = null)
    {
        if ($id === null) {
            $id = static::getCoId();
        }
        unset(static::$idMaps[$id]);
    }

    /**
     * 不传默认父协程
     * @param null $id
     */
    public static function clearAppsAndDatas($id = null)
    {
        $id = static::getPid($id);
        unset(static::$apps[$id]);
        unset(static::$datas[$id]);
    }
}
