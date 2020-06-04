<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class DescriptionsTest extends TestCase
{

    protected $Descriptions;

    protected function setUp()
    {
        parent::setUp();

        \OnFact\Endpoint\Api::setApiKey(getenv('API_KEY'));
        \OnFact\Endpoint\Api::setCompanyUuid(getenv('COMPANY_UUID'));
        $this->Descriptions = new \OnFact\Endpoint\Descriptions();
    }

    /**
     * Test index method
     *
     * @throws Exception
     */
    public function testIndex($queryParams = []) {
        $list = $this->Descriptions->index();
        $this->assertContains('Index', get_class($list));
        $this->assertContains('Paging', get_class($list->getPaging()));
        $this->assertInternalType('int', $list->getCount());
        $this->assertInternalType('array', $list->getItems());

        $list = $this->Descriptions->index(['q' => 'language_id:nld']);
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
        $Description = new \OnFact\Model\Description($data);
        $id = $this->Descriptions->create($Description);
        $this->assertInternalType('integer', $id);
        $this->assertEquals($id, $Description->getId());
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
        $Description = new \OnFact\Model\Description($data);
        $id = $this->Descriptions->create($Description);

        $read = $this->Descriptions->read($id);

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
        $Description = new \OnFact\Model\Description($data);
        $id = $this->Descriptions->create($Description);

        $Description->setName('Product or service B');
        $Description->setGLAccountCode('700100');
        $Description->setProfitMargin(150);
        $this->Descriptions->update($Description);

        $read = $this->Descriptions->read($id);
        $this->assertEquals($Description->getName(), $read->getName());
        $this->assertEquals($Description->getGLAccountCode(), $read->getGLAccountCode());
        $this->assertEquals($Description->getProfitMargin(), $read->getProfitMargin());
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
        $Description = new \OnFact\Model\Description($data);
        $id = $this->Descriptions->create($Description);

        $this->Descriptions->delete($id);

        $read = $this->Descriptions->read($id);
        $this->assertTrue($read->getDeleted());
    }

}

