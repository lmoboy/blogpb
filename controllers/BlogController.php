<?php
require "models/Blog.php";

class BlogController
{
  public function index()
  {
    $posts = Blog::all();
    require "views/blog/index.view.php";
  }

  public function bake($data)
  {
    try {
      $result = Blog::bake($data);
      if ($result['success']) {
        http_response_code(201);
        echo json_encode($result);
      } else {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to create post']);
      }
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function scavenge($data)
  {

    try {
      $result = Blog::find($data);
      http_response_code(201);
      echo json_encode($result);
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    }

  }

  public function yeet($id)
  {
    try {
      $result = Blog::yeet($id);
      http_response_code(201);
      echo json_encode($result);
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

  public function sprinkle($params)
  {
    $id = $params['id'];
    $data = $params['content'];
    
    try {
      $result = Blog::update($id, $data);
      http_response_code(201);
      echo json_encode($result);
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(['error' => $e->getMessage()]);
    }
  }

}
