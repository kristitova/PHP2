<?php

namespace Php\App\Blog\tests\Repositories\CommentsRepository;

use Php\App\Blog\Exceptions\CommentNotFoundException;
use Php\App\Blog\Exceptions\InvalidArgumentException;
use Php\App\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use Php\App\Blog\Comment;
use Php\App\Blog\UUID;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;


class SqliteCommentsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $connectionMock = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStub);


        $repository = new SqliteCommentsRepository($connectionMock);
        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot find comment: 955a53ac-968f-4c69-a48a-497d1a918fd9');

        $repository->get(new UUID('955a53ac-968f-4c69-a48a-497d1a918fd9'));
    }

    // Тест, проверяющий, что репозиторий сохраняет данные в БД
    public function testItSavesCommentToDatabase(): void
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
                ':post_uuid' => '7b973af1-9c65-4872-b711-84db819b6feb',
                ':author_uuid' => '7b973af1-9d65-4872-b711-84db819b6feb',
                ':text' => 'Some text'
            ]);
        // 3. При вызове метода prepare стаб подключения
        // возвращает мок запроса
        $connectionStub->method('prepare')
            ->willReturn($statementMock);
        // 1. Передаём в репозиторий стаб подключения
        $repository = new SqliteCommentsRepository($connectionStub);
        // Вызываем метод сохранения комментария
        $repository->save(
            new Comment( // Свойства комментария точно такие,
                // как и в описании мока
                new UUID('7b973af1-9a35-4872-b711-84db819b6feb'),
                new UUID('7b973af1-9c65-4872-b711-84db819b6feb'),
                new UUID('7b973af1-9d65-4872-b711-84db819b6feb'),
                'Some text'
            )
        );
    }
}
