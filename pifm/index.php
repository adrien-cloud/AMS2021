<html>
  <!--
    Copyright 2014 Emil Suleymanov <suleymanovemil8@gmail.com>
    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at
         http://www.apache.org/licenses/LICENSE-2.0
    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
  -->
   <head>
      <meta charset="UTF-8">
      <title>RPi FM</title>
      <script type="text/javascript" src="js/jquery.min.js"></script>   
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/flat-ui.css">
      <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
      <link href="css/main.css" rel="stylesheet">
      <style>
        p.ex1 {
          margin-bottom: -0.5%;
          font-size: 14px;
          color: rgb(45, 45, 45);
        }
        p.ex2 {
          margin-bottom: 0.5%;
          margin-left: 1%;
          margin-top: 1%;
          font-size: 14px;
          font-weight: bold;
          color: rgb(45, 45, 45);
        }
        table, th, td {
          margin-top: 1%;
          margin-left: 1%;
          margin-right: 1%;
          border: 1px solid black;
        }
        th, td {
          padding: 15px;
        }
        li {
          margin-left: 1%;
        }
        img.ex1 {
          margin-right: 10%;
        }
        table.ex1, th.ex1, td.ex1 {
          margin-top: 1%;
          margin-left: 1%;
          margin-right: 1%;
          border: 0px solid black;
        }
        table.ex2, th.ex2, td.ex2 {
        	background-color:#7a7a7a;
        	-moz-border-radius:14px;
        	-webkit-border-radius:14px;
        	border-radius:14px;
        	border:1px solid #ffffff;
        	display:inline-block;
        	cursor:pointer;
                color:#ffffff;
        	font-family:arial;
        	font-size:14px;
		padding:8px 15px;
		text-decoration:none;
		text-shadow:0px 1px 0px #2f6627;
        }
        .myButton {
        	background-color:#7a7a7a;
        	-moz-border-radius:14px;
        	-webkit-border-radius:14px;
        	border-radius:14px;
        	border:1px solid #ffffff;
        	display:inline-block;
        	cursor:pointer;
                color:#ffffff;
        	font-family:arial;
        	font-size:14px;
		padding:8px 15px;
		text-decoration:none;
		text-shadow:0px 1px 0px #2f6627;
	}
        .myButton2 {
        	background-color:#A30000;
        	-moz-border-radius:14px;
        	-webkit-border-radius:14px;
        	border-radius:14px;
        	border:1px solid #ffffff;
        	display:inline-block;
        	cursor:pointer;
                color:#ffffff;
        	font-family:arial;
        	font-size:14px;
		padding:8px 15px;
		text-decoration:none;
		text-shadow:0px 1px 0px #2f6627;
	}
        .myButton3 {
        	background-color:#007A00;
        	-moz-border-radius:14px;
        	-webkit-border-radius:14px;
        	border-radius:14px;
        	border:1px solid #ffffff;
        	display:inline-block;
        	cursor:pointer;
                color:#ffffff;
        	font-family:arial;
        	font-size:14px;
		padding:8px 15px;
		text-decoration:none;
		text-shadow:0px 1px 0px #2f6627;
                margin-left:30px;
                width:47.3%
	}
	.myButton:hover {
		background-color:#9c9c9c;
	}
	.myButton:active {
		position:relative;
		top:1px;
	}

      </style>
      <script type="text/javascript">
         function loadlink(){
           $('#list').load('list.php',function () {
            $(this).unwrap();
           });
           $('#current').load('current.php',function () {
            $(this).unwrap();
           });
         }

         function loadlink2(){
           $('#frequency').load('frequency.php',function () {
            $(this).unwrap();
           });
         }

         function onClick(path){
           var req = new XMLHttpRequest();
           req.open('GET', 'load_me.php?path=' + path, false);
           req.send(null);
           if(req.status == 200)
              alert(req.responseText);
         }

         function onClickF(){
           var req = new XMLHttpRequest();
           req.open('GET', 'load_frequency.php?value=' + document.getElementById('freq').value, false);
           req.send(null);
           if(req.status == 200)
              alert(req.responseText);
         }

         function onClickRemove(path){
           var req = new XMLHttpRequest();
           req.open('GET', 'rm.php?path=' + path, false);
           req.send(null);
           if(req.status == 200)
              alert(req.responseText);
         }
         
         loadlink();
         
         setInterval(function(){
         loadlink()
         }, 1000);
         
         setInterval(function(){
         loadlink2()
         }, 50000);
      </script>
   </head>
   <body style="padding:0;">

      <div style="background-color:#F2F2F2;">
         <div style="color:#585858; margin:20px; padding:20px;">
            <p class="ex2">Current:</p>
            <div id="current">
               <script type="text/javascript">
                  $('#current').load('current.php',function () {
                   $(this).unwrap();
                  });
               </script>
            </div>
         </div>
      </div>
  
      <div style="background-color:#F2F2F2;">
         <div style="color:#585858; margin:20px; padding:20px;">
            <p class="ex2">Frequency:</p>
            <div id="frequency">
               <script type="text/javascript">
                  $('#frequency').load('frequency.php',function () {
                   $(this).unwrap();
                  });
               </script>
            </div>
         </div>
      </div>
            

      <p class="ex2">Upload:</p>
      <form class="myButton3" action="upload.php" method="post" enctype="multipart/form-data">
           Select File:
           <input style="width:100%" name="uploaded_file" type="file"/><br/>
           <input style="width:100%" type="submit" value="Upload"/>
       </form>

      <div style="background-color:#F2F2F2;">
         <div style="color:#585858; margin:20px; padding:20px;">
            <p class="ex2">Playlist:</p>
            <div id="list"">
               <script type="text/javascript">
                  $('#list').load('list.php',function () {
                   $(this).unwrap();
                  });
               </script>
            </div>            
         </div>
      </div>

     
                <div class="row">
			<div id="footer-language-bar" class="col-lg-12">
                                <p class="ex1">Copyright © 2014-2015 Emil Suleymanov</p>
	        	</div>
		</div>
             </center>
            </div>
   </body>
</html>
