<?php

namespace Peresmishnyk\Task\Services;

use Dice\Dice;
use League\Container\Container;
use Peresmishnyk\Task\Traits\ForwardCall;

class App
{
    use ForwardCall;

    /**
     * Объект одиночки храниться в статичном поле класса. Это поле — массив, так
     * как мы позволим нашему Одиночке иметь подклассы. Все элементы этого
     * массива будут экземплярами кокретных подклассов Одиночки. Не волнуйтесь,
     * мы вот-вот познакомимся с тем, как это работает.
     */
    private static $instances = [];
    private static $config_key;
    private static $container;


    /**
     * Конструктор Одиночки всегда должен быть скрытым, чтобы предотвратить
     * создание объекта через оператор new.
     */
    protected function __construct($config_key)
    {
        static::$config_key = $config_key;
        static::$container = new \Dice\Dice();
        static::$container = static::$container->addRules(config('dice'));
    }

    /**
     * Одиночки не должны быть клонируемыми.
     */
    protected function __clone()
    {
    }

    /**
     * Одиночки не должны быть восстанавливаемыми из строк.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * Это статический метод, управляющий доступом к экземпляру одиночки. При
     * первом запуске, он создаёт экземпляр одиночки и помещает его в
     * статическое поле. При последующих запусках, он возвращает клиенту объект,
     * хранящийся в статическом поле.bre
     *
     * Эта реализация позволяет вам расширять класс Одиночки, сохраняя повсюду
     * только один экземпляр каждого подкласса.
     */
    public static function getInstance($config_key = 'app'): App
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static($config_key);
        }

        return self::$instances[$cls];
    }

    protected static function service($service, $params = [])
    {
        if (!isset(self::$instances[$service])) {
            if (isset(config(static::$config_key)['services'][$service])) {
                self::$instances[$service] = static::$container->create(config(static::$config_key)['services'][$service]);
            } else {
                throw(new \Exception('Undefined service "' . $service . '"'));
            }
        }
        return self::$instances[$service];
    }
}