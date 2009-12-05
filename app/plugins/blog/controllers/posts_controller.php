<?php
    class PostsController extends BlogAppController
    {
        var $name = 'Posts';

        function index()
        {
            $this->set('posts', $this->Post->find('all'));
        }
    }
?>