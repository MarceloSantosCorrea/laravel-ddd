<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

/**
 * @property string name
 * @property string description
 * @property bool isActive
 * @property DateTime|string createdAt
 */
class Category
{
    use MethodsMagicsTrait;

    public function __construct(
        protected Uuid|string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $isActive = true,
        protected DateTime|string $createdAt = '',
    ) {
        $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
        $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime();
        $this->validate();
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    public function update(
        string $name = '',
        string $description = '',
    ): void
    {
        $this->name = $name;
        $this->description = $description;
    }

    private function validate(): void
    {
        DomainValidation::notNull(value: $this->name);
        DomainValidation::strMinLength(value: $this->name, length: 3);
        DomainValidation::strMaxLength(value: $this->name, length: 255);

        DomainValidation::strMinLength(value: $this->description, length: 3);
        DomainValidation::strMaxLength(value: $this->description, length: 255);
    }
}
