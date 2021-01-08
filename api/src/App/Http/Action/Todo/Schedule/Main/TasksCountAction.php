<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Main;

use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\Entity\Schedule\Task\Task;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class TasksCountAction implements RequestHandlerInterface
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
     *     path="/todo/main/tasks/count",
     *     tags={"Get main schedule tasks count"},
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
        $person = $this->persons->getById(new Id($request->getAttribute('oauth_user_id') ?? ''));
        $schedule = $this->schedules->getPersonMainSchedule($person);

        $tasksCount = count($schedule->getTasksCollection()->filter(function (Task $task) {
            return $task->isNotComplete();
        }));

        return $this->response->json([
            'count' => $tasksCount
        ]);
    }
}
