<?php

namespace App\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use JsonSerializable;

use function response;

trait ApiResponseTrait
{
    public function respondNotFound(?string $message = null, ?string $key = 'message'): JsonResponse
    {
        $message = $message ? $message : 'Not Found!';

        return $this->apiResponse(
            [$key => $this->morphMessage($message)],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @param array|Arrayable|JsonSerializable|null $contents
     */
    public function respondWithSuccess($contents = null): JsonResponse
    {
        $contents = $this->morphToArray($contents) ?? [];

        $data = [] === $contents ? ['message' => 1] : $contents;
        return $this->apiResponse($data);
    }

    public function respondOk(?string $message = null): JsonResponse
    {
        return $this->respondWithSuccess(['message' => $message]);
    }

    public function respondUnAuthenticated(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
            ['error' => $message ?? 'Unauthenticated'],
            Response::HTTP_UNAUTHORIZED
        );
    }

    public function respondForbidden(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
            ['error' => $message ?? 'Forbidden'],
            Response::HTTP_FORBIDDEN
        );
    }

    public function respondError($errors = null, int $code = 0): JsonResponse
    {
        $errors ??= [];
        $errors = $this->morphToArray($errors);
        $errors = $errors === [] ? ['error' => __('Something went wrong')] : $errors;
        if (is_string($errors) || $errors instanceof \Exception) {
            $errors = ['error' => $errors];
        }

        if ($code === 0) {
            $code = Response::HTTP_BAD_REQUEST;
        }

        return $this->apiResponse(
            ['errors' => $errors],
            $code
        );
    }

    /**
     * @param array|Arrayable|JsonSerializable|null $data
     */
    public function respondCreated($data = null): JsonResponse
    {
        $data ??= [];
        return $this->apiResponse(
            $this->morphToArray($data),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param string|\Exception $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondFailedValidation($errors = null): JsonResponse
    {
        $errors ??= [];
        $errors = $this->morphToArray($errors);
        $errors = $errors === [] ? ['error' => __('Something went wrong')] : $errors;
        if (is_string($errors) || $errors instanceof \Exception) {
            $errors = ['error' => $errors];
        }

        return $this->apiResponse(
            ['errors' => $errors],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function respondNoContent(): Response
    {
        return response()->noContent();
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        return $this->respondWithSuccess([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    private function apiResponse(array $data, int $code = 200): JsonResponse
    {
        return response()->json($data, $code, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array|Arrayable|JsonSerializable|null $data
     * @return array|null
     */
    private function morphToArray($data): ?array
    {
        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        if ($data instanceof JsonSerializable) {
            return $data->jsonSerialize();
        }

        return $data;
    }

    /**
     * @param string|\Exception $message
     * @return string
     */
    private function morphMessage($message): string
    {
        return $message instanceof \Exception
          ? $message->getMessage()
          : $message;
    }
}
