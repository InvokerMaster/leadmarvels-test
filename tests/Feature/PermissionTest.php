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
        $admin = User::role('admin')->first();

        $user = User::factory()->create();
        $this->actingAs($admin);
        Livewire::test(EditUser::class)
            ->call('toggleVerified', $user)
            ->assertHasNoErrors();

        $guest = User::factory()->create([
            'email_verified_at' => now()
        ]);
        $this->actingAs($guest);
        Livewire::test(EditUser::class)
            ->call('toggleVerified', $user)
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function test_general_users_cant_see_user_page()
    {
        $admin = User::role('admin')->first();

        $guest = User::factory()->create([
            'email_verified_at' => now()
        ]);

        $this->actingAs($admin);
        $this->get(route('admin.user'))
            ->assertStatus(200);
            
        $this->actingAs($guest);
        $this->get(route('admin.user'))
            ->assertRedirect();
    }
}
