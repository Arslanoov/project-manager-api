<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task;

use App\Exception\ForbiddenException;
use App\Validation\Validator;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\Todo\UseCase\Schedule\Task\Edit\Command;
use Domain\Todo\UseCase\Schedule\Task\Edit\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class EditAction implements RequestHandlerInterface
{
    private Handler $handler;
    private Validator $validator;
    private TaskRepository $tasks;
    private ResponseFactory $response;

    /**
     * EditAction constructor.
     * @param Handler $handler
     * @param Validator $validator
     * @param TaskRepository $tasks
     * @param ResponseFactory $response
     */
    public function __construct(
        Handler $handler,
        Validator $validator,
        TaskRepository $tasks,
        ResponseFactory $response
    ) {
        $this->handler = $handler;
        $this->validator = $validator;
        $this->tasks = $tasks;
        $this->response = $response;
    }

    /**
     * @OA\Patch(
     *     path="/todo/task/edit",
     *     tags={"Edit task"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id", "name", "description", "level"},
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="level", type="string")
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

        $taskId = $body['id'] ?? '';
        $name = $body['name'] ?? '';
        $description = $body['description'] ?? '';
        $level = $body['level'] ?? '';

        $task = $this->tasks->getById(new Id($taskId));
        $this->canEditTask($request->getAttribute('oauth_user_id'), $task);

        $this->validator->validate($command = new Command($taskId, $name, $level, $description));
        $this->handler->handle($command);

        return $this->response->json([], 204);
    }

    /**
     * @param string $userId
     * @param Task $task
     * @throws ForbiddenException
     */
    private function canEditTask(string $userId, Task $task): void
    {
        if ($userId !== $task->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
