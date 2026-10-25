<?php

namespace Tests\Unit;

use Illuminate\Auth\GenericUser;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TokenGuardSecurityTest extends TestCase
{
    public static function malformedCredentials(): array
    {
        return [[['known-token'], 'GET'], [['known-token'], 'POST'], [[['known-token']], 'POST'], [1, 'GET'], [true, 'POST']];
    }

    #[DataProvider('malformedCredentials')]
    public function test_malformed_credentials_do_not_reach_the_user_provider(mixed $credential, string $method): void
    {
        $provider = $this->createMock(UserProvider::class);
        $provider->expects($this->never())->method('retrieveByCredentials');
        $guard = new TokenGuard($provider, Request::create('/', $method, ['api_token' => $credential]));
        $this->assertNull($guard->user());
    }

    public function test_valid_string_credentials_still_authenticate(): void
    {
        $user = new GenericUser(['id' => 42]);
        $provider = $this->createMock(UserProvider::class);
        $provider->expects($this->once())->method('retrieveByCredentials')->with(['api_token' => 'known-token'])->willReturn($user);
        $this->assertSame($user, (new TokenGuard($provider, Request::create('/', 'GET', ['api_token' => 'known-token'])))->user());
    }
}
