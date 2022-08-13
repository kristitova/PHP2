<?php

namespace Php\App\Blog\tests\Repositories\PostsRepository;

use Php\App\Blog\Exceptions\PostNotFoundException;
use Php\App\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Php\App\Blog\Post;
use Php\App\Blog\UUID;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;


class SqlitePostsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);


        $repository = new SqlitePostsRepository($connectionMock);
        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot find post: 7a973af1-9a35-4872-b711-84db819b6feb');

        $repository->get(new UUID('7a973af1-9a35-4872-b711-84db819b6feb'));
    }

    // Тест, проверяющий, что репозиторий сохраняет данные в БД
    public function testItSavesPostToDatabase(): void
    {
        // 2. Создаём стаб подключения
        $connectionStub = $this->createStub(PDO::class);
        // 4. Создаём мок запроса, возвращаемый стабом подключения
        $statementMock = $this->createMock(PDOStatement::class);
        // 5. Описываем ожидаемое взаимодействие
        // нашего репозитория с моком запроса
        $statementMock
            ->expects($this->once()) // Ожидаем, что будет вызван один раз
            ->method('execute') // метод execute
            ->with([ // с единственным аргументом - массивом
                ':uuid' => '7b973af1-9a35-4872-b711-84db819b6feb',
                ':author_uuid' => '7b973af1-9c65-4872-b711-84db819b6feb',
                ':title' => 'Some title',
                ':text' => 'Some text'
            ]);
        // 3. При вызове метода prepare стаб подключения
        // возвращает мок запроса
        $connectionStub->method('prepare')
            ->willReturn($statementMock);
        // 1. Передаём в репозиторий стаб подключения
        $repository = new SqlitePostsRepository($connectionStub);
        // Вызываем метод сохранения статьи
        $repository->save(
            new Post( // Свойства статьи точно такие,
                // как и в описании мока
                new UUID('7b973af1-9a35-4872-b711-84db819b6feb'),
                new UUID('7b973af1-9c65-4872-b711-84db819b6feb'),
                'Some title',
                'Some text'
            )
        );
    }
}
