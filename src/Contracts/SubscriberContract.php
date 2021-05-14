<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\ButtonDownEmail\Contracts;

interface SubscriberContract
{
    public static function hydrate(array $attributes): self;

    public static function validate(array $attributes): void;
}

