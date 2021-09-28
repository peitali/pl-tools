<?php
/**
 * Author: peitali
 * Datetime: 2021/9/27 21:47
 */

namespace PlTools;

class Facade
{
    /**
     * 创建Facade实例
     * @param string $class
     * @param array $args
     * @return mixed
     */
    protected static function createFacade(string $class = '', array $args = [])
    {
        $class = $class ?: static::class;

        $classPath = dirname($class, 2);

        $facadeClass = static::getFacadeClass();

        $class = $classPath . '\\' . ucfirst($facadeClass);

        return new $class();
    }

    /**
     * 获取当前Facade对应类名
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
    }

    /**
     * 调用实际类的方法
     * @param $method
     * @param $params
     * @return false|mixed
     */
    public static function __callStatic($method, $params)
    {
        return call_user_func_array([static::createFacade(), $method], $params);
    }
}