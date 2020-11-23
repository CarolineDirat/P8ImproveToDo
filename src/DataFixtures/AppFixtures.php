<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // 51 tasks, 25 are done
        for ($i = 1; $i <= 51; ++$i) {
            $task = new Task();
            $task->setTitle('Titre de la tâche n°'.$i);
            $task->setContent('Texte du contenu de la tâche n°'.$i);
            if (0 === $i % 2) {
                $task->toggle(true);
            }
            $manager->persist($task);
        }

        $manager->flush();
    }
}
