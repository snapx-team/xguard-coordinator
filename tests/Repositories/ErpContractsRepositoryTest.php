<?php

namespace Tests\Unit\Repositories;

use App\Models\Contract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Xguard\Coordinator\Repositories\ErpContractsRepository;

class ErpContractsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    const ZERO = 0;
    const ONE = 1;
    const SEARCH_TERM_MIN = 1;
    const SEARCH_TERM_MAX = 3;
    const IDENTIFIER_MIN = 1;
    const IDENTIFIER_MAX = 16;
    const LOOP_MAX = 10;
    const CONTRACT_COUNT_MAX = 10;

    public function setUp(): void
    {
        parent::setUp();
        $this->contractsRepository = new ErpContractsRepository();
    }

    public function testRetrieveOnInValidIdReturnsNull()
    {
        $result = $this->contractsRepository::retrieve(rand());
        $this->assertNull($result);
    }

    public function testGetSomeContractsReturnsCollectionOfMatchingEntries()
    {
        $searchTerm = Str::random(rand(self::SEARCH_TERM_MIN, self::SEARCH_TERM_MAX));
        $matchCount = 0;

        for ($i = self::ZERO; $i < self::LOOP_MAX; $i++) {
            $identifier = Str::random(rand(self::IDENTIFIER_MIN, self::IDENTIFIER_MAX));
            factory(Contract::class)->create(['contract_identifier' => $identifier]);
            if (stripos($identifier, $searchTerm) === self::ZERO || stripos($identifier, $searchTerm)) {
                $matchCount = $matchCount + self::ONE;
            }
        }

        $result = $this->contractsRepository::getSomeContracts($searchTerm);
        $this->assertEquals($matchCount, $result->count());
    }

    public function testGetSomeContractsReturnsCollectionOfMax10Contracts()
    {
        $searchTerm = Str::random(rand(self::SEARCH_TERM_MIN, self::SEARCH_TERM_MAX));

        factory(Contract::class, self::CONTRACT_COUNT_MAX + self::ONE)->create(['contract_identifier' => $searchTerm]);

        $result = $this->contractsRepository::getSomeContracts($searchTerm);

        $this->assertEquals(self::CONTRACT_COUNT_MAX, $result->count());
    }

    public function testGetAllContractsReturnsCollectionOfAllContracts()
    {
        $contractCount = rand(self::ZERO, self::CONTRACT_COUNT_MAX);
        factory(Contract::class, $contractCount)->create();
        $result = $this->contractsRepository::getAllContracts();
        $this->assertEquals($contractCount, $result->count());
    }
}
