<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use App\Validation\Validator;
use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\UseCase\Schedule\Task\Step\ChangeStatus\Command;
use Domain\Todo\UseCase\Schedule\Task\Step\ChangeStatus\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class ChangeStatusAction implements RequestHandlerInterface
{
    private StepRepository $steps;
    private Handler $handler;
    private Validator $validator;
    private ResponseFactory $response;

    /**
     * ChangeStatusAction constructor.
     * @param StepRepository $steps
     * @param Handler $handler
     * @param Validator $validator
     * @param ResponseFactory $response
     */
    public function __construct(
        StepRepository $steps,
        Handler $handler,
        Validator $validator,
        ResponseFactory $response
    ) {
        $this->steps = $steps;
        $this->handler = $handler;
        $this->validator = $validator;
        $this->response = $response;
    }

    /**
     * @OA\Patch(
     *     path="/todo/task/step/change-status",
     *     tags={"Change step status"},
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

        $id = intval($body['id'] ?? 0);
        $status = $body['status'] ?? '';

        $step = $this->steps->getById(new Id($id));
        $this->canChangeStatus($request->getAttribute('oauth_user_id'), $step);

        $this->validator->validate($command = new Command($id, $status));
        $this->handler->handle($command);

        return $this->response->json([], 204);
    }

    /**
     * @param string $userId
     * @param Step $step
     * @throws ForbiddenException
     */
    private function canChangeStatus(string $userId, Step $step): void
    {
        if ($userId !== $step->getTask()->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
