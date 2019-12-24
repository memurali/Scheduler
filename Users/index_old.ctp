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
                <h2 id="" class="date">Thursday - February 14, 2019<span class="label">Valentines Day</span></h2>
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
            <div class="schedule">
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
                      <th width="80%">
                        <div class="grid-x">
                          <div class="cell large-auto">12A</div>
                          <div class="cell large-auto">1A</div>
                          <div class="cell large-auto">2A</div>
                          <div class="cell large-auto">3A</div>
                          <div class="cell large-auto">4A</div>
                          <div class="cell large-auto">5A</div>
                          <div class="cell large-auto">6A</div>
                          <div class="cell large-auto">7A</div>
                          <div class="cell large-auto">8A</div>
                          <div class="cell large-auto">9A</div>
                          <div class="cell large-auto">10A</div>
                          <div class="cell large-auto">11A</div>
                          <div class="cell large-auto">12A</div>
                          <div class="cell large-auto">1P</div>
                          <div class="cell large-auto">2P</div>
                          <div class="cell large-auto">3P</div>
                          <div class="cell large-auto">4P</div>
                          <div class="cell large-auto">5P</div>
                          <div class="cell large-auto">6P</div>
                          <div class="cell large-auto">7P</div>
                          <div class="cell large-auto">8P</div>
                          <div class="cell large-auto">9P</div>
                          <div class="cell large-auto">10P</div>
                          <div class="cell large-auto">11P</div>
                        </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="staff">
                      <td id="" class="name">Allen Abesamis<span id="" class="total-hours">0</td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name">Ayden Abesamis<span id="" class="total-hours">0</td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name">Jerome de Joya<span id="" class="total-hours">0</td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name">Michael Urata<span id="" class="total-hours">0</td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name">Monica Abelardo<span id="" class="total-hours">0</td>
                      <td></td>
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
