<?php


namespace OnFact\Model;


use Doctrine\Common\Inflector\Inflector;

class ModelFactory
{

    public static function create(string $model, $data = [])
    {
        $model = Inflector::singularize($model);
        $class = __NAMESPACE__ . '\\' . $model;

        if (class_exists($class)) {
            return new $class($data);
        }
    }

}
