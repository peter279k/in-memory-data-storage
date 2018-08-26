<?php

namespace Scheb\InMemoryDataStorage\Test;

use Scheb\InMemoryDataStorage\Repository\DataRepository;
use Scheb\InMemoryDataStorage\DataStorage\ArrayDataStorage;
use Scheb\InMemoryDataStorage\Test\TestCase;
use Scheb\InMemoryDataStorage\Exception\ItemNotFoundException;
use Scheb\InMemoryDataStorage\Exception\NamedItemNotFoundException;

class DataRepositoryTest extends TestCase
{
    /**
     * @var ArrayDataStorage
     */
    private $dataRepository;

    public function setUp()
    {
        $this->dataRepository = new DataRepository(new ArrayDataStorage());
    }

    /**
     * @test
     */
    public function addItem_addOneItem(): void
    {
        $this->dataRepository->addItem(100);

        $this->assertSame([100], $this->dataRepository->getAllItems());
        $this->assertTrue($this->dataRepository->containsItem(100));
        $this->assertFalse($this->dataRepository->containsItem(200));
    }

    /**
     * @test
     */
    public function removeItem_removeOneItem(): void
    {
        $this->dataRepository->addItem(100);
        $this->dataRepository->removeItem(100);

        $this->assertNotContains(100, $this->dataRepository->getAllItems());
    }

    /**
     * @test
     */
    public function removeItem_removeNonExistedItem(): void
    {
        $this->expectException(ItemNotFoundException::class);
        $this->expectExceptionMessage('Item "100" does not exist in data storage.');

        $this->dataRepository->removeItem(100);
    }

    /**
     * @test
     */
    public function addNamedItem_addOneNamedItem(): void
    {
        $this->dataRepository->addNamedItem('key', 'value');

        $this->assertSame('value', $this->dataRepository->getNamedItem('key'));
        $this->assertTrue($this->dataRepository->namedItemExists('key'));
    }

    /**
     * @test
     */
    public function getNamedItem_getNonExistedNamedItem(): void
    {
        $this->expectException(NamedItemNotFoundException::class);
        $this->expectExceptionMessage('Named item "non_existed_key" does not exist in data storage.');

        $this->dataRepository->getNamedItem('non_existed_key');
    }

    /**
     * @test
     */
    public function replaceNamedItem_replaceOneNamedItem(): void
    {
        $this->dataRepository->addNamedItem('key', 'value');
        $this->dataRepository->replaceNamedItem('key', 'new_value');

        $this->assertSame('new_value', $this->dataRepository->getNamedItem('key'));
    }

    /**
     * @test
     */
    public function replaceNamedItem_replaceInvalidNamedItem(): void
    {
        $this->expectException(NamedItemNotFoundException::class);
        $this->expectExceptionMessage('Named item "non_existed_key" does not exist in data storage.');

        $this->dataRepository->replaceNamedItem('non_existed_key', 'new_value');
    }

    /**
     * @test
     */
    public function removeNamedItem_removeOneNamedItem(): void
    {
        $this->dataRepository->addNamedItem('key', 'value');
        $this->dataRepository->removeNamedItem('key');

        $this->assertNotContains('key', $this->dataRepository->getAllItems());
        $this->assertFalse($this->dataRepository->namedItemExists('key'));
    }

    /**
     * @test
     */
    public function removeNamedItem_removeNonExistedNamedItem(): void
    {
        $this->expectException(NamedItemNotFoundException::class);
        $this->expectExceptionMessage('Named item "non_existed_key" does not exist in data storage.');

        $this->dataRepository->removeNamedItem('non_existed_key');
    }
}
