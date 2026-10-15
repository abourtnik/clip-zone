<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_access_admin_routes() :void
    {
        $admin = User::factory()
            ->admin()
            ->create();

        $this
            ->actingAs($admin)
            ->get(route('admin.index'))
            ->assertOk();
    }

    #[Test]
    public function non_admin_gets_404() :void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get(route('admin.index'))
            ->assertNotFound();
    }

    #[Test]
    public function guest_gets_404() :void
    {
        $this
            ->get(route('admin.index'))
            ->assertNotFound();
    }
}
