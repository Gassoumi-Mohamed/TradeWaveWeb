<?php

namespace App\Test\Controller;

use App\Entity\Market;
use App\Repository\MarketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MarketControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private MarketRepository $repository;
    private string $path = '/market/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Market::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Market index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'market[IDmarket]' => 'Testing',
            'market[name]' => 'Testing',
            'market[rate]' => 'Testing',
            'market[Sprice]' => 'Testing',
            'market[Bprice]' => 'Testing',
            'market[Mcap]' => 'Testing',
            'market[typeM]' => 'Testing',
            'market[IMG_SRC]' => 'Testing',
            'market[ID_user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/market/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Market();
        $fixture->setIDmarket('My Title');
        $fixture->setName('My Title');
        $fixture->setRate('My Title');
        $fixture->setSprice('My Title');
        $fixture->setBprice('My Title');
        $fixture->setMcap('My Title');
        $fixture->setTypeM('My Title');
        // $fixture->setIMG_SRC('My Title');
        // $fixture->setID_user('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Market');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Market();
        $fixture->setIDmarket('My Title');
        $fixture->setName('My Title');
        $fixture->setRate('My Title');
        $fixture->setSprice('My Title');
        $fixture->setBprice('My Title');
        $fixture->setMcap('My Title');
        $fixture->setTypeM('My Title');
        // $fixture->setIMG_SRC('My Title');
        // $fixture->setID_user('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'market[IDmarket]' => 'Something New',
            'market[name]' => 'Something New',
            'market[rate]' => 'Something New',
            'market[Sprice]' => 'Something New',
            'market[Bprice]' => 'Something New',
            'market[Mcap]' => 'Something New',
            'market[typeM]' => 'Something New',
            'market[IMG_SRC]' => 'Something New',
            'market[ID_user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/market/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getIDmarket());
        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getRate());
        self::assertSame('Something New', $fixture[0]->getSprice());
        self::assertSame('Something New', $fixture[0]->getBprice());
        self::assertSame('Something New', $fixture[0]->getMcap());
        self::assertSame('Something New', $fixture[0]->getTypeM());
        // self::assertSame('Something New', $fixture[0]->getIMG_SRC());
        // self::assertSame('Something New', $fixture[0]->getID_user());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Market();
        $fixture->setIDmarket('My Title');
        $fixture->setName('My Title');
        $fixture->setRate('My Title');
        $fixture->setSprice('My Title');
        $fixture->setBprice('My Title');
        $fixture->setMcap('My Title');
        $fixture->setTypeM('My Title');
        // $fixture->setIMG_SRC('My Title');
        // $fixture->setID_user('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

       // $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/market/');
    }
}
