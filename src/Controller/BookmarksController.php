<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Bookmarks Controller
 *
 * @property \App\Model\Table\BookmarksTable $Bookmarks
 *
 */
class BookmarksController extends AppController
{
    /**
     * Before Filter method
     *
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event){
        parent::beforeFilter($event);

        $session = $this->request->getSession();
        $this->login_user_id = $session->read('Auth.User.id');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $data = $this->Bookmarks->findUrlPostJson($this->login_user_id);
        $bookmarks = json_encode($data);
        $this->set(compact('bookmarks'));
    }
    /**
     * Check URL method
     *
     * @return \Cake\Http\Response|null
     */
    public function checkUrl()
    {
        $this->autoRender = false;

        if ($this->request->is("ajax")) {
            $url = $this->request->getData()['url'];

            $resistered_url = $this->Bookmarks->fetchRegisteredUrl($this->login_user_id, true);

            if (in_array($url, $resistered_url, true)) {
                $result = 'count';
            } else {
                $resistered_url_deleted = $this->Bookmarks->fetchRegisteredUrl($this->login_user_id, false);
                if (in_array($url, $resistered_url_deleted, true)) {
                    $result = 'count_deleted';
                } else {
                    $result = 'insert';
                }
            }

            echo json_encode($result);
        }
    }

    /**
     * Add Bookmark method
     *
     * @return \Cake\Http\Response|null
     */
    public function addBookmark()
    {
        $this->autoRender = false;
        $BookmarkUrlsTable = TableRegistry::getTableLocator()->get('BookmarkUrls');
        $BookmarksTable = TableRegistry::getTableLocator()->get('Bookmarks');
        $result = false;

        if ($this->request->is("ajax")) {
            $ajax_data = $this->request->getData();

            // url自体が登録されているか確認する
            $url_exist = $BookmarkUrlsTable->find()
                ->where(['url' => $ajax_data['url']])
                ->count();
            if ($url_exist) {
                // 登録されている場合、既存のidを使う
                $url = $BookmarkUrlsTable->find()
                    ->select(['id'])
                    ->where(['url' => $ajax_data['url']])
                    ->all()
                    ->toArray();
                $bookmark_url_id = $url[0]->id;
            } else {
                // 登録されていない場合、URLをINSERTする
                $bookmark_url = $BookmarkUrlsTable->newEntity();
                $bookmark_url->url = $ajax_data['url'];
                $BookmarkUrlsTable->save($bookmark_url);
                $bookmark_url_id = $bookmark_url->id;
            }

            // Bookmarksに保存
            $bookmark = $BookmarksTable->newEntity();
            $bookmark->bookmark_url_id = $bookmark_url_id;
            $bookmark->user_id = $this->login_user_id;
            $bookmark->title = $ajax_data['title'];
            $bookmark->description = $ajax_data['description'];
            $bookmark->count = 1;
            if ($BookmarksTable->save($bookmark)) {
                $result = true;
            }
        }

        echo json_encode($result);
    }

    /**
     * Count Up Bookmark method
     *
     * @return \Cake\Http\Response|null
     */
    public function countUpBookmark()
    {
        $this->autoRender = false;
        $BookmarksTable = TableRegistry::getTableLocator()->get('Bookmarks');
        $result = false;

        if ($this->request->is("ajax")) {
            $ajax_data = $this->request->getData();

            $bookmark_update = $this->Bookmarks->find()
                ->select(['id', 'count', 'deleted'])
                ->contain(['BookmarkUrls'])
                ->where(['BookmarkUrls.url' => $ajax_data['url'], 'user_id' => $this->login_user_id])
                ->all()
                ->toArray();

            if ($bookmark_update[0]->deleted == 1) {
                $ajax_data['deleted'] = 0;
            }
            $ajax_data['count'] = $bookmark_update[0]->count + 1;

            // Bookmarksに上書き保存
            $ajax_update = $BookmarksTable->patchEntity($BookmarksTable->get($bookmark_update[0]->id), $ajax_data); 
            if ($BookmarksTable->save($ajax_update)) {
                $result = true;
            }
        }

        echo json_encode($result);
    }

    /**
     * Fetch Bookmark List method
     *
     * @return \Cake\Http\Response|null
     */
    public function fetchBookmarkList()
    {
        $this->autoRender = false;

        // ブックマークの一覧を取得する
        $data = $this->Bookmarks->findUrlPostJson($this->login_user_id);
        echo json_encode($data);
    }

    /**
     * Update Bookmark method
     *
     * @return \Cake\Http\Response|null
     */
    public function updateBookmark()
    {
        $this->autoRender = false;
        $BookmarksTable = TableRegistry::getTableLocator()->get('Bookmarks');
        $result = false;

        if ($this->request->is("ajax")) {
            $ajax_data = $this->request->getData();

            // Bookmarksに上書き保存
            $ajax_update = $BookmarksTable->patchEntity($BookmarksTable->get($ajax_data['id']), $ajax_data); 
            if ($BookmarksTable->save($ajax_update)) {
                $result = true;
            }
        }

        echo json_encode($result);
    }
}
