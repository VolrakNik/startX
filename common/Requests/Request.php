<?php

namespace Common\Requests;

class Request
{
    private array $get;
    private array $post;
    private array $json;
    private array $validated = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->json = $this->loadJson();
    }

    private function loadJson(): array
    {
        if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return $data;
            }
        }
        return [];
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post, $this->json);
    }

    public function getValidated(): array
    {
        return $this->validated;
    }

    public function setValidated(array $validated): void
    {
        $this->validated = $validated;
    }

    public function rules(): array
    {
        return [];
    }
}