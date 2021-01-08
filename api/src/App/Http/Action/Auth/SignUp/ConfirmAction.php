<?php

declare(strict_types=1);

namespace App\Http\Action\Auth\SignUp;

use App\Service\TransactionInterface;
use App\Validation\Validator;
use Doctrine\DBAL\ConnectionException;
use Domain\Todo\UseCase\Person;
use Domain\User\Entity\User\UserRepository;
use Domain\User\UseCase\User;
use Exception;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Domain\Todo\UseCase\Schedule;
use OpenApi\Annotations as OA;

final class ConfirmAction implements RequestHandlerInterface
{
    private Schedule\CreateMain\Handler $createScheduleHandler;
    private User\SignUp\Confirm\Handler $signUpConfirmHandler;
    private UserRepository $users;
    private Validator $validator;
    private ResponseFactory $response;
    private TransactionInterface $transaction;

    /**
     * ConfirmAction constructor.
     * @param Schedule\CreateMain\Handler $createScheduleHandler
     * @param User\SignUp\Confirm\Handler $signUpConfirmHandler
     * @param UserRepository $users
     * @param Validator $validator
     * @param ResponseFactory $response
     * @param TransactionInterface $transaction
     */
    public function __construct(
        Schedule\CreateMain\Handler $createScheduleHandler,
        User\SignUp\Confirm\Handler $signUpConfirmHandler,
        UserRepository $users,
        Validator $validator,
        ResponseFactory $response,
        TransactionInterface $transaction
    ) {
        $this->createScheduleHandler = $createScheduleHandler;
        $this->signUpConfirmHandler = $signUpConfirmHandler;
        $this->users = $users;
        $this->validator = $validator;
        $this->response = $response;
        $this->transaction = $transaction;
    }

    /**
     * @OA\Post(
     *     path="/auth/sign-up/confirm",
     *     tags={"Sign Up Confirm"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"token"},
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success response",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Domain errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true),
     *             @OA\Property(property="errors", type="array", @OA\Items())
     *         )
     *     )
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ConnectionException
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);

        $token = $body['token'] ?? '';
        $user = $this->users->getBySignUpConfirmToken($token);

        $signUpConfirmCommand = new User\SignUp\Confirm\Command($token);
        $createScheduleCommand = new Schedule\CreateMain\Command($user->getId()->getValue());

        $this->transaction->begin();

        try {
            $this->validator->validateObjects($signUpConfirmCommand, $createScheduleCommand);
            $this->signUpConfirmHandler->handle($signUpConfirmCommand);
            $this->createScheduleHandler->handle($createScheduleCommand);
            $this->transaction->commit();
        } catch (Exception $e) {
            $this->transaction->rollback();
            throw $e;
        }

        return $this->response->json([], 204);
    }
}
