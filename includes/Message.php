<?php

class Message {
	private $user_obj;
	private $con;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	public function getMostRecentUser() {
		$userLoggedIn = $this->user_obj->getUsername();
	
		$query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC LIMIT 1");
	
		if(mysqli_num_rows($query) == 0)
			return null; // Return null instead of false
	
		$row = mysqli_fetch_array($query);
		$user_to = $row['user_to'];
		$user_from = $row['user_from'];
	
		if($user_to != $userLoggedIn)
			return $user_to;
		else 
			return $user_from;
	}

	public function sendMessage($user_to, $body, $date) {

		if($body != "") {
			$userLoggedIn = $this->user_obj->getUsername();
			$query = mysqli_query($this->con, "INSERT INTO messages VALUES('', '$user_to', '$userLoggedIn', '$body', '$date', 'no', 'no', 'no')");
		}
	}





	public function getLatestMessage($userLoggedIn, $user2) {
		$details_array = array();
	
		$query = mysqli_query($this->con, "SELECT body, user_to, date FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$user2') OR (user_to='$user2' AND user_from='$userLoggedIn') ORDER BY id DESC LIMIT 1");
		$row = mysqli_fetch_array($query);
		$sent_by = ($row['user_to'] == $userLoggedIn) ? "They said: " : "You said: ";
	
		//Timeframe
		$date_time_now = new DateTime();
		$start_date = new DateTime($row['date']); //Time of post
		$interval = $start_date->diff($date_time_now); //Difference between dates 
	
		$time_message = $this->getTimeMessage($interval);
	
		array_push($details_array, $sent_by);
		array_push($details_array, $row['body']);
		array_push($details_array, $time_message);
	
		return $details_array;
	}
	
	private function getTimeMessage($interval) {
		if ($interval->y >= 1) {
			return ($interval->y == 1) ? "1 year ago" : $interval->y . " years ago";
		} elseif ($interval->m >= 1) {
			$days = "";
			if ($interval->d > 0) {
				$days = ($interval->d == 1) ? "1 day ago" : $interval->d . " days ago";
			}
			return ($interval->m == 1) ? "1 month " . $days : $interval->m . " months " . $days;
		} elseif ($interval->d >= 1) {
			return ($interval->d == 1) ? "Yesterday" : $interval->d . " days ago";
		} elseif ($interval->h >= 1) {
			return ($interval->h == 1) ? "1 hour ago" : $interval->h . " hours ago";
		} elseif ($interval->i >= 1) {
			return ($interval->i == 1) ? "1 minute ago" : $interval->i . " minutes ago";
		} else {
			return ($interval->s < 30) ? "Just now" : $interval->s . " seconds ago";
		}
	}

	public function getMessages($otherUser) {
		$userLoggedIn = $this->user_obj->getUsername();
		$data = "";

		$query = mysqli_query($this->con, "UPDATE messages SET opened='yes' WHERE user_to='$userLoggedIn' AND user_from='$otherUser'");

		$get_messages_query = mysqli_query($this->con, "SELECT * FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$otherUser') OR (user_from='$userLoggedIn' AND user_to='$otherUser')");

		while($row = mysqli_fetch_array($get_messages_query)) {
			$user_to = $row['user_to'];
			$user_from = $row['user_from'];
			$body = $row['body'];

			$div_top = ($user_to == $userLoggedIn) ? "
			<div class='message max-w-xs p-4 space-y-4 my-2 text-gray-500 bg-white divide-x divide-gray-200 rounded-lg shadow dark:text-white dark:divide-gray-700 space-x dark:bg-blue-600' >" : "
			<div class='message max-w-xs p-4 space-y-4 ml-auto my-2 text-right text-gray-500 bg-white divide-x divide-gray-200 rounded-lg shadow dark:text-white dark:divide-gray-700 space-x dark:bg-green-600' >";
			$data = $data . $div_top . $body . "</div>";
		}
		return $data;
	}

	public function getConvos() {
		$userLoggedIn = $this->user_obj->getUsername();
		$return_string = "";
		$convos = array();

		$query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC");

		while($row = mysqli_fetch_array($query)) {
			$user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

			if(!in_array($user_to_push, $convos)) {
				array_push($convos, $user_to_push);
			}
		}

		foreach($convos as $username) {
			$user_found_obj = new User($this->con, $username);
			$latest_message_details = $this->getLatestMessage($userLoggedIn, $username);

			$dots = (strlen($latest_message_details[1]) >= 12) ? "..." : "";
			$split = str_split($latest_message_details[1], 12);
			$split = $split[0] . $dots; 

			$return_string .= "<a href='messages.php?u=$username'> <div class='user_found_messages mt-2 '>
								<img class='w-30 h-30 rounded-full' src='" . $user_found_obj->getProfilePic() . "' style='margin-right: 5px;'>
								" . $user_found_obj->getFirstAndLastName() . "
								<span class='timestamp_smaller' id='grey'> " . $latest_message_details[2] . "</span>
								<p id='grey' style='margin: 0;'>" . $latest_message_details[0] . $split . " </p>
								</div>
								</a>";
		}

		return $return_string;

	}


	public function getConvosDropdown($data, $limit) {

		$page = $data['page'];
		$userLoggedIn = $this->user_obj->getUsername();
		$return_string = "";
		$convos = array();

		if($page == 1)
			$start = 0;
		else 
			$start = ($page - 1) * $limit;

		$set_viewed_query = mysqli_query($this->con, "UPDATE messages SET viewed='yes' WHERE user_to='$userLoggedIn'");

		$query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC");

		while($row = mysqli_fetch_array($query)) {
			$user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

			if(!in_array($user_to_push, $convos)) {
				array_push($convos, $user_to_push);
			}
		}

		$num_iterations = 0; //Number of messages checked 
		$count = 1; //Number of messages posted

		foreach($convos as $username) {

			if($num_iterations++ < $start)
				continue;

			if($count > $limit)
				break;
			else 
				$count++;


			$is_unread_query = mysqli_query($this->con, "SELECT opened FROM messages WHERE user_to='$userLoggedIn' AND user_from='$username' ORDER BY id DESC");
			$row = mysqli_fetch_array($is_unread_query);
			$style = (isset($row['opened']) && $row['opened'] == 'no') ? "background-color: #DDEDFF;" : "";


			$user_found_obj = new User($this->con, $username);
			$latest_message_details = $this->getLatestMessage($userLoggedIn, $username);

			$dots = (strlen($latest_message_details[1]) >= 12) ? "..." : "";
			$split = str_split($latest_message_details[1], 12);
			$split = $split[0] . $dots; 

			$return_string .= "<a href='messages.php?u=$username'> 
								<div class='user_found_messages' style='" . $style . "'>
								<img class='rounded-full' src='" . $user_found_obj->getProfilePic() . "' >
								" . $user_found_obj->getFirstAndLastName() . "
								<span class='timestamp_smaller' id='grey'> " . $latest_message_details[2] . "</span>
							 	<p id='grey' style='margin: 0;'>" . $latest_message_details[0] . $split . " </p>
								</div>
								</a>";
		}


		//If posts were loaded
		if($count > $limit)
			$return_string .= "<input type='hidden' class='nextPageDropdownData' value='" . ($page + 1) . "'><input type='hidden' class='noMoreDropdownData' value='false'>";
		else 
			$return_string .= "<input type='hidden' class='noMoreDropdownData' value='true'> <p style='text-align: center;'>No more messages to load!</p>";

		return $return_string;
	}

	public function getUnreadNumber() {
		$userLoggedIn = $this->user_obj->getUsername();
		$query = mysqli_query($this->con, "SELECT * FROM messages WHERE viewed='no' AND user_to='$userLoggedIn'");
		return mysqli_num_rows($query);
	}

}




?>