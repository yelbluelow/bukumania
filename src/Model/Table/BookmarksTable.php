<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

/**
 * Bookmarks Model
 *
 * @property \App\Model\Table\BookmarkUrlsTable&\Cake\ORM\Association\BelongsTo $BookmarkUrls
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\TagsTable&\Cake\ORM\Association\BelongsToMany $Tags
 *
 * @method \App\Model\Entity\Bookmark get($primaryKey, $options = [])
 * @method \App\Model\Entity\Bookmark newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Bookmark[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bookmark|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bookmark saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bookmark patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Bookmark[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bookmark findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BookmarksTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('bookmarks');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('BookmarkUrls', [
            'foreignKey' => 'bookmark_url_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsToMany('Tags', [
            'foreignKey' => 'bookmark_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'bookmarks_tags',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 50)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description', null, 'create');

        $validator
            ->integer('count')
            ->requirePresence('count', 'create')
            ->notEmptyString('count');

        $validator
            ->integer('looked_status')
            ->notEmptyString('looked_status');

        $validator
            ->integer('favorite_status')
            ->notEmptyString('favorite_status');

        $validator
            ->integer('deleted')
            ->notEmptyString('deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['bookmark_url_id'], 'BookmarkUrls'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    /**
     * ログインしているユーザーが投稿した情報を一覧にしてJSON形式で出力する。
     *
     * @param integer $user_id ログインしているユーザーのdbのid
     * @return JSON
     */
    public function findUrlPostJson($user_id)
    {
        $data = $this->find()
            ->contain(['BookmarkUrls'])
            ->where(['user_id' => $user_id, 'deleted' => 0])
            ->order(['count' => 'DESC', 'Bookmarks.modified' => 'DESC'])
            ->all()
            ->toArray();

        return $data;
    }

    /**
     * 投稿されたurlの一覧を取得する
     *
     * @return array
     */
    public function fetchRegisteredUrl($user_id, $deleted_flg)
    {
        $deleted = $deleted_flg ? 0 : 1;
        $data = $this->find()
            ->contain(['BookmarkUrls'])
            ->select('BookmarkUrls.url')
            ->where(['user_id' => $user_id, 'deleted' => $deleted])
            ->hydrate(false)
            ->toList();
        $url_array = Hash::extract($data, '{n}.bookmark_url.url');

        return $url_array;
    }
}
