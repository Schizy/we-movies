<?php

namespace App\Tests\TMDB;

use App\TMDB\DTO\Genre;
use App\TMDB\DTO\Movie;
use App\TMDB\DTO\Video;
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

    public function testMostPopular(): void
    {
        $this->stubToReturnFromClient('tests/Stubs/TMDB/most-popular.json');
        $data = self::getContainer()->get(TMDBManager::class)->mostPopular();

        $this->assertEquals(new Movie([
            'id' => 278,
            'poster_path' => "/9cqNxx0GxF0bflZmeSMuL5tnGzr.jpg",
            'overview' => "Imprisoned in the 1940s for the double murder of his wife and her lover, upstanding banker Andy Dufresne begins a new life at the Shawshank prison, where he puts his accounting skills to work for an amoral warden. During his long stretch in prison, Dufresne comes to be admired by the other inmates -- including an older prisoner named Red -- for his integrity and unquenchable sense of hope.",
            'title' => "The Shawshank Redemption",
            'release_date' => "1994-09-23",
            'vote_average' => 8.707,
            'vote_count' => 27011,
        ]), $data);
    }

    public function testVideos(): void
    {
        $this->stubToReturnFromClient('tests/Stubs/TMDB/videos.json');
        $data = self::getContainer()->get(TMDBManager::class)->videosByMovieId(550);
        $this->assertCount(4, $data);

        $this->assertEquals(new Video([
            'name' => "20th Anniversary Trailer",
            'key' => "dfeUzm6KF4g",
            'site' => 'YouTube',
            'type' => 'Trailer',
            'official' => true,
            'published_at' => '2019-10-15T18:59:47.000Z',
        ]), $data[0]);
    }

    public function testMovieById(): void
    {
        $this->stubToReturnFromClient('tests/Stubs/TMDB/movie.json');
        $data = self::getContainer()->get(TMDBManager::class)->movieById(550);

        $this->assertEquals(new Movie([
            'id' => 550,
            'poster_path' => "/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg",
            'overview' => "A ticking-time-bomb insomniac and a slippery soap salesman channel primal male aggression into a shocking new form of therapy. Their concept catches on, with underground \"fight clubs\" forming in every town, until an eccentric gets in the way and ignites an out-of-control spiral toward oblivion.",
            'title' => "Fight Club",
            'release_date' => "1999-10-15",
            'vote_average' => 8.438,
            'vote_count' => 29258,
        ]), $data);
    }

    private function stubToReturnFromClient(string $stubName): void
    {
        $mockClient = new MockHttpClient(MockResponse::fromFile($stubName));
        self::getContainer()->set(HttpClientInterface::class, $mockClient);
    }
}
