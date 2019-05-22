<?php

class AddressModel extends Model
{
    public function get_address($key, $value)
    {
        $stmt = $this->db->prepare("SELECT * FROM Address WHERE $key = ?");
        $stmt->execute([$value]);

        return ($stmt->fetch());
    }

    public function delete_address($key, $value)
    {
        $stmt = $this->db->prepare("DELETE FROM Address WHERE $key = ?");
        return $stmt->execute([$value]);
    }

    public function new_address($userid, $formatted_address, $street_number, $route, $locality, $country, $postal_code, $source)
    {
        $stmt = $this->db->prepare("INSERT INTO `Address`
        (`user_id`, `formatted_address`, `street_number`, `route`, `locality`, `country`, `postal_code`, `source` )
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userid, $formatted_address, $street_number, $route, $locality, $country, $postal_code, $source]);
    }

    public function force_new_address($data)
    {
        $res = $data['results'][0];
        $components = $res['address_components'];
        $this->new_address($_SESSION['id'], $res['formatted_address'], $components[0]['long_name'], $components[1]['long_name'], $components[2]['long_name'], $components[5]['long_name'], $components[6]['long_name'], "remote_addr");
    }

    public function user_is_in_city($userid, $city)
    {
        $stmt = $this->db->prepare("SELECT * FROM Address WHERE `user_id` = ? AND `locality` = ?");
        $stmt->execute([$userid, $city]);
        $res = $stmt->fetchAll();
        return (count($res) > 0);
    }

    public function get_distance($pos1, $pos2)
    {
        $req = "https://maps.googleapis.com/maps/api/distancematrix/json?units=kms&origins=" . $pos1['lat'] . "," . $pos1['long'] . "&destinations=" . $pos2['lat'] . "," . $pos2['long'] . "&key=YOURKEY";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $req,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return (json_decode($response, true)); //because of true, it's in an array
    }
}
