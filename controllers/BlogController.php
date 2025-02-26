<?php
require "models/Blog.php";

class BlogController
{
    public function index() {
        $posts = Blog::all();
        require "views/blog/index.view.php";
      }

}
