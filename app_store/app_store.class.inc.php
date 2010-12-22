<?php

class AppStoreAPI {
  protected $podio;

  public function __construct() {
    $this->podio = PodioBaseAPI::instance();
  }

  public function getTopApps($locale, $limit = 5) {
    if ($response = $this->podio->request('/app_store/top/' . $locale . '/', array('limit' => $limit, 'offset' => 0))) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function getAppsByCategory($locale, $category, $limit = 100, $offset = 0) {
    if ($response = $this->podio->request('/app_store/' . $category . '/' . $locale . '/', array('limit' => $limit, 'offset' => $offset))) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function getTopAppsV2($locale, $type = '', $limit = 5) {
    if ($response = $this->podio->request('/app_store/top/v2/', array('type' => $type, 'language' => $locale, 'limit' => $limit, 'offset' => 0))) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function getAppsByCategoryV2($locale, $category, $sort = 'name', $type = '', $limit = 30, $offset = 0) {
    if ($response = $this->podio->request('/app_store/category/' . $category . '/', array('language' => $locale, 'type' => $type, 'limit' => $limit, 'offset' => $offset, 'sort' => $sort))) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function getAppsByReference($ref_type, $ref_id) {
    if ($response = $this->podio->request('/app_store/' . $ref_type . '/' . $ref_id . '/', array())) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function search($locale, $words, $sort = 'name', $type = '', $limit = 30, $offset = 0) {
    if ($response = $this->podio->request('/app_store/search/', array('texts' => $words, 'language' => $locale, 'sort' => $sort, 'type' => $type, 'limit' => $limit, 'offset' => $offset))) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function getCategories($language = NULL) {
    static $list;
    
    $key = $language ? $language : 'no-language';
    $data = $language ? array('language' => $language) : array();
    
    if (!$list[$key]) {
      if ($response = $this->podio->request('/app_store/category/', $data)) {
        $list[$key] = json_decode($response->getBody(), TRUE);
      }
    }
    return $list[$key];
  }

  public function getOwn() {
    if ($response = $this->podio->request('/app_store/own/', array('limit' => 99))) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function getAppsByAuthor($locale, $author, $limit = 100) {
    if ($response = $this->podio->request('/app_store/author/' . $author . '/' . $locale . '/', array('limit' => $limit, 'offset' => 0))) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function getProfile($user_id) {
    if ($response = $this->podio->request('/app_store/author/' . $user_id . '/profile')) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function install($app_id, $space_id) {
    if ($response = $this->podio->request('/app_store/' . $app_id . '/install', array('space_id' => $space_id), HTTP_Request2::METHOD_POST)) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function installV2($app_id, $space_id, $dependencies) {
    $response = $this->podio->request('/app_store/' . $app_id . '/install/v2', array('space_id' => $space_id, 'dependencies' => $dependencies), HTTP_Request2::METHOD_POST);
    if ($response->getStatus() == '204') {
      return TRUE;
    }
    return json_decode($response->getBody(), TRUE);
  }

  public function get($app_id) {
    if ($response = $this->podio->request('/app_store/' . $app_id)) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function getSharedApp($share_id) {
    if ($response = $this->podio->request('/app_store/' . $share_id . '/v2')) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function shareApp($app_id, $abstract, $description, $language, $category_ids, $file_ids, $features, $children = array()) {
    $request_data = array('ref_id' => $app_id, 'ref_type' => 'app', 'abstract' => $abstract, 'description' => $description, 'language' => $language, 'category_ids' => $category_ids, 'file_ids' => $file_ids, 'children' => $children , 'features' => array());
    if ($features) {
      $request_data['features'] = $features;
    }
    if ($response = $this->podio->request('/app_store/', $request_data, HTTP_Request2::METHOD_POST)) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function sharePack($app_id, $title, $abstract, $description, $language, $category_ids, $file_ids, $features, $children = array()) {
    $request_data = array('ref_id' => $app_id, 'ref_type' => 'space', 'name' => $title, 'abstract' => $abstract, 'description' => $description, 'language' => $language, 'category_ids' => $category_ids, 'file_ids' => $file_ids, 'children' => $children , 'features' => array());
    if ($features) {
      $request_data['features'] = $features;
    }
    if ($response = $this->podio->request('/app_store/', $request_data, HTTP_Request2::METHOD_POST)) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function update($share_id, $abstract, $description, $language, $category_ids, $file_ids) {
    if ($response = $this->podio->request('/app_store/' . $share_id, array('abstract' => $abstract, 'description' => $description, 'language' => $language, 'category_ids' => $category_ids, 'file_ids' => $file_ids), HTTP_Request2::METHOD_PUT)) {
      return json_decode($response->getBody(), TRUE);
    }
  }

  public function unshare($share_id) {
    if ($response = $this->podio->request('/app_store/' . $share_id, array(), HTTP_Request2::METHOD_DELETE)) {
      if ($response->getStatus() == '204') {
        return TRUE;
      }
      return FALSE;
    }
  }

  public function getFeaturedApp($language, $type = '') {
    if ($response = $this->podio->request('/app_store/featured', array('language' => $language, 'type' => $type))) {
      return json_decode($response->getBody(), TRUE);
    }
  }
}

