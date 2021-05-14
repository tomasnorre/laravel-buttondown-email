<?php

declare(strict_types=1);

namespace JustSteveKing\Laravel\ButtonDownEmail;

use JustSteveKing\UriBuilder\Uri;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Symfony\Component\HttpFoundation\Response;
use JustSteveKing\Laravel\ButtonDownEmail\Concerns\HasFake;
use JustSteveKing\Laravel\ButtonDownEmail\Resources\Subscribers;
use JustSteveKing\Laravel\ButtonDownEmail\Resources\Unsubscribers;
use JustSteveKing\Laravel\ButtonDownEmail\Contracts\ClientContract;

class Client implements ClientContract
{
    use HasFake;

    /**
     * Client constructor.
     *
     * @return void
     */
    public function __construct(
        protected Uri $url,
        protected string $apiKey,
        protected int|string $timeout = 10,
        protected null|string|int $retryTimes = null,
        protected null|string|int $retryMilliseconds = null,
    ) {}

    /**
     * Make a new Client
     *
     * @return Client
     */
    public static function make(
        Uri $url,
        string $apiKey,
        int $timeout = 10,
        null|int $retryTimes = null,
        null|int $retryMilliseconds = null,
    ): Client {
        return new Client(
            url: $url,
            apiKey: $apiKey,
            timeout: $timeout,
            retryTimes: $retryTimes,
            retryMilliseconds: $retryMilliseconds,
        );
    }

    /**
     * Build our default Request
     *
     * @return PendingRequest
     */
    public function buildRequest(): PendingRequest
    {
        $request = Http::withToken(
            token: $this->apiKey,
            type: 'Token',
        )->withHeaders([
            'Accept' => 'application/json'
        ])->timeout(
            seconds: (int) $this->timeout,
        );

        if (
            ! is_null($this->retryTimes)
            && ! is_null($this->retryMilliseconds)
        ) {
            $request->retry(
                times: (int) $this->retryTimes,
                sleep: (int) $this->retryMilliseconds,
            );
        }

        return $request;
    }

    public function ping(): bool
    {
        $request = $this->buildRequest();

        $response = $request->get(
            url: "{$this->url}/ping",
        );

        if (! $response->successful()) {
            throw new $response->toException();
        }

        return ($response->status() === Response::HTTP_OK);
    }

    public function subscribers(): Subscribers
    {
        return new Subscribers(
            url: Uri::fromString(
                uri: "{$this->url}/subscribers",
            ),
            request: $this->buildRequest(),
        );
    }

    public function unsubscribers(): Unsubscribers
    {
        return new Unsubscribers(
            url: Uri::fromString(
                uri: "{$this->url}/unsubscribers",
            ),
            request: $this->buildRequest(),
        );
    }
}
