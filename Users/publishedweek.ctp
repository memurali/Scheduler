<?php  
	echo $this->Html->css('foundation.css');
	echo $this->Html->css('app.css');
	echo $this->Html->css('rich_calendar.css');
	
	echo $this->Html->script('jquery.js');
	echo $this->Html->script('app.js');
	echo $this->Html->script('common.js');	
	echo $this->Html->script('foundation.js'); 
	echo $this->Html->script('what-input.js');	
	echo $this->Html->script('rich_calendar.js');
	echo $this->Html->script('rc_lang_en.js');
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundation for Sites</title>
  </head>
  <body class="app">
    <div class="off-canvas-wrapper">
      <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
        <h1>Cold Stone</h1>
        <div class="logo" style="background: url(https://i.ya-webdesign.com/images/cold-stone-creamery-logo-png-17.png)"></div>
        <ul class="vertical menu">
          <li><?php echo $this->Html->link("Dashboard", array('controller' => 'users','action' => 'index'),array('class'=>'home active'));?></li>
		  <li><?php echo $this->Html->link("Published Schedules", array('controller' => 'users','action' => 'publishedday'),array('class'=>'published'));?></li>
		  <li><?php echo $this->Html->link("Scheduler", array('controller' => 'users','action' => 'schedulerday'),array('class'=>'scheduler'));?></li>
          <li><?php echo $this->Html->link("Store Hours", array('controller' => 'users','action' => 'storehours'),array('class'=>'store-hours'));?></li>
          <li><?php echo $this->Html->link("Employees", array('controller' => 'users','action' => 'employees'),array('class'=>'employees'));?></li>		  
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
				<h2 id="" class="date"><?php echo date('F d', strtotime($schedule_start)).'-'.date('F d,Y', strtotime($schedule_end));?></h2><!--February 10 – February 16, 2019--->
              </div>
              <div class="cell">
                <ul class="menu float-right callout cal-menu">
                  <li><a href="../users/publishedweek?date=<?php echo date('Y-m-d',strtotime('previous wednesday', strtotime($schedule_start)));?>" class="previous"></a></li>
                  <li><a class="calendar" onclick="show_cal('caldate');"></a></li>
                  <li><a href="../users/publishedweek?date=<?php echo date('Y-m-d',strtotime('next wednesday', strtotime($schedule_start)));?>" class="next"></a></li>
                  <li><a href="#">Today</a></li>
                  <li>
                    <select onchange="location = this.value;">
                      <option value="week">Week</option>
					  <option value="publishedday?<?php echo $schedule_start;?>">Day</option>
                    </select>
                  </li>
                  <li><a href="#" class="print"></a></li>
                </ul>
				<div  id="caldate"></div>
				<input type="hidden" id="calendardate" name="calendardate"  value="">
				<input type="hidden" id="pagename" name="pagename"  value="<?php echo $pagename;?>">
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
                      <?php echo $thead;?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
						if($error=='')
						{
							for($i=0;$i<=count($tbody);$i++)
							{
								echo $tbody[$i];
							} 
						}
						else
							echo $error;
					?>
                  </tbody>
                </table>
              </div>  
              <div class="callout table-footer">
                <div class="grid-x">
                  <div class="small-6 cell">
                    <p>Total Hours:<span id=""><?php echo $laborhours;?></span></p>
                  </div>
                  <div class="small-6 cell text-right">
                    <p><span class="published-date"><?php echo date('F d, Y',strtotime($lastpublishdate));?></span><a href="../users/schedulerday?selecteddate=<?php echo $schedule_start;?>" class="button small secondary">Edit</a></p>
                  </div>
                </div>
              </div>
            </div>  
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
