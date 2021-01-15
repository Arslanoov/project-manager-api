<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Daily;

use App\Exception\ForbiddenException;
use App\Service\Date;
use DateTimeImmutable;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Exception;
use Framework\Http\Psr7\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class GetByDateAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private ResponseFactory $response;

    /**
     * GetByDateAction constructor.
     * @param ScheduleRepository $schedules
     * @param PersonRepository $persons
     * @param ResponseFactory $response
     */
    public function __construct(ScheduleRepository $schedules, PersonRepository $persons, ResponseFactory $response)
    {
        $this->schedules = $schedules;
        $this->persons = $persons;
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
        $userId = $request->getAttribute('oauth_user_id');
        $day = $request->getAttribute('day') ?? '';
        $month = $request->getAttribute('month') ?? '';
        $year  = $request->getAttribute('year') ?? '';

        $person = $this->persons->getById(new Id($userId));

        try {
            $schedule = $this->schedules->getDailyByDate($person, new DateTimeImmutable(
                ($month + 1) . '/' . $day . '/' . $year . ' 00:00:00'
            ));
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
