<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use App\Validation\Validator;
use Domain\Model\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Model\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Model\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Model\Todo\UseCase\Schedule\Task\Step\ChangeStatus\Command;
use Domain\Model\Todo\UseCase\Schedule\Task\Step\ChangeStatus\Handler;
use App\Http\Response\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class ChangeStatusListAction implements RequestHandlerInterface
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
     *     path="/todo/task/step/change-status/list",
     *     tags={"Change steps status"},
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
        /** @var array $body */
        $body = json_decode($request->getBody()->getContents(), true);

        /** @var string $userId */
        $userId = $request->getAttribute('oauth_user_id');

        /** @var array<int> $ids */
        $ids = $body['ids'] ?? [];
        /** @var string $status */
        $status = $body['status'] ?? '';

        foreach ($ids as $id) {
            $id = intval($id ?? 0);
            $step = $this->steps->getById(new Id($id));
            $this->canChangeStatus($userId, $step);

            $this->validator->validate($command = new Command($id, $status));
            $this->handler->handle($command);
        }

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
