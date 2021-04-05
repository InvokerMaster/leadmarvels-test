<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ContractTest extends TestCase
{
    use RefreshDatabase;
    
    protected $seed = true;

    /**
     * @test
     */
    public function contract_can_be_created_for_a_company() {
        $company = Company::factory()->create();
        $company->contracts()->save(Contract::factory()->make());
        $this->assertEquals($company->contracts()->count(), 1);
    }

    /**
     * @test
     */
    public function contract_start_date_connot_overlap_an_existing_contract_for_same_company() {
        $contract = Contract::factory()->create([
            'start_date' => now(),
            'end_date' => now()->addDays(30)
        ]);
        $daysDiff = $contract->end_date->diffInDays($contract->start_date);
        try {
            $overlap = Contract::factory()->create([
                'company_id' => $contract->company,
                'start_date' => now()->addDays(1),
                'end_date' => now()->addDays(rand(2, $daysDiff))
            ]);
        } catch (ValidationException $ex) {
            $overlap = null;
        } catch (\Exception $ex) {
            $this->assertTrue(false);
        }
        $this->assertNull($overlap);
    }

    /**
     * @test
     */
    public function contract_end_date_can_not_overlap_an_existing_contract_for_the_same_company() {
        $contract = Contract::factory()->create([
            'start_date' => now(),
            'end_date' => now()->addDays(30)
        ]);
        $daysDiff = $contract->end_date->diffInDays($contract->start_date);
        try {
            $overlap = Contract::factory()->create([
                'company_id' => $contract->company,
                'start_date' => now()->addDays(-1),
                'end_date' => now()->addDays(rand(2, $daysDiff))
            ]);
        } catch (ValidationException $ex) {
            $overlap = null;
        } catch (\Exception $ex) {
            $this->assertTrue(false);
        }
        $this->assertNull($overlap);
    }

    /**
     * @test
     */
    public function contract_cannot_be_created_for_a_company_where_start_date_is_before_end_date() {
        try {
            $contract = Contract::factory()->create([
                'start_date' => now(),
                'end_date' => now()->addDays(-30)
            ]);
        } catch (ValidationException $ex) {
            $contract = null;
        } catch (\Exception $ex) {
            $this->assertTrue(false);
        }
        $this->assertNull($contract);
    }

    /**
     * @test
     */
    public function contract_start_date_can_overlap_an_existing_contract_for_another_company() {
        $contract1 = Contract::factory()->create([
            'start_date' => now(),
            'end_date' => now()->addDays(30)
        ]);
        $contract2 = Contract::factory()->create([
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(31)
        ]);
        $this->assertNotEquals($contract1->company_id, $contract2->company_id);
    }

    /**
     * @test
     */
    public function contract_end_date_can_overlap_an_existing_contract_for_another_company() {
        $contract1 = Contract::factory()->create([
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(30)
        ]);
        $contract2 = Contract::factory()->create([
            'start_date' => now(),
            'end_date' => now()->addDays(29)
        ]);
        $this->assertNotEquals($contract1->company_id, $contract2->company_id);
    }
}
