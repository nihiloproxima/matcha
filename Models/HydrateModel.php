<?php

class HydrateModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('UserModel');
        $this->loadModel('AddressModel');
    }

    public function get_stats()
    {
        $stmt = $this->db->query("SELECT (SELECT COUNT(*) FROM Users) as total_users,
		(SELECT COUNT(*) FROM Visits) as total_visits,
		(SELECT COUNT(*) FROM Likes) as total_likes,
		(SELECT COUNT(*) FROM Chat_messages) AS total_messages");
        return $stmt->fetchAll()[0];

    }

    public function generate_user($number, $gender = null)
    {
        if ($gender) {
            if ($gender == "Female") {
                for ($i = 0; $i < $number; $i++) {
                    $this->generate_woman();
                }
            } else if ($gender == "Male") {
                for ($i = 0; $i < $number; $i++) {
                    $this->generate_man();
                }
            } else {
                for ($i = 0; $i < $number; $i++) {
                    $this->generate_non_binary();
                }
            }
        } else {
            for ($i = 0; $i < $number; $i++) {
				$rand = array_rand([1, 2, 3]);
                if ($rand == 1) {
					$this->generate_non_binary();
				} else if ($rand == 2) {
					$this->generate_man();
				} else {
					$this->generate_woman();
				}
            }
        }
    }

    public function generate_man()
    {
        $choices = array("Male", "Female", "Non-binary");
        $first_name = $this->random_first_name("Male");
        $last_name = $this->random_last_name();
        $age = random_int(18, 60);
        $username = $first_name[0] . $last_name . rand(0, 9999);
        while ($this->UserModel->user_exists($username)) {
            $username = $first_name[0] . $last_name . rand(0, 9999);
        }
        $email = strtolower($username) . "@ex-nihilo.me";
        $password = hash('whirlpool', $username);
        $gender = "Male";
        $target_gender = $choices[array_rand($choices, 1)];
        $bio = $this->random_bio();
        $pos = $this->random_position();

        $stmt = $this->db->prepare("INSERT INTO `Users`
        (`username`, `email`, `first_name`, `last_name`, `age`, `password`, `gender`, `target_gender`, `bio`, `mail_confirm`, `profile_complete`, `lat`, `lng`, `bot`, `last_connection`)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP)");
        $stmt->execute([$username, $email, $first_name, $last_name, $age, $password, $gender, $target_gender, $bio, '1', '1', $pos['lat'], $pos['long']]);

        $this->generate_informations($username);
    }

    public function generate_non_binary()
    {
        $choices = array("Male", "Female", "Non-binary");
        $first_name = $this->random_first_name();
        $last_name = $this->random_last_name();
        $age = random_int(18, 60);
        $username = $first_name[0] . $last_name . rand(0, 9999);
        while ($this->UserModel->user_exists($username)) {
            $username = $first_name[0] . $last_name . rand(0, 9999);
        }
        $email = strtolower($username) . "@ex-nihilo.me";
        $password = hash('whirlpool', $username);
        $gender = "Non-binary";
        $target_gender = $choices[array_rand($choices, 1)];
        $bio = $this->random_bio();
        $pos = $this->random_position();

        $stmt = $this->db->prepare("INSERT INTO `Users`
        (`username`, `email`, `first_name`, `last_name`, `age`, `password`, `gender`, `target_gender`, `bio`, `mail_confirm`, `profile_complete`, `lat`, `lng`, `bot`, `last_connection`)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP)");
        $stmt->execute([$username, $email, $first_name, $last_name, $age, $password, $gender, $target_gender, $bio, '1', '1', $pos['lat'], $pos['long']]);

        $this->generate_informations($username);
    }

    public function generate_woman()
    {
        $choices = array("Male", "Female", "Non-binary");
        $first_name = $this->random_first_name("Female");
        $last_name = $this->random_last_name();
        $age = random_int(18, 60);
        $username = $first_name[0] . $last_name . rand(0, 9999);
        while ($this->UserModel->user_exists($username)) {
            $username = $first_name[0] . $last_name . rand(0, 9999);
        }
        $email = strtolower($username) . "@ex-nihilo.me";
        $password = hash('whirlpool', $username);
        $gender = "Female";
        $target_gender = $choices[array_rand($choices, 1)];
        $bio = $this->random_bio();
        $pos = $this->random_position();

        $stmt = $this->db->prepare("INSERT INTO `Users`
        (`username`, `email`, `first_name`, `last_name`, `age`, `password`, `gender`, `target_gender`, `bio`, `mail_confirm`, `profile_complete`, `lat`, `lng`, `bot`, `last_connection`)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP)");
        $stmt->execute([$username, $email, $first_name, $last_name, $age, $password, $gender, $target_gender, $bio, '1', '1', $pos['lat'], $pos['long']]);

        $this->generate_informations($username);
    }

    private function generate_informations($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE `username` = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        $this->generate_tags_entries($user['id']);

        // Creating an address
        $datas = $this->UserModel->get_address($user['lat'], $user['lng']);
        $res = $datas['results'][0];
        $components = $res['address_components'];
        if (!isset($components[6]['long_name'])) {
            $components[6]['long_name'] = "69000";
        }
        if (!isset($components[5]['long_name'])) {
            $components[6]['long_name'] = "France";
        }

        $this->AddressModel->new_address($user['id'], $res['formatted_address'], $components[0]['long_name'], $components[1]['long_name'], $components[2]['long_name'], $components[5]['long_name'], $components[6]['long_name'], "user");
        $this->random_picture($user['id'], $user['gender']);
    }

    private function random_bio()
    {
        $bio = array(
            "Entrepreneur. Troublemaker. Social media practitioner. Subtly charming twitter geek. Proud beer enthusiast. Bacon ninja. Web fan. Typical reader.",
            "Extreme bacon fan. Internet geek. Explorer. Award-winning analyst. Pop culture fanatic. Incurable coffee enthusiast. Freelance introvert.",
            "Troublemaker. Music lover. Internet fan. Evil reader. Alcohol fanatic. Coffee practitioner. Bacon trailblazer.",
            "Friend of animals everywhere. Future teen idol. General internet junkie. Evil webaholic. Extreme reader. Gamer.",
            "Incurable entrepreneur. Extreme internet enthusiast. Subtly charming introvert. Friend of animals everywhere.",
            "Twitter practitioner. Analyst. Unapologetic tv trailblazer. Bacon expert. Internet fanatic.",
            "Proud entrepreneur. Wannabe troublemaker. Twitter advocate. Internet maven. Bacon nerd. Hipster-friendly food buff. Amateur communicator.",
            "Introvert. Prone to fits of apathy. Unable to type with boxing gloves on. Proud bacon aficionado. Alcohol buff. Social media junkie.",
            "Freelance reader. Zombie lover. Troublemaker. Travel fan. Friend of animals everywhere. Extreme writer. Certified social media scholar.",
            "Troublemaker. Unapologetic writer. Alcoholaholic. Pop culture junkie. Social media lover. Lifelong music advocate. Travel practitioner. Twitter guru.",
            "Hardcore travel geek. Lifelong problem solver. Internet junkie. Creator. Thinker. Certified explorer.",
            "Amateur beer guru. Certified pop culture practitioner. Evil music advocate. Food enthusiast.",
            "Food aficionado. Travel guru. Web scholar. Proud problem solver. Zombie advocate. Analyst. Incurable tv nerd.",
            "Music fanatic. Evil alcohol scholar. Lifelong communicator. Devoted beer practitioner. Tv lover.",
            "Extreme coffee buff. Social media expert. Passionate zombie fanatic. Hipster-friendly beer ninja.",
            "Total explorer. Student. Alcoholaholic. Incurable coffee buff. Passionate tv enthusiast. Thinker.",
            "Subtly charming travel guru. Food scholar. Evil communicator. Total social media advocate. Zombie expert.",
            "Zombie expert. Freelance food fanatic. Amateur web maven. Bacon aficionado. Passionate explorer. Proud gamer. Typical analyst.",
            "Hardcore introvert. Falls down a lot. Certified gamer. Internet buff. Lifelong student.",
            "Travel scholar. Avid pop culture enthusiast. Falls down a lot. Unapologetic student. Communicator.",
        );

        $k = array_rand($bio, 1);
        return $bio[$k];
    }

    private function random_last_name()
    {
        $last_names = array(
            "Fernandez", "Donaldson", "Fuentes", "Joseph", "Hall", "Leblanc", "Henson", "Wade", "Rivers",
            "Nunez", "Hebert", "Lane", "Blevins", "Gross", "Cox", "Parks", "Vance", "Humphrey", "Trujillo", "Mcdonald",
            "Keith", "Rangel", "Huffman", "Lane", "Mahoney", "Gray", "Gillespie", "Zamora", "Whitehead", "Winters", "Ferguson", "Cooley",
            "Mathis", "Ayers", "Hood", "Alvarado", "Hobbs", "Oneal", "Maddox", "Chase", "Moses", "Henry", "Silva", "Carey", "Strickland", "Pope", "Horton", "Glass",
            "Abbott", "Robles", "Richards", "Melton", "Oliver", "Graham", "Everett", "Macias", "Bird", "Soto", "Haney", "Wilcox", "Miller", "Bauer",
        );
        $k = array_rand($last_names, 1);
        return $last_names[$k];
    }

    private function random_first_name($gender = null)
    {
        $male_first_names = array(
            "Jagger", "Scott", "James", "Walker", "Alden", "Konner", "Aiden", "Mohammed",
            "Brock", "Leon", "Sawyer", "London", "Maximillian", "Jayden", "Bryant", "Braden",
            "Brodie", "Vicente", "Randy", "Van", "Conner", "Kendrick", "Fabian", "Maximo", "Denzel",
            "Asher", "Josue", "Frederick", "Winston", "Vance", "Tyler", "John", "Gael", "Darius", "Rayan",
            "Benjamin", "Kevin", "Darren", "Erik", "Moses", "Khalil", "Rodney",
            "Reuben", "Phillip", "Jackson", "Steve", "Nathaniel", "Silas", "Nikolai", "Jabari",
            "Romain", "Victor", "Léo", "Louis", "Nathan", "Frederic", "Florent", "Fabrice", "Jordan", "Pierre", "Paul", "Jacques",
            "Jésus", "Jonathan", "Robert", "Thomas", "Thibault", "Maurice", "Patrick", "Alphonse", "Kevin", "Simon", "Quentin",
            "Maxime", "Augustin", "Stanley", "Roméo", "Vincent", "Lucas", "Luc", "Jean-Luc", "Jean-Michel", "Michel", "Jean-Abdoul",
            "Jean-Mehmoud", "Jean-Eustache", "Jeremie", "Etienne", "Jean-Etienne", "Mohammed", "Abdul", "Mouloud", "Ben Laden",
            "Benoit", "Jean-Paul", "Guillaume", "Nicolas", "Pierre-Edouard", "Alexis", "Didier", "Xavier", "Cyrille", "Remie", "Alain",
            "Valentin", "Leon", "Justin", "Constant", "Voyelle", "Parfait", "Fet. Nat.", "Yaniss", "Yanick", "Noah", "Brice", "Gilbert",
            "Roger", "Benjamin", "Quentin", "Hugo", "Antoine", "Robin", "Philippe", "Philistin",
        );

        $female_first_names = array(
            "Harper", "Jaslyn", "Zoe", "Keyla", "Patricia", "Aylin", "Danica", "Sophie", "Katelynn", "Angelique", "Kailey",
            "Kasey", "Valerie", "Shayna", "Alyson", "Karly", "Teagan", "Melody", "Abbigail", "Andrea", "Heidy", "Kenzie", "Madelynn",
            "Jacqueline", "Fatima", "Caitlyn", "Celeste", "Alexus", "Erika", "Lorelei", "Genesis", "Skyler", "Valentina", "Mackenzie", "Evelyn", "Wendy",
            "Floyne", "Florence", "Camille", "Bernadette", "Brigitte", "Ninon", "Louise", "Aurelie", "Astrid", "Salomé", "Anne", "Charlotte", "Pauline",
            "Paule", "Marie", "Adele", "Justine", "Clarisse", "Carole", "Coralie", "Alizee", "Ines", "Celia", "Julie", "Julianne", "Laurine", "Adlaée",
            "Hermionne", "Nolwenne", "Bouftou Royal", "Romaine", "Germaine", "Gertrude", "Michelle", "Natacha", "Zoe", "Lucie", "Fanny", "Emie",
            "Amandine", "Lisa", "Emilie", "Tatiana", "Micheline", "Visclum", "Ursula", "Oceane", "MagicPenis", "Pauline", "Anna", "Agathe", "Claire",
            "Lise", "Aline", "Estelle", "Juliette", "Helene", "Margot", "Mahaut", "Olive", "Charlotte", "Anne-Lise", "Marie-Lyne", "Marie-Christine",
            "Roselyne", "Joelle", "Jodelle", "Aude", "Candice", "Cassandre", "Cassandra", "Océane", "Justine", "Adele", "Clarisse", "Juliette", "Hannah",
            "Nathalie", "Louise", "Louisa", "Alexandra", "Pauljul", "Paulette", "Gertrude", "Madeleine", "Madeline",
        );

        if ($gender) {
            if ($gender == "Female") {
                $k = array_rand($female_first_names);
                return $female_first_names[$k];
            } else if ($gender == "Male") {
                $k = array_rand($male_first_names, 1);
                return $male_first_names[$k];
            }
        } else {
            $names = array_merge($male_first_names, $female_first_names);
            $k = array_rand($names, 1);
            return $names[$k];
        }
    }

    private function random_position()
    {
        $pos = array(
            [
                'lat' => 45.69724016,
                'long' => 4.8984876,
            ],
            [
                'lat' => 45.68198773,
                'long' => 4.99075207,
            ],
            [
                'lat' => 45.59918104,
                'long' => 4.809033,
            ],
            [
                'lat' => 45.65471859,
                'long' => 5.0310896,
            ],
            [
                'lat' => 45.77167117,
                'long' => 4.84187311,
            ],
            [
                'lat' => 45.64708354,
                'long' => 4.79954539,
            ],
            [
                'lat' => 45.74242675,
                'long' => 4.760228,
            ],
            [
                'lat' => 45.83619039,
                'long' => 4.97698759,
            ],
            [
                'lat' => 45.85147859,
                'long' => 4.87386801,
            ],
            [
                'lat' => 45.606117,
                'long' => 4.69055891,
            ],
            [
                'lat' => 45.62427365,
                'long' => 4.8219572,
            ],
            [
                'lat' => 45.85769975,
                'long' => 4.90895462,
            ],
            [
                'lat' => 45.59493857,
                'long' => 4.96634038,
            ],
            [
                'lat' => 45.62969812,
                'long' => 4.93481082,
            ],
            [
                'lat' => 45.8287908,
                'long' => 5.06449022,
            ],
            [
                'lat' => 45.78118742,
                'long' => 4.86943459,
            ],
            [
                'lat' => 45.76750129,
                'long' => 5.10219548,
            ],
            [
                'lat' => 45.7730126,
                'long' => 4.7363948,
            ],
            [
                'lat' => 45.70108589,
                'long' => 4.78700414,
            ],
            [
                'lat' => 45.7480996,
                'long' => 4.62243483,
            ],
            [
                'lat' => 45.87764461,
                'long' => 4.91694836,
            ],
            [
                'lat' => 45.76729528,
                'long' => 4.92756214,
            ],
            [
                'lat' => 45.82064611,
                'long' => 4.7381622,
            ],
            [
                'lat' => 45.77003125,
                'long' => 4.88166668,
            ],
            [
                'lat' => 45.84633571,
                'long' => 4.80946927,
            ],
            [
                'lat' => 45.71496522,
                'long' => 4.97571991,
            ],
            [
                'lat' => 45.78424241,
                'long' => 5.09442285,
            ],
            [
                'lat' => 45.69145048,
                'long' => 4.92366366,
            ],
            [
                'lat' => 45.6927198,
                'long' => 4.78240499,
            ],
            [
                'lat' => 45.80645008,
                'long' => 4.73544969,
            ],
            [
                'lat' => 45.81549872,
                'long' => 4.94971632,
            ],
            [
                'lat' => 45.73709215,
                'long' => 4.66828818,
            ],
            [
                'lat' => 45.72253828,
                'long' => 4.72666783,
            ],
            [
                'lat' => 45.69320474,
                'long' => 5.02434801,
            ],
            [
                'lat' => 45.62318286,
                'long' => 4.77355067,
            ],
            [
                'lat' => 45.84374053,
                'long' => 4.8241574,
            ],
            [
                'lat' => 45.74458476,
                'long' => 4.87403804,
            ],
            [
                'lat' => 45.70788176,
                'long' => 4.6666098,
            ],
            [
                'lat' => 45.58753557,
                'long' => 4.92488775,
            ],
            [
                'lat' => 45.68227386,
                'long' => 4.76312098,
            ],
            [
                'lat' => 45.88823796,
                'long' => 4.71246432,
            ],
            [
                'lat' => 45.62102456,
                'long' => 4.71263846,
            ],
            [
                'lat' => 45.82300612,
                'long' => 4.96568014,
            ],
            [
                'lat' => 45.85279595,
                'long' => 4.84998994,
            ],
            [
                'lat' => 45.66806428,
                'long' => 4.9733868,
            ],
            [
                'lat' => 45.73158527,
                'long' => 5.09630265,
            ],
            [
                'lat' => 45.88762957,
                'long' => 4.84473112,
            ],
            [
                'lat' => 45.70366476,
                'long' => 4.61618752,
            ],
            [
                'lat' => 45.66772248,
                'long' => 4.70586922,
            ],
            [
                'lat' => 45.73912558,
                'long' => 5.08897448,
            ],
            [
                'lat' => 45.72839573,
                'long' => 4.81252009,
            ],
            [
                'lat' => 45.73580916,
                'long' => 4.83291826,
            ],
            [
                'lat' => 45.75168796,
                'long' => 4.8222622,
            ],
            [
                'lat' => 45.77172799,
                'long' => 4.82704888,
            ],
            [
                'lat' => 45.76285116,
                'long' => 4.85351883,
            ],
            [
                'lat' => 45.74387892,
                'long' => 4.8315409,
            ],
            [
                'lat' => 45.72775581,
                'long' => 4.81708961,
            ],
            [
                'lat' => 45.77170322,
                'long' => 4.82145938,
            ],
            [
                'lat' => 45.74930528,
                'long' => 4.83536725,
            ],
            [
                'lat' => 45.74171842,
                'long' => 4.85646219,
            ],
            [
                'lat' => 45.76092781,
                'long' => 4.85436811,
            ],
            [
                'lat' => 45.74429257,
                'long' => 4.81645271,
            ],
            [
                'lat' => 45.7547495,
                'long' => 4.85697012,
            ],
            [
                'lat' => 45.74314241,
                'long' => 4.83757774,
            ],
            [
                'lat' => 45.72277999,
                'long' => 4.8326951,
            ],
            [
                'lat' => 45.76155737,
                'long' => 4.82157877,
            ],
            [
                'lat' => 45.76714452,
                'long' => 4.83828227,
            ],
            [
                'lat' => 45.7301334,
                'long' => 4.84565034,
            ],
            [
                'lat' => 45.74941961,
                'long' => 4.84331768,
            ],
            [
                'lat' => 45.76808673,
                'long' => 4.82413716,
            ],
            [
                'lat' => 45.74852369,
                'long' => 4.79929045,
            ],
            [
                'lat' => 45.74744122,
                'long' => 4.81061262,
            ],
            [
                'lat' => 45.74935449,
                'long' => 4.87209529,
            ],
            [
                'lat' => 45.76403721,
                'long' => 4.84183955,
            ],
            [
                'lat' => 45.75183663,
                'long' => 4.81798549,
            ],
            [
                'lat' => 45.76830973,
                'long' => 4.86028879,
            ],
            [
                'lat' => 45.73005948,
                'long' => 4.81128484,
            ],
            [
                'lat' => 45.74313702,
                'long' => 4.81885346,
            ],
            [
                'lat' => 45.76982188,
                'long' => 4.83989496,
            ],
            [
                'lat' => 45.75928038,
                'long' => 4.86254987,
            ],
            [
                'lat' => 45.73382683,
                'long' => 4.86437686,
            ],
            [
                'lat' => 45.74329105,
                'long' => 4.83913593,
            ],
            [
                'lat' => 45.72794967,
                'long' => 4.83509238,
            ],
            [
                'lat' => 45.75173193,
                'long' => 4.86590141,
            ],
            [
                'lat' => 45.74065019,
                'long' => 4.84004031,
            ],
            [
                'lat' => 45.75896083,
                'long' => 4.86223879,
            ],
            [
                'lat' => 45.75357367,
                'long' => 4.86553397,
            ],
            [
                'lat' => 45.76509976,
                'long' => 4.85726013,
            ],
            [
                'lat' => 45.72512144,
                'long' => 4.84423928,
            ],
            [
                'lat' => 45.75423363,
                'long' => 4.84429311,
            ],
            [
                'lat' => 45.75257098,
                'long' => 4.79846815,
            ],
            [
                'lat' => 45.77576957,
                'long' => 4.83061052,
            ],
            [
                'lat' => 45.75825575,
                'long' => 4.82570604,
            ],
            [
                'lat' => 45.77154114,
                'long' => 4.83084971,
            ],
            [
                'lat' => 45.74680685,
                'long' => 4.85138412,
            ],
            [
                'lat' => 45.75939125,
                'long' => 4.83329433,
            ],
            [
                'lat' => 45.74256824,
                'long' => 4.8489121,
            ],
            [
                'lat' => 45.74357508,
                'long' => 4.84695104,
            ],
            [
                'lat' => 45.75277831,
                'long' => 4.8022828,
            ],
            [
                'lat' => 45.74322424,
                'long' => 4.84354667,
            ],
            [
                'lat' => 45.98498086,
                'long' => 4.79388909,
            ],
            [
                'lat' => 46.27743869,
                'long' => 4.48732674,
            ],
            [
                'lat' => 45.7973856,
                'long' => 5.62201186,
            ],
            [
                'lat' => 45.85683242,
                'long' => 3.86368179,
            ],
            [
                'lat' => 46.54066929,
                'long' => 5.04509057,
            ],
            [
                'lat' => 46.45174628,
                'long' => 4.27419101,
            ],
            [
                'lat' => 46.02668495,
                'long' => 4.05920715,
            ],
            [
                'lat' => 45.72342569,
                'long' => 5.00970111,
            ],
            [
                'lat' => 45.22023883,
                'long' => 4.10679663,
            ],
            [
                'lat' => 45.38517752,
                'long' => 5.16257671,
            ],
            [
                'lat' => 46.36621412,
                'long' => 3.97892205,
            ],
            [
                'lat' => 45.06954304,
                'long' => 4.77420819,
            ],
            [
                'lat' => 45.3923361,
                'long' => 5.50517175,
            ],
            [
                'lat' => 46.10968486,
                'long' => 4.91641327,
            ],
            [
                'lat' => 45.4026483,
                'long' => 4.91852022,
            ],
            [
                'lat' => 45.11669783,
                'long' => 5.68315894,
            ],
            [
                'lat' => 46.54248775,
                'long' => 4.25944847,
            ],
            [
                'lat' => 45.9932501,
                'long' => 3.76082539,
            ],
            [
                'lat' => 45.42586875,
                'long' => 4.09516784,
            ],
            [
                'lat' => 45.97796904,
                'long' => 4.27155024,
            ],
            [
                'lat' => 45.54039806,
                'long' => 4.0986727,
            ],
            [
                'lat' => 45.21055871,
                'long' => 5.37612297,
            ],
            [
                'lat' => 45.70847814,
                'long' => 4.51591411,
            ],
            [
                'lat' => 45.20838017,
                'long' => 4.26096101,
            ],
            [
                'lat' => 46.08562564,
                'long' => 5.25035889,
            ],
            [
                'lat' => 45.64226759,
                'long' => 4.42707819,
            ],
            [
                'lat' => 45.43339241,
                'long' => 5.72825594,
            ],
            [
                'lat' => 45.72789748,
                'long' => 5.21546124,
            ],
            [
                'lat' => 45.51230577,
                'long' => 4.66733024,
            ],
            [
                'lat' => 46.0228998,
                'long' => 5.3736818,
            ],
            [
                'lat' => 45.22459517,
                'long' => 5.70126031,
            ],
            [
                'lat' => 45.55319498,
                'long' => 3.96271632,
            ],
            [
                'lat' => 45.66053853,
                'long' => 5.15936909,
            ],
            [
                'lat' => 44.95466125,
                'long' => 4.54328292,
            ],
            [
                'lat' => 45.37802607,
                'long' => 3.91978514,
            ],
            [
                'lat' => 45.45154191,
                'long' => 3.87433483,
            ],
            [
                'lat' => 45.7901519,
                'long' => 4.76309802,
            ],
            [
                'lat' => 46.29459423,
                'long' => 5.34788919,
            ],
            [
                'lat' => 45.51525602,
                'long' => 4.16249563,
            ],
            [
                'lat' => 45.71562866,
                'long' => 6.06384805,
            ],
            [
                'lat' => 46.53979212,
                'long' => 4.98329817,
            ],
            [
                'lat' => 45.92950632,
                'long' => 5.82847941,
            ],
            [
                'lat' => 45.92744273,
                'long' => 5.88066005,
            ],
            [
                'lat' => 45.92470897,
                'long' => 4.69823913,
            ],
            [
                'lat' => 45.74033915,
                'long' => 5.41346885,
            ],
            [
                'lat' => 44.94862378,
                'long' => 4.63546219,
            ],
            [
                'lat' => 46.257667,
                'long' => 5.75302924,
            ],
            [
                'lat' => 44.91218042,
                'long' => 4.39438231,
            ],
            [
                'lat' => 45.89696446,
                'long' => 5.17913492,
            ],
            [
                'lat' => 45.39840864,
                'long' => 5.5332758,
            ],
        );
        $k = array_rand($pos, 1);
        return $pos[$k];
    }

    private function random_tags()
    {
        $stmt = $this->db->prepare("SELECT id FROM Tags");
        $stmt->execute();
        $tags = $stmt->fetchAll();

        $k = array_rand($tags, 5);
        for ($i = 0; $i < 5; $i++) {
            $ret[] = $tags[$k[$i]];
        }
        return $ret;
    }

    private function get_address($latitude, $longitude)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&key=YOURKEY,
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

    private function generate_tags_entries($userid)
    {
        $tags = $this->random_tags();
        foreach ($tags as $tag) {
            $stmt = $this->db->prepare("INSERT INTO `Tag_entries`(`tag_id`, `user_id`) VALUES (?, ?)");
            $stmt->execute([$tag['id'], $userid]);
        }
    }

    private function random_picture($userid, $gender = null)
    {
        $picture_gender = "";
        $genders = array("Male", "Female");

        if (isset($gender) && $gender == "Non-binary") {
            $gender = $genders[array_rand($genders)];
        }

        while ($picture_gender != strtolower($gender)) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://randomuser.me/api/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $data = (json_decode($response, true)); //because of true, it's in an array
            $picture_gender = $data['results'][0]['gender'];
        }

        $picture = $data['results'][0]['picture']['large'];
        $filename = 'assets/uploads/' . uniqid() . ".jpg";
        copy($picture, $filename);

        // Save picture to bdd
        $stmt = $this->db->prepare("INSERT INTO `Pictures`(`user_id`, `path`) VALUES (?, ?)");
        $stmt->execute([$userid, $filename]);
        $stmt = $this->db->prepare(" SELECT id FROM Pictures WHERE `user_id` = ?");
        $stmt->execute([$userid]);
        $res = $stmt->fetch();

        $stmt = $this->db->prepare("UPDATE `Users` SET `profile_pic_id` = ? WHERE `id` = ?");
        $stmt->execute([$res['id'], $userid]);
    }
}
