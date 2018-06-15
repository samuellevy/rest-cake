<?php
namespace App\Controller\Api;

use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;
use Firebase\JWT\JWT;

class UsersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add', 'token']);
    }

    public function add()
    {
        $this->Crud->on('afterSave', function(Event $event) {
            if ($event->subject->created) {
                $this->set('data', [
                    'id' => $event->subject->entity->id,
                    'token' => JWT::encode(
                        [
                            'sub' => $event->subject->entity->id,
                            'exp' =>  time() + 604800
                        ],
                    Security::salt())
                ]);
                $this->Crud->action()->config('serialize.data', 'data');
            }
        });
        return $this->Crud->execute();
    }
    public function token()
    {
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('CPF ou senha incorreto(s)');
        }

        $this->set([
            'success' => true,
            'data' => [
                'user' => $user['username'],
                'token' => JWT::encode([
                    'sub' => $user['id'],
                    'exp' =>  time() + 604800
                ],
                Security::salt())
            ],
            '_serialize' => ['success', 'data']
        ]);
    }

    public function me(){
        $user = $this->Auth->identify();
        $this->set([
            'success' => true,
            'user' => [
                'username' => $user['username'],
                'name' => $user['name'],
                'loja' => '3aW - Brasil',
                'ranking' => '4',
                'pontuacao' => '510'
            ],
            '_serialize' => ['success', 'user']
        ]);
    }
}