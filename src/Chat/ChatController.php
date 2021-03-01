<?php


namespace App\Chat;


use App\User\UserService;
use Cycle\ORM\ORMInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Pusher\Pusher;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\ViewRenderer;

class ChatController
{
    private ViewRenderer $viewRenderer;
    private UserService $userService;
    private ChatService $chatService;
    private DataResponseFactoryInterface $responseFactory;

    public function __construct(DataResponseFactoryInterface $responseFactory, ViewRenderer $viewRenderer, UserService $userService, ChatService $chatService)
    {
        $this->responseFactory = $responseFactory;
        $this->viewRenderer = $viewRenderer->withController($this);
        $this->userService = $userService;
        $this->chatService = $chatService;
    }

    public function index(ORMInterface $orm, CurrentUser $currentUser): ResponseInterface
    {
        return $this->viewRenderer->render('index', ['isGuest' => $currentUser->isGuest()]);
    }

    public function getMessages(Request $request, ChatMessageRepository $repository): ResponseInterface
    {
        $params = $request->getQueryParams();
        $afterId = $params['after_id'] ?? 0;

        $messages = $repository->findAll(['ch.id' => ['>' => $afterId]]);

        return $this->responseFactory->createResponse($messages);
    }

    public function sendMessage(Request $request, Pusher $pusher): ResponseInterface
    {
        $messageText = $request->getParsedBody()['message'];

        try {

            $message = new ChatMessage($messageText);
            $this->chatService->saveMessage($this->userService->getUser(), $message);

            $data = [
                'id' => $message->getId(),
                'text' => $messageText,
                'name' => $message->getUser()->getLogin(),
                'timestamp' => $message->getCreatedAt(),
            ];

            $pusher->trigger('chat', 'message', $data, [
                'info' => 'subscription_count',
            ]);

            $responseData = ['status' => 'success'];
        } catch (\Throwable $e) {
            $responseData = [
                'status' => 'failed',
                'error' => $e->getMessage(),
            ];
        }

        return $this->responseFactory->createResponse($responseData);

    }

}
