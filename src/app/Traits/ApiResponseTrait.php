<?php

namespace App\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use JsonSerializable;
use Throwable;

use function response;

trait ApiResponseTrait
{
    /**
     * @param string $message
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound(string $message = 'Not Found!', string $key = 'message'): JsonResponse
    {
        return $this->apiResponse(
            [$key => $this->morphMessage($message)],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @param array|Arrayable|JsonSerializable $contents
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithSuccess(array|Arrayable|JsonSerializable $contents = []): JsonResponse
    {
        $contents = $this->morphToArray($contents);

        $data = [] === $contents ? ['message' => 1] : $contents;
        return $this->apiResponse($data);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondOk(string $message = 'OK'): JsonResponse
    {
        return $this->respondWithSuccess(['message' => $message]);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUnAuthenticated(string $message = 'Unauthenticated'): JsonResponse
    {
        return $this->apiResponse(
            ['error' => $message],
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondForbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->apiResponse(
            ['error' => $message],
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * @param array|string|Arrayable|JsonSerializable|Throwable $errors
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondError(array|string|Arrayable|JsonSerializable|Throwable $errors = [], int $code = 0): JsonResponse
    {
        if ($errors === []) {
            $errors = ['error' => __('Something went wrong')];
        } elseif (is_string($errors) || $errors instanceof Throwable) {
            $errors = ['error' => $errors];
        } else {
            $errors = $this->morphToArray($errors);
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
     * @param array|Arrayable|JsonSerializable|Throwable $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated(array|Arrayable|JsonSerializable|Throwable $data = []): JsonResponse
    {
        return $this->apiResponse(
            $this->morphToArray($data),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param array|string|Arrayable|JsonSerializable|Throwable $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondFailedValidation($errors = []): JsonResponse
    {
        if ($errors === []) {
            $errors = ['error' => __('Something went wrong')];
        } elseif (is_string($errors) || $errors instanceof Throwable) {
            $errors = ['error' => $errors];
        } else {
            $errors = $this->morphToArray($errors);
        }

        return $this->apiResponse(
            ['errors' => $errors],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function respondNoContent(): Response
    {
        return response()->noContent();
    }

    /**
     * @param array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    private function apiResponse(array $data, int $code = 200): JsonResponse
    {
        return response()->json($data, $code, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param array|Arrayable|JsonSerializable|null $data
     * @return array
     */
    private function morphToArray($data): array
    {
        if (is_array($data)) {
            return $data;
        }

        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        if ($data instanceof JsonSerializable) {
            return $data->jsonSerialize();
        }

        return [];
    }

    /**
     * @param string|Throwable $message
     * @return string
     */
    private function morphMessage(string|Throwable $message): string
    {
        return $message instanceof Throwable ? $message->getMessage() : $message;
    }
}
