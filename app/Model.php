<?php

namespace App;

class Model
{
    private $_data          = [];
    private $_changedFields = [];

    private $_isDataLoaded  = false;
    private $_isDeleted     = false;

    public $primaryKey = 'id';

    /** @var Database $_database */
    private $_database;

    protected $table = null;

    public function __construct()
    {
        $this->_database = Database::getInstance();
    }

    /**
     * Удаляет запись из БД
     *
     * @return $this
     */
    private function _delete()
    {
        $this->_database->delete(
            $this->getTable(),
            $this->getData()
        );

        return $this;
    }

    /**
     * Создает запись в БД
     *
     * @return $this
     */
    private function _create()
    {
        $this->_database->insert(
            $this->getTable(),
            $this->getData()
        );

        return $this;
    }

    /**
     * Обновляет запись в БД
     *
     * @return $this
     */
    private function _update()
    {
        $this->_database->update(
            $this->getTable(),
            $this->_getChangedData()
        );

        return $this;
    }

    /**
     * Изменяет флаг модели для ее последующего удаления
     *
     */
    public function delete()
    {
        $this->_isDeleted = true;

        $this->save();
    }

    /**
     * Сохраняет/создает/удаляет модель.
     *
     * Если поля модели были изменены и модель была загружена из базы, то модель сохраняется.
     *
     * Если поля модели изменены, но данные не были получены через метод load, то создается запись в БД.
     *
     * Если был вызван метод delete, то модель удаляется.
     *
     * @return $this|void
     */
    public function save()
    {
        $primaryKey = $this->getPrimaryKey();

        if ($this->_isDeleted && $this->_isDataLoaded && $primaryKey) {
            return $this->_delete();
        }

        if (empty($this->_changedFields)) {
            return;
        }

        if ($this->_isDataLoaded && $primaryKey) {
            return $this->_update();
        }

        return $this->_create();
    }

    /**
     * Возвращает значение первичного ключа.
     *
     * @return array|mixed|null
     */
    private function getPrimaryKey()
    {
        return $this->getData($this->primaryKey);
    }

    /**
     * Возвращает модель с заполненными данными из таблицы.
     *
     * @param int $value
     * @return static
     * @throws \ReflectionException
     */
    public static function load(int $value)
    {
        $model = new static();

        /** @var \PDOStatement $selectData */
        $selectData = $model->_database->query(
            "SELECT * FROM " . $model->getTable() . " WHERE " . $model->primaryKey . " = :id",
            [
                ':id' => $value
            ]
        );

        if ($selectData->rowCount() == 0) {
            throw new \RuntimeException('There are no records with this key value');
        }

        if ($selectData->rowCount() > 1) {
            throw new \RuntimeException('The are more than one row with this key value');
        }

        foreach ($selectData->fetch() as $field => $value) {
            $model->setData($field, $value);
        }

        $model->_isDataLoaded = true;

        return $model;
    }

    /**
     * Получаем название таблицы
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getTable()
    {
        if (is_null($this->table)) {
            $className = (new \ReflectionClass($this))->getShortName();
            $this->table = strtolower($className);
        }

        return $this->table;
    }

    /**
     * Получаем данные, которые были изменены
     *
     * @return array|mixed|null
     */
    private function _getChangedData()
    {
        return array_intersect_key($this->getData(), array_flip($this->_changedFields));
    }

    /**
     * Сохраняем атрибут и его значение во внутренний ассоциативный массив.
     *
     * @param $key
     * @param $value
     */
    public function setData(string $key, $value)
    {
        if (empty($key) || is_null($key)) {
            return;
        }

        if (!isset($this->_changedFields[$key])) {
            $this->_changedFields[] = $key;
        }

        $this->_data[$key] = $value;
    }

    /**
     * Получаем атрибут из внутреннего ассоциативного массива
     *
     * @param string $key
     * @return array|mixed|null
     */
    public function getData(string $key = null)
    {
        if (empty($key) || is_null($key)) {
            return $this->_data;
        }

        return $this->_data[$key] ?? null;
    }

    /**
     * Сохраняем атрибут во внутренний ассоциативный массив
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->setData($name, $value);
    }

    /**
     * Получаем атрибут из внутреннего ассоциативного массива
     *
     * @param $name
     * @return array|mixed|null
     */
    public function __get($name)
    {
        return $this->getData($name);
    }
}
