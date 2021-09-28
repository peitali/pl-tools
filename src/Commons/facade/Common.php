<?php

namespace PlTools\Commons\facade;

use PlTools\Facade;

/**
 * @see \PlTools\Commons\Common
 * @method static array required(bool $condition, string $err) 条件验证
 */
class Common extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @return string|void
     */
    protected static function getFacadeClass()
    {
        return 'common';
    }
}