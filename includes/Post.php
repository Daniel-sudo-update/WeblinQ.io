
<?php
class Post{
	private $con;
	private $user_obj;

	public function __construct($con, $user){
		$this->con = $con;
		$this->user_obj = new User($con, $user);
	}

	public function submitPost($body, $user_to) {
		$body = strip_tags($body); //removes html tags 
		$body = mysqli_real_escape_string($this->con, $body);
		$check_empty = preg_replace('/\s+/', '', $body); //Deletes all spaces 
	
		if($check_empty != "") {
			// upload videos from youtube
			$body_array = preg_split("/\s+/", $body);
	
			foreach($body_array as $key => $value) {
				if(strpos($value, "www.youtube.com/watch?v=") !== false) {
					$link = preg_split("!&!", $value);
					$value = preg_replace("!watch\?v=!", "embed/", $link[0]);
					$value = "<br><iframe width=\'420\' height=\'315\' src=\'" . $value ."\'></iframe><br>";
					$body_array[$key] = $value;
				}
			}
			$body = implode(" ", $body_array);
	
			//Current date and time
			$date_added = date("Y-m-d H:i:s");
			//Get username
			$added_by = $this->user_obj->getUsername();
	
			//If user is on own profile, user_to is 'none'
			if($user_to == $added_by) {
				$user_to = NULL;
			}
	
			//Insert post 
			$stmt = $this->con->prepare("INSERT INTO posts(body, added_by, user_to, date_added, user_closed, deleted, likes) VALUES (?, ?, ?, ?, 'no', 'no', '0')");
			$stmt->bind_param("ssss", $body, $added_by, $user_to, $date_added);
			$stmt->execute();
			$returned_id = $stmt->insert_id;
			$stmt->close();
	
			//Insert notification
			if($user_to != NULL) {
				$notification = new Notification($this->con, $added_by);
				$notification->insertNotification($returned_id, $user_to, "like");
			}
	
			//Update post count for user 
			$num_posts = $this->user_obj->getNumPosts();
			$num_posts++;
			$stmt = $this->con->prepare("UPDATE users SET num_posts = ? WHERE username = ?");
			$stmt->bind_param("is", $num_posts, $added_by);
			$stmt->execute();
			$stmt->close();
		}
	}
	
	

	public function loadPostsFriends($data, $limit) {
		$page = $data['page']; 
		if($page == 1) 
			$start = 0;
		else 
			$start = ($page - 1) * $limit;
		
		$str = ""; //String to return 
		$userLoggedIn = $this->user_obj->getUsername();
		$limit = mysqli_real_escape_string($this->con, $limit);
	
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");
	
		if(mysqli_num_rows($data_query) > 0) {


				$num_iterations = 0; //Number of results checked (not necasserily posted)
				$count = 1;

				while($row = mysqli_fetch_array($data_query)) {
					$id = $row['id'];
					$body = $row['body'];
					$added_by = $row['added_by'];
					$date_time = $row['date_added'];

					//Prepare user_to string so it can be included even if not posted to a user
					if($row['user_to'] == "none") {
						$user_to = "";
					}
					else {
						$user_to_obj = new User($this->con, $row['user_to']);
						$user_to_name = $user_to_obj->getFirstAndLastName();
						$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
					}

					//Check if user who posted, has their account closed
					$added_by_obj = new User($this->con, $added_by);
					if($added_by_obj->isClosed()) {
						continue;
					}

					$user_logged_obj = new User($this->con, $userLoggedIn);
					if($user_logged_obj->isFriend($added_by)){

							if($num_iterations++ < $start)
								continue; 

                               
							//Once 10 posts have been loaded, break
							if($count > $limit) {
								break;
							}
							else {
								$count++;
							}
                               
							  // delete button
							  if($userLoggedIn == $added_by)
							  $delete_button = "<button class='delete_button' id='post$id'>X</button>";
						  else 
							  $delete_button = "";
							 
							

							$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
							$user_row = mysqli_fetch_array($user_details_query);
							$first_name = $user_row['first_name'];
							$last_name = $user_row['last_name'];
							$profile_pic = $user_row['profile_pic'];

							?>

								<script>
									function toggle<?php echo $id; ?>() {

										var target = $(event.target);
										if(!target.is("a")){
											var element = document.getElementById("toggleComment<?php echo $id; ?>");
							
											if(element.style.display == "block") 
												element.style.display = "none";
											else 
												element.style.display = "block";

										}


										
									}
								</script>
							
							<?php 
                            
							
							$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
							$comments_check_num = mysqli_num_rows($comments_check);

							//Timeframe
							$date_time_now = date("Y-m-d H:i:s");
							$start_date = new DateTime($date_time); // Time of post
							$end_date = new DateTime($date_time_now); // Current time
							$interval = $start_date->diff($end_date); // Difference between dates

							if ($interval->y >= 1) {
								$time_message = ($interval->y == 1) ? $interval->y . " year ago" : $interval->y . " years ago";
							} else if ($interval->m >= 1) {
								$days = ($interval->d == 0) ? " ago" : (($interval->d == 1) ? $interval->d . " day ago" : $interval->d . " days ago");
								$time_message = ($interval->m == 1) ? $interval->m . " month" . $days : $interval->m . " months" . $days;
							} else if ($interval->d >= 1) {
								$time_message = ($interval->d == 1) ? "Yesterday" : $interval->d . " days ago";
							}else if ($interval->h >= 1) {
								$time_message = ($interval->h == 1) ? $interval->h . " hour ago" : $interval->h . " hours ago";
							} else if ($interval->i >= 1) {
								$time_message = ($interval->i == 1) ? $interval->i . " minute ago" : $interval->i . " minutes ago";
							} else {
								$time_message = ($interval->s < 30) ? "Just now" : $interval->s . " seconds ago";
							}

							// content postari si comentarii
							$str .= "

							<section class='text-gray-400 body-font '>
								<div class='container px-5 py-0 mx-auto'>
									<div class='flex items-center  lg:w-3/5 mx-auto mb-10 bg-gray-900 h-auto rounded-3xl sm:flex-row flex-col my-8 status_post'>
									
										
										<div class='flex-grow sm:text-left p-4 mt-6 sm:mt-0' >
										
										<img src='$profile_pic' width='40' class='rounded-full align-top inline'>
										<a href='$added_by' class='font-semibold text-2xl pt-2 inline'> $first_name $last_name </a> &nbsp;&nbsp;&nbsp;&nbsp;<small class='text-gray-700'>$time_message</small>
										$delete_button
										
										
											
											
												   <div class='block my-4 post_body'>
													$body	
													</div>
													<p class='newsfeedPostOption inline' onClick='javascript:toggle$id()'>($comments_check_num)&nbsp;&nbsp;&nbsp;</p>
														<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-6 h-6 inline' onClick='javascript:toggle$id()'>
														<path stroke-linecap='round' stroke-linejoin='round' d='M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155' />
														</svg>
														
														

														<p class='text-left inline'> 
														<iframe src='./like.php?post_id=$id' id='comment_iframe'class='border-0 h-16 inline align-top' style='color:white'></iframe>
														</p>
														
		
						
						
							
							";
							// dimensionare sectiune de comentarii
                        if($comments_check_num>0){
							$str .="
									<div class='text-left inline scrollbar-container comment_section'> 
									<div class='post_comment' id='toggleComment$id' style='display: none;' >
														<iframe src='./comment_frame.php?post_id=$id' id=comment_iframe class='no-scrollbar overflow-y-auto h-screen hide-scrollbar border-0 w-full my-12 '> </iframe>
														</div>
													
														
													   
												
									    </div>
									  
									</div>
							    </div>
							</section>
							";
						} else 
						{
								$str .="
									<div class='text-left inline scrollbar-container comment_section'> 
									<div class='post_comment' id='toggleComment$id' style='display: none;' >
														<iframe src='./comment_frame.php?post_id=$id' id=comment_iframe class='no-scrollbar overflow-y-auto h-80 border-0 w-full my-12'> </iframe>
														</div>
													
														
													   
												
									    </div>
									</div>
							    </div>
							</section>
							";
						}
						// END sectiunde de dimensionare a sectiunii de comentarii

					//  end content postari si comentarii
 

						
				}
				?>
				<!-- script for delete posts -->
				 <script>
					$(document).ready(function () {
						$('#post<?php echo $id;?>').on('click', function () {
							// Utilizează funcția nativă `confirm()` în locul Bootbox
							var result = confirm("Ești sigur că vrei să ștergi postarea?");

							// Dacă utilizatorul a apăsat pe "OK", execută cererea de ștergere
							if (result) {
							$.post("../includes/delete_post.php?post_id=<?php echo $id; ?>", { result: result })
								.done(function () {
								// Reîncarcă pagina după ce cererea s-a încheiat cu succes
								location.reload();
								})
								.fail(function () {
								// Afișează un mesaj de eroare în cazul în care cererea nu a reușit
								alert("A apărut o problemă în timpul ștergerii postării. Încearcă din nou.");
								});
							}
						});
					});


				 </script>
				 <!-- end script for delete posts -->
				<?php

				} //End while loop

				if($count > $limit) {
					$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
						<input type='hidden' class='noMorePosts' value='false'>";
				}
				else {
					$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'> No more posts to show! </p>";
				}

				echo $str;

			}
		}
		

// load profile posts
    public function loadProfilePosts($data, $limit) {
	$page = $data['page']; 
	$profileUser = $data['profileUsername'];
	if($page == 1) 
		$start = 0;
	else 
		$start = ($page - 1) * $limit;
	
	$str = ""; //String to return 
	$userLoggedIn = $this->user_obj->getUsername();
	$limit = mysqli_real_escape_string($this->con, $limit);

	$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND ((added_by='$profileUser' AND user_to = 'none') OR user_to = 'profileUser') ORDER BY id DESC");

	if(mysqli_num_rows($data_query) > 0) {


			$num_iterations = 0; //Number of results checked (not necasserily posted)
			$count = 1;

			while($row = mysqli_fetch_array($data_query)) {
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];


				

						if($num_iterations++ < $start)
							continue; 

						   
						//Once 10 posts have been loaded, break
						if($count > $limit) {
							break;
						}
						else {
							$count++;
						}
						   
						  // delete button
						  if($userLoggedIn == $added_by)
						  $delete_button = "<button class='delete_button' id='post$id'>X</button>";
					  else 
						  $delete_button = "";
						 
						

						$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
						$user_row = mysqli_fetch_array($user_details_query);
						$first_name = $user_row['first_name'];
						$last_name = $user_row['last_name'];
						$profile_pic = $user_row['profile_pic'];

						?>

							<script>
								function toggle<?php echo $id; ?>() {

									var target = $(event.target);
									if(!target.is("a")){
										var element = document.getElementById("toggleComment<?php echo $id; ?>");
						
										if(element.style.display == "block") 
											element.style.display = "none";
										else 
											element.style.display = "block";

									}


									
								}
							</script>
						
						<?php 
						
						
						$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
						$comments_check_num = mysqli_num_rows($comments_check);

						//Timeframe
						$date_time_now = date("Y-m-d H:i:s");
						$start_date = new DateTime($date_time); // Time of post
						$end_date = new DateTime($date_time_now); // Current time
						$interval = $start_date->diff($end_date); // Difference between dates

						if ($interval->y >= 1) {
							$time_message = ($interval->y == 1) ? $interval->y . " year ago" : $interval->y . " years ago";
						} else if ($interval->m >= 1) {
							$days = ($interval->d == 0) ? " ago" : (($interval->d == 1) ? $interval->d . " day ago" : $interval->d . " days ago");
							$time_message = ($interval->m == 1) ? $interval->m . " month" . $days : $interval->m . " months" . $days;
						} else if ($interval->d >= 1) {
							$time_message = ($interval->d == 1) ? "Yesterday" : $interval->d . " days ago";
						}else if ($interval->h >= 1) {
							$time_message = ($interval->h == 1) ? $interval->h . " hour ago" : $interval->h . " hours ago";
						} else if ($interval->i >= 1) {
							$time_message = ($interval->i == 1) ? $interval->i . " minute ago" : $interval->i . " minutes ago";
						} else {
							$time_message = ($interval->s < 30) ? "Just now" : $interval->s . " seconds ago";
						}

						// content postari si comentarii
						$str .= "

						<section class='text-gray-400 body-font '>
							<div class='container px-5 py-0 mx-auto'>
								<div class='flex items-center  lg:w-3/5 mx-auto mb-10 bg-gray-900 h-auto rounded-3xl sm:flex-row flex-col my-8 status_post'>
								
									
									<div class='flex-grow sm:text-left p-4 mt-6 sm:mt-0' >
									
									<img src='$profile_pic' width='40' class='rounded-full align-top inline'>
									<a href='$added_by' class='font-semibold text-2xl pt-2 inline'> $first_name $last_name </a> &nbsp;&nbsp;&nbsp;&nbsp;<small class='text-gray-700'>$time_message</small>
									&nbsp;&nbsp;&nbsp;&nbsp;
									

									$delete_button 
									
									
										
										
											   <div class='block my-4 post_body'>
												$body	
												</div>
												<p class='newsfeedPostOption inline' onClick='javascript:toggle$id()'>($comments_check_num)&nbsp;&nbsp;&nbsp;</p>
													<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-6 h-6 inline' onClick='javascript:toggle$id()'>
													<path stroke-linecap='round' stroke-linejoin='round' d='M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155' />
													</svg>
													
													

													<p class='text-left inline'> 
													<iframe src='./like.php?post_id=$id' id='comment_iframe'class='border-0 h-16 inline align-top' style='color:white'></iframe>
													</p>
													
	
					
					
						
						";
						// dimensionare sectiune de comentarii
					if($comments_check_num>0){
						$str .="
								<div class='text-left inline scrollbar-container comment_section'> 
								<div class='post_comment' id='toggleComment$id' style='display: none;' >
													<iframe src='./comment_frame.php?post_id=$id' id=comment_iframe class='no-scrollbar overflow-y-auto h-screen hide-scrollbar border-0 w-full my-12 '> </iframe>
													</div>
												
													
												   
											
									</div>
								  
								</div>
							</div>
						</section>
						";
					} else 
					{
							$str .="
								<div class='text-left inline scrollbar-container comment_section'> 
								<div class='post_comment' id='toggleComment$id' style='display: none;' >
													<iframe src='./comment_frame.php?post_id=$id' id=comment_iframe class='no-scrollbar overflow-y-auto h-80 border-0 w-full my-12'> </iframe>
													</div>
												
													
												   
											
									</div>
								</div>
							</div>
						</section>
						";
					}
					// END sectiunde de dimensionare a sectiunii de comentarii

				 //  end content postari si comentarii


					
			    
			?>
			<!-- script for delete posts -->
			 <script>
				$(document).ready(function () {
					$('#post<?php echo $id;?>').on('click', function () {
						// Utilizează funcția nativă `confirm()` în locul Bootbox
						var result = confirm("Are you sure you want to delete this post?");

						// Dacă utilizatorul a apăsat pe "OK", execută cererea de ștergere
						if (result) {
						$.post("../includes/delete_post.php?post_id=<?php echo $id; ?>", { result: result })
							.done(function () {
							// Reîncarcă pagina după ce cererea s-a încheiat cu succes
							location.reload();
							})
							.fail(function () {
							// Afișează un mesaj de eroare în cazul în care cererea nu a reușit
							alert("A apărut o problemă în timpul ștergerii postării. Încearcă din nou.");
							});
						}
					});
				});


			 </script>
			 <!-- end script for delete posts -->
			<?php

			} //End while loop

			if($count > $limit) {
				$str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
					<input type='hidden' class='noMorePosts' value='false'>";
			}
			else {
				$str .= "<input type='hidden' class='noMorePosts' value='true'><p style='color:white; text-align: center;'> No more posts to show! </p>";
			}

			echo $str;

		}
	}


	public function getSinglePost($post_id) {

		$userLoggedIn = $this->user_obj->getUsername();

		$opened_query = mysqli_query($this->con, "UPDATE notifications SET opened='yes' WHERE user_to='$userLoggedIn' AND link LIKE '%=$post_id'");

		$str = ""; //String to return 
		$data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND id='$post_id'");

		if(mysqli_num_rows($data_query) > 0) {


			$row = mysqli_fetch_array($data_query); 
				$id = $row['id'];
				$body = $row['body'];
				$added_by = $row['added_by'];
				$date_time = $row['date_added'];

				//Prepare user_to string so it can be included even if not posted to a user
				if($row['user_to'] == "none") {
					$user_to = "";
				}
				else {
					$user_to_obj = new User($this->con, $row['user_to']);
					$user_to_name = $user_to_obj->getFirstAndLastName();
					$user_to = "to <a href='" . $row['user_to'] ."'>" . $user_to_name . "</a>";
				}

				//Check if user who posted, has their account closed
				$added_by_obj = new User($this->con, $added_by);
				if($added_by_obj->isClosed()) {
					return;
				}

				$user_logged_obj = new User($this->con, $userLoggedIn);
				if($user_logged_obj->isFriend($added_by)){


					if($userLoggedIn == $added_by)
						$delete_button = "<button class='delete_button btn-danger' id='post$id'>X</button>";
					else 
						$delete_button = "";


					$user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
					$user_row = mysqli_fetch_array($user_details_query);
					$first_name = $user_row['first_name'];
					$last_name = $user_row['last_name'];
					$profile_pic = $user_row['profile_pic'];


					?>

								<script>
									function toggle<?php echo $id; ?>() {

										var target = $(event.target);
										if(!target.is("a")){
											var element = document.getElementById("toggleComment<?php echo $id; ?>");
							
											if(element.style.display == "block") 
												element.style.display = "none";
											else 
												element.style.display = "block";

										}


										
									}
								</script>
							
							<?php 
                            
							
							$comments_check = mysqli_query($this->con, "SELECT * FROM comments WHERE post_id='$id'");
							$comments_check_num = mysqli_num_rows($comments_check);

							//Timeframe
							$date_time_now = date("Y-m-d H:i:s");
							$start_date = new DateTime($date_time); // Time of post
							$end_date = new DateTime($date_time_now); // Current time
							$interval = $start_date->diff($end_date); // Difference between dates

							if ($interval->y >= 1) {
								$time_message = ($interval->y == 1) ? $interval->y . " year ago" : $interval->y . " years ago";
							} else if ($interval->m >= 1) {
								$days = ($interval->d == 0) ? " ago" : (($interval->d == 1) ? $interval->d . " day ago" : $interval->d . " days ago");
								$time_message = ($interval->m == 1) ? $interval->m . " month" . $days : $interval->m . " months" . $days;
							} else if ($interval->d >= 1) {
								$time_message = ($interval->d == 1) ? "Yesterday" : $interval->d . " days ago";
							}else if ($interval->h >= 1) {
								$time_message = ($interval->h == 1) ? $interval->h . " hour ago" : $interval->h . " hours ago";
							} else if ($interval->i >= 1) {
								$time_message = ($interval->i == 1) ? $interval->i . " minute ago" : $interval->i . " minutes ago";
							} else {
								$time_message = ($interval->s < 30) ? "Just now" : $interval->s . " seconds ago";
							}

							// content postari si comentarii
							$str .= "

							<section class='text-gray-400 body-font '>
								<div class='container px-5 py-0 mx-auto'>
									<div class='flex items-center  lg:w-3/5 mx-auto mb-10 bg-gray-900 h-auto rounded-3xl sm:flex-row flex-col my-8 status_post'>
									
										
										<div class='flex-grow sm:text-left p-4 mt-6 sm:mt-0' >
										
										<img src='$profile_pic' width='40' class='rounded-full align-top inline'>
										<a href='$added_by' class='font-semibold text-2xl pt-2 inline'> $first_name $last_name </a> &nbsp;&nbsp;&nbsp;&nbsp;<small class='text-gray-700'>$time_message</small>
										$delete_button

												   <div class='block my-4 post_body'>
													$body	
													</div>
													<p class='newsfeedPostOption inline' onClick='javascript:toggle$id()'>($comments_check_num)&nbsp;&nbsp;&nbsp;</p>
														<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-6 h-6 inline' onClick='javascript:toggle$id()'>
														<path stroke-linecap='round' stroke-linejoin='round' d='M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155' />
														</svg>
														
														

														<p class='text-left inline'> 
														<iframe src='./like.php?post_id=$id' id='comment_iframe'class='border-0 h-16 inline align-top' style='color:white'></iframe>
														</p>
														
	
							";
							// dimensionare sectiune de comentarii
                        if($comments_check_num>0){
							$str .="
									<div class='text-left inline scrollbar-container comment_section'> 
									<div class='post_comment' id='toggleComment$id' style='display: none;' >
														<iframe src='./comment_frame.php?post_id=$id' id=comment_iframe class='no-scrollbar overflow-y-auto h-screen hide-scrollbar border-0 w-full my-12 '> </iframe>
														</div>
													
														
													   
												
									    </div>
									  
									</div>
							    </div>
							</section>
							";
						} else 
						{
								$str .="
									<div class='text-left inline scrollbar-container comment_section'> 
									<div class='post_comment' id='toggleComment$id' style='display: none;' >
														<iframe src='./comment_frame.php?post_id=$id' id=comment_iframe class='no-scrollbar overflow-y-auto h-80 border-0 w-full my-12'> </iframe>
														</div>
													
														
													   
												
									    </div>
									</div>
							    </div>
							</section>
							";
						}
						// END sectiunde de dimensionare a sectiunii de comentarii

					  //  end content postari si comentarii	
					
					
				?>
				<!-- script for delete posts -->
				 <script>
					$(document).ready(function () {
						$('#post<?php echo $id;?>').on('click', function () {
							// Utilizează funcția nativă `confirm()` în locul Bootbox
							var result = confirm("Are you sure you want to delete this post?");

							// Dacă utilizatorul a apăsat pe "OK", execută cererea de ștergere
							if (result) {
							$.post("../includes/delete_post.php?post_id=<?php echo $id; ?>", { result: result })
								.done(function () {
								// Reîncarcă pagina după ce cererea s-a încheiat cu succes
								location.reload();
								})
								.fail(function () {
								// Afișează un mesaj de eroare în cazul în care cererea nu a reușit
								alert("A apărut o problemă în timpul ștergerii postării. Încearcă din nou.");
								});
							}
						});
					});


				 </script>
				 <!-- end script for delete posts -->

  

				<?php
				} else {
					echo "<p>You can not see this post because you are not friend with this user</p>";
					return;
				}

				

				

			}
			else {
				echo "<p>No post found link is broken</p>";
				return;
			}
			echo $str;
	}
	
}

?>