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
    try {
        $tasks = $this->taskService->getAllTasks();

        Response::success($tasks);

    } catch (Exception $e) {
        Response::error($e->getMessage(), $e->getCode());
    }
}

    public function store()
{
    try {
        $data = json_decode(file_get_contents("php://input"), true);

        Validator::required(['title'], $data);

        $task = $this->taskService->createTask($data);

        Response::success($task, "Task created", 201);

    } catch (Exception $e) {
        Response::error($e->getMessage(), $e->getCode());
    }
}
    public function restore($id)
{
    try {
        $result = $this->taskService->restoreTask($id);

        echo json_encode([
            "status" => "success",
            "message" => "Task restored",
            "data" => $result
        ]);
    } catch (Exception $e) {
        http_response_code($e->getCode() ?: 500);

        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}
}