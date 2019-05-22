<?php

class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('AddressModel');
        $this->loadModel('PictureModel');
        $this->loadModel('UserModel');
        $this->loadModel('TagModel');
    }

    public function index()
    {
        $data = array();
        if (isset($_SESSION['id'])) {
            $data = array(
                'users' => $this->UserModel->get_popular_users(),
                'tags' => $this->TagModel->get_all_tags(),
            );
            $data['users'] = $this->get_distance($data['users']);
            if ($_SESSION['profile_complete'] == 1) {
                $data['suggested'] = $this->UserModel->suggested_users();
                $data['suggested'] = $this->get_distance($data['suggested']);
                $data['suggested'] = $this->get_score($data['suggested']);
                $sorted = array();
                foreach ($data['suggested'] as $key => $row) {
                    $sorted[$key] = $row['score'];
                }
                array_multisort($sorted, SORT_DESC, $data['suggested']);
            }
        }

        $this->loadView('templates/header');
        $this->loadView('Home/index', $data);
        $this->loadView('templates/footer');
    }

    public function perform()
    {
        $datas = array(
            'age_min' => empty($_GET['age_min']) ? "18" : $_GET['age_min'],
            'age_max' => empty($_GET['age_max']) ? "120" : $_GET['age_max'],
            'popularity_min' => empty($_GET['popularity_min']) ? "0" : $_GET['popularity_min'],
            'popularity_max' => empty($_GET['popularity_max']) ? "99999999999999" : $_GET['popularity_max'],
            'city' => trim(addslashes(htmlspecialchars($_GET['city']))),
        );

        if (!empty($_GET['tags'])) {
            $datas['tags'] = $this->TagModel->get_tags_properly($_GET['tags']);
        }

        $users = $this->UserModel->custom_suggested_users($datas);
        $users = $this->get_distance($users);
        if (isset($_GET['distance']) && is_numeric($_GET['distance']) && $_GET['distance'] > 0) {
            $users = $this->cleanWithDistance($users, $_GET['distance']);
        }
        $users = $this->get_score($users);

        if ($_GET['sort'] == "popularity_asc") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = $row['popularity_score'];
            }
            array_multisort($sorted, SORT_DESC, $users);
        } else if ($_GET['sort'] == "popularity_desc") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = $row['popularity_score'];
            }
            array_multisort($sorted, SORT_ASC, $users);
        } else if ($_GET['sort'] == "age_asc") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = $row['age'];
            }
            array_multisort($sorted, SORT_ASC, $users);
        } else if ($_GET['sort'] == "age_desc") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = $row['age'];
            }
            array_multisort($sorted, SORT_DESC, $users);
        } else if ($_GET['sort'] == "location") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = $row['locality'];
            }
            array_multisort($sorted, SORT_ASC, $users);
        } else if ($_GET['sort'] == "shared_tags_asc") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = count($row['shared_tags']);
            }
            array_multisort($sorted, SORT_ASC, $users);
        } else if ($_GET['sort'] == "shared_tags_desc") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = count($row['shared_tags']);
            }
            array_multisort($sorted, SORT_DESC, $users);
        } else if ($_GET['sort'] == "score") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = $row['score'];
            }
            array_multisort($sorted, SORT_DESC, $users);
        } else if ($_GET['sort'] == "distance_desc") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = $row['distance'];
            }
            array_multisort($sorted, SORT_DESC, $users);
        } else if ($_GET['sort'] == "distance_asc") {
            $sorted = array();
            foreach ($users as $key => $row) {
                $sorted[$key] = $row['distance'];
            }
            array_multisort($sorted, SORT_ASC, $users);
        }
        if (isset($datas['tags']) && count($datas['tags']) > 0) {
            $users = $this->cleanWithTags($users, $datas['tags']);
        }

        echo json_encode($users);

    }

    private function get_distance($users)
    {
        $pos1 = array(
            'lat' => $_SESSION['lat'],
            'long' => $_SESSION['lng'],
        );
        for ($i = 0; $i < count($users); $i++) {
            $pos2 = array(
                'lat' => $users[$i]['lat'],
                'long' => $users[$i]['lng'],
            );
            $users[$i]['distance'] = $this->distance($pos1['lat'], $pos1['long'], $pos2['lat'], $pos2['long'], "K");
        }
        return $users;
    }

    private function get_score($users)
    {
        $goodusers = array();
        for ($i = 0; $i < count($users); $i++) {
            $distance = substr($users[$i]['distance'], 0, -3);
            $score = 20;
            if (count($users[$i]['shared_tags']) > 5) {
                $score += 25;
            } else {
                $score += count($users[$i]['shared_tags']) * 5;
            }

            if ($distance >= 0 && $distance < 5) {
                $score += 40;
            } else if ($distance >= 5 && $distance < 15) {
                $score += 30;
            } else if ($distance >= 15 && $distance < 50) {
                $score += 20;
            } else if ($distance >= 50 && $distance < 100) {
                $score += 10;
            } else {
                $score += 5;
            }

            $picturesCount = $this->PictureModel->get_pictures_count($users[$i]['id']);
            $score += ($picturesCount['pictures_count'] * 5);
            $users[$i]['score'] = $score;
            $goodusers[] = $users[$i];
        }
        return $goodusers;
    }

    public function distance($lat1, $long1, $lat2, $long2, $unit)
    {
        $theta = $long1 - $long2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        // Convert unit and return distance
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return round($miles * 1.609344, 2) . ' km';
        } elseif ($unit == "M") {
            return round($miles * 1609.344, 2) . ' meters';
        } else {
            return round($miles, 2) . ' miles';
        }
    }

    private function cleanWithDistance($users, $distance)
    {
        $goodusers = array();
        for ($i = 0; $i < count($users); $i++) {
            if (substr($users[$i]['distance'], 0, -3) <= $distance) {
                $goodusers[] = $users[$i];
            }
        }
        return $goodusers;
    }

    private function cleanWithTags($users, $tags)
    {
        $goodusers = array();
        for ($i = 0; $i < count($users); $i++) {
            for ($j = 0; $j < count($tags); $j++) {
                if (in_array($tags[$j], $users[$i]['tags'])) {
                    if ($j == count($tags) - 1) {
                        $goodusers[] = $users[$i];
                    }
                } else {
                    break;
                }
            }
        }
        return $goodusers;
    }
}