<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Collection\Collection;
use Cake\ORM\Entity;

/**
 * Class Article
 * @package App\Model\Entity
 */
class Article extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
        'slug' => false,
    ];

    /**
     * @return string
     */
    protected function _getTagString(): string
    {
        if (isset($this->_properties['tag_string'])) {
            return $this->_properties['tag_string'];
        }
        if (empty($this->tags)) {
            return '';
        }
        $tags = new Collection($this->tags);
        $str = $tags->reduce(function ($string, $tag) {
            return $string . $tag->title . ', ';
        }, '');

        return trim($str, ', ');
    }
}
