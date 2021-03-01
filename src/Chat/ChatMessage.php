<?php

declare(strict_types=1);

namespace App\Chat;

use App\User\User;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repository="App\Chat\ChatMessageRepository")
 * @Table(
 *     indexes={
 *         @Index(columns={"user_id"}, unique=false),
 *     }
 * )
 */
class ChatMessage
{
    /**
     * @Column(type="primary")
     */
    private ?int $id = null;

    /**
     * @BelongsTo(target="App\User\User", nullable=false)
     *
     * @var \Cycle\ORM\Promise\Reference|User
     */
    private $user = null;
    private ?int $user_id = null;

    /**
     * @Column(type="string(255)", default="")
     */
    private $message;

    /**
     * @Column(type="integer")
     */
    private int $created_at;

    /**
     * @Column(type="integer")
     */
    private int $updated_at;

    public function __construct(string $message)
    {
        $this->message = $message;
        $this->created_at = time();
        $this->updated_at = time();
    }

    public function getId(): ?string
    {
        return $this->id === null ? null : (string)$this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->meassage = $message;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }


    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): int
    {
        return $this->updated_at;
    }

    public function isNewRecord(): bool
    {
        return $this->getId() === null;
    }
}
