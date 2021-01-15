<?php

declare(strict_types=1);

namespace App\Http\Action\Profile;

use App\Validation\Validator;
use Domain\Todo\UseCase\Person\ChangePhoto\Command;
use Domain\Todo\UseCase\Person\ChangePhoto\Handler;
use Framework\Http\Psr7\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class UploadPhotoAction implements RequestHandlerInterface
{
    private Handler $handler;
    private Validator $validator;
    private ResponseFactory $response;

    /**
     * UploadPhotoAction constructor.
     * @param Handler $handler
     * @param Validator $validator
     * @param ResponseFactory $response
     */
    public function __construct(Handler $handler, Validator $validator, ResponseFactory $response)
    {
        $this->handler = $handler;
        $this->validator = $validator;
        $this->response = $response;
    }

    /**
     * @OA\Post(
     *     path="/profile/upload/photo",
     *     tags={"Profile photo upload"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"task_id", "name", "file"},
     *             @OA\Property(property="task_id", type="string")
     *          ),
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="file",
     *                      type="file",
     *                      description="Background photo file",
     *                      @OA\Items(type="string", format="binary")
     *                  )
     *              )
     *          )
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
     *     security={{"oauth2": {"common"}}}
     * )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('oauth_user_id');
        if (!$file = $request->getUploadedFiles()['file']) {
            throw new InvalidArgumentException('File required');
        }

        $this->validator->validate($command = new Command($file, $userId));

        $this->handler->handle($command);

        return $this->response->json([], 204);
    }
}
