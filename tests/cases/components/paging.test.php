<?php
App::import('Component', 'Paging.Paging');
App::import('Controller', 'AppController');
App::import('Core', array('AppModel', 'Model'));
App::import('Behavior', array('Paging.Paging'));

class Post extends AppModel {

    var $actsAs = array('Paging.Paging');

    var $paginateType = 'public';

    var $paginateOptions = array(
        'public' => array(
            'conditions' => array('Post.published' => 'Y'),
            'limit' => 1,
            'order' => array('Post.created' => 'DESC'),
    ),
        'all' => array(
            'limit' => 20,
            'order' => array('Post.created' => 'ASC'),
    ),
    );

}

class Author extends AppModel {

    var $paginateType = 'public';

    var $displayField = 'user';

    function getPaginateOptions($type) {
        return array(
            'type'  => $type,
            'limit' => 20,
            'order' => array('Author.id' => 'ASC'),
        );
    }

}

class PostsController extends AppController {
}

class PagingTestCase extends CakeTestCase {

    var $fixtures = array('core.post', 'core.author');

    /**
     * @var PagingComponent
     */
    public $Paging;

    /**
     *
     * @var Post
     */
    public $Post;

    /**
     *
     * @var Author
     */
    public $Author;

    function startTest() {
        $this->Paging = new PagingComponent();
        $this->Post   = ClassRegistry::init('Post');
        $this->Author = ClassRegistry::init('Author');
    }

    function endTest()
    {
        unset($this->Author);
        unset($this->Post);
        unset($this->Paging);
        ClassRegistry::flush();
    }

    function testSetType()
    {
        $controller = new PostsController();
        $controller->params['url'] = Router::parse('/posts/index');
        $this->Paging->startup($controller);
        $this->Paging->setType('all');

        $this->assertEqual($this->Paging->Controller->paginate['Post'], $this->Post->paginateOptions['all']);

        $result = $this->Paging->Controller->paginate();
        $this->assertEqual($result[0]['Post']['id'], 1);
        $this->assertEqual(count($result), 3);

        // --
        $this->Paging->setType('Author', 'list');
        $this->assertEqual($this->Paging->Controller->paginate['Author'], array(
            'type'  => 'list',
            'limit' => 20,
            'order' => array('Author.id' => 'ASC'),
        ));

        $result = $this->Paging->Controller->paginate('Author');
        $this->assertEqual($result[1], 'mariano');
        $this->assertEqual(count($result), 4);
    }

    function testStartup()
    {
        $controller = new PostsController();
        $controller->params['url'] = Router::parse('/posts/index');
        $this->Paging->startup($controller);

        $this->assertEqual($this->Paging->Controller->paginate['Post'], $this->Post->paginateOptions['public']);

        $result = $this->Paging->Controller->paginate();
        $this->assertEqual($result[0]['Post']['id'], 3);
        $this->assertEqual(count($result), 1);
    }


}
