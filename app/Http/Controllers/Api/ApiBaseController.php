<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    protected $user;
    protected $userId;
    protected $roleId;
    protected $isTeamLead;
    protected $currentRole;

    public function __construct(Request $request)
    {
        // Get authenticated user if available
        $this->user = Auth::user();
        if ($this->user) {
            $this->userId = $this->user->id;
            $this->roleId = $this->user->role_id ?? null;
            $this->isTeamLead = $this->user->is_team_lead ?? false;
            $this->currentRole = $this->user->current_role ?? null;
        }
    }

    protected function checkAuthToken(): bool
    {
        if (!$this->user) {
            $this->sendErrorResponse('Unauthorized', 401);
            return false;
        }
        return true;
    }

    protected function validateRequestMethod(Request $request, array $allowedMethods)
    {
        if (!in_array($request->method(), $allowedMethods)) {
            return $this->sendErrorResponse('Method not allowed!', 405);
        }
        return true;
    }

    protected function sendErrorResponse(string $message, int $statusCode = 400, array $data = []): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    protected function sendSuccessResponse(array $data, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
