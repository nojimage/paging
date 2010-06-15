<?php
App::import('Core', array('AppModel', 'Model'));
App::import('Behavior', array('Paging.Paging'));

App::import('Fixture', array('PostFixture', 'PostsTagFixture'));

class PagingBehaviorTest extends CakeTestCase
{

    var $fixtures = array('core.post', 'core.posts_tag');

    /**
     *
     * @var Post
     */
    var $Post;

    /**
     *
     * @var PostFixture
     */
    var $PostFixture;

    /**
     *
     * @var PostsTagFixture
     */
    var $PostsTagFixture;

    function startTest() {

        $this->Post =& ClassRegistry::init('Post');
        $this->PostFixture =& ClassRegistry::init('PostFixture');
        $this->PostsTagFixture =& ClassRegistry::init('PostsTagFixture');

    }

    /**
     * endTest method
     *
     * @access public
     * @return void
     */
    function endTest() {
        unset($this->PosPostsTagFixturet);
        unset($this->PostFixture);
        unset($this->Post);
        ClassRegistry::flush();
    }

    function testSetup()
    {
        $paginateOptions = array('published' => array(
                'conditions' => array('Post.published' => true),
                'limit' => 10,
                'order' => array('Post.created' => 'DESC'),
        ),);

        $this->Post->paginateOptions = $paginateOptions;
        $this->Post->Behaviors->attach('Paging');
        $this->assertEqual($this->Post->Behaviors->Paging->settings[$this->Post->name], $paginateOptions);
    }

    function testGetPaginateOptions()
    {
        $paginateOptions = array(
        'published' => array(
                'conditions' => array('Post.published' => true),
                'limit' => 10,
                'order' => array('Post.created' => 'DESC'),
        ),
        'all' => array(
                'limit' => 20,
                'order' => array('Post.created' => 'ASC'),
        ),
        );

        $this->Post->paginateOptions = $paginateOptions;
        $this->Post->paginateType = 'published';
        $this->Post->Behaviors->attach('Paging');

        $this->assertEqual($this->Post->getPaginateOptions(), $paginateOptions['published']);

        $this->assertEqual($this->Post->getPaginateOptions('all'), $paginateOptions['all']);

        $this->assertEqual($this->Post->getPaginateOptions('published'), $paginateOptions['published']);
    }
}