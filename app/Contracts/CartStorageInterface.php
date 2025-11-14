<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface CartStorageInterface
{
    public function get(): array;
    public function delete($id): bool;
    public function post(Request $request, string $id): bool;
    public function patch(Request $request, $id): bool;
    public function delivery(Request $request): bool;
}