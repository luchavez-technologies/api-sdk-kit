<?php

namespace Luchavez\ApiSdkKit\Traits;

use Luchavez\StarterKit\Traits\UsesDataParsingTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait UsesHttpFieldsTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait UsesHttpFieldsTrait
{
    use UsesDataParsingTrait;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var array
     */
    protected array $files = [];

    /***
     * @var array
     */
    protected array $headers = [];

    /**
     * @var array
     */
    protected array $httpOptions = [];

    /***** HTTP DATA RELATED *****/

    /**
     * @return Collection
     */
    public function getData(): Collection
    {
        return collect($this->data);
    }

    /**
     * @param  mixed  $data
     * @return void
     */
    public function setData(mixed $data): void
    {
        $this->data = $this->parse($data);
    }

    /**
     * @param  mixed  $data
     * @param  bool  $merge If true, the new data will be merged to the old data. If false, the new data will replace the old data.
     * @param  bool  $override If true, common keys from new data will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function data(mixed $data, bool $merge = true, bool $override = true): static
    {
        $data = $this->parse($data);

        $this->arrayMergeOverride($this->data, $data, $merge, $override);

        return $this;
    }

    /***** HTTP HEADERS RELATED *****/

    /**
     * @return Collection
     */
    public function getHeaders(): Collection
    {
        return collect($this->headers);
    }

    /**
     * @param  mixed  $headers
     * @return void
     */
    public function setHeaders(mixed $headers): void
    {
        $this->headers = $this->parse($headers);
    }

    /**
     * @param  mixed  $headers
     * @param  bool  $merge If true, the new headers will be merged to the old headers. If false, the new headers will replace the old headers.
     * @param  bool  $override If true, common keys from new headers will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function headers(mixed $headers, bool $merge = true, bool $override = true): static
    {
        $headers = $this->parse($headers);

        $this->arrayMergeOverride($this->headers, $headers, $merge, $override);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getHttpOptions(): Collection
    {
        return collect($this->httpOptions);
    }

    /**
     * @param  mixed  $httpOptions
     * @return void
     */
    public function setHttpOptions(mixed $httpOptions): void
    {
        $this->httpOptions = $this->parse($httpOptions);
    }

    /**
     * @param  mixed  $httpOptions
     * @param  bool  $merge If true, the new http options will be merged to the old http options. If false, the new http options will replace the old http options.
     * @param  bool  $override If true, common keys from new http options will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function httpOptions(mixed $httpOptions, bool $merge = true, bool $override = true): static
    {
        $httpOptions = $this->parse($httpOptions);

        $this->arrayMergeOverride($this->httpOptions, $httpOptions, $merge, $override);

        return $this;
    }

    /***** FILES RELATED *****/

    /**
     * @param  bool  $as_multipart
     * @param  string|null  $base_key_dot_notation
     * @return array
     */
    public function getFiles(bool $as_multipart = false, string $base_key_dot_notation = null): array
    {
        if ($as_multipart) {
            $files = [];
            $filtered = $base_key_dot_notation ? Arr::get($this->files, $base_key_dot_notation) : $this->files;
            foreach ($filtered as $key => $file) {
                if (is_array($file)) {
                    $key = $base_key_dot_notation ? "$base_key_dot_notation.$key" : $key;
                    $files = array_merge($files, $this->getFiles($as_multipart, $key));
                } elseif ($file instanceof UploadedFile) {
                    $name = $base_key_dot_notation ? "$base_key_dot_notation.$key" : $key;
                    $name = Str::of($name)
                        ->trim('.')
                        ->replaceFirst('.', '[')
                        ->replace('.', '][')
                        ->whenContains('[', fn (Stringable $str) => $str->finish(']'))
                        ->jsonSerialize();

                    $files[] = [
                        'name' => $name,
                        'contents' => fopen($file->getRealPath(), 'r'),
                        'filename' => $file->getClientOriginalName(),
                    ];
                }
            }

            return $files;
        }

        return $this->files;
    }

    /**
     * @param  array  $files
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     * @param  array  $files
     * @param  bool  $merge If true, the new files will be merged to the old files. If false, the new files will replace the old files.
     * @param  bool  $override If true, common keys from new files will replace the old one's. If false, common keys will be ignored.
     * @return static
     */
    public function files(array $files, bool $merge = true, bool $override = true): static
    {
        $this->arrayMergeOverride($this->files, $files, $merge, $override);

        return $this;
    }

    /**
     * @param  array  $original
     * @param  array  $additional
     * @param  bool  $merge
     * @param  bool  $override
     * @return void
     */
    protected function arrayMergeOverride(
        array &$original,
        array $additional,
        bool $merge = true,
        bool $override = true
    ): void {
        if (! $merge) {
            $original = [];
        } elseif (! $override) {
            $additional = array_diff_key($additional, $original);
        }

        $original = array_merge($original, $additional);
    }
}
