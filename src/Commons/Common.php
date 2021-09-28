<?php

namespace PlTools\Commons;

class Common
{
    /**
     * 条件验证
     * @param bool $condition 验证条件
     * @param string $err 失败后信息
     * @return bool
     * @throws \Exception
     */
    public function required(bool $condition, string $err)
    {
        if (!$condition) throw new \Exception($err);
        return true;
    }
}