<?php

namespace App\Model;

class Project
{
    /**
     * @var array
     */
    public $_data;

	// Нет никакого преимущества у класса такого типа перед простым массивом. Желательно прописать свойства,
	// аналогичные всем полям у записи в БД. Так можно будет контролировать тип данных
    public function __construct($data)
    {
        $this->_data = $data;
    }

    /**
     * @return int
     */
    public function getId()
    {
    	// лучше это вынести в отдельное поле
        return (int) $this->_data['id'];
    }

    /**
     * @return string
     */
	// Преобразовываться в json — не зона ответственности данного класса.
    public function toJson()
    {
        return json_encode($this->_data);
    }
}
