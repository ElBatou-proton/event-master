<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class LanguageTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    public function testSwitchToSpanishAndEnglish(): void
    {
        // Switch to Spanish
        $result = $this->get('lang/es');
        $result->assertRedirect();
        $result->assertSessionHas('locale', 'es');

        // Switch to English
        $result = $this->get('lang/en');
        $result->assertRedirect();
        $result->assertSessionHas('locale', 'en');

        // Unsupported locale should not set invalid locale in session
        $result = $this->withSession(['locale' => 'en'])->get('lang/fr');
        $result->assertRedirect();
        $result->assertSessionHas('locale', 'en');
    }

    public function testSpanishPageContentRenders(): void
    {
        $result = $this->withSession(['locale' => 'es'])->get('/');
        $result->assertStatus(200);
        $result->assertSee('lang="es"');
        $result->assertSee('Panel');
        $result->assertSee('Eventos');
        $result->assertSee('Proveedores');
        $result->assertSee('Categorías');
    }
}
