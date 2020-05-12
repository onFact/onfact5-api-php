<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ProductGroupsTest extends TestCase
{

    protected $ProductGroups;

    protected function setUp()
    {
        parent::setUp();

        \OnFact\Endpoint\Api::setApiKey(getenv('API_KEY'));
        \OnFact\Endpoint\Api::setCompanyUuid(getenv('COMPANY_UUID'));
        $this->ProductGroups = new \OnFact\Endpoint\ProductGroups();
    }

    /**
     * Test index method
     *
     * @throws Exception
     */
    public function testIndex() {
        $list = $this->ProductGroups->index();
        $this->assertContains('Index', get_class($list));
        $this->assertContains('Paging', get_class($list->getPaging()));
        $this->assertInternalType('int', $list->getCount());
        $this->assertInternalType('array', $list->getItems());
    }

    /**
     * Test create method
     *
     * @throws Exception
     */
    public function testCreate() {
        $data = [
            'name' => 'Product or service A',
            'g_l_account_code' => 700000,
            'profit_margin' => 100,
        ];
        $productGroup = new \OnFact\Model\ProductGroup($data);
        $id = $this->ProductGroups->create($productGroup);
        $this->assertInternalType('integer', $id);
        $this->assertEquals($id, $productGroup->getId());
    }

    /**
     * Test read method
     *
     * @throws Exception
     */
    public function testRead() {
        $data = [
            'name' => 'Product or service A',
            'g_l_account_code' => 700000,
            'profit_margin' => 100,
        ];
        $productGroup = new \OnFact\Model\ProductGroup($data);
        $id = $this->ProductGroups->create($productGroup);

        $read = $this->ProductGroups->read($id);

        $this->assertEquals($data['name'], $read->getName());
        $this->assertEquals($data['g_l_account_code'], $read->getGLAccountCode());
        $this->assertEquals($data['profit_margin'], $read->getProfitMargin());
    }

    /**
     * Test update method
     *
     * @throws Exception
     */
    public function testUpdate() {
        $data = [
            'name' => 'Product or service A',
            'g_l_account_code' => 700000,
            'profit_margin' => 100,
        ];
        $productGroup = new \OnFact\Model\ProductGroup($data);
        $id = $this->ProductGroups->create($productGroup);

        $productGroup->setName('Product or service B');
        $productGroup->setGLAccountCode('700100');
        $productGroup->setProfitMargin(150);
        $this->ProductGroups->update($productGroup);

        $read = $this->ProductGroups->read($id);
        $this->assertEquals($productGroup->getName(), $read->getName());
        $this->assertEquals($productGroup->getGLAccountCode(), $read->getGLAccountCode());
        $this->assertEquals($productGroup->getProfitMargin(), $read->getProfitMargin());
    }

    /**
     * Test delete method
     *
     * @throws Exception
     */
    public function testDelete() {
        $data = [
            'name' => 'Product or service A',
            'g_l_account_code' => 700000,
            'profit_margin' => 100,
        ];
        $productGroup = new \OnFact\Model\ProductGroup($data);
        $id = $this->ProductGroups->create($productGroup);

        $this->ProductGroups->delete($id);

        $read = $this->ProductGroups->read($id);
        $this->assertTrue($read->getDeleted());
    }

}

