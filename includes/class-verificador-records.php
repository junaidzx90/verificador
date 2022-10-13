<?php
class Verificador_list extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $action = $this->current_action();

        $data = $this->table_data();
        usort($data, array(&$this, 'usort_reorder'));

        $perPage = 50;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage,
        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
      
        $this->items = $data;
    }

    // Sorting function
    function usort_reorder($a, $b)
    {
        // If no sort, default to user_login
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'ID';
        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'desc';
        // Determine sort order
        $result = strnatcmp($a[$orderby], $b[$orderby]);
        
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }
    
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" name="records[]" />',
            'username' => 'Name',
            'useremail' => 'Email',
            'coupon' => 'Coupon',
            'mangername' => 'Manger name',
            'date' => 'Record date'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array();
    }    

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        global $wpdb;
        $data = array();

        $records = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}verificador");
        if($records){
            foreach($records as $record){
                $arr = [
                    'ID' => $record->ID,
                    'username' => $record->username,
                    'useremail' => '<a href="mailto: '.$record->useremail.'">'.$record->useremail.'</a>',
                    'coupon' => $record->coupon,
                    'mangername' => $record->manager_name,
                    'date' => date("F j, Y - g:i a", strtotime($record->created))
                ];
                
                $data[] = $arr;
            }
        }
        
        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case $column_name:
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }

    public function column_username($item){
        $actions = array(
            'delete' => '<a href="?page=verificador&action=delete&records='.$item['ID'].'">Delete</a>',
        );

        return sprintf('%1$s %2$s', $item['username'], $this->row_actions($actions));
    }

    public function get_bulk_actions(){
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="records[]" value="%s" />', $item['ID']
        );
    }

    // All form actions
    public function current_action(){
        global $wpdb;
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete' && isset($_REQUEST['records'])) {
            if(is_array($_REQUEST['records'])){
                $ids = $_REQUEST['records'];
                foreach($ids as $ID){
                    $wpdb->query("DELETE FROM {$wpdb->prefix}verificador WHERE ID = $ID");
                }
            }else{
                $ID = intval($_REQUEST['records']);
                $wpdb->query("DELETE FROM {$wpdb->prefix}verificador WHERE ID = $ID");
            }

            if(!is_wp_error( $wpdb )){
                wp_safe_redirect( admin_url( 'admin.php?page=verificador' ) );
                exit;
            }
        }
    }

} //class
