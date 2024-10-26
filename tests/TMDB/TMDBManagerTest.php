<?php

namespace App\Tests\TMDB;

use App\TMDB\DTO\Genre;
use App\TMDB\TMDBManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBManagerTest extends KernelTestCase
{
    public function testGetGenres(): void
    {
        $this->stubToReturnFromClient('tests/Stubs/TMDB/genres.json');
        $data = self::getContainer()->get(TMDBManager::class)->getGenres();

        $this->assertCount(19, $data);
        $this->assertEquals(new Genre(28, "Action"), $data[0]);
        $this->assertEquals(new Genre(37, "Western"), $data[18]);
    }

    private function stubToReturnFromClient(string $stubName): void
    {
        $mockClient = new MockHttpClient(MockResponse::fromFile($stubName));
        self::getContainer()->set(HttpClientInterface::class, $mockClient);
    }
}
