<?php

namespace core;


use PDO;
/**
 * Base model
 */
abstract class Model
{
    protected static $db;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];
    protected static $fillable = [];

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if(in_array($key, static::$fillable)) {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
    * Dynamically retrieve attributes on the model.
    *
    * @param  string  $key
    * @return mixed
    */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }
    /**
    * Get an attribute from the model.
    *
    * @param  string  $key
    * @return mixed
    */
    public function getAttribute($key)
    {
        if (!$key) {
            return;
        }
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return null;
    }

    /**
    * Dynamically set attributes on the model.
    *
    * @param  string  $key
    * @param  mixed  $value
    * @return void
    */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
    * Set a given attribute on the model.
    *
    * @param  string  $key
    * @param  mixed  $value
    * @return $this
    */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Get the PDO database connection
     *
     * @return PDO
     */
    protected static function getDB()
    {
        if(!static::$db) {
            $dbConfig = APP_CONFIG['db'];
            $dsn = 'mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['name'] . ';charset=utf8';
            static::$db = new PDO($dsn, $dbConfig['user'], $dbConfig['password']);
            // Throw an Exception when an error occurs
            static::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return static::$db;
    }

    public static function findAll(array $params = [], $sort = [])
    {
        $values = [];
        $query = 'SELECT * FROM ' . static::tableName();
        if(!empty($params)) {
            $conditions = static::createConditions($params,$values);
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }
        if(!empty($sort)) {
            $query.= ' ORDER BY ' . $sort['by'] . ' ' . $sort['directions'];
        }
        return static::find($query, $values);
    }

    private static function createConditions($params, &$values)
    {
        $conditions = [];
        foreach ($params as $key => $value) {
            if(is_array($value) && count($value) == 3) {
                $conditions[] = '`' . $value[0] .'` '. $value[1] .' ?';
                $values [] = $value[2];
            } else {
                $values[] = $value;
                $conditions[] = '`' . $key .'` = ?';
            }
        }

        return $conditions;
    }

    /**
     * @param $params
     * @return bool|null|static
     */
    public static function findOne($params)
    {
        $values = [];
        $query = 'SELECT * FROM ' . static::tableName();
        if(is_int($params)) {
            $values[] = $params;
            $query .= ' WHERE `id` = ? LIMIT 1';
        } elseif (is_array($params)) {
            $conditions = static::createConditions($params,$values);
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        } else {
            return false;
        }

        $result = static::find($query, $values);
        if(empty($result)) {
            return null;
        }
        return (new static($result[0]));
    }

    private static function find($query, $values = [])
    {
        $db = static::getDB();
        $stmt = $db->prepare($query);
        $stmt->execute($values);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($params)
    {
        $values = [];
        $query = 'UPDATE ' . static::tableName() . ' SET ';
        $conditions = static::createConditions($params,$values);
        $query .= implode(', ', $conditions);
        $query .= " WHERE id = " . $this->id;
        $db = static::getDB();
        return $db->prepare($query)->execute($values);
    }

    public function delete($params = [])
    {
        $values = [];
        $query = 'DELETE FROM ' . static::tableName();
        $conditions = static::createConditions($params,$values);
        $query .= implode(', ', $conditions);
        $query .= " WHERE id = " . $this->id;
        $db = static::getDB();
        return $db->prepare($query)->execute($values);
    }

    /**
     * @param $values
     * @return static
     */
    public static function create($values)
    {
        $values = array_filter($values, function($item) {
            return in_array($item, static::$fillable);
        }, ARRAY_FILTER_USE_KEY);

        $id = (int) static::insert($values);
        if($id) {
            return static::findOne($id);
        }
        return false;
    }

    private static function insert($values)
    {
        $columns = array_keys($values);
        $parameters = array_values($values);
        $query = 'INSERT INTO ' . static::tableName() . '('. implode(', ',  $columns) .') VALUES(' . str_repeat(' ?,', count($columns) - 1) . ' ?)';
        $db = static::getDB();
        if($db->prepare($query)->execute($parameters)) {
            return $db->lastInsertId();
        };
        return false;
    }

    public static function tableName()
    {
        $tableName = explode('\\', static::class);

        return strtolower(end($tableName));
    }

    public function __toString()
    {
        return json_encode($this->attributes);
    }

    public function toArray()
    {
        return $this->attributes;
    }
}