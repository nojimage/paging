<?php
/**
 * virtual field collection Behavior for CakePHP 1.3
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
class VirtualFieldCollectionBehavior extends ModelBehavior {

    var $__cache = array();

    /**
     *
     * @param AppModel $model
     * @param array    $query
     */
    function beforeFind(&$model, $query = array()) {

        if (isset($query['virtualFields'])) {

            $query['virtualFields'] = Set::normalize($query['virtualFields']);

            foreach ($query['virtualFields'] as $key => $sql) {

                if (empty($sql)) {

                    if (isset($model->virtualFieldCollection[$key])) {
                        $query['virtualFields'][$key] = $model->virtualFieldCollection[$key];
                    } else {
                        unset($query['virtualFields'][$key]);
                    }

                }

            }

        }

        if (!empty($query['virtualFields'])) {
            $this->__cache[$model->alias] = $model->virtualFields;
            $model->virtualFields = am($model->virtualFields, $query['virtualFields']);
        }

        return $query;
    }

    /**
     *
     * @param AppModel $model
     * @param mixed    $results
     * @param boolean  $primary
     */
    function afterFind(&$model, $results, $primary) {
        if (!empty($this->__cache[$model->alias])) {
            $model->virtualFields = $this->__cache[$model->alias];
        }
        return true;
    }
}
