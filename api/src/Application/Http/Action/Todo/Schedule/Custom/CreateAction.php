<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Custom;

use App\Service\UuidGeneratorInterface;
use App\Validation\Validator;
use Domain\Model\Todo\Entity\Person\Id as PersonId;
use Domain\Model\Todo\Entity\Person\PersonRepository;
use Domain\Model\Todo\Entity\Schedule\Id;
use Domain\Model\Todo\Entity\Schedule\Schedule;
use Domain\Model\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Model\Todo\Entity\Schedule\Task\Task;
use Domain\Model\Todo\UseCase\Schedule\CreateCustom\Command;
use Domain\Model\Todo\UseCase\Schedule\CreateCustom\Handler;
use App\Http\Response\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class CreateAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private Validator $validate;
    private UuidGeneratorInterface $uuid;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * CreateAction constructor.
     * @param ScheduleRepository $schedules
     * @param PersonRepository $persons
     * @param Validator $validate
     * @param UuidGeneratorInterface $uuid
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(
        ScheduleRepository $schedules,
        PersonRepository $persons,
        Validator $validate,
        UuidGeneratorInterface $uuid,
        Handler $handler,
        ResponseFactory $response
    ) {
        $this->schedules = $schedules;
        $this->persons = $persons;
        $this->validate = $validate;
        $this->uuid = $uuid;
        $this->handler = $handler;
        $this->response = $response;
    }

    /**
     * @OA\Post(
     *     path="/todo/custom/create",
     *     tags={"Custom schedule create"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name"},
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
     *             @OA\Property(property="id", type="string"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="tasks", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="importantLevel", type="string"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="stepsCount", type="integer"),
     *                 @OA\Property(property="finishedSteps", type="integer")
     *                 @OA\Property(property="isCustom", type="boolean")
     *             )),
     *             @OA\Property(property="tasksCount", type="integer")
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var array $body */
        $body = json_decode($request->getBody()->getContents(), true);

        /** @var string $userId */
        $userId = $request->getAttribute('oauth_user_id');
        $id = $this->uuid->uuid4();
        /** @var string $name */
        $name = $body['name'] ?? '';

        $this->validate->validate($command = new Command($id, $userId, $name));

        $this->handler->handle($command);

        $person = $this->persons->getById(new PersonId($userId));
        $schedule = $this->schedules->getCustomById($person, new Id($id));

        return $this->response->json([
            'id' => $schedule->getId()->getValue(),
            'name' => $schedule->getName()->getValue(),
            'tasks' => $this->tasks($schedule),
            'tasksCount' => $schedule->getTasksCount(),
            'isCustom' => true
        ], 201);
    }

    private function tasks(Schedule $schedule): array
    {
        return array_map(function (Task $task) {
            return [
                'id' => $task->getId()->getValue(),
                'name' => $task->getName()->getValue(),
                'description' => $task->getDescription()->getValue(),
                'importantLevel' => $task->getLevel()->getValue(),
                'status' => $task->getStatus()->getValue(),
                'stepsCount' => $task->getStepsCollection()->count(),
                'finishedSteps' => $task->getFinishedSteps()
            ];
        }, array_reverse($schedule->getTasks()));
    }
}
