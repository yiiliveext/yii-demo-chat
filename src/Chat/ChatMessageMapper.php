<?php

declare(strict_types=1);

namespace App\Chat;

use Cycle\ORM\Command\ContextCarrierInterface;
use Cycle\ORM\Command\Database\Update;
use Cycle\ORM\Heap\Node;
use Cycle\ORM\Heap\State;
use Cycle\ORM\Mapper\Mapper;

final class ChatMessageMapper extends Mapper
{
    /**
     * @param User $entity
     */
    public function queueUpdate($entity, Node $node, State $state): ContextCarrierInterface
    {
        /** @var Update $command */
        $command = parent::queueUpdate($entity, $node, $state);

        $now = time();

        $state->register('updated_at', $now, true);
        $command->registerAppendix('updated_at', $now);

        return $command;
    }
}
