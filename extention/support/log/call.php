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

        class CallService {

            public function GetValues() {
                $id_user = $_POST['id'];
                if (!empty($id_user)) {
                    $this->SQLRequest($id_user);
                } else {
                    exit('This user is offline. Refresh the Page');
                }
            }

            public function SQLRequest($id_user) {
                if (!empty($id_user)) {
                    $sqlRequest = 'SELECT `url` from users WHERE `id_user` = "' . $id_user . '"';
                    $this->Connector($sqlRequest);
                } else {
                    exit('No user selected. Refresh the Page');
                }
            }

            public function Connector($sqlRequest) {
                if (!empty($sqlRequest)) {
                    $log_info = parse_ini_file('../info/conf.ini', TRUE);
                    $connection = mysqli_connect($log_info['connection']['host'], $log_info['connection']['user'], $log_info['connection']['password'], $log_info['connection']['db_name'], $log_info['connection']['port']);
                    if (!$connection) {
                        exit('No connection to database. Error - ' . mysqli_connect_errno() . ' : %s\n' . mysqli_connect_error());
                    } else {
                        if ($stmt = mysqli_prepare($connection, $sqlRequest)) {
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

            public function OpenProfil($res) {
                if (!empty($res)) {
                    header("Location:" . $res);
                } else {
                    exit('No user was registried');
                }
            }

        }

        $object = new CallService();
        $object->GetValues();
        ?>
    </body>
</html>
