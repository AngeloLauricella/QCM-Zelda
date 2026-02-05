<?php

namespace App\DataFixtures;

use App\Entity\Zone;
use App\Entity\Question;
use App\Entity\Trophy;
use App\Entity\Gallery;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ZeldaOcarinaFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer des zones basées sur Zelda Ocarina of Time
        $zones = $this->createZones($manager);
        
        // Créer des trophées
        $trophies = $this->createTrophies($manager);
        
        // Créer des questions pour chaque zone
        $this->createQuestions($manager, $zones);
        
        // Créer des galeries d'images
        $this->createGalleries($manager, $trophies);
        
        // Créer des utilisateurs de test
        $this->createTestUsers($manager, $trophies);
        
        $manager->flush();
    }

    private function createZones(ObjectManager $manager): array
    {
        $zones = [
            [
                'name' => 'La Forêt Perdue',
                'description' => 'Plongez dans la Forêt Perdue où vivent les Kokiri. Découvrez les secrets bien gardés de ce monde enchanteur.',
                'displayOrder' => 1,
                'minPointsToUnlock' => 0,
            ],
            [
                'name' => 'Château d\'Hyrule',
                'description' => 'Explorez le majestueux Château d\'Hyrule, où la Princesse Zelda règne. Résolvez les énigmes royales.',
                'displayOrder' => 2,
                'minPointsToUnlock' => 100,
            ],
            [
                'name' => 'Désert Géant',
                'description' => 'Traversez les terres arides du Désert Géant. Affrontez des ennemis redoutables et découvrez des temples anciens.',
                'displayOrder' => 3,
                'minPointsToUnlock' => 300,
            ],
            [
                'name' => 'Caverne Glace',
                'description' => 'Explorez les terres gelées de la Caverne Glace. Affrontez le froid extrême et les énigmes glaciales.',
                'displayOrder' => 4,
                'minPointsToUnlock' => 500,
            ],
            [
                'name' => 'Temple de l\'Eau',
                'description' => 'Découvrez les secrets du Temple de l\'Eau, où les énigmes jouent avec les niveaux d\'eau.',
                'displayOrder' => 5,
                'minPointsToUnlock' => 750,
            ],
            [
                'name' => 'Montagne Désolée',
                'description' => 'Escaladez la Montagne Désolée et affrontez les épreuves du sommet. Ganondorf attend quelque part...',
                'displayOrder' => 6,
                'minPointsToUnlock' => 1000,
            ],
        ];

        $zoneObjects = [];
        foreach ($zones as $zoneData) {
            $zone = new Zone();
            $zone->setName($zoneData['name']);
            $zone->setDescription($zoneData['description']);
            $zone->setDisplayOrder($zoneData['displayOrder']);
            $zone->setMinPointsToUnlock($zoneData['minPointsToUnlock']);
            $zone->setIsActive(true);
            $manager->persist($zone);
            $zoneObjects[] = $zone;
        }

        return $zoneObjects;
    }

    private function createTrophies(ObjectManager $manager): array
    {
        $trophies = [
            [
                'name' => 'Épée de Kokiri',
                'description' => 'Une épée légère mais tranchante, utilisée par les Kokiri.',
                'type' => 'passive',
                'heartBonus' => 0,
                'pointsMultiplier' => 1.1,
                'icon' => 'sword-kokiri.png',
                'unlockCondition' => 'Complétez la Forêt Perdue',
                'price' => 50,
            ],
            [
                'name' => 'Master Sword',
                'description' => 'L\'Épée Maîtresse, l\'arme légendaire capable de sceller le Mal.',
                'type' => 'active',
                'heartBonus' => 1,
                'pointsMultiplier' => 1.5,
                'icon' => 'master-sword.png',
                'unlockCondition' => 'Gagnez 500 points',
                'price' => 200,
            ],
            [
                'name' => 'Bouclier Hylia',
                'description' => 'Un bouclier sacré avec le symbole du royaume d\'Hyrule.',
                'type' => 'passive',
                'heartBonus' => 1,
                'pointsMultiplier' => 1.0,
                'icon' => 'shield-hylian.png',
                'unlockCondition' => 'Protégez-vous 10 fois',
                'price' => 100,
            ],
            [
                'name' => 'Ocarina du Temps',
                'description' => 'Un instrument magique capable de manipuler le temps.',
                'type' => 'active',
                'heartBonus' => 2,
                'pointsMultiplier' => 2.0,
                'icon' => 'ocarina.png',
                'unlockCondition' => 'Maîtrisez 5 mélodies',
                'price' => 500,
            ],
            [
                'name' => 'Tuque Gerudo',
                'description' => 'La tenue traditionnelle des Gerudos du désert.',
                'type' => 'passive',
                'heartBonus' => 0,
                'pointsMultiplier' => 1.2,
                'icon' => 'gerudo-outfit.png',
                'unlockCondition' => 'Traversez le Désert Géant',
                'price' => 75,
            ],
            [
                'name' => 'Triforce',
                'description' => 'La Triforce sacrée, source ultime de pouvoir.',
                'type' => 'active',
                'heartBonus' => 3,
                'pointsMultiplier' => 3.0,
                'icon' => 'triforce.png',
                'unlockCondition' => 'Battez Ganondorf',
                'price' => 1000,
            ],
            [
                'name' => 'Cape Bleue',
                'description' => 'Une cape magique qui offre une protection supplémentaire.',
                'type' => 'passive',
                'heartBonus' => 1,
                'pointsMultiplier' => 1.1,
                'icon' => 'blue-cape.png',
                'unlockCondition' => 'Complétez 3 zones',
                'price' => 80,
            ],
            [
                'name' => 'Bagues Cristal',
                'description' => 'Des bagues magiques qui amplifient les pouvoirs spéciaux.',
                'type' => 'passive',
                'heartBonus' => 0,
                'pointsMultiplier' => 1.25,
                'icon' => 'crystal-rings.png',
                'unlockCondition' => 'Collectez 100 rubis',
                'price' => 120,
            ],
        ];

        $trophyObjects = [];
        foreach ($trophies as $trophyData) {
            $trophy = new Trophy();
            $trophy->setName($trophyData['name']);
            $trophy->setDescription($trophyData['description']);
            $trophy->setType($trophyData['type']);
            $trophy->setHeartBonus($trophyData['heartBonus']);
            $trophy->setPointsMultiplier($trophyData['pointsMultiplier']);
            $trophy->setIcon($trophyData['icon']);
            $trophy->setUnlockCondition($trophyData['unlockCondition']);
            $trophy->setIsVisible(true);
            $trophy->setDisplayOrder(count($trophyObjects) + 1);
            $manager->persist($trophy);
            $trophyObjects[] = $trophy;
        }

        return $trophyObjects;
    }

    private function createQuestions(ObjectManager $manager, array $zones): void
    {
        $questionsData = [
            // Forêt Perdue (Zone 1)
            [
                'zoneIndex' => 0,
                'questions' => [
                    [
                        'title' => 'Comment s\'appelle l\'ami de Link dans la Forêt Perdue ?',
                        'description' => 'Complétez la phrase: "___ est le meilleur ami de Link dans la Forêt Perdue"',
                        'optionA' => 'Navi',
                        'optionB' => 'Saria',
                        'optionC' => 'Darunia',
                        'optionD' => 'Impa',
                        'correctAnswer' => 'A',
                        'category' => 'Personnages',
                        'pointsValue' => 10,
                        'rewardHearts' => 0,
                        'rewardPoints' => 10,
                        'penaltyHearts' => 0,
                        'penaltyPoints' => 0,
                    ],
                    [
                        'title' => 'Quel artefact Link doit-il obtenir dans la Forêt Perdue ?',
                        'description' => 'Que collecte Link pour la première fois dans la Forêt Perdue?',
                        'optionA' => 'Le Pendentif d\'Émeraude',
                        'optionB' => 'La Clé Dorée',
                        'optionC' => 'L\'Épée de Kokiri',
                        'optionD' => 'L\'Ocarina du Temps',
                        'correctAnswer' => 'C',
                        'category' => 'Objets',
                        'pointsValue' => 15,
                        'rewardHearts' => 1,
                        'rewardPoints' => 15,
                        'penaltyHearts' => 0,
                        'penaltyPoints' => -5,
                    ],
                    [
                        'title' => 'Quel arbre ancien habite la Forêt Perdue ?',
                        'description' => 'Quel est le gardien naturel de la Forêt Perdue?',
                        'optionA' => 'Grand Oak',
                        'optionB' => 'Arbre Mojo',
                        'optionC' => 'Géant Sylvain',
                        'optionD' => 'Deku Père',
                        'correctAnswer' => 'D',
                        'category' => 'Boss',
                        'pointsValue' => 20,
                        'rewardHearts' => 1,
                        'rewardPoints' => 20,
                        'penaltyHearts' => 1,
                        'penaltyPoints' => -10,
                    ],
                ],
            ],
            // Château d\'Hyrule (Zone 2)
            [
                'zoneIndex' => 1,
                'questions' => [
                    [
                        'title' => 'Qui règne sur le Château d\'Hyrule ?',
                        'description' => 'Qui est la princesse du Château d\'Hyrule?',
                        'optionA' => 'Zelda',
                        'optionB' => 'Impa',
                        'optionC' => 'Malon',
                        'optionD' => 'Ruto',
                        'correctAnswer' => 'A',
                        'category' => 'Personnages',
                        'pointsValue' => 10,
                        'rewardHearts' => 0,
                        'rewardPoints' => 10,
                        'penaltyHearts' => 0,
                        'penaltyPoints' => 0,
                    ],
                    [
                        'title' => 'Quel est le nom complet de Link ?',
                        'description' => 'Connaissez-vous le vrai nom complet du héros?',
                        'optionA' => 'Link Hylien',
                        'optionB' => 'Link de Kokiri',
                        'optionC' => 'Link du Destin',
                        'optionD' => 'Link Temporel',
                        'correctAnswer' => 'A',
                        'category' => 'Lore',
                        'pointsValue' => 15,
                        'rewardHearts' => 0,
                        'rewardPoints' => 15,
                        'penaltyHearts' => 0,
                        'penaltyPoints' => -5,
                    ],
                    [
                        'title' => 'Combien de Pendants d\'Cristal Link doit-il récupérer ?',
                        'description' => 'Quel est le nombre total de Pendants à collecter?',
                        'optionA' => '2',
                        'optionB' => '3',
                        'optionC' => '4',
                        'optionD' => '5',
                        'correctAnswer' => 'B',
                        'category' => 'Quêtes',
                        'pointsValue' => 20,
                        'rewardHearts' => 1,
                        'rewardPoints' => 20,
                        'penaltyHearts' => 1,
                        'penaltyPoints' => -10,
                    ],
                ],
            ],
            // Désert Géant (Zone 3)
            [
                'zoneIndex' => 2,
                'questions' => [
                    [
                        'title' => 'Qui règne sur le Désert Géant ?',
                        'description' => 'Qui est le roi des Gerudos?',
                        'optionA' => 'Ganondorf',
                        'optionB' => 'Nabooru',
                        'optionC' => 'Koume et Kotake',
                        'optionD' => 'Vaati',
                        'correctAnswer' => 'A',
                        'category' => 'Boss',
                        'pointsValue' => 10,
                        'rewardHearts' => 0,
                        'rewardPoints' => 10,
                        'penaltyHearts' => 0,
                        'penaltyPoints' => 0,
                    ],
                    [
                        'title' => 'Quel temple Link doit-il conquérir dans le désert ?',
                        'description' => 'Quel est le nom du temple du désert?',
                        'optionA' => 'Temple de Pierre',
                        'optionB' => 'Temple du Désert',
                        'optionC' => 'Temple Sacré',
                        'optionD' => 'Temple du Vent',
                        'correctAnswer' => 'B',
                        'category' => 'Donjons',
                        'pointsValue' => 15,
                        'rewardHearts' => 1,
                        'rewardPoints' => 15,
                        'penaltyHearts' => 0,
                        'penaltyPoints' => -5,
                    ],
                ],
            ],
            // Caverne Glace (Zone 4)
            [
                'zoneIndex' => 3,
                'questions' => [
                    [
                        'title' => 'Quelle est la température extrême de la Caverne Glace ?',
                        'description' => 'À quel froid glacial Link doit-il résister?',
                        'optionA' => '-50°C',
                        'optionB' => '-100°C',
                        'optionC' => '-200°C',
                        'optionD' => '-273°C',
                        'correctAnswer' => 'B',
                        'category' => 'Environnement',
                        'pointsValue' => 10,
                        'rewardHearts' => 0,
                        'rewardPoints' => 10,
                        'penaltyHearts' => 0,
                        'penaltyPoints' => 0,
                    ],
                ],
            ],
            // Temple de l\'Eau (Zone 5)
            [
                'zoneIndex' => 4,
                'questions' => [
                    [
                        'title' => 'Quel est le mécanisme principal du Temple de l\'Eau ?',
                        'description' => 'Comment fonctionne le Temple de l\'Eau?',
                        'optionA' => 'Les niveaux d\'eau',
                        'optionB' => 'Les portails magiques',
                        'optionC' => 'Les miroirs lumineux',
                        'optionD' => 'Les cristaux de temps',
                        'correctAnswer' => 'A',
                        'category' => 'Donjons',
                        'pointsValue' => 15,
                        'rewardHearts' => 1,
                        'rewardPoints' => 15,
                        'penaltyHearts' => 0,
                        'penaltyPoints' => -5,
                    ],
                ],
            ],
            // Montagne Désolée (Zone 6)
            [
                'zoneIndex' => 5,
                'questions' => [
                    [
                        'title' => 'Qui est le grand antagoniste du jeu ?',
                        'description' => 'Quel est le dernier ennemi que Link doit affronter?',
                        'optionA' => 'Phantom Ganon',
                        'optionB' => 'Ganondorf',
                        'optionC' => 'Ganon',
                        'optionD' => 'Tous les deux',
                        'correctAnswer' => 'D',
                        'category' => 'Boss Final',
                        'pointsValue' => 30,
                        'rewardHearts' => 2,
                        'rewardPoints' => 50,
                        'penaltyHearts' => 2,
                        'penaltyPoints' => -20,
                    ],
                ],
            ],
        ];

        foreach ($questionsData as $zoneQuestions) {
            $zone = $zones[$zoneQuestions['zoneIndex']];
            foreach ($zoneQuestions['questions'] as $index => $qData) {
                $question = new Question();
                $question->setZone($zone);
                $question->setTitle($qData['title']);
                $question->setDescription($qData['description']);
                $question->setOptionA($qData['optionA']);
                $question->setOptionB($qData['optionB']);
                $question->setOptionC($qData['optionC']);
                $question->setOptionD($qData['optionD']);
                $question->setCorrectAnswer($qData['correctAnswer']);
                $question->setCategory($qData['category']);
                $question->setPointsValue($qData['pointsValue']);
                $question->setRewardHearts($qData['rewardHearts']);
                $question->setRewardPoints($qData['rewardPoints']);
                $question->setPenaltyHearts($qData['penaltyHearts']);
                $question->setPenaltyPoints($qData['penaltyPoints']);
                $question->setDisplayOrder($index + 1);
                $question->setIsActive(true);
                $question->setStep(1);
                $manager->persist($question);
            }
        }
    }

    private function createGalleries(ObjectManager $manager, array $trophies): void
    {
        // Créer les galeries associées aux trophées
        $galleryItems = [
            [
                'trophy' => $trophies[1], // Master Sword
                'imagePath' => 'master-sword.jpg',
                'title' => 'Master Sword - L\'Épée Maîtresse',
                'price' => 150,
            ],
            [
                'trophy' => $trophies[3], // Ocarina
                'imagePath' => 'ocarina-time.jpg',
                'title' => 'Ocarina du Temps - L\'Instrument Magique',
                'price' => 200,
            ],
            [
                'trophy' => $trophies[5], // Triforce
                'imagePath' => 'triforce-sacred.jpg',
                'title' => 'La Triforce Sacrée',
                'price' => 500,
            ],
            [
                'trophy' => $trophies[2], // Shield
                'imagePath' => 'shield-hylian.jpg',
                'title' => 'Bouclier Hylia - Protégez Hyrule',
                'price' => 100,
            ],
        ];

        // Créer un utilisateur spécial pour les galeries publiques
        $adminUser = new User();
        $adminUser->setEmail('gallery@zelda.local');
        $adminUser->setUsername('GalerieZelda');
        $adminUser->setPassword($this->hasher->hashPassword($adminUser, 'ZeldaGallery123!'));
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setIsVerified(true);
        $adminUser->setProfileImage('link-hero.jpg');
        $manager->persist($adminUser);

        foreach ($galleryItems as $item) {
            $gallery = new Gallery();
            $gallery->setUser($adminUser);
            $gallery->setTitle($item['title']);
            $gallery->setImagePath($item['imagePath']);
            $gallery->setPrice($item['price']);
            $gallery->setCreatedAt(new DateTimeImmutable());
            $manager->persist($gallery);
        }
    }

    private function createTestUsers(ObjectManager $manager, array $trophies): void
    {
        $testUsers = [
            [
                'username' => 'Link',
                'email' => 'link@hyrule.local',
                'profileImage' => 'link-hero.jpg',
                'password' => 'Link123!',
            ],
            [
                'username' => 'Zelda',
                'email' => 'zelda@hyrule.local',
                'profileImage' => 'zelda-princess.jpg',
                'password' => 'Zelda123!',
            ],
            [
                'username' => 'Darunia',
                'email' => 'darunia@hyrule.local',
                'profileImage' => 'darunia-goron.jpg',
                'password' => 'Darunia123!',
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setUsername($userData['username']);
            $user->setPassword($this->hasher->hashPassword($user, $userData['password']));
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(true);
            $user->setProfileImage($userData['profileImage']);
            $manager->persist($user);

            // Ajouter quelques galeries à chaque utilisateur
            for ($i = 1; $i <= 2; $i++) {
                $gallery = new Gallery();
                $gallery->setUser($user);
                $gallery->setTitle($userData['username'] . ' - Collection ' . $i);
                $gallery->setImagePath('collection-' . $userData['username'] . '-' . $i . '.jpg');
                $gallery->setPrice(50 + ($i * 25));
                $gallery->setCreatedAt(new DateTimeImmutable('-' . $i . ' days'));
                $manager->persist($gallery);
            }
        }
    }
}
