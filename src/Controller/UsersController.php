<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 */
class UsersController extends AppController
{
    /**
     * Initialize method
     *
     * @return \Cake\Http\Response|null
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['logout']);
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null
     */
    public function login()
    {
        if ($this->Auth->user()) {
            $this->Flash->error('既にログインしています。');
            return $this->redirect(['controller' => 'Bookmarks', 'action' => 'index']);
        }
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                $this->Flash->success('ログインに成功しました。');
                return $this->redirect($this->Auth->redirectUrl(['controller' => 'Bookmarks', 'action' => 'index']));
            }
            $this->Flash->error('あなたのユーザー名またはパスワードが不正です。');
        }
    }
    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null
     */
    public function logout()
    {
        $this->Flash->success('ログアウトしました。');
        return $this->redirect($this->Auth->logout());
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null
     */
    public function addUser()
    {
        if ($this->Auth->user()) {
            $this->Flash->error('既にログインしています。');
            return $this->redirect(['controller' => 'Bookmarks', 'action' => 'index']);
        }
        if ($this->request->is('post')) {
            $user = $this->Users->newEntity($this->request->getData());
            if ($this->Users->save($user)) {
                $this->Auth->setUser($user);
                $this->Flash->success('アカウント作成に成功しました。');
                return $this->redirect($this->Auth->redirectUrl(['controller' => 'Bookmarks', 'action' => 'index']));
            } else {
                $this->Flash->error('アカウント名が重複しています。');
            }
            
        }
    }
}
