<?php
/**
 * PaginateModelComponent for CakePHP 1.3
 *
 * Copyright 2010, nojimage (http://php-tips.com/)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author     nojimage
 * @package    paging
 * @subpackage paging.controllers.components
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
class PagingComponent extends Object {

    /**
     *
     * @var AppController
     */
    var $Controller;

    /**
     *
     * @param AppContrller $controller
     * @param $type
     */
    function startup(&$controller, $type = null) {

        if (PHP5) {
            $this->Controller = $controller;
        } else {
            $this->Controller =& $controller;
        }

        $this->setType();
    }

    /**
     * set paginate type
     *
     * @param string $modelName
     * @param string $type
     */
    function setType($modelName = null, $type = null){

        if($type === null){
            $type = $modelName;
            $modelName = $this->Controller->modelClass;
        }

        if(empty($modelName) || $modelName == 'App'){
            return false;
        }

        if (empty($this->Controller->{$modelName})) {
            $this->Controller->{$modelName} = ClassRegistry::init($modelName);
        }

        if (empty($this->Controller->{$modelName}) || !in_array('getPaginateOptions', array_keys($this->Controller->{$modelName}->Behaviors->methods()))) {
            return false;
        }

        $this->Controller->paginate[$modelName] = $this->Controller->{$modelName}->getPaginateOptions($type);

        return true;
    }

}