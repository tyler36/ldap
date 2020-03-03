<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Tyler36\Ldap\LdapAuthenticator;

/**
 * Class LdapAuthenticatorTest
 *
 * @test
 */
class LdapAuthenticatorTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test setup
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->route = 'test/ldap';

        Route::post($this->route, function (Request $request) {
            $ldap = new LdapAuthenticator($request);
            $user = $ldap->authenticate();
        });
    }

    /** @test */
    public function it_can_get_credential_rules()
    {
        $authenticator = new LdapAuthenticator(new Request());
        $rules         = $authenticator->getRules();

        $this->assertSame('required', $rules['username']);
        $this->assertSame('required', $rules['password']);
    }

    /** @test */
    public function it_can_generate_full_username_with_prefix_and_suffix()
    {
        config()->set([
            'ldap.username'        => 'username',
            'ldap.username_prefix' => '',
            'ldap.username_suffix' => '',
        ]);
        $authenticator = new LdapAuthenticator(new Request(['username' => 'Dave', 'password' => 'pass01']));

        $this->assertSame('Dave', $authenticator->fullUsername('Dave'));

        config()->set(['ldap.username_prefix' => 'local\\']);
        $this->assertSame('local\Dave', $authenticator->fullUsername('Dave'));

        config()->set(['ldap.username_suffix' => '@example.com']);
        $this->assertSame('local\Dave@example.com', $authenticator->fullUsername('Dave'));
    }
}
