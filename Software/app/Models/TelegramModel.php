<?php

namespace App\Models;

/**
 * Manages Telegram chat subscribers and a log of sent notifications.
 *
 * ⚠️ NEW FUNCTIONALITY: no Telegram integration exists anywhere in the
 * original project. Added in preparation for Step 8's Telegram
 * notification feature (e.g. alerting when a sensor value goes out of
 * range, or the ESP32 disconnects).
 */
class TelegramModel extends FirebaseModel
{
    /** @return array<int, string> Registered Telegram chat IDs. */
    public function subscribers(): array
    {
        $data = $this->firebase->get('telegram/subscribers');
        return is_array($data) ? array_keys($data) : [];
    }

    public function addSubscriber(string $chatId): void
    {
        $this->firebase->set('telegram/subscribers/' . $chatId, true);
    }

    public function removeSubscriber(string $chatId): void
    {
        $this->firebase->delete('telegram/subscribers/' . $chatId);
    }

    public function logMessage(string $chatId, string $message): void
    {
        $this->firebase->push('telegram/logs', [
            'chat_id' => $chatId,
            'message' => $message,
            'sent_at' => date('c'),
        ]);
    }
}
