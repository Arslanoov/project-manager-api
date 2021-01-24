<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task;

use App\Exception\ForbiddenException;
use App\Service\UuidGeneratorInterface;
use App\Validation\Validator;
use Domain\Model\Todo\Entity\Schedule\Id as ScheduleId;
use Domain\Model\Todo\Entity\Schedule\Schedule;
use Domain\Model\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Model\Todo\Entity\Schedule\Task\TaskRepository;
use App\Http\Response\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Domain\Model\Todo\UseCase\Schedule\Task;
use Domain\Model\Todo\Entity\Schedule\Task\Id as TaskId;
use OpenApi\Annotations as OA;

final class CreateAction implements RequestHandlerInterface
{
    private Task\Create\Handler $handler;
    private Validator $validator;
    private ScheduleRepository $schedules;
    private TaskRepository $tasks;
    private UuidGeneratorInterface $uuid;
    private ResponseFactory $response;

    /**
     * CreateAction constructor.
     * @param Task\Create\Handler $handler
     * @param Validator $validator
     * @param ScheduleRepository $schedules
     * @param TaskRepository $tasks
     * @param UuidGeneratorInterface $uuid
     * @param ResponseFactory $response
     */
    public function __construct(
        Task\Create\Handler $handler,
        Validator $validator,
        ScheduleRepository $schedules,
        TaskRepository $tasks,
        UuidGeneratorInterface $uuid,
        ResponseFactory $response
    ) {
        $this->handler = $handler;
        $this->validator = $validator;
        $this->schedules = $schedules;
        $this->tasks = $tasks;
        $this->uuid = $uuid;
        $this->response = $response;
    }

    /**
     * @OA\Post(
     *     path="/todo/task/create",
     *     tags={"Create task"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"schedule_id", "name"},
     *             @OA\Property(property="schedule_id", type="string"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string", nullable=true),
     *             @OA\Property(property="level", type="string", nullable=true)
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
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="importantLevel", type="string"),
     *             @OA\Property(property="status", type="string")
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
        /** @var array $body */
        $body = json_decode($request->getBody()->getContents(), true);

        /** @var string $scheduleId */
        $scheduleId = $body['schedule_id'] ?? '';
        /** @var string $name */
        $name = $body['name'] ?? '';
        /** @var string $description */
        $description = $body['description'] ?? 'Description';
        /** @var string $level */
        $level = $body['level'] ?? 'Important';

        /** @var string $userId */
        $userId = $request->getAttribute('oauth_user_id');
        $schedule = $this->schedules->getById(new ScheduleId($scheduleId));
        $this->canCreateTask($userId, $schedule);

        $taskId = $this->uuid->uuid1();

        $this->validator->validate($command = new Task\Create\Command(
            $scheduleId,
            $taskId,
            $name,
            $description,
            $level
        ));
        $this->handler->handle($command);

        $task = $this->tasks->getById(new TaskId($taskId));

        return $this->response->json([
            'id' => $task->getId()->getValue(),
            'name' => $task->getName()->getValue(),
            'description' => $task->getDescription()->getValue(),
            'importantLevel' => $task->getLevel()->getValue(),
            'status' => $task->getStatus()->getValue()
        ]);
    }

    /**
     * @param string $userId
     * @param Schedule $schedule
     * @throws ForbiddenException
     */
    private function canCreateTask(string $userId, Schedule $schedule): void
    {
        if ($userId !== $schedule->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
