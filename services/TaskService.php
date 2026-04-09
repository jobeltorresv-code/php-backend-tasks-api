<?php

class TaskService
{
    private $taskModel;

    public function __construct($taskModel)
    {
        $this->taskModel = $taskModel;
    }

    public function getAllTasks()
    {
        return $this->taskModel->getAll();
    }

    public function getTaskById($id)
    {
        $task = $this->taskModel->getById($id);

        if (!$task) {
            throw new Exception("Task not found", 404);
        }

        return $task;
    }

    public function createTask($data)
    {
        return $this->taskModel->create($data);
    }

    public function updateTask($id, $data)
    {
        $task = $this->taskModel->getById($id);

        if (!$task) {
            throw new Exception("Task not found", 404);
        }

        return $this->taskModel->update($id, $data);
    }

    public function deleteTask($id)
    {
        $task = $this->taskModel->getById($id);

        if (!$task) {
            throw new Exception("Task not found", 404);
        }

        return $this->taskModel->softDelete($id);
    }

    public function restoreTask($id)
    {
        $task = $this->taskModel->getByIdIncludingDeleted($id);

        if (!$task) {
            throw new Exception("Task not found", 404);
        }

        if ($task['deleted_at'] === null) {
            throw new Exception("Task is not deleted", 400);
        }

        return $this->taskModel->restore($id);
    }
}