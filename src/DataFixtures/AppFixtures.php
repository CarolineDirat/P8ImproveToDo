<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use DateInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
    * encoder
    *
    * @var UserPasswordEncoderInterface
    */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // 51 tasks, 25 are done
        for ($i = 1; $i <= 51; ++$i) {
            $task = new Task();
            $task->setTitle('Tâche n°'.$i);
            $task->setContent('Texte du contenu de la tâche n°'.$i);
            $duration = 'PT'.(string) $i.'H';
            $task->setUpdatedAt($task->getUpdatedAt()->add(new DateInterval($duration)));
            if (0 === $i % 2) {
                $task->toggle(true);
            }
            $manager->persist($task);
        }

        $user = new User();
        $user->setUsername('user1');
        $user->setPassword($this->encoder->encodePassword($user, 'password'));
        $user->setEmail('user1@email.com');
        $manager->persist($user);

        $manager->flush();
    }
}
