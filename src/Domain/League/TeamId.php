<?php
declare(strict_types=1);

namespace Championship\Domain\League;

class TeamId
{
    private $id;

    private function __construct(string $anId)
    {
        $this->id = $anId;
    }

    public static function create($anId): TeamId
    {
        return new static($anId);
    }

    public function __toString(): string
    {
        return $this->id;
    }
}