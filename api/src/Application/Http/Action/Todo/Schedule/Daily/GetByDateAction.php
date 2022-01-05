<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Daily;

use App\Exception\ForbiddenException;
use App\Service\Date;
use DateTimeImmutable;
use Domain\Model\Todo\Entity\Person\Id;
use Domain\Model\Todo\Entity\Person\PersonRepository;
use Domain\Model\Todo\Entity\Schedule\Schedule;
use Domain\Model\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Model\Todo\Entity\Schedule\Task\Task;
use Domain\Model\Todo\UseCase\Schedule\CreateByDate\Command;
use Domain\Model\Todo\UseCase\Schedule\CreateByDate\Handler;
use Exception;
use App\Http\Response\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class GetByDateAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private ValidatorInterface $validator;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * @param ScheduleRepository $schedules
     * @param PersonRepository $persons
     * @param ValidatorInterface $validator
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(
        ScheduleRepository $schedules,
        PersonRepository $persons,
        ValidatorInterface $validator,
        Handler $handler,
        ResponseFactory $response
    ) {
        $this->schedules = $schedules;
        $this->persons = $persons;
        $this->validator = $validator;
        $this->handler = $handler;
        $this->response = $response;
    }

    /**
     * @OA\Get(
     *     path="/todo/daily/get-by-date/{day}/{month}/{year}",
     *     tags={"Get custom schedule by date"},
     *     @OA\Parameter(
     *         name="day",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="month",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="year",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
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
        /** @var string $userId */
        $userId = $request->getAttribute('oauth_user_id');
        /** @var string $day */
        $day = $request->getAttribute('day') ?? '';
        /** @var string $month */
        $month = $request->getAttribute('month') ?? '';
        /** @var string $year */
        $year  = $request->getAttribute('year') ?? '';

        $person = $this->persons->getById(new Id($userId));

        try {
            $date = new DateTimeImmutable((intval($month) + 1) . '/' . $day . '/' . $year . ' 00:00:00');
            $schedule = $this->schedules->findDailyByDate($person, $date);

            if (!$schedule) {
                $this->validator->validate($command = new Command($date, $userId));
                $this->handler->handle($command);
                $schedule = $this->schedules->findDailyByDate($person, $date);
            }
        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }

        $this->canGetByDate($userId, $schedule);

        return $this->response->json([
            'id' => $schedule->getId()->getValue(),
            'date' => [
                'day' => (int) $schedule->getDate()->format('d'),
                'month' => (int) $schedule->getDate()->format('m') - 1,
                'year' => (int) $schedule->getDate()->format('Y'),
                'string' => $schedule->getDate()->format('d') . 'th ' .
                    Date::MONTHS[intval($schedule->getDate()->format('m')) - 1]
            ],
            'tasksCount' => $schedule->getTasksCount(),
            'tasks' => $this->tasks($schedule)
        ]);
    }

    /**
     * @param string $userId
     * @param Schedule $schedule
     * @throws ForbiddenException
     */
    private function canGetByDate(string $userId, Schedule $schedule): void
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
