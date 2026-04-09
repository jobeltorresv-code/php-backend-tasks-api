<?php
class TaskController
{
    private $taskService;

    public function __construct($taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        echo json_encode($tasks);
    }
}