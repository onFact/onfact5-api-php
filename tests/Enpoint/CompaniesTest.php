<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CompaniesTest extends TestCase
{

    protected $Companies;

    protected function setUp()
    {
        parent::setUp();

        \OnFact\Endpoint\Api::setApiKey(getenv('API_KEY'));
        \OnFact\Endpoint\Api::setCompanyUuid(getenv('COMPANY_UUID'));
        $this->Companies = new \OnFact\Endpoint\Companies();
    }

    /**
     * Test index method
     *
     * @throws Exception
     */
    public function testIndex() {
        $list = $this->Companies->index();
        $this->assertContains('Index', get_class($list));
        $this->assertContains('Paging', get_class($list->getPaging()));
        $this->assertEquals(1, $list->getCount());
        $this->assertInternalType('int', $list->getCount());
        $this->assertInternalType('array', $list->getItems());
    }

    /**
     * Test create method
     *
     * @throws Exception
     */
    public function testCreateExistsError() {
        $data = [
            'name' => 'Product or service A',
            'users' => [
                [
                    'email' => 'tes@infinwebs.be',
                    'password' => rand(100000, 99999) . 'ABC'
                ]
            ]
        ];
        $company = new \OnFact\Model\Company($data);
        try {
            $id = $this->Companies->create($company);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $error = json_decode($e->getResponse()->getBody()->__toString());
            $this->assertEquals('ValidationError', $error->code);
            $this->assertEquals('This value is already in use', $error->errors->users[0]->email->_isUnique);
        }
    }

    /**
     * Test create method
     *
     * @throws Exception
     */
    public function testCreateExistsInvalidEmail() {
        $data = [
            'name' => 'Product or service A',
            'users' => [
                [
                    'email' => 'test',
                    'password' => rand(100000, 99999) . 'ABC'
                ]
            ]
        ];
        $company = new \OnFact\Model\Company($data);
        try {
            $id = $this->Companies->create($company);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $error = json_decode($e->getResponse()->getBody()->__toString());
            $this->assertEquals('ValidationError', $error->code);
            $this->assertEquals('The provided value is invalid', $error->errors->users[0]->email->email);
        }
    }

    /**
     * Test create method
     *
     * @throws Exception
     */
    public function testCreate() {
        $data = [
            'name' => 'Product or service A',
            'platform_id' => 4,
            'role' => 'affiliate',
            'users' => [
                [
                    'email' => 'test' . rand(10000000000, 999999999999) . '@infinwebs.be',
                    'password' => rand(100000, 99999) . 'ABC'
                ]
            ]
        ];
        $company = new \OnFact\Model\Company($data);
        $id = $this->Companies->create($company);
        $this->assertInternalType('integer', $id);
        $this->assertEquals($id, $company->getId());

        $response = $this->Companies->getResponse();
        $this->assertContains('?token', $response->redirect_url);
    }
}

