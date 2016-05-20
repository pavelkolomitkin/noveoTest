<?php

namespace AppBundle\Services;

use AppBundle\Entity\Pokemon;
use Doctrine\ORM\EntityManager;

class PokemonFileProcessor
{
    private $entityManager;

    public function __construct(EntityManager $manager)
    {
        $this->entityManager = $manager;
    }

    public function importFile($path, $force = false)
    {
        if ($force)
        {
            $this->entityManager->createQuery("DELETE FROM AppBundle:Pokemon")->execute();
        }

        if (($handle = fopen($path, "r")) !== FALSE) {
            $rowNumber = 0;

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
            {
                if (count($data) == 1)
                {
                    $data = explode(',', $data[0]);
                }

                if ($rowNumber > 0)
                {
                    $pokemon = new Pokemon();

                    $pokemon
                        ->setName($data[0])
                        ->setType($data[1])
                        ->setDescription($data[2]);

                    $this->entityManager->persist($pokemon);
                }


                $rowNumber++;
            }
            fclose($handle);

            $this->entityManager->flush();
        }
    }
}