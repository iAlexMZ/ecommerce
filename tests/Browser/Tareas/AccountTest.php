<?php

namespace Tests\Browser\Tareas;

use App\CreateData;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Category;
use App\Models\Subcategory;

class AccountTest extends DuskTestCase
{
    use DatabaseMigrations;
    use CreateData;

    //Test ya modificado en una sola línea
    /** @test */
    public function it_shows_the_options_login_when_logout()
    {
        $this->createCategory();

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->pause(500)
                ->assertSee('Categorías')
                ->click('@unlogged-image')
                ->pause(500)
                ->assertSee('Iniciar sesión')
                ->assertSee('Registrarse')
                ->screenshot('logout');
        });
    }

    //Test ya modificado en una sola línea
    /** @test */
    public function it_shows_the_account_options()
    {
        $this->createCategory();

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::factory()->create())
                ->visit('/')
                ->click('@profile_image')
                ->pause(500)
                ->assertSee('Perfil')
                ->assertSee('Finalizar sesión')
                ->pause(500)
                ->screenshot('account');
        });
    }
}
