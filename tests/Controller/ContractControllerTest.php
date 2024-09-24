<?php

namespace App\Test\Controller;

use App\Entity\Contract;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContractControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/contract/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Contract::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contract index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'contract[provider]' => 'Testing',
            'contract[description]' => 'Testing',
            'contract[startDate]' => 'Testing',
            'contract[endDate]' => 'Testing',
            'contract[pricePerKwh]' => 'Testing',
            'contract[additionalCosts]' => 'Testing',
            'contract[initialReading]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contract();
        $fixture->setProvider('My Title');
        $fixture->setDescription('My Title');
        $fixture->setStartDate('My Title');
        $fixture->setEndDate('My Title');
        $fixture->setPricePerKwh('My Title');
        $fixture->setAdditionalCosts('My Title');
        $fixture->setInitialReading('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Contract');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contract();
        $fixture->setProvider('Value');
        $fixture->setDescription('Value');
        $fixture->setStartDate('Value');
        $fixture->setEndDate('Value');
        $fixture->setPricePerKwh('Value');
        $fixture->setAdditionalCosts('Value');
        $fixture->setInitialReading('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'contract[provider]' => 'Something New',
            'contract[description]' => 'Something New',
            'contract[startDate]' => 'Something New',
            'contract[endDate]' => 'Something New',
            'contract[pricePerKwh]' => 'Something New',
            'contract[additionalCosts]' => 'Something New',
            'contract[initialReading]' => 'Something New',
        ]);

        self::assertResponseRedirects('/contract/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getProvider());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getStartDate());
        self::assertSame('Something New', $fixture[0]->getEndDate());
        self::assertSame('Something New', $fixture[0]->getPricePerKwh());
        self::assertSame('Something New', $fixture[0]->getAdditionalCosts());
        self::assertSame('Something New', $fixture[0]->getInitialReading());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Contract();
        $fixture->setProvider('Value');
        $fixture->setDescription('Value');
        $fixture->setStartDate('Value');
        $fixture->setEndDate('Value');
        $fixture->setPricePerKwh('Value');
        $fixture->setAdditionalCosts('Value');
        $fixture->setInitialReading('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/contract/');
        self::assertSame(0, $this->repository->count([]));
    }
}
