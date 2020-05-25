<!--
Identification : index.php
Author : group19  (Wonjin, Paul)
Purpose : This is the main page for the project.         
-->
<?php
	session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>INFO-5094 Project - HOME</title>
  <!--      <link rel="stylesheet" type="text/css" href="./css/main.css" >    -->
        <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
        <link href="./css/default.css" rel="stylesheet" type="text/css" media="all" />
        <link href="./css/fonts.css" rel="stylesheet" type="text/css" media="all" />
 
	</head>
	<body>
    <div id="header-wrapper" class="container">
        <div id="header" >
            <div id="menu">
                <ul>
                    <li class="current_page_item"><a href="#" class="icon icon-home"> HOME</a></li>
                </ul>
            </div>
            <div id="logo">
                <h1><a href="#">Microwave Paths</a></h1>
                <span>By Wonjin Song and Paul Taylor, LAMP2 - GROUP 19</span>
            </div>
        </div>
    </div>
    <div id="header-featured"><h2>Microwave Radio Path Data Analysis</h2></div>
    <div id="banner-wrapper" class="container">
        <div id="banner" >
            <p>Telecommunications engineering companies can use this application to design microwave communication systems using point-to-point communications operating at frequencies greater than 1GigaHertz (GHz). Microwave path data can be uploaded from properly formatted CSV files. Paths can be viewed, edited and calculated. Developed for  
                <strong>LAMP 5094</strong>
                course, as part of the Internet Applications and Web Development program at Fanshawe College 
                <a href="https://www.fanshawec.ca/" rel="nofollow">FANSHAWE</a>.
            </p>
        </div>
    </div>
    <div id="wrapper" class="container">
        <div id="featured-wrapper">
            <div id="featured" >
                <div class="column1"> 
                    <a href="part1/index.php?action=load">
                        <span class="icon icon-upload"></span>
                        <div class="title">
                            <h2>Upload Path Data File</h2>
                        </div>
                    </a>
                </div>
                <div class="column2"> 
                    <a href="part1/index.php?action=display">
                        <span class="icon icon-list"></span>
                        <div class="title">
                            <h2>Display Path Data</h2>
                        </div>
                    </a>
                </div>
                <div class="column3"> 
                    <a href="part1/index.php?action=reset">
                        <span class="icon icon-backward"></span>
                        <div class="title">
                            <h2>Reset Path Data</h2>
                        </div>
                    </a>
                </div>
                <div class="column4"> 
                    <a href="part2/index.php?action=edit">
                        <span class="icon icon-edit"></span>
                        <div class="title">
                            <h2>Edit Path Data</h2>
                        </div>
                    </a>
                </div>
                <div class="column5"> 
                    <a href="part3/index.php?action=calculate">
                        <span class="icon icon-signal"></span>
                        <div class="title">
                            <h2>Calculate Path Data</h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>