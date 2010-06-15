<?php
App::import('Core', array('AppModel', 'Model'));
App::import('Behavior', array('Paging.VirtualFieldCollection'));

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

    function testFind()
    {
        $virtualFields = array(
            'title_length' => 'LENGTH(Post.title)',
        );

        $collections = array(
            'tag_count' => 'count(PostsTag.tag_id)',
        );

        $this->Post->virtualFields = $virtualFields;
        $this->Post->virtualFieldCollection = $collections;
        $this->Post->Behaviors->attach('VirtualFieldCollection');

        // --
        $query = array('virtualFields' => array('title_length'));
        $result = $this->Post->find('all', $query);

        $this->assertEqual($result[0]['Post']['title_length'], 10);
        $this->assertEqual($this->Post->virtualFields, $virtualFields);

        // --
        /* @var $ds DboSource */
        $ds = $this->Post->getDataSource();
        $query = array(
            'joins' => array(array(
                'type' => 'LEFT',
                'table' => $ds->fullTableName('posts_tags'),
                'alias' => $ds->name('PostsTag'),
                'conditions' => array('Post.id = PostsTag.post_id'),
            )),
            'group' => array('Post.id'),
            'virtualFields' => array('tag_count'));
        $result = $this->Post->find('all', $query);

        $this->assertEqual($result[0]['Post']['title_length'], 10);
        $this->assertEqual($result[0]['Post']['tag_count'], 2);
        $this->assertEqual($this->Post->virtualFields, $virtualFields);

    }
}