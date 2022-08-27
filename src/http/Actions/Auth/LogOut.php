<?php

namespace GeekBrains\LevelTwo\http\Actions\Auth;

use GeekBrains\LevelTwo\http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\AuthTokensRepositoryInterface;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Exceptions\AuthException;
use GeekBrains\LevelTwo\Exceptions\AuthTokenNotFoundException;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;
use DateTimeImmutable;
use GeekBrains\LevelTwo\http\SuccessfulResponse;


class LogOut implements ActionInterface
{

    private const HEADER_PREFIX = 'Bearer ';
    public function __construct(

        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $header = $request->header('Authorization');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }
        if (!str_starts_with($header, self::HEADER_PREFIX)) {
            throw new AuthException("Malformed token: [$header]");
        }
        $token = mb_substr($header, strlen(self::HEADER_PREFIX));
        try {
            $authToken = $this->authTokensRepository->get($token);
        } catch (AuthTokenNotFoundException) {
            throw new AuthException("Bad token: [$token]");
        }

        $expiresOn = (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM);

        $authToken->expiresOn($expiresOn);

        $this->authTokensRepository->save($authToken);

        return new SuccessfulResponse([
            'token' => $authToken->token(),
        ]);
    }
}
