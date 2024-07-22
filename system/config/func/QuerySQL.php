<?php

/**
 * DorewSite Software
 * Author: Dorew
 * Email: khanh65me1@gmail.com or awginao@protonmail.com
 * Website: https://dorew.gq
 * License: license.txt
 * Copyright: (C) 2022 Dorew All Rights Reserved.
 * This file is part of the source code.
 */

defined('_DOREW') or die('Access denied');

class QuerySQL
{
    public function __construct()
    {
        global $db, $mydb;
        $this->db = $db;
        $this->conn = $mydb;
    }

    /*
    -----------------------------------------------------------------
    Generic SQL Query with Entire Table
    -----------------------------------------------------------------
    */

    /* --- QUERY AND PROCESS DATA IN TABLE --- */

    public function query($sql = null)
    {
        if (empty($sql)) {
            return false;
        }
        $result = $this->db->query($sql);
        if (!$result || $result === false) {
            return false;
        }
        if ($result !== true) {
            $this->$result = $result;
        }
        return true;
    }

    public function table_exists($table_name)
    {
        $sql = "SHOW TABLES LIKE '$table_name'";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* --- MANIPULATING TABLES --- */

    public function create_table_with_column($table_name = null, $array_column = null)
    {
        if (!$table_name || !$array_column) {
            return 'There is not table_name or columns in create_table_with_column()';
        } else {
            if (!$this->table_exists($table_name)) {
                $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
                `id` int(10) UNSIGNED NULL AUTO_INCREMENT,
                ";
                if (!$array_column) {
                    $sql .= "`time` int(11) NOT NULL,
                    ";
                } else {
                    foreach ($array_column as $key => $value) {
                        $sql .= "`$key` $value, ";
                    }
                }
                $sql .= "UNIQUE KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                mysqli_query($this->conn, $sql);
                return 'Table `' . $table_name . '` created with your columns';
            } else {
                return 'Table `' . $table_name . '` already exists';
            }
        }
    }

    public function create_table($table_name = null)
    {
        if (!$table_name) {
            return 'There is not table_name in create_table()';
        } else {
            if ($this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` already exists';
            } else {
                $column = ["time" => "`time` int(11) NOT NULL"];
                return $this->create_table_with_column($table_name, $column);
            }
        }
    }

    public function drop_table($table_name = null)
    {
        if (!$table_name) {
            return 'There is not table_name in drop_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "DROP TABLE `$table_name`";
                mysqli_query($this->conn, $sql);
                return 'Table `' . $table_name . '` dropped';
            }
        }
    }

    public function rename_table($table_name = null, $new_table_name = null)
    {
        if (!$table_name || !$new_table_name) {
            return 'There is not table_name or new_table_name in rename_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "RENAME TABLE `$table_name` TO `$new_table_name`";
                mysqli_query($this->conn, $sql);
                return 'Table `' . $table_name . '` renamed to `' . $new_table_name . '`';
            }
        }
    }

    public function get_row_count($table_name = null, $where = null)
    {
        if (!$table_name) {
            return 'There is not table_name in get_column_count()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT COUNT(*) FROM `$table_name`";
                $sql_operator = ['>=', '<=', '>', '<', '='];
                if ($where) {
                    //where: {'column': 'value', 'column2': 'value2','operator': '>='}
                    $operator = $where['operator'] ? $where['operator'] : '=';
                    if (!in_array($operator, $sql_operator)) {
                        $operator = '=';
                    }
                    $sql .= " WHERE ";
                    $where_new = [];
                    foreach ($where as $key => $value) {
                        if ($key !== 'operator') {
                            $where_new[$key] = $value;
                        }
                    }
                    foreach ($where_new as $key => $value) {
                        $sql .= "`$key` " . $operator . " '$value'";
                        //thêm AND nếu còn thêm điều kiện
                        if (next($where_new)) {
                            $sql .= " AND ";
                        }
                        //return $sql;
                    }
                }
                $result = mysqli_query($this->conn, $sql);
                $row = mysqli_fetch_row($result);
                return $row[0] ? $row[0] : 0;
            }
        }
    }

    public function get_table_count($table_name = null)
    {
        if (!$table_name) {
            return 'There is not table_name in get_data_count_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                return $this->get_row_count($table_name);
            }
        }
    }

    public function create_column_table($table_name = null, $column_name = null, $column_type = null)
    {
        if (!$table_name || !$column_name || !$column_type) {
            return 'There is not table_name or column_name or column_type in create_column_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "ALTER TABLE $table_name ADD $column_name $column_type";
                mysqli_query($this->conn, $sql);
                return 'Column ' . $column_name . ' in table `' . $table_name . '` created with type ' . $column_type;
            }
        }
    }


    public function drop_column_table($table_name = null, $column_name = null)
    {
        if (!$table_name || !$column_name) {
            return 'There is not table_name or column_name in drop_column_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "ALTER TABLE $table_name DROP $column_name";
                mysqli_query($this->conn, $sql);
                return 'Column ' . $column_name . ' in table `' . $table_name . '` dropped';
            }
        }
    }

    public function insert_row_array_table($table_name = null, $array_row = null)
    {
        if (!$table_name || !$array_row) {
            return 'There is not table_name or array_row in create_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "INSERT INTO `$table_name` SET ";
                foreach ($array_row as $key => $value) {
                    $sql .= "`$key` = '$value', ";
                }
                $sql = substr($sql, 0, -2);
                mysqli_query($this->conn, $sql);
                return 'Rows in table `' . $table_name . '` created';
            }
        }
    }

    public function insert_row_table($table_name = null, $column_name = null, $column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value) {
            return 'There is not table_name or column_name or column_value in create_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $array = [$column_name => $column_value];
                return $this->insert_row_array_table($table_name, $array);
            }
        }
    }

    public function select_table_limit_offset($table_name = null,  $limit = null, $offset = null, $order = null, $sort = null)
    {
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name || !$limit || !$offset) {
            return 'There is not table_name or limit or offset in select_table_data()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT * FROM $table_name ORDER BY $order $sort LIMIT $limit OFFSET $offset";
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                return $rows;
            }
        }
    }

    public function select_table_data($table_name = null, $order = null, $sort = null)
    {
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name) {
            return 'There is not table_name in select_table_data()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT * FROM $table_name ORDER BY $order $sort";
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                return $rows;
            }
        }
    }

    public function query_select_table($table_name = null, $column = null, $other_sql = null)
    {
        if (!$table_name) {
            return 'There is not table_name in query_select_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                if (!$column) {
                    $sql = "SELECT * FROM $table_name";
                } else {
                    $sql = "SELECT $column FROM $table_name";
                }
                if ($other_sql) {
                    $sql .= " $other_sql";
                }
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                //return $sql;
                return $rows;
            }
        }
    }

    public function query_update_table($table_name = null, $array_row = null, $other_sql = null)
    {
        if (!$table_name) {
            return 'There is not table_name in query_update_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                if (is_array($array_row)) {
                    $sql = "UPDATE $table_name SET ";
                    foreach ($array_row as $key => $value) {
                        $sql .= "`$key` = '$value',";
                    }
                    $sql = rtrim($sql, ',');
                    if ($other_sql) {
                        $sql .= " $other_sql";
                    }
                    mysqli_query($this->conn, $sql);
                    return 'Rows in table `' . $table_name . '` updated';
                } else {
                    return 'There is not array_row in query_update_table()';
                }
            }
        }
    }

    /*
    -----------------------------------------------------------------
    SQL queries with WHERE conditions compare equals
    -----------------------------------------------------------------
    */

    public function update_row_array_table($table_name = null, $array_row = null, $where_column_name = null, $where_column_value = null)
    {
        if (!$table_name || !$array_row || !$where_column_name || !$where_column_value) {
            return 'There is not table_name or array_row or where_column_name or where_column_value in update_row_array_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                //{"column_name1":"column_value1","column_name2":"column_value2"}
                $sql = "UPDATE $table_name SET ";
                foreach ($array_row as $key => $value) {
                    $sql .= "`$key` = '$value', ";
                }
                $sql = substr($sql, 0, -2);
                $sql .= " WHERE `$where_column_name` = '$where_column_value'";
                mysqli_query($this->conn, $sql);
                return 'Rows in table `' . $table_name . '` updated';
            }
        }
    }

    public function update_row_table($table_name = null, $column_name = null, $column_value = null, $where_column_name = null, $where_column_value = null)
    {
        if (!$table_name || !$column_name || !$where_column_name || !$where_column_value) {
            return 'There is not table_name or column_name or where_column_name or where_column_value in update_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $array = [$column_name => $column_value];
                return $this->update_row_array_table($table_name, $array, $where_column_name, $where_column_value);
            }
        }
    }

    public function delete_row_table($table_name = null, $column_name = null, $column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value) {
            return 'There is not table_name or column_name or column_value in delete_row_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "DELETE FROM $table_name WHERE $column_name = $column_value";
                mysqli_query($this->conn, $sql);
                return 'Rows in table `' . $table_name . '` deleted';
            }
        }
    }

    public function select_table($table_name = null, $column = null, $where = null, $order = null, $sort = null, $limit = null, $count = null)
    {
        if (!$table_name) {
            return 'There is not table_name in select_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT ";
                if ($column) {
                    $sql .= $column;
                } else {
                    $sql .= "*";
                }
                $sql .= " FROM $table_name";
                if ($where) {
                    // where = {'column1': 'value1', 'column2': 'value2'}
                    if (is_array($where)) {
                        $sql .= " WHERE ";
                        foreach ($where as $key => $value) {
                            $sql .= "$key = '$value' AND ";
                        }
                        $sql = substr($sql, 0, -4);
                    } else {
                        return 'An error occurred in the public function select_table(): `where` referenced is not an array.';
                        exit;
                    }
                }
                if (!$order) $order = 'id';
                if (!$sort) $sort = 'asc';
                $sql .= " ORDER BY $order $sort";
                if ($limit) {
                    $error_limit = 'An error occurred in the public function select_table(): `limit` referenced must be a numberic, or an array containing 2 elements `start` and `end`.';
                    if (is_array($limit)) {
                        // limit = {'start':1,'end':10}
                        if (count($limit) == 2) {
                            if (is_numeric($limit['start']) && is_numeric($limit['end'])) {
                                $sql .= " LIMIT " . $limit['start'] . "," . $limit['end'];
                            } else {
                                return $error_limit;
                                exit;
                            }
                        } else {
                            return $error_limit;
                            exit;
                        }
                    } else {
                        if (is_numeric($limit)) {
                            $sql .= " LIMIT " . $limit;
                        } else {
                            return $error_limit;
                            exit;
                        }
                    }
                }
                //return $sql;
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                if ($count == 'count') {
                    return count($rows) ? count($rows) : 0;
                } else {
                    return $rows;
                }
            }
        }
    }

    public function select_table_offset($table_name = null, $column = null, $where = null, $order = null, $sort = null, $limit = null, $offset = null, $count = null)
    {
        if (!$table_name) {
            return 'There is not table_name in select_table_offset()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT ";
                if ($column) {
                    $sql .= $column;
                } else {
                    $sql .= "*";
                }
                $sql .= " FROM $table_name";
                if ($where) {
                    // where = {'column1': 'value1', 'column2': 'value2'}
                    if (is_array($where)) {
                        $sql .= " WHERE ";
                        foreach ($where as $key => $value) {
                            $sql .= "$key = '$value' AND ";
                        }
                        $sql = substr($sql, 0, -4);
                    } else {
                        return 'An error occurred in the public function select_table_offset(): `where` referenced is not an array.';
                        exit;
                    }
                }
                if (!$order) $order = 'id';
                if (!$sort) $sort = 'asc';
                $sql .= " ORDER BY $order $sort";
                if ($offset) {
                    $error_offset = 'An error occurred in the public function select_table_offset(): `offset` referenced must be a numberic.';
                    if (is_numeric($offset)) {
                        $sql .= " OFFSET " . $offset;
                    } else {
                        return $error_offset;
                        exit;
                    }
                }
                if ($limit) {
                    $error_limit = 'An error occurred in the public function select_table_offset(): `limit` referenced must be a numberic, or an array containing 2 elements `start` and `end`.';
                    if (is_array($limit)) {
                        // limit = {'start':1,'end':10}
                        if (count($limit) == 2) {
                            if (is_numeric($limit['start']) && is_numeric($limit['end'])) {
                                $sql .= " LIMIT " . $limit['start'] . "," . $limit['end'];
                            } else {
                                return $error_limit;
                                exit;
                            }
                        } else {
                            return $error_limit;
                            exit;
                        }
                    } else {
                        if (is_numeric($limit)) {
                            $sql .= " LIMIT " . $limit;
                        } else {
                            return $error_limit;
                            exit;
                        }
                    }
                }
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                if ($count == 'count') {
                    return count($rows) ? count($rows) : 0;
                } else {
                    return $rows;
                }
            }
        }
    }

    public function select_table_where_data_limit_offset($table_name = null, $where_column_name, $where_column_value, $limit, $offset, $order = null, $sort = null)
    {
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name) {
            return 'There is not table_name in select_table_data()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT * FROM $table_name WHERE $where_column_name = $where_column_value ORDER BY $order $sort LIMIT $limit OFFSET $offset";
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                return $rows;
            }
        }
    }

    public function select_table_row_data($table_name =  null, $column_name = null, $column_value = null)
    {
        if (!$table_name || !$column_name || !$column_value) {
            return 'There is not table_name or row or column_name or column_value in select_table_row_data()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT * FROM $table_name WHERE $column_name = '$column_value'";
                $query = mysqli_query($this->conn, $sql);
                $row = mysqli_fetch_assoc($query);
                return $row;
            }
        }
    }

    public function select_table_where_data($table_name = null, $where_column_name = null, $where_column_value = null, $order = null, $sort = null)
    {
        if (!$order) $order = 'id';
        if (!$sort) $sort = 'ASC';
        if (!$table_name || !$where_column_name || !$where_column_value) {
            return 'There is not table_name or where_column_name or where_column_value in select_table_where_data()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $sql = "SELECT * FROM $table_name WHERE $where_column_name = '$where_column_value' ORDER BY $order $sort";
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                $total = ['total' => $rows ? count($rows) : 0];
                return array_merge($total, $rows);
            }
        }
    }

    /*
    -----------------------------------------------------------------
    SQL queries related to the search
    -----------------------------------------------------------------
    */

    public function search_key_in_table($table_name = null, $column = null, $string = null, $random = null)
    {
        if (!$table_name || !$column || !$string) {
            return 'There is not table_name or column or string (or keyword) in search_key_in_table()';
        } else {
            if (!$this->table_exists($table_name)) {
                return 'Table `' . $table_name . '` does not exist';
            } else {
                $array = explode(' ', $string);
                $array = array_unique($array);
                $count = count($array);
                if ($count > 1) {
                    foreach ($array as $key => $value) {
                        $array[$key] = '%' . $value . '%';
                    }
                    $sql = "SELECT * FROM `$table_name` WHERE `$column` LIKE '" . implode("' OR `title` LIKE '", $array) . "'";
                } else {
                    $sql = "SELECT * FROM `$table_name` WHERE `$column` LIKE '%" . $string . "%'";
                }
                if (is_numeric($random)) {
                    $sql .= " ORDER BY RAND() LIMIT $random";
                }
                $query = mysqli_query($this->conn, $sql);
                $rows = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $rows[] = $row;
                }
                return $rows;
            }
        }
    }
}