<?php

declare(strict_types=1);

namespace App\Http\Action\Profile;

use Domain\Exception\Person\BackgroundPhotoNotFound;
use Domain\Todo\Entity\Person\BackgroundPhoto;
use Domain\Todo\Entity\Person\Id;
use Domain\Todo\Entity\Person\PersonRepository;
use Framework\Http\Psr7\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Annotations as OA;

final class GetPhotoAction implements RequestHandlerInterface
{
    private string $path;
    private PersonRepository $persons;
    private ResponseFactory $response;

    /**
     * GetPhotoAction constructor.
     * @param string $path
     * @param PersonRepository $persons
     * @param ResponseFactory $response
     */
    public function __construct(string $path, PersonRepository $persons, ResponseFactory $response)
    {
        $this->path = $path;
        $this->persons = $persons;
        $this->response = $response;
    }

    /**
     * @OA\Get(
     *     path="/profile/get/photo",
     *     tags={"Profile Photo"},
     *     @OA\Response(
     *         response=200,
     *         description="Success response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="url", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Photo not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", nullable=true)
     *         )
     *     ),
     *     security={{"oauth2": {"common"}}}
 *     )
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $person = $this->persons->getById(new Id($request->getAttribute('oauth_user_id')));
        if (!$person->hasBackgroundPhoto() or $person->getBackgroundPhoto() === null) {
            throw new BackgroundPhotoNotFound();
        }

        /** @var BackgroundPhoto $photo */
        $photo = $person->getBackgroundPhoto();

        return $this->response->json([
            'url' => $this->path . '/' . $photo->getPath()
        ]);
    }
}
