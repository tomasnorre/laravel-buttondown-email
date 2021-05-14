<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\ButtonDownEmail\Resources;

use JustSteveKing\UriBuilder\Uri;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\PendingRequest;
use JustSteveKing\Laravel\ButtonDownEmail\DTO\Unsubscriber;
use JustSteveKing\Laravel\ButtonDownEmail\Concerns\CanQuery;
use JustSteveKing\Laravel\ButtonDownEmail\DTO\BaseSubscriber;
use JustSteveKing\Laravel\ButtonDownEmail\Contracts\ResourceContract;

class Unsubscribers implements ResourceContract
{
    use CanQuery;

    public function __construct(
        public Uri $url,
        public PendingRequest $request,
    ) {}

    public function all(): Collection
    {
        return $this->get();
    }

    public function get(): Collection
    {
        $response = $this->request->get(
            url: $this->url->toString(),
        );

        if (! $response->successful()) {
            throw new $response->toException();
        }

        $unsubscribers = new Collection();

        foreach ($response->json()['results'] as $result) {
            if (! empty($result)) {
                $unsubscribers->add(
                    item: Unsubscriber::hydrate(
                        attributes: $result,
                    ),
                );
            }
        }

        return $unsubscribers;
    }

    public function find(string $id): BaseSubscriber
    {
        $this->url->addPath(
            path: "{$this->url->path()}/{$id}",
        );

        $response = $this->request->get(
            url: $this->url->toString(),
        );

        if (! $response->successful()) {
            throw new $response->toException();
        }

        return Unsubscriber::hydrate(
            attributes: $response->json(),
        );
    }
}
