<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ProductsTest extends TestCase
{

    protected $Products;

    protected function setUp()
    {
        parent::setUp();

        \OnFact\Endpoint\Api::setApiKey(getenv('API_KEY'));
        \OnFact\Endpoint\Api::setCompanyUuid(getenv('COMPANY_UUID'));
        $this->Products = new \OnFact\Endpoint\Products();
        $this->ProductGroups = new \OnFact\Endpoint\ProductGroups();
    }

    /**
     * Test index method
     *
     * @throws Exception
     */
    public function testIndex() {
        $list = $this->Products->index([], ['X-FORCE-CACHE' => 300]);
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
        $this->ProductGroups->create($productGroup);


        $data = [
            'name' => 'Product or service A',
            'productgroup_id' => $productGroup->getId(),
            'price' => '500',
        ];
        $product = new \OnFact\Model\Product($data);
        $id = $this->Products->create($product);
        $this->assertInternalType('integer', $id);
        $this->assertEquals($id, $product->getId());
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
        $this->ProductGroups->create($productGroup);

        $data = [
            'name' => 'Product or service A',
            'productgroup_id' => $productGroup->getId(),
            'price' => 500,
        ];
        $product = new \OnFact\Model\Product($data);
        $id = $this->Products->create($product);

        $read = $this->Products->read($id, [], ['X-FORCE-CACHE' => 300]);
        $this->assertEquals($data['name'], $read->getName());
        $this->assertEquals($data['productgroup_id'], $read->getProductgroupId());
        $this->assertEquals($data['price'], $read->getPrice());
        $this->assertEquals(0, $read->getVirtualStock());
        $this->assertEquals(0, $read->getActualStock());
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
        $this->ProductGroups->create($productGroup);

        $data = [
            'name' => 'Product or service A',
            'productgroup_id' => $productGroup->getId(),
            'price' => 500,
        ];
        $product = new \OnFact\Model\Product($data);
        $this->Products->create($product);

        $product->setName('Product or service B');
        $product->setPrice(600);
        $this->Products->update($product);

        $read = $this->Products->read($product->getId());
        $this->assertEquals($product->getName(), $read->getName());
        $this->assertEquals($product->getProductgroupId(), $read->getProductgroupId());
        $this->assertEquals($product->getPrice(), $read->getPrice());
        $this->assertEquals(0, $read->getVirtualStock());
        $this->assertEquals(0, $read->getActualStock());
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
        $this->ProductGroups->create($productGroup);

        $data = [
            'name' => 'Product or service A',
            'productgroup_id' => $productGroup->getId(),
            'price' => 500,
        ];
        $product = new \OnFact\Model\Product($data);
        $this->Products->create($product);
        $this->Products->delete($product->getId());
        $read = $this->Products->read($product->getId());
        $this->assertTrue($read->getDeleted());
    }

}

