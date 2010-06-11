<?php
/**
 * PaginateModel Behavior for CakePHP 1.3
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

    var $defaults = array(
        'options' => array(),
        'virtualFieldsCollection' => array(),
    );

    /**
     *
     * @param AppModel $model
     * @param array    $config
     */
    function setup(&$model, $config = array()) {

        $config = am($this->defaults, $config);

        if (!empty($model->options)) {
            $config['options'] = am($config['options'], $model->options);
        }

        if (!empty($model->virtualFieldsCollection)) {
            $config['virtualFieldsCollection'] = am($config['virtualFieldsCollection'], $model->virtualFieldsCollection);
        }

        $this->settings[$model->alias] = $config;

    }

    /**
     *
     * @param AppModel $model
     * @param array    $query
     */
    function beforeFind(&$model, $query = array()) {

        if (!empty($query['extra']['type']) && !array_key_exists($query['extra']['type'], $model->_findMethods)) {

            if (isset($query['virtualFields'])) {
                $virtualFields = Set::normalize($query['virtualFields']);
                unset($query['virtualFields']);

                foreach ($virtualFields as $key => $sql) {
                    if (empty($sql)) {
                        if (isset($this->settings[$model->alias]['virtualFieldsCollection'][$key])) {
                            $virtualFields[$key] = $this->settings[$model->alias]['virtualFieldsCollection'][$key];
                        } else {
                            unset($virtualFields[$key]);
                        }
                    }
                }

                if (!empty($virtualFields)){

                    $this->settings[$model->alias]['tempVirtualFields'] == $model->virtualFields;
                    $model->virtualFields = array_merge($model->virtualFields, $virtualFields);

                }
            }
        }

        return true;
    }

    /**
     *
     * @param $model
     * @param $results
     * @param $primary
     */
    function afterFind(&$model, &$results, $primary) {

        if(isset($this->settings[$model->alias]['tempVirtualFields'])) {
            $model->virtualFields = $this->settings[$model->alias]['tempVirtualFields'];
            unset($this->settings[$model->alias]['tempVirtualFields']);
        }
        return true;
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

        if (empty($type) || !isset($this->settings[$model->alias]['options'][$type])) {
            return array();
        }

        if (empty($this->settings[$model->alias]['options'][$type])) {
            $this->settings[$model->alias]['options'][$type]['type'] = $type;
        }

        return $this->settings[$model->alias]['options'][$type];
    }
}
