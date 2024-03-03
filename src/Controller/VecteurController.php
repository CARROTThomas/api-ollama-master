<?php

namespace App\Controller;

use App\Entity\Mot;
use App\Entity\MotDuJour;
use App\Entity\Vecteur;
use App\Repository\MotDuJourRepository;
use App\Repository\MotRepository;
use App\Service\RandomMot;
use App\Service\TakeVecteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VecteurController extends AbstractController
{


    #[Route('/vecteur/cheat/motDuJour', name: 'app_vecteur')]
    public function index(MotDuJourRepository $motDuJourRepository): Response
    {
        $dernierMot = $motDuJourRepository->findOneBy([], ['id' => 'DESC']);
        $mot=$dernierMot->getMot()->getMot();

        return $this->json($mot,Response::HTTP_OK);

    }
    #[Route('/vecteur/{newMot}', name: 'app_vecteur_mot' , methods: ['GET'])]
    public function add($newMot, MotRepository $motRepository,TakeVecteur $takeVecteur,HttpClientInterface $httpClient, EntityManagerInterface $entityManager,MotDuJourRepository $motDuJourRepository, RandomMot $randomMot): Response
    {

        $motFind=$this->motDuJour($motDuJourRepository,$motRepository,$entityManager,$takeVecteur,$httpClient, $randomMot);

        $mot= $motRepository->findOneBy(["mot"=>"$newMot"]);
        if ($mot){

            $similar=$takeVecteur->similar($motFind->getVecteurs()->toArray(),$mot->getVecteurs()->toArray());

            if ($similar==100){
                return $this->json(["message"=>100],Response::HTTP_OK);
            }
            else{
                return $this->json(["message"=>$similar],Response::HTTP_OK);
            }
        }

        $addMot=new Mot();
        $addMot->setMot($newMot);
        $entityManager->persist($addMot);

        $tab=$takeVecteur->httpClient($newMot,$httpClient);
        foreach ($tab as $vecteurs){

            foreach ($vecteurs as $vecteur){

                $newVecteur= new Vecteur();
                $newVecteur->setValue($vecteur);
                $newVecteur->setMot($addMot);
                $entityManager->persist($newVecteur);
            }
        }

        $entityManager->flush();

        $similar=$takeVecteur->similar2($motFind->getVecteurs()->toArray(), $tab["embedding"]);


        return $this->json(["message"=>$similar],Response::HTTP_OK);



    }

    public function motDuJour($motDuJourRepository, $motRepository, $entityManager, $takeVecteur, $httpClient, $randomMot)
    {

        $dernierMot = $motDuJourRepository->findOneBy([], ['id' => 'DESC']);

        if ($dernierMot){

            $createdAt = $dernierMot->getCreatedAt();

            $now = new \DateTime();

            $unJourPlusTard = (clone $now)->modify('+1 day');


            if ($createdAt < $unJourPlusTard) {

                return $dernierMot->getMot();
            }
        }

        $apiMot=$randomMot->random();

        if (!$apiMot){

            $motFrancais = [
                "chat", "chien", "maison", "arbre", "fleur", "soleil", "pluie", "nuage", "montagne", "rivière",
                "voiture", "vélo", "avion", "train", "bateau", "mer", "lac", "forêt", "prairie", "plage",
                "étoile", "lune", "planète", "galaxie", "univers", "terre", "ciel", "nuage", "vent", "tempête",
                "sable", "rocher", "herbe", "papillon", "abeille", "fourmi", "oiseau", "poisson", "requin", "tigre",
                "lion", "éléphant", "girafe", "singe", "serpent", "tortue", "grenouille", "hibou", "loup", "renard",
                "mouton", "vache", "cochon", "poulet", "œuf", "pain", "fromage", "fruits", "légumes", "pomme",
                "orange", "banane", "raisin", "tomate", "carotte", "poisson", "viande", "pâtes", "riz", "pizza",
                "chocolat", "gâteau", "bonbon", "sucre", "sel", "poivre", "eau", "jus", "thé", "café",
                "livre", "journal", "stylo", "crayon", "pinceau", "peinture", "musique", "film", "théâtre", "danse",
                "école", "professeur", "élève", "classe", "cours", "examen", "diplôme", "travail", "bureau", "ordinateur",
                "internet", "téléphone", "maison", "appartement", "chambre", "cuisine", "salle de bain", "salon", "lit",
                "table", "chaise", "fenêtre", "porte", "plafond", "sol", "mur", "lampe", "horloge", "miroir",
                "télévision", "radio", "journal", "magazine", "film", "musique", "art", "science", "mathématiques", "histoire",
                "géographie", "français", "anglais", "espagnol", "allemand", "chinois", "russe", "italien", "portugais", "japonais",
                "arabe", "informatique", "physique", "chimie", "biologie", "médecine", "santé", "maladie", "médecin", "hôpital",
                "pharmacie", "sport", "football", "basketball", "tennis", "natation", "course", "jogging", "yoga", "vélo", "escalade",
                "pique-nique", "fête", "anniversaire", "mariage", "noël", "nouvel an", "pâques", "halloween", "carnaval", "vacances",
                "voyage", "aventure", "découverte", "exploration", "rêve", "cauchemar", "espoir", "amour", "amitié", "famille",
                "étranger", "pays", "ville", "village", "nature", "environnement", "écologie", "énergie", "pollution", "recyclage",
                "technologie", "innovation", "robot", "intelligence artificielle", "espace", "astronaute", "satellite", "lanceur", "exploration spatiale",
                "extraterrestre", "ovni", "histoire", "préhistoire", "antiquité", "moyen âge", "renaissance", "révolution", "guerre", "paix",
                "révolte", "protestation", "politique", "gouvernement", "économie", "travail", "industrie", "agriculture", "commerce", "finance",
                "banque", "argent", "richesse", "pauvreté", "famine", "santé", "maladie", "médecine", "pharmacie", "hôpital",
                "alimentation", "nourriture", "cuisine", "restaurant", "café", "boisson", "vin", "bière", "alcool", "tabac",
                "drogue", "criminalité", "justice", "prison", "police", "crime", "vol", "vandalisme", "violence", "guerre",
                "paix", "armée", "soldat", "arme", "pistolet", "fusil", "bombardement", "paix", "amour", "tolérance",
                "respect", "égalité", "liberté", "justice", "droits de l'homme", "démocratie", "éducation", "apprentissage", "connaissance",
                "curiosité", "imagination", "créativité", "art", "musique", "peinture", "sculpture", "théâtre", "danse",
                "cinéma", "littérature", "poésie", "philosophie", "religion", "spiritualité", "croyance", "athéisme", "agnosticisme",
                "église", "temple", "mosquée", "synagogue", "cathédrale", "prière", "méditation", "rituel", "fête religieuse",
                "mythologie", "légende", "contes", "fables", "fantaisie", "science-fiction", "aventure", "mystère", "horreur",
                "comédie", "drame", "action", "romance", "thriller", "fantasy", "animation", "documentaire", "biographie",
                "histoire", "géographie", "science", "technologie", "art", "musique", "sport", "cuisine", "voyage", "nature",
                "environnement", "société", "culture", "éducation", "religion", "philosophie", "politique", "économie", "science-fiction",
                "fantasy", "romance", "horreur", "mystère", "thriller", "comédie", "drame", "animation", "documentaire", "biographie",
                "histoire", "géographie", "science", "technologie", "art", "musique", "sport", "cuisine", "voyage", "nature",
                "environnement", "société", "culture", "éducation", "religion", "philosophie", "politique", "économie", "science-fiction",
                "fantasy", "romance", "horreur", "mystère", "thriller", "comédie", "drame", "animation", "documentaire", "biographie",
                "histoire", "géographie", "science", "technologie", "art", "musique", "sport", "cuisine", "voyage", "nature",
                "environnement", "société", "culture", "éducation", "religion", "philosophie", "politique", "économie", "science-fiction",
                "fantasy", "romance", "horreur", "mystère", "thriller", "comédie", "drame", "animation", "documentaire", "biographie",
                "histoire", "géographie", "science", "technologie", "art", "musique", "sport", "cuisine", "voyage", "nature",
                "environnement", "société", "culture", "éducation", "religion", "philosophie", "politique", "économie", "science-fiction",
                "fantasy", "romance", "horreur", "mystère", "thriller", "comédie", "drame", "animation", "documentaire", "biographie",
                "histoire", "géographie", "science", "technologie", "art", "musique", "sport", "cuisine", "voyage", "nature",
                "environnement", "société", "culture", "éducation", "religion", "philosophie", "politique", "économie", "science-fiction",
                "fantasy", "romance", "horreur", "mystère", "thriller", "comédie", "drame", "animation", "documentaire", "biographie",
                "histoire", "géographie", "science", "technologie", "art", "musique", "sport", "cuisine", "voyage", "nature",
                "environnement", "société", "culture", "éducation", "religion", "philosophie", "politique", "économie"
            ];

            $randomIndex = array_rand($motFrancais);

            $motAleatoire = $motFrancais[$randomIndex];
        }else{
            $motAleatoire=$apiMot;
        }

        $mot=$motRepository->findOneBy(["mot"=>$motAleatoire]);

        if ($mot){
            $newMotDuJour= new MotDuJour();

            $newMotDuJour->setMot($mot);

            $newMotDuJour->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($newMotDuJour);

            $entityManager->flush();

            return $mot;
        }else{
            $addMot = new Mot();
            $addMot->setMot($motAleatoire);
            $entityManager->persist($addMot);

            $tab=$takeVecteur->httpClient($addMot->getMot(),$httpClient);

            foreach ($tab as $vecteurs){

                foreach ($vecteurs as $vecteur){

                    $newVecteur= new Vecteur();
                    $newVecteur->setValue($vecteur);
                    $newVecteur->setMot($addMot);
                    $entityManager->persist($newVecteur);
                }
            }

            $newMotDuJour= new MotDuJour();

            $newMotDuJour->setMot($addMot);

            $newMotDuJour->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($newMotDuJour);

            $entityManager->flush();

            return $addMot;
        }

        /*
         $all=$motRepository->findAll();

          if (!empty($motFrancais)) {
            $randomIndex = array_rand($motFrancais);

            $motAleatoire = $motFrancais[$randomIndex];

            dd($randomIndex,$motAleatoire);

            $newMotDuJour= new MotDuJour();

            $newMotDuJour->setMot($motAleatoire);

            $newMotDuJour->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($newMotDuJour);

            $entityManager->flush();

            return $motAleatoire;
        } else {
            $addMot = new Mot();
            $addMot->setMot("train");
            $entityManager->persist($addMot);

            $tab=$takeVecteur->httpClient($addMot->getMot(),$httpClient);

            foreach ($tab as $vecteurs){

                foreach ($vecteurs as $vecteur){

                    $newVecteur= new Vecteur();
                    $newVecteur->setValue($vecteur);
                    $newVecteur->setMot($addMot);
                    $entityManager->persist($newVecteur);
                }
            }
            $entityManager->flush();

            return $addMot;
        }
        */
    }

}
