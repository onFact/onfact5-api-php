<?php


namespace OnFact\Model;


use Doctrine\Common\Inflector\Inflector;

class Model implements \JsonSerializable
{
    protected $_accessible = [];
    protected $_fields = [];

    public function __construct($fields = [])
    {
        $this->readOpenApi();

        foreach ($fields as $attribute => $value) {
            $this->_set($attribute, $value);
        }
    }

    private function readOpenApi() {
        $json = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'openapi.json';
        $openApi = file_get_contents($json);
        $openApi = json_decode($openApi);
        $model = substr(strrchr(get_class($this), "\\"), 1);
        $this->_accessible = $openApi->components->schemas->{$model}->properties;
    }

    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) == 'set') {
            $field = Inflector::tableize(substr($name, 3));
            $value = $arguments[0];

            return $this->_set($field, $value);
        } elseif (substr($name, 0, 3) == 'get') {
            $field = Inflector::tableize(substr($name, 3));

            return $this->_get($field);
        }
    }


    protected function _get($name)
    {
        return isset($this->_fields[$name]) ? $this->_fields[$name] : null;
    }

    protected function _set($name, $value)
    {
        $this->_fields[$name] = $value;

        return $this;
    }

    public function jsonSerialize() {
        return $this->_fields;
    }

    public function toArray() {
        return $this->_fields;
    }

}
