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

        class LogoutService {

            public function Connector() {
                $res = FALSE;
                if (isset($_COOKIE['email'])) {
                    $log_info = parse_ini_file('../info/conf.ini', TRUE);
                    $connection = mysqli_connect($log_info['connection']['host'], $log_info['connection']['user'], $log_info['connection']['password'], $log_info['connection']['db_name'], $log_info['connection']['port']);
                    if (!$connection) {
                        exit('No connection to database. Error - ' . mysqli_connect_errno() . ' : %s\n' . mysqli_connect_error());
                    } else {
                        if ($stmt = mysqli_prepare($connection, 'UPDATE `webcall-db`.status SET `online` = 0, `offline` = 1 WHERE id_status in (SELECT `id_user` FROM `webcall-db`.users WHERE `email`="' . $_COOKIE['email'] . '")')) {
                            mysqli_stmt_execute($stmt);
                            $res = TRUE;
                            mysqli_stmt_close($stmt);
                            mysqli_close($connection);
                        } else {
                            exit('Database error!');
                        }
                    }
                }
                $this->End($res);
            }
            
            public function End($res) {
                if ($res == TRUE){
                    header("Location:http://localhost");
                } else {
                     header("Location:http://th-wildau.de");
                }
            }
        }
        $object = new LogoutService();
        $object->Connector();    
        ?>
    </body>
</html>
