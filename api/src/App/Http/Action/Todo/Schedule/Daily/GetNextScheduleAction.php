<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Daily;

use App\Exception\ForbiddenException;
use App\Service\Date;
use App\Validation\Validator;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Entity\Schedule\Id as ScheduleId;
use Domain\Todo\Entity\Person\Id as PersonId;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\UseCase\Schedule\CreateByDate\Command;
use Domain\Todo\UseCase\Schedule\CreateByDate\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetNextScheduleAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private Handler $handler;
    private Validator $validator;
    private ResponseFactory $response;

    /**
     * GetNextScheduleAction constructor.
     * @param ScheduleRepository $schedules
     * @param PersonRepository $persons
     * @param Handler $handler
     * @param Validator $validator
     * @param ResponseFactory $response
     */
    public function __construct(
        ScheduleRepository $schedules,
        PersonRepository $persons,
        Handler $handler,
        Validator $validator,
        ResponseFactory $response
    ) {
        $this->schedules = $schedules;
        $this->persons = $persons;
        $this->handler = $handler;
        $this->validator = $validator;
        $this->response = $response;
    }

    /**
     * @OA\Get(
     *     path="/todo/daily/next/{id}",
     *     tags={"Get next daily schedule"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
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
     *             @OA\Property(property="date", type="array", @OA\Items(
     *                 @OA\Property(property="day", type="integer"),
     *                 @OA\Property(property="month", type="integer"),
     *                 @OA\Property(property="year", type="integer"),
     *                 @OA\Property(property="string", type="string")
     *             )),
     *             @OA\Property(property="tasks", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="importantLevel", type="string"),
     *                 @OA\Property(property="status", type="string"),
     *                 @OA\Property(property="stepsCount", type="integer"),
     *                 @OA\Property(property="finishedSteps", type="integer")
     *             )),
     *             @OA\Property(property="tasksCount", type="integer")
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
        $userId = $request->getAttribute('oauth_user_id');
        $scheduleId = $request->getAttribute('id') ?? '';

        $schedule = $this->schedules->getById(new ScheduleId($scheduleId));
        $person = $this->persons->getById(new PersonId($userId));
        $this->canGetNext($userId, $schedule);

        $nextSchedule = $this->schedules->findNextSchedule($person, $schedule);
        if (!$nextSchedule) {
            $this->validator->validate($command = new Command(
                $schedule->getDate()->modify('+1 day'),
                $userId
            ));
            $this->handler->handle($command);
            $nextSchedule = $this->schedules->findNextSchedule($person, $schedule);
        }

        /** @var Schedule $nextSchedule */

        return $this->response->json([
            'id' => $nextSchedule->getId()->getValue(),
            'date' => [
                'day' => $nextSchedule->getDate()->format('d'),
                'month' => intval($nextSchedule->getDate()->format('m')) - 1,
                'year' => $nextSchedule->getDate()->format('Y'),
                'string' => $nextSchedule->getDate()->format('d') . 'th ' .
                    Date::MONTHS[intval($nextSchedule->getDate()->format('m')) - 1]
            ],
            'tasksCount' => $nextSchedule->getTasksCount(),
            'tasks' => $this->tasks($nextSchedule)
        ]);
    }

    /**
     * @param string $userId
     * @param Schedule $schedule
     * @throws ForbiddenException
     */
    private function canGetNext(string $userId, Schedule $schedule): void
    {
        if ($userId !== $schedule->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
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
