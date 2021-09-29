<?php
/**
 * Author: peitali
 * Datetime: 2021/9/29 17:06
 */

namespace PlTools\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class ClassNotFoundException extends Exception implements NotFoundExceptionInterface
{
    protected $class;

    public function __construct(string $message, string $class = '', Throwable $previous = null)
    {
        $this->message = $message;
        $this->class = $class;

        parent::__construct($message, 0, $previous);
    }

    /**
     * 获取类名
     * @access public
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}