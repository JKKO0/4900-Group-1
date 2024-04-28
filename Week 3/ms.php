<?php
    // Display Errors
    error_reporting(E_ALL);
    //ini_set('display_errors', 1);

    // initializing and setting up session 
    require_once('php_backend/inc_libraries/inc_session.php');
    session_set_save_handler('_open','_close','_read','_write','_destroy','_clean'); 
    session_start();

    // database initialization 
    require_once('php_backend/inc_libraries/inc_db_conn_san.php');
    $dbc = dbConnectInitial();

    // Access User Email
    $lemail = dbTrimSanitizeStandard($_SESSION["login"]);

    // TO FETCH ALL GOALS (NOT JUST CURRENT USER GOALS) UNCOMMENT THIS 
    /*
    $all_goals_stmt = $dbc->prepare("SELECT goal_id, goal_type, goal_date, goal_shared FROM goal_data");
    if ($all_goals_stmt->execute()===false) {
        sql_execute_fail();
    }
    $all_goals = $all_goals_stmt->fetchAll(PDO::FETCH_ASSOC);
    $all_milestones_stmt = $dbc->prepare("SELECT milestone_id, user_email, assoc_goal, goal_milestone_type, goal_milestone_text, goal_milestone_comp, goal_milestone_end_date, goal_milestone_title, goal_milestone_magnitude, goal_milestone_keyword FROM goal_milestone_data");
    if ($all_milestones_stmt->execute()===false) {
        sql_execute_fail();
    }
    $all_milestones = $all_milestones_stmt->fetchAll(PDO::FETCH_ASSOC);
    */

    function getMilestones($dbc, $lemail) {
        // Prepare SQL statement to Collect All User Milestones
        $milestone_stmt = $dbc->prepare("SELECT milestone_id, assoc_goal, goal_milestone_type, goal_milestone_text, goal_milestone_comp, goal_milestone_end_date, goal_milestone_title, goal_milestone_magnitude, goal_milestone_keyword FROM goal_milestone_data WHERE user_email = ?");
        // Execute the SQL command & Fetch all the milestones and place into an array
        if($milestone_stmt->execute(array($lemail))===false) {
            sql_execute_fail();
        } 
        $milestone_arr = $milestone_stmt->fetchAll(PDO::FETCH_ASSOC);
        return $milestone_arr;
    }

    function getGoals($dbc, $lemail) {
        // Prepare SQL Statement to Collect All User Goals
        $goal_stmt = $dbc->prepare("SELECT goal_id, goal_type, goal_date, goal_shared FROM goal_data WHERE user_id = ?");

        // Execute the SQL command & Fetch all the goals and place into an array
        if($goal_stmt->execute(array($lemail))===false) {
            sql_execute_fail();
        } 
        $goal_arr = $goal_stmt->fetchAll(PDO::FETCH_ASSOC);
        return $goal_arr;
    }

    function milestoneWithStartDate($dbc, $lemail) {
        $goal_arr = getGoals($dbc, $lemail);

        $milestone_arr = getMilestones($dbc, $lemail);

        // For every Goal, find milestones associated with the goal, and then append to the goal array
        $milestones = [];
        foreach($goal_arr as &$goal) {
            $milestones = [];
            $start_date = $goal['goal_date']; 
            foreach($milestone_arr as &$milestone) {
                if ($milestone['assoc_goal'] == $goal["goal_id"]) {
                    $milestone['start_date'] = $start_date;
                    $milestones[] = $milestone;
                }
            }
        }
        return $milestones;
    }

    function getGoalAndMilestones($dbc, $lemail) {

        $goal_arr = getGoals($dbc, $lemail);

        $milestone_arr = getMilestones($dbc, $lemail);

        // For every Goal, find milestones associated with the goal, and then append to the goal array
        foreach($goal_arr as &$goal) {
            $milestones = [];
            $start_date = $goal['goal_date']; 
            foreach($milestone_arr as &$milestone) {
                if ($milestone['assoc_goal'] == $goal["goal_id"]) {
                    $milestone['start_date'] = $start_date;
                    $milestones[] = $milestone;
                }
            }
            $goal['milestones'] = $milestones;
        }
        return $goal_arr;
    }

    function get_associated_milestones($dbc, $goal_id): string {
        $associated_milestones = [];
    
        $query = 'SELECT milestone_id FROM goal_milestone_data
                    WHERE assoc_goal = ?';
        $stmt = $dbc->prepare($query);
    
        if($stmt->execute(array($goal_id)) === false) {
            sql_execute_fail();
        } else {
            /* Fetch the next row of the result set and it is guaranteed to just be a single column
                so we use PDO::FETCH_COLUMN*/
            while($milestone_id = $stmt->fetch(PDO::FETCH_COLUMN)){
                $query = 'SELECT milestone_id, goal_milestone_comp
                            FROM goal_milestone_data
                            WHERE milestone_id IN
                                (SELECT dependency_parent FROM goal_milestone_dependencies 
                                WHERE dependency_child=?)';
                $inner_stmt = $dbc->prepare($query);
                // End entire program if something goes wrong at this step
                if($inner_stmt->execute(array($milestone_id)) === false) {
                    sql_execute_fail();
                }
                // Fetch all rows as an indexed array
                $results = $inner_stmt->fetchAll(PDO::FETCH_ASSOC);
                $associated_milestones[$milestone_id]['dependencies'] = $results;
    
                $query = 'SELECT user_email, goal_milestone_type, goal_milestone_text, goal_milestone_comp
                            goal_milestone_end_date, goal_milestone_title, goal_milestone_magnitude, goal_milestone_keyword
                            FROM goal_milestone_data
                            WHERE milestone_id=?';
                $inner_stmt = $dbc -> prepare($query);
                if($inner_stmt->execute(array($milestone_id)) === false) {
                    sql_execute_fail();
                }
                $results = $inner_stmt->fetchAll(PDO::FETCH_ASSOC);
                $associated_milestones[$milestone_id]['milestone_information'] = $results;
            }
        }
        return $associated_milestones;
    }

    $data = getGoalAndMilestones($dbc, $lemail);
    // echo json_encode($data); 
?>
