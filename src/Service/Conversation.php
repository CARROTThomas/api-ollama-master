<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Conversation
{
    private const MAX_MESSAGES = 10;


    public function addMessageToConversation($role, $content,$session)
    {
        $conversation = $this->getConversationFromSession($session);

        $message = [
            'role' => $role,
            'content' => $content,
        ];

        $conversation[] = $message;

        $conversation = array_slice($conversation, -self::MAX_MESSAGES);

        $session->set('conversation', $conversation);

        return $this->getConversation($session);
    }

    public function getConversation($session)
    {
        return $this->getConversationFromSession($session);
    }

    private function getConversationFromSession($session)
    {
        return $session->get('conversation', []);
    }
}