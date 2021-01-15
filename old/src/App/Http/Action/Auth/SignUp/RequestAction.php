<?php

declare(strict_types=1);

namespace App\Http\Action\Auth\SignUp;

use App\Service\TransactionInterface;
use App\Service\UuidGeneratorInterface;
use App\Validation\Validator;
use Doctrine\DBAL\ConnectionException;
use Domain\Todo\UseCase\Person;
use Domain\User\Entity\User\Id;
use Domain\User\Entity\User\UserRepository;
use Domain\User\UseCase\User;
use Exception;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestAction implements RequestHandlerInterface
{
    private Validator $validator;
    private TransactionInterface $transaction;
    private UuidGeneratorInterface $uuid;
    private User\SignUp\Request\Handler $requestHandler;
    private UserRepository $users;
    private Person\Create\Handler $personCreateHandler;
    private ResponseFactory $response;

    /**
     * RequestAction constructor.
     * @param Validator $validator
     * @param TransactionInterface $transaction
     * @param UuidGeneratorInterface $uuid
     * @param User\SignUp\Request\Handler $requestHandler
     * @param UserRepository $users
     * @param Person\Create\Handler $personCreateHandler
     * @param ResponseFactory $response
     */
    public function __construct(
        Validator $validator,
        TransactionInterface $transaction,
        UuidGeneratorInterface $uuid,
        User\SignUp\Request\Handler $requestHandler,
        UserRepository $users,
        Person\Create\Handler $personCreateHandler,
        ResponseFactory $response
    ) {
        $this->validator = $validator;
        $this->transaction = $transaction;
        $this->uuid = $uuid;
        $this->requestHandler = $requestHandler;
        $this->users = $users;
        $this->personCreateHandler = $personCreateHandler;
        $this->response = $response;
    }

    /**
     * @OA\Post(
     *     path="/auth/sign-up/request",
     *     tags={"Sign Request"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"login", "email", "password"},
     *             @OA\Property(property="login", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
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

        $id = $this->uuid->uuid1();
        $login = $body['login'] ?? '';

        $requestCommand = new User\SignUp\Request\Command($id, $login, $body['email'] ?? '', $body['password'] ?? '');
        $createPersonCommand = new Person\Create\Command($id, $login);

        $this->transaction->begin();

        try {
            $this->validator->validateObjects($requestCommand, $createPersonCommand);
            $this->requestHandler->handle($requestCommand);
            $this->personCreateHandler->handle($createPersonCommand);
            $this->transaction->commit();
        } catch (Exception $e) {
            $this->transaction->rollback();
            throw $e;
        }

        $user = $this->users->getById(new Id($id));

        return $this->response->json([
            'email' => $user->getEmail()->getValue()
        ], 201);
    }
}
