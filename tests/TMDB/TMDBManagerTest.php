<?php

namespace App\Tests\TMDB;

use App\TMDB\DTO\Genre;
use App\TMDB\DTO\Movie;
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
        $this->assertEquals(new Genre(['id' => 28, 'name' => 'Action']), $data[0]);
        $this->assertEquals(new Genre(['id' => 37, 'name' => 'Western']), $data[18]);
    }

    public function testGetMoviesByGenre(): void
    {
        $this->stubToReturnFromClient('tests/Stubs/TMDB/movies.json');
        $data = self::getContainer()->get(TMDBManager::class)->getMoviesByGenre(35);
        $this->assertCount(20, $data);

        $this->assertEquals(new Movie([
            'id' => 533535,
            'poster_path' => "/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg",
            'overview' => "A listless Wade Wilson toils away in civilian life with his days as the morally flexible mercenary, Deadpool, behind him. But when his homeworld faces an existential threat, Wade must reluctantly suit-up again with an even more reluctant Wolverine.",
            'title' => "Deadpool & Wolverine",
            'release_date' => "2024-07-24",
            'vote_average' => 7.713,
            'vote_count' => 4804,
        ]), $data[0]);
    }

    public function testSearchMovies(): void
    {
        $this->stubToReturnFromClient('tests/Stubs/TMDB/search.json');
        $data = self::getContainer()->get(TMDBManager::class)->searchMovies('figh');
        $this->assertCount(20, $data);

        $this->assertEquals(new Movie([
            'id' => 45288,
            'poster_path' => "/9UNq2kogJ0fNA6TfFJdJt0PXOwV.jpg",
            'overview' => "Kimura finally has his championship match. It takes place at the annual champion carnival. But now he must face the intense champion, Mashiba for the Junior Lightweight championship.",
            'title' => "Fighting Spirit - Mashiba vs. Kimura",
            'release_date' => "2003-09-05",
            'vote_average' => 6.8,
            'vote_count' => 46,
        ]), $data[0]);
    }

    private function stubToReturnFromClient(string $stubName): void
    {
        $mockClient = new MockHttpClient(MockResponse::fromFile($stubName));
        self::getContainer()->set(HttpClientInterface::class, $mockClient);
    }
}
