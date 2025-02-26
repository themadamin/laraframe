<?php

namespace Core;

class Request
{
    public string $method;
    public string $uri;
    public array $headers;
    public array $queryParams;
    public array $bodyParams;

    public function __construct()
    {
        $this->method = $this->setMethod();
        $this->uri = $this->setUri();
        $this->headers = $this->setHeaders();
        $this->queryParams = $this->setQueryParams();
        $this->bodyParams = $this->setBodyParams();
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @return array
     */
    public function getBodyParams(): array
    {
        return $this->bodyParams;
    }

    /**
     * @return array
     */
    private function getAllInput(): array
    {
        return array_merge($this->queryParams, $this->bodyParams);
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        $input = $this->getAllInput();
        return $input[$name] ?? null;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name): mixed
    {
        return $this->queryParams[$name];
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function post(string $name): mixed
    {
        return $this->bodyParams[$name];
    }

    /**
     * @return mixed
     */
    private function setMethod(): mixed
    {
        return $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return false|int|array|string|null
     */
    private function setUri(): false|int|array|string|null
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * @return false|array
     */
    private function setHeaders(): false|array
    {
        return getallheaders();
    }

    /**
     * @return array
     */
    private function setQueryParams(): array
    {
        return $_GET;
    }

    /**
     * @return array
     */
    private function setBodyParams(): array
    {
        return $_POST;
    }
}