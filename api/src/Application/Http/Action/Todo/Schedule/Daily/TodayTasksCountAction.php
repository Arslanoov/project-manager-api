<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Daily;

use Doctrine\Common\Collections\Collection;
use Domain\Model\Todo\Entity\Person\Id;
use Domain\Model\Todo\Entity\Person\PersonRepository;
use Domain\Model\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Model\Todo\Entity\Schedule\Task\Task;
use App\Http\Response\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TodayTasksCountAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private PersonRepository $persons;
    private ResponseFactory $response;

    /**
     * TasksCountAction constructor.
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
     *     path="/todo/daily/today/tasks/count",
     *     tags={"Get today daily schedule tasks count"},
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="count", type="integer")
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var string $userId */
        $userId = $request->getAttribute('oauth_user_id') ?? '';
        $person = $this->persons->getById(new Id($userId));

        $schedule = $this->schedules->findPersonTodaySchedule($person);
        if ($schedule) {
            $tasks = $schedule->getTasksCollection();
            $tasksCount = count($tasks->filter(function (Task $task) {
                return $task->isNotComplete();
            }));
        } else {
            $tasksCount = 0;
        }

        return $this->response->json([
            'count' => $tasksCount
        ]);
    }
}
