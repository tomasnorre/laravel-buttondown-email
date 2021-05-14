<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\ButtonDownEmail\Concerns;

trait CanQuery
{
    public function where(string $key, mixed $value): self
    {
        $this->url->addQueryParam(
            key: $key,
            value: $value,
        );

        return $this;
    }
}
