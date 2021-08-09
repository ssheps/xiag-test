<?php

namespace App\Storage;

use App\Model;

// Нужно разделять на разные классы логику работы с БД и получением из неё данных
class DataStorage
{
    /**
     * @var \PDO 
     */
    public $pdo;

    public function __construct()
    {
    	// Эти данные лучше перенести в .env файл, т.к. они могут отличаться на разных стендах

		// Здесь также лучше сделать обработчик исключений, т.к. это критичное место системы и в случае, если мы не сможем
		// как-то промониторить проблему здесь, сайт будет лежать полностью. Можно даже сделать уведомление по почте от
		// таком исключении
        $this->pdo = new \PDO('mysql:dbname=task_tracker;host=127.0.0.1', 'user');
    }

    /**
     * @param int $projectId
     * @throws Model\NotFoundException
     */
    public function getProjectById($projectId)
    {
    	// Опечатка в запросе, не стоит закрывающая кавычка
		// Также в поиск нужно добавить поля active, deleted
        $stmt = $this->pdo->query('SELECT * FROM project WHERE id = ' . (int) $projectId);

        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return new Model\Project($row);
        }

        // Это не исключительная ситуация, проекта действительно может не быть (например, он был удалён, а у кого-то осталась ссылка на него)
        throw new Model\NotFoundException();
    }

    /**
     * @param int $project_id
     * @param int $limit
     * @param int $offset
     */
    public function getTasksByProjectId(int $project_id, $limit, $offset)
    {
    	// $project_id никак не экранирован — потенциальная уязвимость
		// судя по использованию метода, аргументы $limit и $offset могут быть не заданы. Нужно предусмотреть для них дефолтные значения
        $stmt = $this->pdo->query("SELECT * FROM task WHERE project_id = $project_id LIMIT ?, ?");

        // Лучше использовать вариант, когда мы экранируем параметры по формату ключ-значение
        $stmt->execute([$limit, $offset]);

        $tasks = [];
        foreach ($stmt->fetchAll() as $row) {
            $tasks[] = new Model\Task($row);
        }

        return $tasks;
    }

    /**
     * @param array $data
     * @param int $projectId
     * @return Model\Task
     */
    public function createTask(array $data, $projectId)
    {
        $data['project_id'] = $projectId;

        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(function ($v) {
            return is_string($v) ? '"' . $v . '"' : $v;
        }, $data));

        // Отсутствие экранирование может потенциально вызывать sql-инъекцию
		// Отсутствие контроля полей может быть проблемой. Выше упомянал пример с формой
        $this->pdo->query("INSERT INTO task ($fields) VALUES ($values)");

        // Не обрабатываются исключения, если что-то пошло не так при вставке
        $data['id'] = $this->pdo->query('SELECT MAX(id) FROM task')->fetchColumn();

        // Судя по методу выше, Task используется для возвращения всех данных по задаче. Здесь же предполагается
		// использовать её примерно так же, как массив. Нужно выбрать стратегию использования
        return new Model\Task($data);
    }
}
