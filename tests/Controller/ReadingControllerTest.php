<?php

namespace App\Test\Controller;

use App\Entity\Reading;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReadingControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/reading/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Reading::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reading index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'reading[readingDate]' => 'Testing',
            'reading[valueinKwh]' => 'Testing',
            'reading[comment]' => 'Testing',
            'reading[contract]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reading();
        $fixture->setReadingDate('My Title');
        $fixture->setValueinKwh('My Title');
        $fixture->setComment('My Title');
        $fixture->setContract('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reading');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reading();
        $fixture->setReadingDate('Value');
        $fixture->setValueinKwh('Value');
        $fixture->setComment('Value');
        $fixture->setContract('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reading[readingDate]' => 'Something New',
            'reading[valueinKwh]' => 'Something New',
            'reading[comment]' => 'Something New',
            'reading[contract]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reading/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getReadingDate());
        self::assertSame('Something New', $fixture[0]->getValueinKwh());
        self::assertSame('Something New', $fixture[0]->getComment());
        self::assertSame('Something New', $fixture[0]->getContract());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reading();
        $fixture->setReadingDate('Value');
        $fixture->setValueinKwh('Value');
        $fixture->setComment('Value');
        $fixture->setContract('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/reading/');
        self::assertSame(0, $this->repository->count([]));
    }
}
