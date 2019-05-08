<?php
include_once("Request.php");
include_once("Router.php");

$app = new Router(new Request);

$app->get('/profile', function($request) {
	return $request->getBody();
});

$app->post('/product/create', function($request) {

	return json_encode($request->getBody());

});