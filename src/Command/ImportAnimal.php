<?php
namespace App\Command;

use App\Entity\Animal;
use App\Entity\Category;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportAnimal extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:importarticle';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }


    protected function configure()
    {
    // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cm =  $this->container->get('doctrine')->getManager();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://apiv3.iucnredlist.org/api/v3/species/category/CR?token=9bb4facb6d23f48efbf424bb05c0c1ef1cf6f468393bc745d42179ac4aca5fee");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $animals = json_decode($output, true)['result'];

        $allCategory = array_map(function($a) {
            return [$a->getName() => ['categ' => $a, 'id' => $a->getId()]];
        }, $cm->getRepository('App:Category')->findAll());

        $allAnimals = array_map(function($a) {
            return $a->getScientificName();
        }, $cm->getRepository('App:Animal')->findAll());

        $temp = [];
        foreach ($allCategory as $key => $item) {
            $temp[$key] = $item;
        }

        $allCategory = $temp;
        if(!count($animals)) throw new \Exception('hello there');
        $count = count($animals);
        var_dump($count.' Founded');
        $i = 1;
        foreach ($animals as $animal) {
            var_dump( $i . ' / '. $count . ' ('.$animal['scientific_name'].')', in_array($animal['scientific_name'], $allAnimals));
            $i++;
            if (!in_array($animal['scientific_name'], $allAnimals)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,
                    "http://apiv3.iucnredlist.org/api/v3/species/".$animal['scientific_name']."?token=9bb4facb6d23f48efbf424bb05c0c1ef1cf6f468393bc745d42179ac4aca5fee&fbclid=IwAR1J4Lb856Pm1haugOHsr1hxiI7n4B95JiFqKXDqvBZaF-jdNK56vgQj5lE");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $outputAnimal = curl_exec($ch);
                $outputAnimal = json_decode($outputAnimal, true);
                if(isset($outputAnimal['result'])) {
                    $entry = $outputAnimal['result'][0];
                    if(isset($entry['kingdom']) && $entry['kingdom'] == 'ANIMALIA') {
                        $categoryName = $entry["class"];
                        $category = null;
                        if(!array_key_exists($categoryName, $allCategory)) {
                            var_dump('Creating categ '. $categoryName);
                            $category = new Category();
                            $category->setName($categoryName);
                            $cm->persist($category);

                            $allCategory[$categoryName] =  ['categ' => $category, 'id' => $category->getId()];
                        }

                        if(!$category) $category = $allCategory[$categoryName]['categ'];

                        if(!in_array($animal['scientific_name'], $allAnimals) && isset($entry['main_common_name'])) {
                            var_dump('importing '. $entry['scientific_name']);
                            var_dump('Usage name '. $entry['main_common_name']);
                            $newAnimal = new Animal();
                            $newAnimal->setScientificName(explode(" ", $entry['scientific_name'])[1])
                                ->setName($entry['main_common_name'])
                                ->setMarine($entry['marine_system'])
                                ->setFreshwater($entry['freshwater_system'])
                                ->setTerrestrial($entry['terrestrial_system'])
                                ->setTaxon($entry['taxonid'])
                                ->setScore(0)
                                ->setAggressivity(1)
                                ->setRarety($entry['category'])
                                ->setTrend(false)
                                ->setRemaining(0)
                                ->setDescription("")
                                ->setCategory($category);

                            $cm->persist($newAnimal);
                            $allAnimals[] = $entry['scientific_name'];
                        }
                    }
                }

                curl_close($ch);
            }
        }

        $cm->flush();

    return 0;
    }
}
