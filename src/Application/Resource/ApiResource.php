<?php
declare(strict_types=1);

namespace Championship\Application\Resource;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResource
{
    protected $statusCode = 200;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function setStatusCode($statusCode): ApiResource
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respond($data, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    public function respondWithErrors($errors, $headers = []): JsonResponse
    {
        return new JsonResponse(['errors' => $errors], $this->getStatusCode(), $headers);
    }

    public function respondUnauthorized($message = 'Not authorized!'):JsonResponse
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    public function respondValidationError($message = 'Validation errors'): JsonResponse
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    public function respondNotFound($message = 'Not found!'): JsonResponse
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    public function respondCreated($data = []): JsonResponse
    {
        return $this->setStatusCode(201)->respond($data);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        if ($data === null) {
            return $request;
        }
        $request->request->replace($data);

        return $request;
    }
}
