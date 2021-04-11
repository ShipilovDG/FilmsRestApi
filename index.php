<?php


header('Content-type: text/json');
require 'connect.php';
require 'moduls.php';

$params = explode('/', $_GET['q']);
// разбиение на параметры остаток url
$type = $params[0];
$id = $params[1];

//GET
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'GET') {
    if ($type ==  'films') {
        if (isset($id)) {
            getFilms($link, $id);
        } else {
            getFilms($link, $id = null);
        }
    }
    if ($type ==  'actors') {
        if (isset($id)) {
            getActors($link, $id);
        } else {
            getActors($link, $id = null);
        }
    }
}
//POST
if (($method == 'POST') && ($type == 'films')) {
    addFilm($link, $_POST);
}
if (($method == 'POST') && ($type == 'actors')) {
    addActor($link, $_POST);
}
if (($method == 'POST') && ($type == 'genre')) {
    findbyGenre($link, $_POST);
}
if (($method == 'POST') && ($type == 'actor')) {
    findbyActor($link, $_POST);
}

//DELETE
if (($method == 'DELETE') && ($type == 'actors')) {
    deleteActor($link, $id);
}
if (($method == 'DELETE') && ($type == 'films')) {
    deleteFilm($link, $id);
}

//PATCH
if (($method == 'PATCH') && ($type == 'actors')) {
    if (isset($id)) {
        $data = file_get_contents('php://input');
        $data = (array)json_decode($data);


        updateActor($link, $id, $data);
    } else {
        http_response_code(404);
        $res = [
            'status' => false,
            'massage' => 'not Found'
        ];
        print_r($res);
    }
}
if (($method == 'PATCH') && ($type == 'films')) {
    if (isset($id)) {
        $data = file_get_contents('php://input');

        $data = (array)json_decode($data);
        updateFilm($link, $id, $data);
    } else {
        http_response_code(404);
        $res = [
            'status' => false,
            'massage' => 'not Found'
        ];
        print_r($res);
    }
}
?> 