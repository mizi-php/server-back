<?php

namespace Mizi\Response;

use Mizi\File;

class InstanceResponse
{
    protected ?int $status = STS_OK;
    protected array $header = [];

    protected mixed $content = null;
    protected string $contentType = 'text/html';

    protected null|int|bool $cache = 0;

    protected bool $download = false;
    protected ?string $downloadName = null;

    function __construct(mixed $content = null, ?int $status = null)
    {
        $this->content($content);
        $this->status($status);
    }

    /** Define o status HTTP da resposta */
    function status(?int $status): static
    {
        $this->status = $status;
        return $this;
    }

    /** Define o conteúdo da resposta */
    function content(mixed $content): static
    {
        $this->content = $content;
        return $this;
    }

    /** Altera o contentType da resposta */
    function contentType(string $contentType): static
    {
        $this->contentType = EX_MIMETYPE[$contentType] ?? $contentType;
        return $this;
    }

    /** Define uma variavel do cabeçalho da resposta */
    function header(string $name, string $value): static
    {
        $this->header[$name] = $value;
        return $this;
    }

    /** Define se o arquivo deve ser armazenado em cache */
    function cache(null|bool|int $time): static
    {
        $this->cache = $time;
        return $this;
    }

    /** Define se o navegador deve fazer download da resposta */
    function download(null|bool|string $download): static
    {
        if (is_string($download)) {
            $this->downloadName = $download;
            $download = true;
        }
        $this->download = boolval($download);
        return $this;
    }

    /** Envia a resposta finalizando a aplicação */
    function send(?int $status = null): void
    {
        $status = $status ?? $this->status;

        $this->sendObject($status);

        $this->prepareContent();

        $this->prepareCache();

        $this->prepareDownload();

        $this->prepareHeaders();

        $this->sendContent();
        exit;
    }

    /** Envia o conteúdo caso seja um objeto com o metodo send */
    protected function sendObject(?int $status): void
    {
        if (is_object($this->content) && is_callable([$this->content, 'send']))
            $this->content->send($status);
    }

    /** Envia o conteúdo ao navegador */
    protected function sendContent(): void
    {
        echo $this->content;
    }

    /** Prepara o conteúdo da resposta */
    protected function prepareContent(): void
    {
        if (is_array($this->content)) {
            $this->content(json_encode($this->content));
            $this->contentType('application/json');
        }
    }

    /** Prepara os cabeçalhos de cache */
    protected function prepareCache(): void
    {
        if (!is_null($this->cache)) {
            if ($this->cache === true) {
                $cacheEx = array_flip(EX_MIMETYPE)[$this->contentType] ?? null;
                $this->cache = env(strtoupper("RESPONSE_CACHE_$cacheEx")) ?? env("RESPONSE_CACHE");
            }

            if ($this->cache) {
                $this->cache = $this->cache * 60 * 60;
                $this->header('Pragma', 'public');
                $this->header('Cache-Control', "max-age=" . $this->cache);
                $this->header('Expires', gmdate('D, d M Y H:i:s', time() + $this->cache) . ' GMT');
            } else {
                $this->header("Pragma", "no-cache");
                $this->header('Cache-Control', ' no-cache, no-store, must-revalidate');
                $this->header("Expires", "0");
            }
        }
    }

    /** Prepara os cabeçalhos de download */
    protected function prepareDownload(): void
    {
        if ($this->download) {
            $ex = array_flip(EX_MIMETYPE)[$this->contentType] ?? 'download';
            $fileName = $this->downloadName ?? 'download';
            File::ensure_extension($fileName, $ex);
            $this->header('Content-Disposition', "attachment; filename=$fileName");
        }
    }

    /** Prepara os cabeçalhos da resposta */
    protected function prepareHeaders(): void
    {
        $this->header('Content-Type', $this->contentType);

        http_response_code($this->status ?? STS_OK);

        foreach ($this->header as $name => $value)
            header("$name: $value");
    }
}