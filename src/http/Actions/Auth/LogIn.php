<?php

namespace GeekBrains\LevelTwo\http\Actions\Auth;

use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\AuthTokensRepositoryInterface;
use GeekBrains\LevelTwo\http\Auth\PasswordAuthenticationInterface;
use GeekBrains\LevelTwo\Exceptions\AuthException;
use DateTimeImmutable;
use GeekBrains\LevelTwo\Blog\AuthToken;
use GeekBrains\LevelTwo\http\ErrorResponse;
use GeekBrains\LevelTwo\http\SuccessfulResponse;


class LogIn implements ActionInterface
{

    public function __construct(

        private PasswordAuthenticationInterface $passwordAuthentication,

        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }

    public function handle(Request $request): Response
    {

        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $authToken = new AuthToken(

            bin2hex(random_bytes(40)),
            $user->uuid(),

            (new DateTimeImmutable())->modify('+1 day')
        );

        $this->authTokensRepository->save($authToken);

        return new SuccessfulResponse([
            'token' => $authToken->token(),
        ]);
    }
}
