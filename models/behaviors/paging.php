<?php
/**
 * Paging Behavior for CakePHP 1.3
 *
 * Copyright 2010, nojimage (http://php-tips.com/)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author     nojimage
 * @package    paging
 * @subpackage paging.models.behaviors
 * @copyright  2010 nojimage (http://php-tips.com/)
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 * =====
 * Original Code is:
 *
 *   hiromi2424
 *     http://d.hatena.ne.jp/hiromi2424/20100609/1276076490
 *
 *   Thank you!
 */
class PagingBehavior extends ModelBehavior {

    var $defaults = array();

    /**
     *
     * @param AppModel $model
     * @param array    $config
     */
    function setup(&$model, $config = array()) {

        $config = am($this->defaults, $config);

        if (!empty($model->paginateOptions)) {
            $config = am($config, $model->paginateOptions);
        }

        $this->settings[$model->alias] = $config;

    }

    /**
     *
     * @param AppModel $model
     * @param string   $type
     * @return array
     */
    function getPaginateOptions(&$model, $type = null){

        if (empty($type)) {
            $type = $model->paginateType;
        }

        if (empty($type) || !isset($this->settings[$model->alias][$type])) {
            return array();
        }

        if (empty($this->settings[$model->alias][$type])) {
            $this->settings[$model->alias][$type]['type'] = $type;
        }

        return $this->settings[$model->alias][$type];
    }
}
