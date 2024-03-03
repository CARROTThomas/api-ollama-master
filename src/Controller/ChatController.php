<?php

namespace App\Controller;

use App\Entity\ConversationEntry;
use App\Service\Askia;
use App\Service\Chat;
use App\Service\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\isEmpty;

class ChatController extends AbstractController
{
    #[Route('/chat/', name: 'app_chat', methods: ["POST"])]
    public function index(Chat $chatService ,Request $request,Conversation $conversation,SessionInterface $session): Response
    {

        $jsonData = json_decode($request->getContent(), true);

        $userMessage = $jsonData['message'] ?? null;


        if ($userMessage !== null) {
            $conversation->addMessageToConversation('user',$userMessage,$session);
            $response = $chatService->sendMessage($userMessage);
            $conversation->addMessageToConversation($response["role"],$response["content"],$session);
            return $this->json($response,Response::HTTP_OK);

        }

        return $this->json("bad request",Response::HTTP_BAD_REQUEST);
    }

    #[Route('/upload', name: 'app_upload_file')]
    public function uploadFile(Request $request): Response
    {
        // Récupérer le fichier de la requête
        $file = $request->files->get('document');

        // Vérifier si un fichier a été envoyé
        if ($file) {
            // Déplacez le fichier vers le répertoire de votre choix
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $file->move($uploadsDirectory, $file->getClientOriginalName());

            // Faites ce que vous voulez avec le fichier, par exemple, renvoyez une réponse JSON
            return $this->json(['message' => 'Fichier reçu avec succès', 200]);
        } else {
            return $this->json(['error' => 'Aucun fichier n a été envoyé'], 400);
        }
    }

    #[Route('/api/chat/messages', name: 'app_get_messages')]
    public function getMessages(): Response
    {
        $user = $this->getUser();
        if (!$user) {return $this->json("No user connected to get conversation", 200);}

        $conversationHistory = $user->getProfile()->getConversationHistory();

        return $this->json($conversationHistory, 200);
    }



    #[Route('/api/chat/ask/pdf', name: 'app_file_ask', methods: ['POST'])]
    public function chat(Request $request, Askia $service, EntityManagerInterface $manager): Response
    {
        // check user connected
        $user = $this->getUser();
        if (!$user) {return $this->json(["error" => "No user connected to send prompt"], 401);}


        // Get Messages, Check if messages
        $jsonData = json_decode($request->getContent(), true);
        $userMessage = $jsonData['message'] ?? null;
        if (!$userMessage || !is_array($userMessage)) {return $this->json(["error" => "No messages received"], 400);}




        // voir si il y a un historique
        $messageHistory = $user->getProfile()->getConversationHistory();

        //dd($messageHistory);
        //dd($userMessage[0]['content']);

        if (empty($messageHistory)) {
            $response = $service->sendPrompt($userMessage);
        }
        else {
            $history = [];

            foreach ($messageHistory as $message) {
                $history[] = ['role' => 'user', 'content' => $message["question"]];
                $history[] = ['role' => 'assistant', 'content' => $message["response"]];
            }

            $history[] = ['role' => 'user', 'content' => $userMessage[0]['content']];

            $response = $service->sendPrompt($history);
        }



        // add new message to DB
        $messageConversation = new ConversationEntry();
        $messageConversation->setProfile($user->getProfile());
        $messageConversation->setQuestion($userMessage[0]['content']);
        $messageConversation->setResponse($response['content']);


        $manager->persist($messageConversation);
        $manager->flush();


        $messageHistory = $user->getProfile()->getConversationHistory();


        if (empty($messageHistory)) {
            $responseData = [
                'lastMessage' => $messageConversation,
                'history' => $messageHistory,
            ];
            return $this->json($responseData, 200, [], ["groups"=>"display:history"]);
        }
        else {
            $responseData = [
                'lastMessage' => $messageConversation,
                'history' => $messageHistory,
            ];

            return $this->json($responseData, 200, [], ["groups"=>"display:history"]);
        }
    }
}
