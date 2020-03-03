<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tyler36\Ldap\Exceptions\LdapConfigurationException;
use Tyler36\Ldap\LdapConnector;

/**
 * Class ConnectorTest
 *
 * @test
 */
class LdapConnectorTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @group exception
     */
    public function it_throws_an_exception_if_host_is_not_configured()
    {
        $this->expectException(LdapConfigurationException::class);

        config()->set(['ldap.host' => null]);
        new LdapConnector();
    }

    /** @test */
    public function it_can_generate_base_dn()
    {
        config()->set([
            'ldap.common_name' => '',
            'ldap.domain_comp' => 'dc=example,dc=local',
        ]);
        $connector = new LdapConnector();

        $this->assertSame('dc=example,dc=local', $connector->getBaseDn());
    }
}
