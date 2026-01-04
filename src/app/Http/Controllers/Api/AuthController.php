<?php

namespace App\Http\Controllers\Api;

use App\Actions\GenerateGameLinkAction;
use App\Actions\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\GameLinkResource;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        protected RegisterUserAction $registerUserAction,
        protected GenerateGameLinkAction $generateGameLinkAction
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $user = $this->registerUserAction->execute(
                $validated['username'],
                $validated['phonenumber']
            );

            $link = $this->generateGameLinkAction->execute($user);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage(), $e->getCode());
        }

        return $this->respondCreated(new GameLinkResource($link));
    }
}
