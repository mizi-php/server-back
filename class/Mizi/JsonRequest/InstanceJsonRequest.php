<?php

namespace Mizi\JsonRequest;

use CURLFile;
use Exception;
use Mizi\File;

class InstanceJsonRequest
{
    protected string $host;
    protected array $header = [];

    protected array $file = [];

    function __construct(string $host)
    {
        $this->host = $host;
    }

    /** Define uma variavel do cabeçalho da requisição */
    function header(string $name, string $value): static
    {
        $this->header[$name] = $value;
        return $this;
    }

    /** Define os aquivos para requisições do tipo POST */
    function file(string $path, ?string $name = null): static
    {
        $name = $name ?? File::getOnly($path);
        $this->file[$name] = path($path);
        return $this;
    }

    /** Se a chamada deve utilizar o metodo relativo */
    function relativeMethod(bool $useRelativeMethod = true): static
    {
        $this->relativeMethod = $useRelativeMethod;
        return $this;
    }

    /** Executa a requisção com o metodo GET */
    function get(...$params)
    {
        return $this->run('get', $params);
    }

    /** Executa a requisção com o metodo POST */
    function post(...$params)
    {
        return $this->run('post', $params);
    }

    /** Executa a requisção com o metodo PUT */
    function put(...$params)
    {
        return $this->run('put', $params);
    }

    /** Executa a requisção com o metodo DELETE */
    function delete(...$params)
    {
        return $this->run('delete', $params);
    }

    /** Executa a requisição e converte a resposta em JSON */
    protected function run($method, $params)
    {
        $path = [];
        $data = [];

        foreach ($params as $param) {
            if (is_bool($param)) {
                if ($param && !isset($data['_method'])) {
                    $data['_method'] = $method;
                    $method = 'post';
                }
            } else if (is_array($param)) {
                $data = [...$data, ...$param];
            } else {
                $path[] = $param;
            }
        }

        $host = url($this->host, ...$path);

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $host);


        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($method == 'post' && !empty($this->file)) {
            foreach ($this->file as $name => $path)
                $data[$name]  =  new CURLFile(path($path));

            curl_setopt($curl, CURLOPT_POST,  1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data;']);
            curl_setopt($curl, CURLOPT_POSTFIELDS,  $data);
        } else {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));
        }

        $response = curl_exec($curl);

        if (!$response) $response = '[]';

        if (curl_errno($curl))
            throw new Exception('error curl [' . curl_errno($curl) . ']');

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) >= 300)
            throw new Exception('error curl response [' . curl_getinfo($curl, CURLINFO_HTTP_CODE) . ']', CURLINFO_HTTP_CODE);

        if (!is_json($response))
            throw new Exception('no recived json response');

        curl_close($curl);

        return json_decode($response, true);
    }
}