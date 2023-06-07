<?php 
include("../includes/header.php");

if(isset($_GET['q'])) {
    $query = $_GET['q'];
}
else {
    $query = "";
}

if(isset($_GET['type'])) {
    $type = $_GET['type'];
}
else {
    $type = "name";
}
?>
<head>
<link rel="stylesheet" href="./background.css">
</head>

<div class="p-8 sm:ml-64 text-white mt-2">
  <div class=" mt-14">
  <?php 
    if($query == "")
        echo "You must enter something in the search box.";
    else {
        if($type == "username") 
            $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
        else {
            $names = explode(" ", $query);

            if(count($names) == 3)
                $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE ((first_name LIKE '$names[0]%' AND last_name LIKE '$names[2]%') OR job LIKE '$query%') AND user_closed='no'");
            else if(count($names) == 2)
                $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE ((first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') OR job LIKE '$query%') AND user_closed='no'");
            else 
                $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%' OR job LIKE '$query%') AND user_closed='no'");
        }

        if(mysqli_num_rows($usersReturnedQuery) == 0)
            echo "Nu am putut găsi: " . $type . " like: " .$query;
        else 
            echo mysqli_num_rows($usersReturnedQuery) . " rezultatele găsite: <br> <br>";

        echo "<p id='grey'>Ai incercat să cauți:</p>";
        echo "<a href='search.php?q=" . $query ."&type=name'>Nume</a>, <a href='search.php?q=" . $query ."&type=username'>Usernames sau Jobs</a><br><br><hr id='search_hr'>";

        while($row = mysqli_fetch_array($usersReturnedQuery)) {
            $user_obj = new User($con, $user['username']);

            $button = "";
            $mutual_friends = "";

            if($user['username'] != $row['username']) {

                if($user_obj->isFriend($row['username']))
                    $button = "<input type='submit' name='" . $row['username'] . "' value='Remove Friend' class='focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900'>";
                else if($user_obj->didReceiveRequest($row['username']))
                    $button = "<input type='submit' name='" . $row['username'] . "' value='Respond to request' class='focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:focus:ring-yellow-900'>";
                else if($user_obj->didSendRequest($row['username']))
                    $button = "<input type='submit' value='Request Sent' class='text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'>";
                else 
                    $button = "<input type='submit' name='" . $row['username'] . "' value='Add Friend' class='focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800'>";

                $mutual_friends = $user_obj->getMutualFriends($row['username']) . " friends in common";

                if(isset($_POST[$row['username']])) {

                    if($user_obj->isFriend($row['username'])) {
                        $user_obj->removeFriend($row['username']);
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                    else if($user_obj->didReceiveRequest($row['username'])) {
                        header("Location: requests.php");
                    }
                    else if($user_obj->didSendRequest($row['username'])) {

                    }
                    else {
                        $user_obj->sendRequest($row['username']);
                        header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                    }
                }
            }

            echo "
            <section class='text-white'>
            <div class='flex items-center py-8 px-4 mx-auto max-w-screen-xl'>
            <div class='mx-auto max-w-screen-sm text-center'>
            <div class='items-center bg-gray-50 rounded-lg shadow sm:flex dark:bg-gray-700 dark:border-gray-600'>
                <div class='result_profile_pic'>
                    <a href='" . $row['username'] ."'><img src='". $row['profile_pic'] ."' style='height: 100px;'></a>
                </div>
                <div class='p-5'>
                    <h3 class='text-xl font-bold tracking-tight text-gray-900 dark:text-white'>
                        <a href='" . $row['username'] ."'>" . $row['first_name'] . " " . $row['last_name'] . "</a>
                    </h3>
                    <p class='mt-3 mb-4 '>Username: " . $row['username'] . "</p>
                    <p class='mt-3 mb-4 '>Prieteni comuni: " . $mutual_friends . "</p>
                    
                    <div class='searchPageFriendButtons'>
                        <form action='' method='POST'>
                            " . $button . "
                            <br>
                        </form>
                    </div>
                  
                </div>
            </div>
            
            </div>
            </div>
            </section>";
            
        }
    }
?>
  </div>
</div>
