# CodeIgniter 4

## Data User

    Array
    (
        [0] => Array
            (
                [user_id] => 1
                [user_name] => Tomi
                [user_address] => Jakarta
            )

        [1] => Array
            (
                [user_id] => 2
                [user_name] => Roni
                [user_address] => Medan
            )

        [2] => Array
            (
                [user_id] => 3
                [user_name] => Imam
                [user_address] => Aceh
            )
    )

## Data Hobi

    Array
    (
        [0] => Array
            (
                [hobi_id] => 1
                [hobi_name] => Masak
                [user_id] => 1
            )

        [1] => Array
            (
                [hobi_id] => 2
                [hobi_name] => Olahraga
                [user_id] => 1
            )

        [2] => Array
            (
                [hobi_id] => 3
                [hobi_name] => Joging
                [user_id] => 1
            )

        [3] => Array
            (
                [hobi_id] => 4
                [hobi_name] => Mancing
                [user_id] => 2
            )

        [4] => Array
            (
                [hobi_id] => 5
                [hobi_name] => Bersepeda
                [user_id] => 2
            )

        [5] => Array
            (
                [hobi_id] => 6
                [hobi_name] => Olahraga
                [user_id] => 2
            )

        [6] => Array
            (
                [hobi_id] => 7
                [hobi_name] => Joging
                [user_id] => 3
            )

        [7] => Array
            (
                [hobi_id] => 8
                [hobi_name] => Membaca
                [user_id] => 3
            )

        [8] => Array
            (
                [hobi_id] => 9
                [hobi_name] => Mabok
                [user_id] => 3
            )

    )

## Data Tim

    Array
    (
        [0] => Array
            (
                [tim_id] => 1
                [tim_name] => Jeni
                [user_id] => 1
            )

        [1] => Array
            (
                [tim_id] => 2
                [tim_name] => Michael
                [user_id] => 1
            )

        [2] => Array
            (
                [tim_id] => 3
                [tim_name] => Dodi
                [user_id] => 2
            )

        [3] => Array
            (
                [tim_id] => 4
                [tim_name] => Bambang
                [user_id] => 3
            )

        [4] => Array
            (
                [tim_id] => 5
                [tim_name] => Ivan
                [user_id] => 3
            )

        [5] => Array
            (
                [tim_id] => 6
                [tim_name] => Lala
                [user_id] => 3
            )

    )

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 7.4 or higher is required, with the following extensions installed:

-   [intl](http://php.net/manual/en/intl.requirements.php)
-   [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

-   json (enabled by default - don't turn it off)
-   [mbstring](http://php.net/manual/en/mbstring.installation.php)
-   [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
-   xml (enabled by default - don't turn it off)
