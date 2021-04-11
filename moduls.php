<?php
function Writefilm($js_actors, $link)
{

    mysqli_query($link, "INSERT INTO `films`(`id`, `film`, `genre`, `actors`) VALUES (0,'Feel it','melodram','$js_actors')");
};


//SELECT

function selectActors($link)
{
    $actors_db = mysqli_query($link, "SELECT * FROM `actors_base`");
    $actors_full_array = [];
    while ($actor = mysqli_fetch_assoc($actors_db)) {
        $actors_full_array[] = $actor;
    }
    //Creating actors array

    return $actors_full_array;
};
function selectFilms($link)
{
    $films_mysqli = mysqli_query($link, "SELECT * FROM `films`");
    //Selecting all array in DB

    $films_arr = [];
    while ($film = mysqli_fetch_assoc($films_mysqli)) {
        $films_arr[] = $film;
    }
    for ($i = 0; $i < count($films_arr); $i++) {
        $films_arr[$i] = (array)$films_arr[$i];
        $films_arr[$i]['actors'] = (array)json_decode($films_arr[$i]['actors']);
    }
    return $films_arr;
}

//GET

function getFilms($link, $id)
{
    $films_arr = selectFilms($link);



    if (isset($id)) {
        for ($i = 0; $i < count($films_arr); $i++) {
            if ($films_arr[$i]['id'] == $id) {
                $found  = true;
                print_r(json_encode($films_arr[$i]));
            }
        }
        if (!$found) {
            http_response_code(404);
            $res = [
                "status" => false,
                "massage" => 'not found'
            ];
            print_r(json_encode($res));
        }
    } else {
        $json_films_arr = json_encode($films_arr);
    }
    print_r($json_films_arr);
}

function getActors($link, $id)
{
    $actors_db = selectActors($link);

    $actors_db_json = json_encode($actors_db);
    $found  = false;
    if (isset($id)) {
        for ($i = 0; $i < count($actors_db); $i++) {
            if ($actors_db[$i]['id'] == $id) {
                $found  = true;
                print_r(json_encode($actors_db[$i]));
            }
        }
        if (!$found) {
            http_response_code(404);
            $res = [
                "status" => false,
                "massage" => 'not found'
            ];
            print_r(json_encode($res));
        }
    } else {
        print_r($actors_db_json);
    }
}

//ADD

function addFilm($link, $data)
{
    $actors = $data['actors'];
    $film = $data['film'];
    $genre = $data['genre'];
    mysqli_query($link, "INSERT INTO `films`(`id`, `film`, `genre`, `actors`) VALUES (0,'$film','$genre','$actors')");
    http_response_code(201);
    $res = [
        "status" => true,
        "film_id" => mysqli_insert_id($link)
    ];
    print_r(json_encode($res));
}
function addActor($link, $data)
{
    $actor = $data['actor'];
    print_r($data['actor']);
    mysqli_query($link, "INSERT INTO `actors_base`(`id`, `actor`) VALUES (0,'$actor')");
    http_response_code(201);
    $res = [
        "status" => true,
        "actor_id" => mysqli_insert_id($link)
    ];
    print_r(json_encode($res));
}

//DELETE

function deleteActor($link, $id)
{
    $actors_db = selectActors($link);

    $found = false;
    if (isset($id)) {
        for ($i = 0; $i < count($actors_db); $i++) {
            if ($actors_db[$i]['id'] == $id) {
                $found  = true;
                mysqli_query($link, "DELETE FROM `actors_base` WHERE `actors_base`.`id` = $id");
                http_response_code(200);
                $res = [
                    "status" => true,
                    "actor_id" => $id
                ];
                print_r(json_encode($res));
            }
        }
        if (!$found) {
            http_response_code(404);
            $res = [
                "status" => false,
                "massage" => 'not found'
            ];
            print_r(json_encode($res));
        }
    } else {
        http_response_code(404);
        $res = [
            "status" => false,
            "massage" => 'not found'
        ];
        print_r(json_encode($res));
    }
}
function deleteFilm($link, $id)
{
    $films_arr = selectFilms($link);

    $found = false;
    if (isset($id)) {
        for ($i = 0; $i < count($films_arr); $i++) {
            if ($films_arr[$i]['id'] == $id) {
                $found  = true;
                mysqli_query($link, "DELETE FROM `films` WHERE `films`.`id` = $id");
                http_response_code(200);
                $res = [
                    "status" => true,
                    "actor_id" => $id
                ];
                print_r(json_encode($res));
            }
        }
        if (!$found) {
            http_response_code(404);
            $res = [
                "status" => false,
                "massage" => 'not found'
            ];
            print_r(json_encode($res));
        }
    } else {
        http_response_code(404);
        $res = [
            "status" => false,
            "massage" => 'not found'
        ];
        print_r(json_encode($res));
    }
}

//UPDATE

function updateActor($link, $id, $data)
{
    $actor = $data['actor'];

    mysqli_query($link, "UPDATE `actors_base` SET `actor` = '$actor' WHERE `actors_base`.`id` = $id;");
    http_response_code(200);
    $res = [
        "status" => true,
        "actor_id" => $id
    ];
    print_r(json_encode($res));
}
function updateFilm($link, $id, $data)
{
    $actors = $data['actors'];
    $film = $data['film'];
    $genre = $data['genre'];
    mysqli_query($link, "UPDATE `films` SET `film` = '$film', `genre` = '$genre', `actors` = '$actors' WHERE `films`.`id` = $id");
    http_response_code(200);
    $res = [
        "status" => true,
        "film_id" => $id
    ];
    print_r(json_encode($res));
}


//FILTERS

function findbyGenre($link, $data)
{
    $genre = $data['genre'];

    $genreFilms = [];
    $fims_arr = selectFilms($link);
    for ($i = 0; $i < count($fims_arr); $i++) {
        if ($fims_arr[$i]['genre'] == $genre) {
            $genreFilms[] = $fims_arr[$i];
        }
    }
    print_r(json_encode($genreFilms));
}
function findbyActor($link, $data)
{
    $actor = $data['actor'];

    $actorFilms = [];
    $fims_arr = selectFilms($link);
    for ($i = 0; $i < count($fims_arr); $i++) {
        if (count($fims_arr[$i]['actors']) > 0) {
            for ($j = 0; $j < count($fims_arr[$i]['actors']); $j++)
                if ($fims_arr[$i]['actors'][$j] == $actor) {
                    $actorFilms[] = $fims_arr[$i];
                }
        }
    }
    print_r(json_encode($actorFilms));
}
?>