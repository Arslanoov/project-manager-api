<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task;

use App\Exception\ForbiddenException;
use App\Validation\Validator;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\Todo\UseCase\Schedule\Task\Remove\Command;
use Domain\Todo\UseCase\Schedule\Task\Remove\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class RemoveAction implements RequestHandlerInterface
{
    private TaskRepository $tasks;
    private Handler $handler;
    private Validator $validator;
    private ResponseFactory $response;

    /**
     * RemoveAction constructor.
     * @param TaskRepository $tasks
     * @param Handler $handler
     * @param Validator $validator
     * @param ResponseFactory $response
     */
    public function __construct(
        TaskRepository $tasks,
        Handler $handler,
        Validator $validator,
        ResponseFactory $response
    ) {
        $this->tasks = $tasks;
        $this->handler = $handler;
        $this->validator = $validator;
        $this->response = $response;
    }

    /**
     * @OA\Delete(
     *     path="/todo/task/remove",
     *     tags={"Remove task"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"task_id"},
     *             @OA\Property(property="task_id", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Errors",
     *          @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *          )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ForbiddenException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $taskId = $body['task_id'] ?? '';

        $task = $this->tasks->getById(new Id($taskId));
        $this->canRemoveTask($request->getAttribute('oauth_user_id'), $task);

        $this->validator->validate($command = new Command($taskId));
        $this->handler->handle($command);

        return $this->response->json([], 204);
    }

    /**
     * @param string $userId
     * @param Task $task
     * @throws ForbiddenException
     */
    private function canRemoveTask(string $userId, Task $task): void
    {
        if ($userId !== $task->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
