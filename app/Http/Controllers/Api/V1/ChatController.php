<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getMessages(Request $request, int $caseId)
    {
        $user = $request->user();
        $limit = $request->query('limit', 50);
        $offset = $request->query('offset', 0);

        $case = $this->verifyCaseAccess($caseId, $user->id);
        if ($case instanceof \Illuminate\Http\JsonResponse) {
            return $case;
        }

        // Mark messages as read
        ChatMessage::where('case_id', $caseId)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = ChatMessage::where('case_id', $caseId)
            ->orderBy('sent_at', 'asc')
            ->skip($offset)
            ->take($limit)
            ->get();

        $result = $messages->map(function ($msg) {
            $sender = User::find($msg->sender_id);
            return [
                'id' => $msg->id,
                'case_id' => $msg->case_id,
                'sender_id' => $msg->sender_id,
                'sender_name' => $sender?->full_name,
                'sender_avatar' => $sender?->avatar_url,
                'message' => $msg->message,
                'is_read' => $msg->is_read,
                'sent_at' => $msg->sent_at?->toIso8601String(),
            ];
        });

        return response()->json($result);
    }

    public function sendMessage(Request $request, int $caseId)
    {
        $user = $request->user();
        $request->validate(['message' => 'required|string']);

        $case = $this->verifyCaseAccess($caseId, $user->id);
        if ($case instanceof \Illuminate\Http\JsonResponse) {
            return $case;
        }

        if (!in_array($case->status, ['paid', 'in_progress', 'completed'])) {
            return response()->json(['detail' => 'Chat hanya tersedia setelah pembayaran'], 400);
        }

        $msg = ChatMessage::create([
            'case_id' => $caseId,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        // Send push notification to the other party
        $recipientId = ($user->id === $case->client_id) ? $case->lawyer_id : $case->client_id;
        if ($recipientId) {
            NotificationService::sendPushNotification(
                $recipientId,
                "Pesan dari {$user->full_name}",
                $request->message,
                'chat',
                $caseId,
                ['type' => 'chat', 'case_id' => (string) $caseId]
            );
        }

        return response()->json([
            'id' => $msg->id,
            'case_id' => $msg->case_id,
            'sender_id' => $msg->sender_id,
            'sender_name' => $user->full_name,
            'sender_avatar' => $user->avatar_url,
            'message' => $msg->message,
            'is_read' => $msg->is_read,
            'sent_at' => $msg->sent_at?->toIso8601String(),
        ], 201);
    }

    public function conversations(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'client') {
            $cases = LegalCase::with('lawyer')
                ->where('client_id', $user->id)
                ->whereIn('status', ['paid', 'in_progress', 'completed'])
                ->orderByDesc('created_at')
                ->get();
        } else {
            $cases = LegalCase::with('client')
                ->where('lawyer_id', $user->id)
                ->whereIn('status', ['paid', 'in_progress', 'completed'])
                ->orderByDesc('created_at')
                ->get();
        }

        $conversations = $cases->map(function ($c) use ($user) {
            $opponent = ($user->role === 'client') ? $c->lawyer : $c->client;
            return [
                'case_id' => $c->id,
                'case_number' => $c->case_number,
                'case_status' => $c->status,
                'meeting_address' => $c->meeting_address,
                'meeting_datetime' => $c->meeting_datetime?->toIso8601String(),
                'opponent_name' => $opponent?->full_name,
                'opponent_avatar' => $opponent?->avatar_url,
            ];
        });

        return response()->json($conversations);
    }

    private function verifyCaseAccess(int $caseId, int $userId)
    {
        $case = LegalCase::find($caseId);
        if (!$case) {
            return response()->json(['detail' => 'Case tidak ditemukan'], 404);
        }
        if ($userId !== $case->client_id && $userId !== $case->lawyer_id) {
            return response()->json(['detail' => 'Anda tidak memiliki akses ke chat ini'], 403);
        }
        return $case;
    }
}
