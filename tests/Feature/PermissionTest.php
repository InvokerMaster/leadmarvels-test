<?php

namespace Tests\Feature;

use App\Http\Livewire\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * @test
     */
    public function test_general_users_cant_edit_user_but_only_admin()
    {
        // TODO: implement test
        $admin = User::role('admin')->first();

        $user = User::factory()->create();
        $this->actingAs($admin);
        Livewire::test(EditUser::class)
            ->call('toggleVerified', $user)
            ->assertHasNoErrors();

        $hacker = User::factory()->create();
        $this->actingAs($hacker);
        Livewire::test(EditUser::class)
            ->call('toggleVerified', $user)
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function test_general_users_cant_see_user_page()
    {
        // TODO: implement test
        $this->assertTrue(true);
    }
}
