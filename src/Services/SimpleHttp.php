<?php

namespace Luchavez\ApiSdkKit\Services;

use Closure;
use Luchavez\ApiSdkKit\DataFactories\AuditLogDataFactory;
use Luchavez\ApiSdkKit\Models\AuditLog;
use Luchavez\ApiSdkKit\Traits\UsesHttpFieldsTrait;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

/**
 * Class SimpleHttp
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class SimpleHttp
{
    use UsesHttpFieldsTrait;

    /**
     * BODY FORMATS
     */
    public const AS_JSON = 'json';

    public const AS_FORM = 'form';

    public const AS_MULTIPART = 'multipart';

    /**
     * @var string|null
     */
    protected ?string $base_url;

    /**
     * @var string|null
     */
    protected ?string $append_url;

    /**
     * @var string|null
     */
    protected ?string $body_format = null;

    /**
     * @var array
     */
    protected array $before_request_callables;

    /**
     * @var Response|\Illuminate\Http\Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|null
     */
    protected Response|\Illuminate\Http\Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|null $response = null;

    /**
     * @param  string|null  $base_url
     * @param  bool  $return_as_model
     */
    public function __construct(?string $base_url = null, protected bool $return_as_model = true)
    {
        $this->setBaseUrl($base_url);

        $this->before_request_callables = [
            function (self $request) {
                $request->httpOptions(['verify' => (bool) config('starter-kit.verify_ssl')]);
            },
        ];
    }

    /**
     * @param  string  $method
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    protected function execute(
        string $method,
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        // Prepare URL
        if (! ($this->getBaseUrl())) {
            throw new RuntimeException('Base URL is empty.');
        }

        // Set Append URL
        $this->setAppendUrl($append_url);

        // Get Complete URL
        $url = $this->getCompleteUrl();

        if (! apiSdkKit()->getCodeByHttpMethod($method)) {
            throw new RuntimeException('HTTP method not available.');
        }

        // Execute Pre-Request Processes
        $this->runBeforeRequestCallables();

        // Execute the API request
        if (is_internal_url($url)) {
            $uri = parse_url($url)['path'];
            $request = Request::create(uri: $uri, method: $method, parameters: $this->data, files: $this->getFiles());
            if (count($this->headers)) {
                $request->headers->add($this->headers);
            }
            try {
                $this->response = app()->handle($request);
            } catch (Throwable) {
                return null;
            }
        } else {
            $client = $this->getHttp();

            if (count($this->files)) {
                $client = $client->attach($this->getFiles(true));
            }

            // Some API like AWS SES has issues with empty array data
            $this->response = count($this->data) ? $client->$method($url, $this->data) : $client->$method($url);
        }

        if ($this->response && $this->return_as_model) {
            $log = new AuditLogDataFactory();
            $log->url = $url;
            $log->method = $method;
            $log->data = $this->getDataFromResponse();
            $log->headers = $this->getHeadersFromResponse();
            $log->status = $this->getStatusFromResponse();

            return $log->create();
        }

        return $this->response;
    }

    /***** HTTP VERBS RELATED *****/

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function executeHead(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->execute('head', $append_url);
    }

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function head(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->executeHead($append_url);
    }

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function executeGet(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->execute('get', $append_url);
    }

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function get(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->executeGet($append_url);
    }

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function executePost(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->execute('post', $append_url);
    }

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function post(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->executePost($append_url);
    }

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function executePut(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->execute('put', $append_url);
    }

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function put(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->executePut($append_url);
    }

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function executeDelete(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->execute('delete', $append_url);
    }

    /**
     * @param  string|null  $append_url
     * @return PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null
     */
    public function delete(
        ?string $append_url = ''
    ): PromiseInterface|Response|\Illuminate\Http\Response|JsonResponse|Builder|AuditLog|null {
        return $this->executeDelete($append_url);
    }

    /***** SETTERS & GETTERS *****/

    /**
     * @return PendingRequest
     */
    public function getHttp(): PendingRequest
    {
        // Prepare HTTP call

        $response = Http::withUserAgent(config('api-sdk-kit.user_agent'))
            ->withHeaders($this->headers)
            ->withOptions($this->httpOptions);

        // Prepare Body Format

        return match ($this->body_format) {
            self::AS_FORM => $response->asForm(),
            self::AS_JSON => $response->asJson(),
            self::AS_MULTIPART => $response->asMultipart(),
            default => $response
        };
    }

    /**
     * @return string|null
     */
    public function getBaseUrl(): ?string
    {
        return $this->base_url;
    }

    /**
     * @param  string|null  $base_url
     * @return void
     */
    public function setBaseUrl(?string $base_url): void
    {
        $base_url = $base_url ? trim($base_url) : null;
        $this->base_url = $base_url ? rtrim($base_url, '/') : null;
    }

    /**
     * @param  string|null  $base_url
     * @return static
     */
    public function baseUrl(?string $base_url): static
    {
        $this->setBaseUrl($base_url);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAppendUrl(): ?string
    {
        return $this->append_url;
    }

    /**
     * @param  string|null  $append_url
     * @return void
     */
    public function setAppendUrl(?string $append_url): void
    {
        $append_url = $append_url ? trim($append_url) : null;
        $this->append_url = $append_url ? ltrim($append_url, '/') : null;
    }

    /**
     * @param  string|null  $append_url
     * @return static
     */
    public function appendUrl(?string $append_url): static
    {
        $this->setAppendUrl($append_url);

        return $this;
    }

    /**
     * @return string
     */
    public function getCompleteUrl(): string
    {
        return collect([$this->base_url, $this->append_url])->filter()->implode('/');
    }

    /**
     * @param  bool  $bool
     * @return static
     */
    public function returnAsModel(bool $bool = true): static
    {
        $this->return_as_model = $bool;

        return $this;
    }

    /**
     * @param  bool  $bool
     * @return static
     */
    public function returnAsResponse(bool $bool = true): static
    {
        return $this->returnAsModel(! $bool);
    }

    /**
     * @return string|null
     */
    public function getBodyFormat(): ?string
    {
        return $this->body_format;
    }

    /**
     * @return static
     */
    public function asForm(): static
    {
        $this->body_format = self::AS_FORM;

        return $this;
    }

    /**
     * @return static
     */
    public function asJson(): static
    {
        $this->body_format = self::AS_JSON;

        return $this;
    }

    /**
     * @return static
     */
    public function asMultipart(): static
    {
        $this->body_format = self::AS_MULTIPART;

        return $this;
    }

    /**
     * @param  Closure|callable|Closure[]|callable[]  $closure
     * @return static
     */
    public function callBeforeRequest(Closure|callable|array $closure): static
    {
        $this->before_request_callables = $this->getBeforeRequestCallables()->merge(collect($closure))->toArray();

        return $this;
    }

    /**
     * @return Collection
     */
    public function getBeforeRequestCallables(): Collection
    {
        return collect($this->before_request_callables);
    }

    /**
     * @return void
     */
    protected function runBeforeRequestCallables(): void
    {
        $this->getBeforeRequestCallables()->each(fn ($closure) => $closure($this));
    }

    /**
     * @return Response|\Illuminate\Http\Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|null
     */
    public function getResponse(): Response|\Illuminate\Http\Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|null
    {
        return $this->response;
    }

    /**
     * @return Collection|null
     */
    public function getDataFromResponse(): ?Collection
    {
        if (! $this->response) {
            return null;
        }

        if (method_exists($this->response, 'collect')) {
            return $this->response->collect();
        } elseif ($this->response instanceof \Illuminate\Http\Response || $this->response instanceof JsonResponse) {
            return collect(json_decode($this->response->getContent(), true));
        }

        return null;
    }

    /**
     * @return array|null
     */
    public function getHeadersFromResponse(): ?array
    {
        if ($this->response instanceof Response) {
            return $this->response->headers();
        } elseif ($this->response instanceof \Illuminate\Http\Response || $this->response instanceof JsonResponse) {
            return $this->response->headers->all();
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getStatusFromResponse(): ?int
    {
        return $this->response?->status();
    }

    /***** OTHER FUNCTIONS *****/

    /**
     * @param  string  $url
     * @return static
     */
    public function proxy(string $url): static
    {
        $closure = function (self $request) use ($url) {
            $request->httpOptions(['proxy' => $url]);
        };

        $this->callBeforeRequest($closure);

        return $this;
    }

    /**
     * @return static
     */
    public function clearHeaders(): static
    {
        $closure = function (self $request) {
            $request->headers([], false);
        };

        $this->callBeforeRequest($closure);

        return $this;
    }

    /**
     * @return static
     */
    public function clearHttpOptions(): static
    {
        $closure = function (self $request) {
            $request->httpOptions([], false);
        };

        $this->callBeforeRequest($closure);

        return $this;
    }
}
