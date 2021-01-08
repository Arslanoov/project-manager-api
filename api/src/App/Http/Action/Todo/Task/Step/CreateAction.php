<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use App\Validation\Validator;
use Doctrine\DBAL\DBALException;
use Domain\Todo\Entity\Schedule\Task\Id;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\Entity\Schedule\Task\TaskRepository;
use Domain\Todo\UseCase\Schedule\Task\Step\Create\Command;
use Domain\Todo\UseCase\Schedule\Task\Step\Create\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class CreateAction implements RequestHandlerInterface
{
    private StepRepository $steps;
    private TaskRepository $tasks;
    private Handler $handler;
    private Validator $validator;
    private ResponseFactory $response;

    /**
     * CreateAction constructor.
     * @param StepRepository $steps
     * @param TaskRepository $tasks
     * @param Handler $handler
     * @param Validator $validator
     * @param ResponseFactory $response
     */
    public function __construct(
        StepRepository $steps,
        TaskRepository $tasks,
        Handler $handler,
        Validator $validator,
        ResponseFactory $response
    ) {
        $this->steps = $steps;
        $this->tasks = $tasks;
        $this->handler = $handler;
        $this->validator = $validator;
        $this->response = $response;
    }

    /**
     * @OA\Post(
     *     path="/todo/task/step/create",
     *     tags={"Create step"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"task_id", "name"},
     *             @OA\Property(property="task_id", type="string"),
     *             @OA\Property(property="name", type="string")
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
     *         response=201,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string")
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ForbiddenException
     * @throws DBALException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $taskId = $body['task_id'] ?? '';
        $name = $body['name'] ?? '';

        $task = $this->tasks->getById(new Id($taskId));
        $this->canCreateStepForTask($request->getAttribute('oauth_user_id'), $task);

        $stepId = $this->steps->getNextId();
        $this->validator->validate($command = new Command($stepId->getValue(), $taskId, $name));
        $this->handler->handle($command);

        return $this->response->json([
            'id' => $stepId->getValue()
        ], 201);
    }

    /**
     * @param string $userId
     * @param Task $task
     * @throws ForbiddenException
     */
    private function canCreateStepForTask(string $userId, Task $task): void
    {
        if ($userId !== $task->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
