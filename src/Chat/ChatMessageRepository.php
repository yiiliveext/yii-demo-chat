<?php

declare(strict_types=1);

namespace App\Chat;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;
use Cycle\ORM\Transaction;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Yii\Cycle\Data\Reader\EntityReader;

class ChatMessageRepository extends Select\Repository
{
    private ORMInterface $orm;

    public function __construct(Select $select, ORMInterface $orm)
    {
        $this->orm = $orm;
        parent::__construct($select);
    }

    public function findAll(array $scope = [], array $orderBy = []): array
    {
        return $this->select()
            ->buildQuery()
            ->where($scope)
            ->from('chat_message AS ch')
            ->columns(['ch.id AS id', 'login AS name', 'message AS text', 'ch.created_at AS timestamp'])
            ->innerJoin('user', 'u')->on('u.id', 'user_id')
            ->orderBy($orderBy)
            ->fetchAll();
    }

    /**
     * @throws \Throwable
     */
    public function save(ChatMessage $message): void
    {
        $transaction = new Transaction($this->orm);
        $transaction->persist($message);
        $transaction->run();
    }
}
