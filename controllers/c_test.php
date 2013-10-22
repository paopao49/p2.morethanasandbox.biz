<?php
class test_controller extends base_controller {

    public function __construct() {
        parent::__construct();
        echo "users_controller construct called<br><br>";
    } 

    public function my_query() {

        $q = "
            DELETE FROM users
            WHERE email = 'samseaborn@whitehouse.gov'
            ";

        /*
        $q = "
            UPDATE users
            SET email = 'samseaborn@whitehouse.gov'
            WHERE email = 'seaborn@whitehouse.gov'
            ";
        */

        /*
        $q = "
            INSERT INTO users SET
            first_name = 'Sam',
            last_name = 'Seaborn',
            email = 'seaborn@whitehouse.gov'
            ";
        */

        echo DB::instance(DB_NAME)->query($q);

    }

} # end of the class