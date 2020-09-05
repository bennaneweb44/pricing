<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\ArticleConcurrent;
use App\Entity\Concurrent;
use App\Entity\Etat;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixture extends Fixture
{

    private $articles = [];
    private $concurrents = [];
    private $etats = [];

    /**
     * Article d'exemple : Jeux vidéo Fifa 20
     * 
     * Sinon, les informations pourrait être saisies via un formulaire
     * ou récupérée à partir de systèmes externes (REST)
     */
    private $articleExemple;
    private $articlesConcurrents = [];

    public function __construct()
    {
        $this->articles = [
            [
                'ref' => 'JVD-105057',
                'title' => 'Fifa 20 édition Ultimate'
            ],
            [
                'ref' => 'LVR-090477',
                'title' => 'La Bible de Jérusalem'
            ],
            [
                'ref' => 'PCP-511052',
                'title' => 'ASUS Vivobook R509JA'
            ],
            [
                'ref' => 'PZL-009895',
                'title' => 'Puzzle de sol'
            ],
            [
                'ref' => 'JCT-119740',
                'title' => 'Grimaud - Rami Junior'
            ]
        ];

        $this->concurrents = [
            [
                'nom' => 'Abc jeux',
                'ville' => 'Paris',
                'tel' => '0151748690'
            ],
            [
                'nom' => 'Games-planete',
                'ville' => 'Rennes',
                'tel' => '0299547768'
            ],
            [
                'nom' => 'Media-games',
                'ville' => 'Nantes',
                'tel' => '0240965514'
            ],
            [
                'nom' => 'Micro-jeux',
                'ville' => 'Toulouse',
                'tel' => '0421557167'
            ],
            [
                'nom' => 'Top-Jeux-video',
                'ville' => 'Marseille',
                'tel' => '0425109357'
            ],
            [
                'nom' => 'Tous-les-jeux',
                'ville' => 'Lille',
                'tel' => '0321511370'
            ],
            [
                'nom' => 'Diffusion-133',
                'ville' => 'Strasbourg',
                'tel' => '0364770890'
            ],
            [
                'nom' => 'France-video',
                'ville' => 'Paris',
                'tel' => '0124789907'
            ]
        ];

        $this->etats = [
            'Etat moyen',
            'Bon état',
            'Très bon état',
            'Comme neuf',
            'Neuf'
        ];

        $this->articlesConcurrents = [
            'Abc jeux' => [
                'etat' => 'Etat moyen',
                'prix' => 14,10
            ],
            'Games-planete' => [
                'etat' => 'Etat moyen',
                'prix' => 16,20
            ],
            'Media-games' => [
                'etat' => 'Bon état',
                'prix' => 18
            ],
            'Tous-les-jeux' => [
                'etat' => 'Bon état',
                'prix' => 24,44
            ],
            'Micro-jeux' => [
                'etat' => 'Très bon état',
                'prix' => 20
            ],
            'Top-Jeux-video' => [
                'etat' => 'Très bon état',
                'prix' => 21,50
            ],            
            'Diffusion-133' => [
                'etat' => 'Comme neuf',
                'prix' => 29
            ],
            'France-video' => [
                'etat' => 'Neuf',
                'prix' => 30,99
            ],
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach($this->articles as $element) {

            $article = new Article();
            $article->setReference($element['ref']);
            $article->setLibelle($element['title']);
            
            $manager->persist($article);     
            $manager->flush();  

            if ($element['ref'] === 'JVD-105057') {
                $this->articleExemple = clone $article;                                                
                $this->setReference('Article', $article);
            }
        }

        // Nous devons absolument avoir un article d'exemple
        if ($this->articleExemple instanceof Article == FALSE) {
            exit('Erreur : L\'article d\'exemple n\'est pas valorisé !');
        }

        foreach($this->etats as $state) {            

            $etatExemple = new Etat();
            $etatExemple->setIntitule($state);

            $manager->persist($etatExemple);
            $manager->flush(); 
            $this->setReference('Etat', $etatExemple);   

            foreach($this->concurrents as $element) {

                if ($this->articlesConcurrents[$element['nom']]['etat'] == $state) {

                    $concurrent = new Concurrent();
                    $concurrent->setNom($element['nom']);
                    $concurrent->setVille($element['ville']);
                    $concurrent->setTel($element['tel']);

                    $manager->persist($concurrent);
                    $manager->flush(); 
                    $this->setReference('Concurrent', $concurrent);

                    // Prix d'exemple
                    $prixExemple = $this->articlesConcurrents[$concurrent->getNom()]['prix'];

                    // ArticleConcurrent
                    $articleConcurrent = new ArticleConcurrent();
                    $articleConcurrent->setArticle($this->getReference('Article'));
                    $articleConcurrent->setConcurrent($this->getReference('Concurrent'));
                    $articleConcurrent->setPrix($prixExemple);
                    $articleConcurrent->setEtat($this->getReference('Etat'));

                    $manager->persist($articleConcurrent); 
                    $manager->flush();
                }          
            }
        }
    }
}
