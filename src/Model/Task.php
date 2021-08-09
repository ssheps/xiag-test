<?php

namespace App\Model;

class Task implements \JsonSerializable
{
    /**
     * @var array
     */
    private $_data;

    // Нет никакого преимущества у класса такого типа перед простым массивом. Желательно прописать свойства,
	// аналогичные всем полям у записи в БД. Так можно будет контролировать тип данных
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    // Преобразовываться в json — не зона ответственности данного класса.
    public function jsonSerialize(): array
    {
        return $this->_data;
    }
}
