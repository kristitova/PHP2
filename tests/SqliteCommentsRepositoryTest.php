<?php


use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Person\Name;
use PHPUnit\Framework\TestCase;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\CommentsRepositoryInterface;



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
                ':uuid' => '7b973af1-9a35-4872-b712-84db819b6feb',
                ':post_uuid' => 'd02eef69-1a06-460f-b859-202b84164734',
                ':author_uuid' => '3e3694b5-6ce1-4ff3-81ec-0cd82da9c1a3',
                ':text' => 'Some text'
            ]);
        // 3. При вызове метода prepare стаб подключения
        // возвращает мок запроса
        $connectionStub->method('prepare')
            ->willReturn($statementMock);
        // 1. Передаём в репозиторий стаб подключения
        $repository = new SqliteCommentsRepository($connectionStub);
        // Вызываем метод сохранения комментария

        $user = new User(
            new UUID('3e3694b5-6ce1-4ff3-81ec-0cd82da9c1a3'),
            'name',
            new Name('first_name', 'last_name')
        );

        $post = new Post(
            new UUID('d02eef69-1a06-460f-b859-202b84164734'),
            $user,
            'title',
            'text'
        );

        $repository->save(
            new Comment( // Свойства комментария точно такие,
                // как и в описании мока
                new UUID('7b973af1-9a35-4872-b712-84db819b6feb'),
                $post,
                $user,
                'Some text'
            )
        );
    }
}
