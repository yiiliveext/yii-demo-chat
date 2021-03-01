<?php

declare(strict_types=1);

namespace App\Chat;

use App\Chat\ChatMessage;
use App\Chat\ChatMessageRepository;
use App\User\User;

final class ChatService
{
    private ChatMessageRepository $repository;

    public function __construct(ChatMessageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function saveMessage(User $user, ChatMessage $model): void
    {
        if ($model->isNewRecord()) {
            $model->setUser($user);
        }

        $this->repository->save($model);
    }
}

