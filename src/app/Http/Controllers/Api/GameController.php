<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeactivateGameLinkAction;
use App\Actions\PlayGameAction;
use App\Actions\RegenerateGameLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameLinkResource;
use App\Http\Resources\GameResultResource;
use App\Http\Resources\GameResultResourceCollection;
use App\Models\GameLink;
use App\Repositories\Contracts\GameResultRepositoryInterface;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    public function __construct(
        protected PlayGameAction $playGameAction,
        protected RegenerateGameLinkAction $regenerateGameLinkAction,
        protected DeactivateGameLinkAction $deactivateGameLinkAction,
        protected GameResultRepositoryInterface $gameResultRepository
    ) {}

    public function show(GameLink $token): JsonResponse
    {
        return $this->respondWithSuccess([
            'valid' => true,
            'data' => [
                'username' => $token->user->username,
            ],
        ]);
    }

    public function store(GameLink $token): JsonResponse
    {
        try {
            $gameResult = $this->playGameAction->execute($token);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage(), $e->getCode());
        }

        return $this->respondCreated(new GameResultResource($gameResult));
    }

    public function index(GameLink $token): GameResultResourceCollection|JsonResponse
    {
        try {
            $history = $this->gameResultRepository->getLatestForLinkUser($token);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage(), $e->getCode());
        }

        return new GameResultResourceCollection($history);
    }

    public function regenerate(GameLink $token): JsonResponse
    {
        try {
            $updatedLink = $this->regenerateGameLinkAction->execute($token);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage(), $e->getCode());
        }

        return $this->respondCreated(new GameLinkResource($updatedLink));
    }

    public function deactivate(GameLink $token): JsonResponse
    {
        try {
            $this->deactivateGameLinkAction->execute($token);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage(), $e->getCode());
        }

        return $this->respondOk('Link deactivated');
    }
}
