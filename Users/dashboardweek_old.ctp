<?php
   echo $this->Html->css("app.css");
   echo $this->Html->css("foundation.css");
   echo $this->Html->script('jquery');
   echo $this->Html->script('app');
   echo $this->Html->script('what-input');
   echo $this->Html->script('foundation');    
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundation for Sites</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
    <div class="off-canvas-wrapper">
      <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
        <div class="logo">Scheduler</div>
        <ul class="vertical menu">
          <li><a href="#">Published Schedules</a></li>
          <li><a href="#">Scheduler</a></li>
          <li><a href="#">Store Hours</a></li>
          <li><a href="#">Employees</a></li>
        </ul>
      </div>
      <div class="off-canvas-content" data-off-canvas-content>
        <div class="grid-x grid-padding-x">
          <div class="top-bar">
            <div class="top-bar-left">
              <ul class="menu">
                <li><button class="menu-icon" type="button" data-toggle="offCanvas"></button></li>
                <li><h1>Published Schedules</h1></li>
              </ul>
            </div>
            <div class="top-bar-right">
             
            </div>
          </div>
        </div>
        <div class="content">
          <div class="grid-container">
            <div class="grid-x grid-padding-x small-up-2">
              <div class="cell">
                <h2 id="" class="date">February 10 – February 16, 2019</h2>
              </div>
              <div class="cell">
                <ul class="menu float-right callout cal-menu">
                  <li>
                    <select>
                      <option value="day">Store Hours</option>
                      <option value="week">24 Hours</option>
                    </select>
                  </li>
                  <li><a href="#" class="previous"></a></li>
                  <li><a href="#" class="calendar"></a></li>
                  <li><a href="#" class="next"></a></li>
                  <li><a href="#">Today</a></li>
                  <li>
                    <select>
                      <option value="day">Day</option>
                      <option value="week">Week</option>
                    </select>
                  </li>
                  <li><a href="#" class="print"></a></li>
                </ul>
              </div>
            </div>
            <div class="schedule week">
              <div class="grid-x">
                <table class="unstriped">
                  <thead>
                    <tr>
                      <th width="20%" class="staff-name">
                        <select>
                          <option value="firstname">First Name</option>
                          <option value="lastname">Last Name</option>
                          <option value="lastname">Start Time</option>
                        </select>
                      </th>
                      <th width="50" class="past"><a href="#">SUN 10</a></th>
                      <th width="50" class="past"><a href="#">MON 11</a></th>
                      <th width="50" class="past"><a href="#">TUE 12</a></th>
                      <th width="50" class="past"><a href="#">WED 13</a></th>
                      <th width="50" class="today"><a href="#">THU 14<span class="day-note">Valentines Day</span></a></th>
                      <th width="50"><a href="#">FRI 15</a></th>
                      <th width="50"><a href="#">SAT 16</a></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="staff">
                      <td id="" class="name">Allen Abesamis<span id="" class="total-hours">40</span></td>
                      <td class="working past"><a href="#">9AM - 12PM<span class="split">1AM - 5PM</a></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="available current"><a href="#">9AM - 5PM</a></td>
                      <td class="available"><a href="#">9AM - 5PM</a></td>
                      <td class="working"><a href="#">9AM - 5PM</a></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name">Ayden Abesamis<span id="" class="total-hours">0</span></td>
                      <td class="off past"><a href="#"></a></td>
                      <td class="off past"><a href="#"></a></td>
                      <td class="off past"><a href="#"></a></td>
                      <td class="off past"><a href="#"></a></td>
                      <td class="off"><a href="#"></a></td>
                      <td class="off"><a href="#"></a></td>
                      <td class="off"><a href="#"></a></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name">Jerome de Joya<span id="" class="total-hours">32</span></td>
                      <td class="available past"><a href="#">9AM - 5PM</a></td>
                      <td class="available past"><a href="#">9AM - 5PM</a></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="working"><a href="#">9AM - 5PM</a></td>
                      <td class="working"><a href="#">9AM - 5PM</a></td>
                      <td class="working"><a href="#">9AM - 5PM</a></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name">Michael Urata<span id="" class="total-hours">32</span></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="available past"><a href="#">9AM - 5PM</a></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="available past"><a href="#">9AM - 5PM</a></td>
                      <td class="working"><a href="#">9AM - 5PM</a></td>
                      <td class="working"><a href="#">9AM - 5PM</a></td>
                      <td class="working"><a href="#">9AM - 5PM</a></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name">Monica Abelardo<span id="" class="total-hours">32</span></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="available past"><a href="#">9AM - 5PM</a></td>
                      <td class="working past"><a href="#">9AM - 5PM</a></td>
                      <td class="working"><a href="#">9AM - 5PM</a></td>
                      <td class="working"><a href="#">9AM - 5PM</a></td>
                      <td class="available"><a href="#">9AM - 5PM</a></td>
                    </tr>
                  </tbody>
                </table>
              </div>  
              <div class="callout table-footer">
                <div class="grid-x">
                  <div class="small-6 cell">
                    <p>Total Hours:<span id=""></span></p>
                  </div>
                   <div class="small-6 cell text-right">
                    <p><span class="published-date">February 3, 2018 </span><a href="#" class="button small secondary">Edit</a></p>
                  </div>
                </div>
              </div>
            </div>  
          </div>
        </div>
      </div>
    </div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/what-input.js"></script>
    <script src="js/vendor/foundation.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
