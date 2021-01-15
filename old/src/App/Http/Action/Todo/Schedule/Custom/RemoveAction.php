<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Schedule\Custom;

use App\Exception\ForbiddenException;
use App\Validation\Validator;
use Domain\Todo\Entity\Schedule\Id;
use Domain\Todo\Entity\Schedule\Schedule;
use Domain\Todo\Entity\Schedule\ScheduleRepository;
use Domain\Todo\UseCase\Schedule\Remove\Command;
use Domain\Todo\UseCase\Schedule\Remove\Handler;
use Framework\Http\Psr7\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class RemoveAction implements RequestHandlerInterface
{
    private ScheduleRepository $schedules;
    private Validator $validator;
    private Handler $handler;
    private ResponseFactory $response;

    /**
     * RemoveAction constructor.
     * @param ScheduleRepository $schedules
     * @param Validator $validator
     * @param Handler $handler
     * @param ResponseFactory $response
     */
    public function __construct(
        ScheduleRepository $schedules,
        Validator $validator,
        Handler $handler,
        ResponseFactory $response
    ) {
        $this->schedules = $schedules;
        $this->validator = $validator;
        $this->handler = $handler;
        $this->response = $response;
    }

    /**
     * @OA\Delete(
     *     path="/todo/custom/remove",
     *     tags={"Custom schedule remove"},
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

        $userId = $request->getAttribute('oauth_user_id');
        $id = (string) $body['id'] ?? '';

        $schedule = $this->schedules->getById(new Id($id));
        $this->canRemove($userId, $schedule);

        if ($schedule->isNotCustom()) {
            throw new InvalidArgumentException('Schedule is not custom');
        }

        $this->validator->validate($command = new Command($id));

        $this->handler->handle($command);

        return $this->response->json([], 204);
    }

    /**
     * @param string $userId
     * @param Schedule $schedule
     * @throws ForbiddenException
     */
    private function canRemove(string $userId, Schedule $schedule): void
    {
        if ($userId !== $schedule->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
