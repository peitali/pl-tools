<?php
/**
 * Author: peitali
 * Datetime: 2021/9/28 16:35
 */

namespace PlTools;

use PlTools\Exceptions\ClassNotFoundException;
use Psr\Container\ContainerInterface;
use ArrayAccess;
use Closure;

class Container implements ContainerInterface, ArrayAccess
{
    /**
     * 当前全局容器对象实例
     * @var static
     */
    protected static $instance;

    /**
     * 容器中的对象实例
     * @var array
     */
    protected $instances = [];

    /**
     * 容器绑定标识
     * @var array
     */
    protected $bind = [];

    /**
     * 获取当前容器的实例（单例）
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * 设置当前容器的实例
     * @param $instance
     * @return mixed
     */
    public static function setInstance($instance)
    {
        return static::$instance = $instance;
    }

    /**
     * 判断容器中是否存在类或标识
     * @param string $abstract 类名或标识
     */
    public function bound(string $abstract)
    {
        return isset($this->bind[$abstract]) || isset($this->instances[$abstract]);
    }

    /**
     * 获取
     * @param string $id
     * @return mixed|void
     * @throws ClassNotFoundException
     */
    public function get(string $abstract)
    {
        if ($this->has($abstract)) {
            return $this->make($abstract);
        }
        throw new ClassNotFoundException('class not exists: ' . $abstract, $abstract);
    }

    /**
     * 判断容器是否存在类或标识
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return $this->bound($id);
    }

    /**
     * 根据别名获取真实类名
     * @param string $abstract
     * @return string
     */
    public function getAlias(string $abstract): string
    {
        if (isset($this->bind[$abstract])) {
            $bind = $this->bind[$abstract];

            if (is_string($bind)) {
                return $this->getAlias($bind);
            }
        }

        return $abstract;
    }

    /**
     * 创建类的实例 已经存在则直接获取
     * @param string $abstract
     * @param array $params
     * @param bool $newInstance
     * @return mixed|void
     */
    public function make(string $abstract, array $params = [], bool $newInstance = false)
    {
        $abstract = $this->getAlias($abstract);

        if (isset($this->instances[$abstract]) && !$newInstance) {
            return $this->instances[$abstract];
        }
    }

    /**
     * 删除容器中的对象实例
     * @access public
     * @param string $name 类名或者标识
     * @return void
     */
    public function delete($name)
    {
        $name = $this->getAlias($name);

        if (isset($this->instances[$name])) {
            unset($this->instances[$name]);
        }
    }

    /**
     * 绑定一个类实例到容器
     * @access public
     * @param string $abstract 类名或者标识
     * @param object $instance 类的实例
     * @return $this
     */
    public function instance(string $abstract, $instance)
    {
        $abstract = $this->getAlias($abstract);
        $this->instances[$abstract] = $instance;

        return $this;
    }

    /**
     * 绑定一个类、闭包、实例、接口实现到容器
     * @access public
     * @param string|array $abstract 类标识、接口
     * @param mixed $concrete 要绑定的类、闭包或者实例
     * @return $this
     */
    public function bind($abstract, $concrete = null)
    {
        if (is_array($abstract)) {
            foreach ($abstract as $key => $val) {
                $this->bind($key, $val);
            }
        } elseif ($concrete instanceof Closure) {
            $this->bind[$abstract] = $concrete;
        } elseif (is_object($concrete)) {
            $this->instance($abstract, $concrete);
        } else {
            $abstract = $this->getAlias($abstract);
            if ($abstract != $concrete) {
                $this->bind[$abstract] = $concrete;
            }
        }

        return $this;
    }

    /**
     * 判断容器中是否存在对象实例
     * @access public
     * @param string $abstract 类名或者标识
     * @return bool
     */
    public function exists(string $abstract): bool
    {
        $abstract = $this->getAlias($abstract);

        return isset($this->instances[$abstract]);
    }

    public function offsetExists($key)
    {
        return $this->exists($key);
    }

    public function offsetGet($key)
    {
        return $this->make($key);
    }

    public function offsetSet($key, $value)
    {
        $this->bind($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->delete($key);
    }
}