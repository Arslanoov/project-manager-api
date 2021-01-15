<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Daily;

use App\Service\Date;
use App\Validation\Validator;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Domain\Todo\UseCase\Schedule\CreateDaily\Command;
use Domain\Todo\UseCase\Schedule\CreateDaily\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetTodayAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private Handler $handler;
    private Validator $validator;
    private ResponseFactory $response;

    /**
     * GetTodayAction constructor.
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
     *     path="/todo/daily/today",
     *     tags={"Get today daily schedule"},
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
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('oauth_user_id');

        $person = $this->persons->getById(new Id($userId));
        $schedule = $this->schedules->findPersonTodaySchedule($person);
        if (!$schedule) {
            $this->validator->validate($command = new Command($userId));
            $this->handler->handle($command);
            $schedule = $this->schedules->findPersonTodaySchedule($person);
        }

        /** @var Schedule $schedule */

        return $this->response->json([
            'id' => $schedule->getId()->getValue(),
            'date' => [
                'day' => $schedule->getDate()->format('d'),
                'month' => intval($schedule->getDate()->format('m')) - 1,
                'year' => $schedule->getDate()->format('Y'),
                'string' => $schedule->getDate()->format('d') . 'th ' .
                    Date::MONTHS[intval($schedule->getDate()->format('m')) - 1]
            ],
            'tasksCount' => $schedule->getTasksCount(),
            'tasks' => $this->tasks($schedule)
        ]);
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
