<?php

namespace App\Contracts;

interface CartStorageInterface
{
    public function get(): array;
    public function delete($id): bool;
    public function post(array $request, string $id): bool;
    public function patch(array $request, $id): bool;
    public function delivery(array $request): bool;
}