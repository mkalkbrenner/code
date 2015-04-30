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
        <meta http-equiv="Refresh" content="59">
        <title></title>
    </head>
    <body>
        <?php

        class AjaxTest {

            public function Connector() {
                $log_info = parse_ini_file('../info/conf.ini', TRUE);
                $connection = mysqli_connect($log_info['connection']['host'], $log_info['connection']['user'], $log_info['connection']['password'], $log_info['connection']['db_name'], $log_info['connection']['port']);
                if (!$connection) {
                    exit('No connection to database. Error - ' . mysqli_connect_errno() . ' : %s\n' . mysqli_connect_error());
                } else {
                    if ($stmt = mysqli_prepare($connection, 'SELECT `id_user`, `name`, `surname` FROM `webcall-db`.users WHERE `id_user` in (select `id_status` FROM `webcall-db`.status WHERE `online`=1 and `offline`=0)')) {
                        mysqli_stmt_execute($stmt);
                        $output = NULL;
                        mysqli_stmt_bind_result($stmt, $id, $name, $surname);
                        while (mysqli_stmt_fetch($stmt)) {
                           $output[$id] = $name . ' ' . $surname;
                        }
                        mysqli_stmt_close($stmt);
                        mysqli_close($connection);
                        $fieldname = NULL;
                        $form = NULL;
                        $fieldname = array_keys($output);
                        $form = '<table>';
                        for($i = 0; $i < count($fieldname); $i++){
                            $form .= '<form id="call_' . $fieldname[$i] . '" method="post" action="call.php" target="_blank">
                                      <tr>
                                      <td style="visibility:hidden"><input type="text" name="id" size="1" value="' . $fieldname[$i] . '" readonly></td>
                                      <td>' . $output[$fieldname[$i]] . '</td>
                                      <td><img src="icons/online.png" width="20" height="20"></td>
                                      <td><button form="call_' . $fieldname[$i] . '"><img src="icons/call.png" width="15" height="15"></button></td>
                                      </tr>
                                      </form>';
                        }
                        $form .= '</table>';
                        echo $form;
                    } else {
                        exit('Prepare problems!');
                    }
                }
            }

        }

        $object = new AjaxTest();
        $object->Connector();
        ?>
    </body>
</html>
