<?php

namespace GeekBrains\LevelTwo\Blog\Commands\FakeData;

use Faker\Generator;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\Interfaces\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class PopulateDB extends Command
{

    public function __construct(
        private Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
        private CommentsRepositoryInterface $commentsRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')

            ->addOption(
                'users-number',
                'unum',
                InputOption::VALUE_OPTIONAL,
                'Users number',
            )

            ->addOption(
                'posts-number',
                'pnum',
                InputOption::VALUE_OPTIONAL,
                'Posts number',
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {

        $usersNum = $input->getOption('users-number');
        $postsNum = $input->getOption('posts-number');

        $users = [];
        for ($i = 0; $i < $usersNum; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->username());
        }


        foreach ($users as $user) {
            for ($i = 0; $i < $postsNum; $i++) {
                $post = $this->createFakePost($user);
                $comment = $this->createFakeComment($user, $post);
                $output->writeln('Post created: ' . $post->getTitle());
                $output->writeln('Comment created: ' . $comment->text());
            }
        }

        return Command::SUCCESS;
    }

    private function createFakeUser(): User
    {
        $user = User::createFrom(
            $this->faker->userName,
            $this->faker->password,
            new Name(
                $this->faker->firstName,
                $this->faker->lastName
            )
        );

        $this->usersRepository->save($user);
        return $user;
    }

    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author,
            $this->faker->sentence(6, true),
            $this->faker->realText
        );

        $this->postsRepository->save($post);
        return $post;
    }

    private function createFakeComment(User $author, Post $post): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $post,
            $author,
            $this->faker->realText
        );

        $this->commentsRepository->save($comment);
        return $comment;
    }
}
