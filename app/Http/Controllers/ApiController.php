<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function apiBeginTransaction()
    {
        return DB::beginTransaction();
    }

    public function apiCommit()
    {
        return DB::commit();
    }

    public function apiRollback()
    {
        return DB::rollback();
    }

    /**
     * Return generic json response with the given data.
     *
     * @param $data
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
     */

    protected function respond($data, int $statusCode = 200, array $headers = []): JsonResponse
    {
        $newData = [
            'status' => 'success',
            'response' => $data
        ];

        return response()->json($newData, $statusCode, $headers);
    }

    /**
     * Respond with success.
     *
     * @param $data
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function respondSuccess($data, int $statusCode = 200): JsonResponse
    {
        return $this->respond($data, $statusCode);
    }

    /**
     * Respond success without data.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondSuccessWithoutData(string $message): JsonResponse
    {
        return $this->respond(['status' => 'Success', 'message' => $message]);
    }

    /**
     * Respond with created.
     *
     * @param $data
     * @return JsonResponse
     */
    protected function respondCreated($data): JsonResponse
    {
        return $this->respond($data, 201);
    }

    /**
     * Respond with no content.
     *
     * @return JsonResponse
     */
    protected function respondNoContent(): JsonResponse
    {
        return $this->respond(null, 204);
    }

    /**
     * Respond with error.
     *
     * @param $message
     * @return JsonResponse
     */
    protected function respondError($message, $statusCode): JsonResponse
    {
        return $this->respond(['status' => 'Error', 'message' => $message], $statusCode);
    }

    /**
     * Respond with error and data.
     *
     * @param $data
     * @param $statusCode
     *
     * @return JsonResponse
     */
    protected function respondErrorWithData($data, $statusCode): JsonResponse
    {
        array_unshift($data, ['status' => 'Error']);

        return $this->respond($data, $statusCode);
    }

    /**
     * Respond with unauthorized.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->respondError($message, 401);
    }

    /**
     * Respond with forbidden.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondForbidden(string $message = 'You do not have access to this resource'): JsonResponse
    {
        return $this->respondError($message, 403);
    }

    /**
     * Respond with not found.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondNotFound(string $message = 'Not Found'): JsonResponse
    {
        return $this->respondError($message, 404);
    }

    /**
     * Respond with failed login.
     *
     * @return JsonResponse
     */
    protected function respondFailedLogin(): JsonResponse
    {
        return $this->respond([
            'errors' => 'email or password is invalid'
        ], 422);
    }

    /**
     * Respond with internal error.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondInternalError(string $message = 'Internal Error'): JsonResponse
    {
        return $this->respondError($message, 500);
    }
}
