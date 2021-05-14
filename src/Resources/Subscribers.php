<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\ButtonDownEmail\Resources;

use RuntimeException;
use JustSteveKing\UriBuilder\Uri;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\PendingRequest;
use JustSteveKing\Laravel\ButtonDownEmail\DTO\Subscriber;
use JustSteveKing\Laravel\ButtonDownEmail\Concerns\CanQuery;
use JustSteveKing\Laravel\ButtonDownEmail\DTO\BaseSubscriber;
use JustSteveKing\Laravel\ButtonDownEmail\Contracts\ResourceContract;

class Subscribers implements ResourceContract
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

        $subscribers = new Collection();

        foreach ($response->json()['results'] as $result) {
            if (! empty($result)) {
                $subscribers->add(
                    item: Subscriber::hydrate(
                        attributes: $result,
                    ),
                );
            }
        }

        return $subscribers;
    }

    public function create(array $attributes): BaseSubscriber
    {
        Subscriber::validate(
            attributes: $attributes,
        );

        $response = $this->request->post(
            url: $this->url->toString(),
            data: $attributes,
        );

        if (! $response->successful()) {
            throw new RuntimeException(
                message: $response->json()[0],
                code: $response->status(),
            );
        }

        return Subscriber::hydrate(
            attributes: $response->json(),
        );
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

        return Subscriber::hydrate(
            attributes: $response->json(),
        );
    }

    public function update(array $attributes, string $id): BaseSubscriber
    {
        Subscriber::validate(
            attributes: $attributes,
        );

        $this->url->addPath(
            path: "{$this->url->path()}/{$id}",
        );

        $response = $this->request->patch(
            url: $this->url->toString(),
            data: $attributes,
        );

        if (! $response->successful()) {
            throw new $response->toException();
        }

        return Subscriber::hydrate(
            attributes: $response->json(),
        );
    }

    public function delete(string $id): bool
    {
        $this->url->addPath(
            path: "{$this->url->path()}/{$id}",
        );

        $response = $this->request->delete(
            url: $this->url->toString(),
        );

        if (! $response->successful()) {
            throw new $response->toException();
        }

        return true;
    }
}
