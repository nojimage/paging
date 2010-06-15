# Paging Plugin for CakePHP 1.3+

**This plugin not testing. please test me. :)**

## Installation

movo to APP/plugins/

    git clone http://github.com/nojimage/paging.git

## Usage

### in Model:

    class Post extends AppModel {
    
        var $name = 'Post';
        
        var $actsAs = array('Paging.Paging');
        
        var $paginateType = 'public';
        
        var $paginateOptions = array(
            'public' => array(
                'fields' => array('*'),
                'conditions' => array(
                    'Post.public' => 1,
                ),
                'order' => 'Post.created DESC',
            ),
        );
    }

### in Controller:

    class PostsController extends AppController {
        
        var $name = 'Posts';
    
        var $components = array('Paging.Paging');
    
        function index() {
            $this->set('posts', $this->paginate());
        }
    }

## License

Licensed under The MIT License.
Redistributions of files must retain the above copyright notice.


Copyright 2010 nojimage, http://php-tips.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.


## Thanks

This plugin is inspired by this entry:

[paginateオプションをモデルに移行する（改善・修正版） - 24時間CakePHP](http://d.hatena.ne.jp/hiromi2424/20100609/1276076490 "paginateオプションをモデルに移行する（改善・修正版） - 24時間CakePHP")
   
Thank you, hiromi2424!