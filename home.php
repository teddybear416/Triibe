<?php
   include_once "connection.php";
   include "like.php";
   session_start();
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <link rel="stylesheet" href="bootstrap-css/bootstrap.min.css" />
      <link rel="stylesheet" href="bootstrap-css/all.min.css" />
      <link rel="stylesheet" href="node_modules/animate.css/animate.css" />
      <link id="theme" rel="stylesheet" href="bootstrap-css/light-home.css" />
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="icon" href="Design/Image/whiteLogo.svg" type="image/x-icon">
      <title>Triibe Home</title>
   </head>
   <body>
      <nav>
         <div class="nav-left">
            <div class="box">
               <img
                  src="Design/Image/home-images/images/logo.svg"
                  alt="logoLight"
                  class="logoLight"
                  />
               <img
                  src="Design/Image/home-images/images/logo2.svg"
                  alt="logoDark"
                  class="logoDark"
                  />
               <p>Triibe</p>
            </div>
            <div class="search-box">
               <img src="Design/Image/home-images/images/Search-Icon.svg" alt="search" />
               <input type="text" placeholder="Search" />
            </div>
         </div>
         <div class="nav-right">
            <ul>
               <li>
                  <img class="SettingsIcon-Light" src="Design/Image/home-images/images/Settings-icon.svg" alt="settingIcon"/>
                  <img class="SettingsIcon-Dark" src="Design/Image/home-images/images/Settings-icon2.svg" alt="settingIcon"/>
               </li>
               <li>
                  <img class="mapIcon-Light" src="Design/Image/home-images/images/mapIcon.svg" alt="mapIcon"
                     />
                  <img class="mapIcon-Dark" src="Design/Image/home-images/images/mapIcon2.svg" alt="mapIcon"
                     />
               </li>
               <li>
                  <img class="themeLight" src="Design/Image/home-images/images/theme-light.svg" alt="themeLight" />
                  <img class="themeDark" src="Design/Image/home-images/images/theme-dark.svg" alt="themeDark" />
               </li>
               <li>
                  <img class="notificationIcon-light" src="Design/Image/home-images/images/notification-logo.svg" alt="notificationIcon" />
                  <img class="notificationIcon-dark" src="Design/Image/home-images/images/notification-logo2.svg" alt="notificationIcon1" />
               </li>
               <li>
                  <img
                     class="chatLight"
                     src="Design/Image/home-images/images/chat-icon.svg"
                     alt="image"
                     />
                  <img
                     class="chatDark"
                     src="Design/Image/home-images/images/chat-icon2.svg"
                     alt="image"
                     />
               </li>
            </ul>
            <div class="nav-user-icon online">
               <img src="<?php echo $_SESSION["img_name"];?>" alt="usrImg"/>
               <div class="name"><?php echo $_SESSION["std_fname"];?></div>
            </div>
         </div>
      </nav>
      <div class="container1">
         <div class="left-sidebar">
            <div class="group-list">
               <a href="#">
               <img class="pagesIcon-Light" src="Design/Image/home-images/images/pages-icon.svg" alt="pages-icon">
               <img class="pagesIcon-Dark" src="Design/Image/home-images/images/pages-icon2.svg" alt="pages-icon2">
               <span>Pages</span>
               </a>
               <a href="#">
               <img class="Groups-Light" src="Design/Image/home-images/images/Groups.svg" alt="">
               <img class="Groups-Dark" src="Design/Image/home-images/images/Groups2.svg" alt=""><span>Groups</span>
               </a>
               <div class="group-page">
                  <p>Friends</p>
                  <?php $sql = "SELECT * FROM friends WHERE user_id = '" . $_SESSION["login_user"] . "'"; // select all friends of the user from the database
                     $result = mysqli_query($conn, $sql); // execute the query
                     if (mysqli_num_rows($result) > 0){ // if there are any friends
                         while ($row = mysqli_fetch_assoc($result)){ //print all friends
                             $sql1 = "SELECT * FROM student WHERE std_id = '" . $row["friend_id"] . "'"; // select all friends of the user from the database
                             $result1 = mysqli_query($conn, $sql1); // execute the query
                             if (mysqli_num_rows($result1) > 0){ // if there are any friends
                                 while ($row1 = mysqli_fetch_assoc($result1)){ //print all friends
                                     $imgid = $row1["img_id"]; // get the image id of the friend
                                     $sqlimg = "SELECT * FROM img WHERE img_id = '$imgid'"; // select the image of the friend
                                     $resultimg = mysqli_query($conn, $sqlimg); // execute the query
                                     $rowimg = mysqli_fetch_assoc($resultimg); // get the image of the friend
                                     echo "<a href='#'><img src='" . $rowimg["img_name"] . "' alt=''/>" . $row1["std_fname"] . " " . $row1["std_lname"] . "</a>"; // print the friend
                                 }
                             }
                         }
                     }?>
               </div>
            </div>
         </div>
         <div class="main-content">
            <div class="story-gallery">
               <div class="story">
                  <img src="Design/Image/home-images/images/upload.png" alt="">
                  <p><?php
                     echo $_SESSION["std_fname"] . " " . $_SESSION["std_lname"]; // print the name of the user
                     ?></p>
               </div>
               <?php $sql = "SELECT * FROM friends WHERE user_id = '" . $_SESSION["login_user"] . "'"; // select all friends of the user from the database
                  $result = mysqli_query($conn, $sql); // execute the query
                  if (mysqli_num_rows($result) > 0){ // if there are any friends
                      while ($row = mysqli_fetch_assoc($result)){ //print all friends
                          $sql1 = "SELECT * FROM student WHERE std_id = '" . $row["friend_id"] . "'"; // select all friends of the user from the database
                          $result1 = mysqli_query($conn, $sql1); // execute the query
                          if (mysqli_num_rows($result1) > 0){ // if there are any friends
                              while ($row1 = mysqli_fetch_assoc($result1)){ //print all friends
                                  echo "<div class='story'><img src='Design/Image/home-images/images/upload.png' alt=''><p>" . $row1["std_fname"] . " " . $row1["std_lname"] . "</p></div>"; // print the friend
                              }
                          }
                      }
                  }?>
            </div>
            <div class="write-post-container">
               <div class="user-profile">
                  <img src="<?php echo $_SESSION["img_name"]; ?>" alt="">
                  <div class="write-post-input" >
                     <textarea class="write-post" rows="3" placeholder="What`s on your mind, <?php echo $_SESSION["std_fname"]; ?>"></textarea>
                  </div>
               </div>
               <?php 
               $likenum =0;

               $sql = "SELECT * FROM post "; // select all posts from the database
                  $result = mysqli_query($conn, $sql); // execute the query
                  if (mysqli_num_rows($result) > 0){ // if there are any posts
                      while ($row = mysqli_fetch_assoc($result)){ //print all posts
                          $sql1 = "SELECT * FROM student WHERE std_id = '" . $row["author"] . "'"; // select all friends of the user from the database
                          $sql2 = "SELECT * FROM img WHERE img_id = '" . $row["img_id"] . "'";  // select the image of the post
                          $result1 = mysqli_query($conn, $sql1); // execute the query
                          $result2 = mysqli_query($conn, $sql2); // execute the query

                          $sqllikenum = "SELECT COUNT(*) FROM post_likes WHERE post_id = '" . $row["post_id"] . "'";
                          $resultlikenum = mysqli_query($conn, $sqllikenum);
                          $rowlikenum = mysqli_fetch_assoc($resultlikenum);
                          $likenum = $rowlikenum["COUNT(*)"];
                          
                          if (mysqli_num_rows($result1) > 0){ // if there are any friends
                              while ($row1 = mysqli_fetch_assoc($result1)){ //print all friends
                                  $imgid = $row1["img_id"]; // get the image id of the friend
                                  $sqlimg = "SELECT * FROM img WHERE img_id = '$imgid'"; // select the image of the friend
                                  $resultimg = mysqli_query($conn, $sqlimg); // execute the query
                                  $rowimg = mysqli_fetch_assoc($resultimg); // get the image of the friend
                            echo "
                            <div class= 'post'>
                              <div class='top-post'>
                                <div class='left-post'>
                                  <div class='name-photo'>
                                <img src='" . $rowimg["img_name"] . "' alt=''>
                                  <div class='name'>" . $row1["std_fname"] . " " . $row1["std_lname"] . "
                              </div>
                              </div>
                                <div class='inside-top'>
                                  " . $row["created_date"] . "
                                <img src='Design/Image/home-images/images/ball.png' alt=''>
                              </div>
                              </div>
                              <div class='right-post'>
                                <img src='Design/Image/home-images/images/Dots.png' alt=''>
                              </div>
                              </div>
                              <div class='mid-post'>
                                <p>" . $row["content"] . "</p>
                              </div> ";
                            }
                          }

                          if (mysqli_num_rows($result2) > 0){ // if there are any friends
                              while ($row2 = mysqli_fetch_assoc($result2)){ //print all friends
                          echo "<div class='end-post>
                          <div class='content-end'>
                          <div class='photo-post'>
                          <img src='" . $row2["img_name"] . "' alt=''>
                        </div>
                          </div>

                          <div class='likes'>
                            <div class='like'>
                              <img src='Design/Image/home-images/images/like1.png' alt=''>
                              <p class='like1' >like " .$likenum."</p>
                            </div>
                            <div class='comment'>
                              <img src='Design/Image/home-images/images/comment.png' alt=''>
                              <p>comment</p>
                            </div>
                            <div class='share'>
                              <img src='Design/Image/home-images/images/share2.png'' alt=''>
                              <p>share</p>
                            </div>
                            <div class='save'>
                              <img src='Design/Image/home-images/images/save.png' alt=''>
                              <p>save</p>
                            </div>
                        </div>
                        </div>
                        <script>
                        document.querySelector('.like1').addEventListener('click',function () {
                         this.classList.toggle('liked');
                      });
                        </script>";
                            }
                          }
                          else{
                              echo "<div class='end-post>
                              <div class='content-end'>
                              </div>
                              <div class='likes'>
                                 <div class='like'>
                                    <img src='Design/Image/home-images/images/like1.png' alt=''>
                                    <p>like ".$likenum."</p>
                                 </div>
                                 <div class='comment'>
                                    <img src='Design/Image/home-images/images/comment.png' alt=''>
                                    <p>comment</p>
                                 </div>
                                 <div class='share'>
                                    <img src='Design/Image/home-images/images/share2.png'' alt=''>
                                    <p>share</p>
                                 </div>
                                 <div class='save'>
                                    <img src='Design/Image/home-images/images/save.png' alt=''>
                                    <p>save</p>
                                 </div>
                              </div>
                              </div>";
                          }
                      }
                  }
                  ?>
               </div>
         </div>
          <div class="right-sidebar">
            <div class="imp-link">
               <a href="#">
               <img class="savedPosts-Light" src="Design/Image/home-images/images/saved-posts.svg" alt="" />
               <img class="savedPosts-Dark" src="Design/Image/home-images/images/saved-posts2.svg" alt="" />
               <span> Saved posts</span>
               </a>
               <a href="#">
               <img class="marketIcon-Light" src="Design/Image/home-images/images/market-Icon.svg" alt=""/>
               <img class="marketIcon-Dark" src="Design/Image/home-images/images/market-Icon2.svg" alt=""/>
               <span>Market</span>
               </a>
               <a href="#">
               <img class="housingIcon-Light" src="Design/Image/home-images/images/housing-icon.svg" alt=""/>
               <img class="housingIcon-Dark" src="Design/Image/home-images/images/housing-icon2.svg" alt=""/>
               <span>Housing</span></a>
               <a href="#">
               <img class="elearningIcon-Light" src="Design/Image/home-images/images/elearning-icon.svg" alt=""/>
               <img class="elearningIcon-Dark" src="Design/Image/home-images/images/elearning-icon2.svg" alt=""/>
               <span>E-Learning</span>
               </a>
               <a href="#">
               <img class="infoIcon-Light" src="Design/Image/home-images/images/Info-Icon.svg" alt=""/>
               <img class="infoIcon-Dark" src="Design/Image/home-images/images/Info-Icon2.svg" alt=""/>
               <span>Student information system</span>
               </a>
               <a href="#">
               <img class="regIcon-Light" src="Design/Image/home-images/images/RegIcon.svg" alt=""/>
               <img class="regIcon-Dark" src="Design/Image/home-images/images/RegIcon2.svg" alt=""/>
               <span>Student registration system</span>
               </a>
               <a href="#">
               <img class="otherLinksIcon-Light" src="Design/Image/home-images/images/otherLinks-icon.svg"alt=""/>
               <img class="otherLinksIcon-Dark" src="Design/Image/home-images/images/otherLinks-icon2.svg"alt=""/>
               <span class="other-link">Other links</span>
               <img class="dropDownIcon-Light" src="Design/Image/home-images/images/dropDown-icon.svg" alt="">
               <img class="dropDownIcon-Dark" src="Design/Image/home-images/images/dropDown-icon2.svg" alt="">
               </a>
            </div>
          </div>
      </div>
      <script src="bootstrap-js/bootstrap.bundle.min.js"></script>
      <script src="bootstrap-js/all.min.js"></script>
      <script src="node_modules/jquery/dist/jquery.min.js"></script>
      <script type="module" src="bootstrap-js/home.js" defer></script>
   </body>
</html>