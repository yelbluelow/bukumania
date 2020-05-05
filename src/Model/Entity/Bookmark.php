<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Bookmark Entity
 *
 * @property int $id
 * @property int $bookmark_url_id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property int $count
 * @property int $looked_status
 * @property int $favorite_status
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $deleted
 *
 * @property \App\Model\Entity\BookmarkUrl $bookmark_url
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Tag[] $tags
 */
class Bookmark extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'bookmark_url_id' => true,
        'user_id' => true,
        'title' => true,
        'description' => true,
        'count' => true,
        'looked_status' => true,
        'favorite_status' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'bookmark_url' => true,
        'user' => true,
        'tags' => true,
    ];
}
