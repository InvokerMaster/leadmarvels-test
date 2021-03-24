<?php

namespace Tests\Feature;

use App\Http\Livewire\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * @test
     */
    public function test_toggle_verified() {
        $admin = User::role('admin')->first();

        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);
        $this->actingAs($admin);
        Livewire::test(EditUser::class)
            ->call('toggleVerified', $user)
            ->assertHasNoErrors();
        
        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    /**
     * @test
     */
    public function test_pagination() {
        $admin = User::role('admin')->first();
        $this->actingAs($admin);
        Livewire::test(EditUser::class)
            ->assertSee('Showing 10 of ' . User::general()->count() . ' users');
    }

    /**
     * @test
     */
    public function test_search() {
        $admin = User::role('admin')->first();
        $this->actingAs($admin);
        $user = User::general()->first();
        Livewire::test(EditUser::class)
            ->set('search', $user->email)
            ->assertSet('total', 1);
    }

    /**
     * @test
     */
    public function test_sort() {
        $admin = User::role('admin')->first();
        $this->actingAs($admin);
        $user = User::general()->orderBy('id', 'desc')->first();
        Livewire::test(EditUser::class)
            ->set('orderDirection', 'desc')
            ->set('orderBy', 'id')
            ->assertSee($user->email);
    }

    /**
     * @test
     */
    public function it_should_have_headers_with_correct_counting() {
        // TODO: implement
        $this->assertTrue(true);
    }
}
