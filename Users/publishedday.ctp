<?php  
	echo $this->Html->css('foundation.css');
	echo $this->Html->css('app.css');
	echo $this->Html->css('rich_calendar.css');
	
	echo $this->Html->script('jquery.js');
	echo $this->Html->script('app.js');
	echo $this->Html->script('common.js');	
	echo $this->Html->script('rich_calendar.js');
	echo $this->Html->script('rc_lang_en.js');
	echo $this->Html->script('foundation.js'); 
	echo $this->Html->script('what-input.js');	  
?>
<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundation for Sites</title>
	<script>
	$(document).ready(function() {
		set_individual_tothrs_published();
		businesshrs();
	});
	</script>
  </head>
  <body class="app">
    <div class="off-canvas-wrapper">
      <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
        <h1>Cold Stone</h1>
        <div class="logo" style="background: url(https://i.ya-webdesign.com/images/cold-stone-creamery-logo-png-17.png)"></div>
        <ul class="vertical menu">
          <li><?php echo $this->Html->link("Dashboard", array('controller' => 'users','action' => 'index'),array('class'=>'home'));?></li>
		  <li><?php echo $this->Html->link("Published Schedules", array('controller' => 'users','action' => 'publishedday'),array('class'=>'published active'));?></li>
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
            <div class="grid-x grid-padding-x">
              <div class="large-6 cell">
                <h2 class="date"><span id="showdate"></span><span class="label"></span></h2>
              </div>
              <div class="large-6 cell">
                <div class="grid-x">
                  <div class="large-12 cell">    
                    <ul class="menu float-right callout cal-menu">
                      <li><a id="published_prev" class="previous"></a></li>
                      <li><a class="calendar" onclick="show_cal('caldate');"></a></li>
                      <li><a onclick="savefunc();" class="next"></a></li>
                      <li><a id="today">Today</a></li>
                      <li>
                        <select onchange="location = this.value;">
                          <option value="publishedday">Day</option>
                          <option id="weekopt" value='publishedweek'>Week</option>
                        </select>
                      </li>
                      <li><a href="#" class="print"></a></li>
                    </ul>
                    <ul class="menu float-right switch-24">
                      <li>
                        <div class="switch large">
                          <input class="switch-input" id="yes-no" type="checkbox" name="exampleSwitch">
                          <label class="switch-paddle" for="yes-no">
                            <span class="show-for-sr">24h</span>
                            <span class="switch-active" aria-hidden="true">24h</span>
                            <span class="switch-inactive" aria-hidden="true"></span>
                          </label>
                        </div>
                      </li>
                    </ul>
					<div  id="caldate"></div>
					<input type="hidden" id="calendardate" name="calendardate"  value="">
                  </div>
                </div>
              </div>
            </div>
            <div class="schedule">
              <div class="grid-x">
                <table class="unstriped">
                  <thead>
                    <tr>
                      <th width="15%" class="staff-name">
                        <select>
                          <option value="firstname">First Name</option>
                          <option value="lastname">Last Name</option>
                          <option value="starttime">Start Time</option>
                        </select>
                      </th>
                      <th width="80%">
                        <div class="grid-x mode-24" id='businesshr'>
                         <!--- <div class="cell large-auto">12A</div>
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
                          <div class="cell large-auto">11P</div>---->
                        </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="table_body">
					<?php 
						for($i=0;$i<=count($create_tablebody);$i++)
						{
							echo $create_tablebody[$i];
						} 
					?>
                  </tbody>
                </table>
              </div>  
              <div class="callout table-footer">
                <div class="grid-x">
                  <div class="small-6 cell">
                    <p>Total Hours:<span id="all_tot_hrs"><?php echo $totalhour;?></span></p>
                  </div>
                   <div class="small-6 cell text-right">
                    <p><span class="published-date"><?php echo date('F d, Y', strtotime($lastpublishdate)); ?></span><a id='edithref' href="../users/schedulerday?selecteddate=<?php echo $dateval;?>" class="button small secondary">Edit</a></p>
					<input type='hidden' name='pagename' id='pagename' value="<?php echo $pagename;?>">
					<input type="hidden" id="currentdate" name="currentdate" value="<?php echo $date; ?>" />
					<input type="hidden" id="action" name="action" value="next" />
					<input type='hidden' name='schedule_start' id='schedule_start' value="<?php echo $schedule_start;?>"> 
					<input type="hidden" id="todaydate" name="todaydate" value="<?php echo $date; ?>" />
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
