/* 
 * Copyright (C) 2015 Maxim Lebedev
 *
 * This file is part of "myWebRTC"
 *
 * "myWebRTC" is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * "myWebRTC" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses/>
 */

<?php
setcookie('email', $_POST['email'], 0);
?>
<!DOCTYPE html>
<!--
Service Data for myWebRTC-System.
Project at the University of Applied Sciences Wildau (Germany)
Web: http://th-wildau.de/
Author: Maxim Lebedev
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php

        class LoginService {

            public function GetValues() {
                $inputValues = $_POST;
                if (!empty($inputValues)) {
                    $this->GetLogin($inputValues);
                } else {
                    exit('Enter your email and password!');
                }
            }

            public function GetLogin($inputArray) {
                if (!empty($inputArray)) {
                    $sqlRequest = 'SELECT url from users WHERE ';
                    foreach ($inputArray as $key => $value) {
                        if (!empty($value)) {
                            $sqlRequest .= $key . '=' . '"' . $value . '"';
                            if (next($inputArray)) {
                                $sqlRequest .= ' AND ';
                            }
                        } else {
                            exit('No values!');
                        }
                    }
                } else {
                    exit('Enter your email and password!');
                }
                $this->Connect($sqlRequest);
            }

            public function Connect($query) {
                if (!empty($query)) {
                    $log_info = parse_ini_file('../info/conf.ini', TRUE);
                    $connection = mysqli_connect($log_info['connection']['host'], $log_info['connection']['user'], $log_info['connection']['password'], $log_info['connection']['db_name'], $log_info['connection']['port']);
                    if (!$connection) {
                        exit('No connection to database. Error - ' . mysqli_connect_errno() . ' : %s\n' . mysqli_connect_error());
                    } else {
                        if ($stmt = mysqli_prepare($connection, $query)) {
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_bind_result($stmt, $res);
                            mysqli_stmt_fetch($stmt);
                            mysqli_stmt_close($stmt);
                            mysqli_close($connection);
                        }
                        $this->OpenProfil($res);
                    }
                } else {
                    exit('Enter your email and password!');
                }
            }

            public function OpenProfil($result) {
                if (!empty($result)) {
                        $log_info = parse_ini_file('../info/conf.ini', TRUE);
                        $connection = mysqli_connect($log_info['connection']['host'], $log_info['connection']['user'], $log_info['connection']['password'], $log_info['connection']['db_name'], $log_info['connection']['port']);
                        if (!$connection) {
                            exit('No connection to database. Error - ' . mysqli_connect_errno() . ' : %s\n' . mysqli_connect_error());
                        } else {
                            if ($stmt = mysqli_prepare($connection, 'UPDATE `webcall-db`.status SET `online` = 1, `offline` = 0 WHERE id_status in (SELECT `id_user` FROM `webcall-db`.users WHERE `url`="' . $result . '")')) {
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                                mysqli_close($connection);
                            } else {
                                exit('Database error!');
                            }
                        }
                        header("Location:" . $result);
                } else {
                    exit('No user was registried');
                }
            }

        }

        $object = new LoginService();
        $object->GetValues();
        ?>
    </body>
</html>
