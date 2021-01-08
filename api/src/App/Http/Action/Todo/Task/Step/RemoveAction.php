<?php

declare(strict_types=1);

namespace App\Http\Action\Todo\Task\Step;

use App\Exception\ForbiddenException;
use App\Validation\Validator;
use Domain\Todo\Entity\Schedule\Task\Step\Id;
use Domain\Todo\Entity\Schedule\Task\Step\Step;
use Domain\Todo\Entity\Schedule\Task\Step\StepRepository;
use Domain\Todo\UseCase\Schedule\Task\Step\Remove\Command;
use Domain\Todo\UseCase\Schedule\Task\Step\Remove\Handler;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class RemoveAction implements RequestHandlerInterface
{
    private StepRepository $steps;
    private Handler $handler;
    private Validator $validator;
    private ResponseFactory $response;

    /**
     * RemoveAction constructor.
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
     * @OA\Delete(
     *     path="/todo/task/step/remove",
     *     tags={"Remove step"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"id"},
     *             @OA\Property(property="id", type="string")
     *         )
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
        $id = intval($body['id'] ?? '');

        $step = $this->steps->getById(new Id($id));
        $this->canDeleteStep($request->getAttribute('oauth_user_id'), $step);

        $this->validator->validate($command = new Command($id));
        $this->handler->handle($command);

        return $this->response->json([], 204);
    }

    /**
     * @param string $userId
     * @param Step $step
     * @throws ForbiddenException
     */
    private function canDeleteStep(string $userId, Step $step): void
    {
        if ($userId !== $step->getTask()->getSchedule()->getPerson()->getId()->getValue()) {
            throw new ForbiddenException();
        }
    }
}
