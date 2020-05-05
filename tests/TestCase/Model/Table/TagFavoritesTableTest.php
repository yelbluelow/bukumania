<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TagFavoritesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TagFavoritesTable Test Case
 */
class TagFavoritesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TagFavoritesTable
     */
    public $TagFavorites;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TagFavorites',
        'app.Users',
        'app.Tags',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TagFavorites') ? [] : ['className' => TagFavoritesTable::class];
        $this->TagFavorites = TableRegistry::getTableLocator()->get('TagFavorites', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TagFavorites);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
