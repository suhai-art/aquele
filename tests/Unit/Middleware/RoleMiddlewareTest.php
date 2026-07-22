<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    protected bool $tenancy = true;

    private ?User $user = null;

    private function makeRequest(string $roleParam = 'admin')
    {
        $request = Request::create('/api/users', 'GET');
        $request->setUserResolver(fn () => $this->user);

        return $this->app->make(RoleMiddleware::class)
            ->handle($request, fn () => new JsonResponse(['ok' => true]), $roleParam);
    }

    public function test_user_with_role_is_allowed(): void
    {
        $this->user = $this->createUserWithRole('admin');
        Sanctum::actingAs($this->user);

        $response = $this->makeRequest('admin');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_user_without_role_is_forbidden(): void
    {
        $this->user = $this->createUserWithRole('user');
        Sanctum::actingAs($this->user);

        $response = $this->makeRequest('admin');

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function test_root_role_bypasses_every_restriction(): void
    {
        $this->user = $this->createUserWithRole('root');
        Sanctum::actingAs($this->user);

        $response = $this->makeRequest('admin');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_comma_separated_roles_accept_any(): void
    {
        $this->user = $this->createUserWithRole('user');
        Sanctum::actingAs($this->user);

        $response = $this->makeRequest('admin,user');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_guest_is_unauthorized(): void
    {
        $request = Request::create('/api/users', 'GET');

        $this->expectException(AuthenticationException::class);

        $this->app->make(RoleMiddleware::class)
            ->handle($request, fn () => new JsonResponse(['ok' => true]), 'admin');
    }
}
