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
	<div class="reveal tiny text-center" id="slider_popup" data-reveal>
      <h2>Please select any one availability</h2>
	  <div class="button-group expanded">
        <a class="button" onclick="popupclose();">Ok</a>
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true" id='popup_close'>&times;</span>
      </button>
    </div>
    <div class="reveal tiny text-center" id="scheduleConfirmation" data-reveal>
      <h2>Ready to publish?</h2>
      <p class="week-schedule" id='popup_date'></p>
      <div class="button-group expanded">
        <a class="button secondary" id='savedraft' data-close>Save To Draft</a>
        <a class="button" id='btnpublish' data-close>Publish</a>
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
	<div class="reveal tiny text-center" id="PrevsaveConfirmation" data-reveal>
      <h2>Ready to Save?</h2>
	  <div></div>
      <div class="button-group expanded">
        <a class="button secondary" id='prevsaveyes'  data-close>Yes</a>
        <a class="button" id='saveno' data-close>No</a>
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
	<div class="reveal tiny text-center" id="NxtsaveConfirmation" data-reveal>
      <h2>Ready to Save?</h2>
	  <div class="button-group expanded">
        <a class="button secondary" id='nxtsaveyes' onclick="savefunc();" data-close>Yes</a>
        <a class="button" id='saveno' data-close>No</a>
      </div>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
	<style>
		.content .schedule .staff .availability .split
		{
			background:#1779ba;
		}
	</style>
	<script>
	$(document).ready(function() {
		var i =1;
		$.each($('.reveal-overlay'), function (index, value) {
			$(this).attr('id', 'popup'+i);
			i++;
		});
		set_individual_tothrs();
		businesshrs();
		$('.slider').addClass('disabled');
	});
	</script>
  </head>
  <body id="scheduler" class="app">
    <div class="off-canvas-wrapper">
      <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
        <h1>Cold Stone</h1>
        <div class="logo" style="background: url(https://i.ya-webdesign.com/images/cold-stone-creamery-logo-png-17.png)"></div>
        <ul class="vertical menu">
          <li><?php echo $this->Html->link("Dashboard", array('controller' => 'users','action' => 'index'),array('class'=>'home'));?></li>
		  <li><?php echo $this->Html->link("Published Schedules", array('controller' => 'users','action' => 'publishedday'),array('class'=>'published'));?></li>
		  <li><?php echo $this->Html->link("Scheduler", array('controller' => 'users','action' => 'schedulerday'),array('class'=>'scheduler active'));?></li>
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
                <li><h1>Scheduler</h1></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="grid-container">
            <div class="grid-x grid-padding-x data-pull" data-equalizer data-equalize-on="medium" id="test-eq">
              <div class="large-3 cell">
                <div class="callout projected-sales" data-equalizer-watch>
                  <form>
                    <div class="grid-x grid-padding-x">
                      <div class="medium-12 cell">
                        <label>Projected Sales Of The Week
                          <div class="input-group">
                            <span class="input-group-label">$</span>
                            <input id="proj_sale"class="input-group-field" type="number">
                          </div>
                        </label>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="large-9 cell">
                <div class="grid-x grid-padding-x">
                  <div class="small-9 cell">
                    <div class="callout data" data-equalizer-watch>
                      <div class="grid-x grid-padding-x">
                        <div class="medium-12 cell">
                          <label>Today</label>
                          <div class="grid-x grid-padding-x small-up-3">
                            <div class="cell hours">
                              <p><span id='labor-hour'><?php echo $totalhour;?><span></p>
                            </div>
                            <div class="cell labor-cost">
                              <p><span id='labor-cost'><?php echo $totalcost;?></span></p>
                            </div>
                            <div class="cell labor-percentage">
                              <p><span id='labor-percentage'></span></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="small-3 cell">
                    <div class="callout" data-equalizer-watch>
                      <div class="grid-x grid-padding-x">
                        <div class="large-12 cell">
                          <label><?php echo date("Y",strtotime("-1 year")).' Sale';?></label>
                          <div class="grid-x grid-padding-x small-up-1">
                            <div class="cell last-year">
                              <p>12345</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="grid-x">
              <div class="large-12 cell">
                <h2 class="date"><span id="showdate"></span><span class="label"></span></h2>
              </div>
            </div> 
            <form>
              <div class="scheduler-menu">
                <div class="grid-x">
                  <div class="large-9 cell">
                    <div class="grid-x">
                      <div class="small-3 cell">
                        <div class="input-group search-staff">
                          <input class="input-group-field" type="text" id="search_name" value="">
                          <div class="input-group-button">
                            <a href="#" class="button"></a>
                          </div>
                        </div>
                      </div>
                      <div class="small-9 cell">
                        <div class="grid-x grid-padding-x">
                          <div class="small-8 cell">
                            <div class="slider" data-slider data-initial-start="0" data-initial-end="100">
                              <span class="slider-handle" data-slider-handle role="slider" tabindex="1"></span>
                              <span class="slider-fill" data-slider-fill></span>
                              <span class="slider-handle" data-slider-handle role="slider" tabindex="1"></span>
                              <input id='slider_start' type="hidden">
                              <input id='slider_end' type="hidden">
                            </div>
                          </div>
                          <div class="small-4 cell">
                            <ul class="menu float-left callout scheduling-menu">
                              <li><a onclick='split();'>Split</a></li>
                              <li><a id="clear_btn">Clear</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="large-3 cell">
                    <ul class="menu float-right callout cal-menu">
                      <li><a id="prevbtn" class="previous" data-open="PrevsaveConfirmation"></a></li>
					  <li><a class="calendar" onclick="show_cal('caldate');"></a></li>
					  <li><a  name="nextbtn" data-open="NxtsaveConfirmation" class="next"></a></li>
                      <li><a id="today" name="today">Today</a></li>
                      <li>
                        <select onchange='location=this.value'>
                          <option value="day">Day</option>
                          <option value="../?selecteddate=<?php echo $dateval;?>">Week</option>
                        </select>
                      </li>
					</ul>
					<div  id="caldate"></div>
					<input type="hidden" id="calendardate" name="calendardate"  value="">
					<ul class="menu float-right switch-24">
                      <li>
                        <div class="switch large">
                          <input class="switch-input" id="yes-no" type="checkbox" name="yes-no">
                          <label class="switch-paddle" for="yes-no">
                            <span class="show-for-sr">24h</span>
                            <span class="switch-active" aria-hidden="true">24h</span>
                            <span class="switch-inactive" aria-hidden="true"></span>
                          </label>
                        </div>
                      </li>
                    </ul>
				  </div>  
				</div>
              </div>
            </form>
			<input type="hidden" id="currentdate" name="currentdate" value="<?php echo $date; ?>" />
			<input type="hidden" id="action" name="action" value="next" />
			<input type="hidden" id="save_scheduled_array" name="save_scheduled_array" value="" />
			<input type="hidden" id="todaydate" name="todaydate" value="<?php echo $date; ?>" />
            <div class="table-header">
              <div class="grid-x">
                <table class="unstriped">
                  <thead>
                    <tr>
                      <th width="15%" class="staff-name">
                        <select class="select">
                          <option value="firstname">First Name</option>
                          <option value="lastname">Last Name</option>
                          <option value="starttime">Start Time</option>
                        </select>
                      </th>
                      <th width="80%">
                        <div class="grid-x work-hours-mode" id='businesshr'>
                          <!---<div class="cell large-auto">9A</div>
                          <div class="cell large-auto">10A</div>
                          <div class="cell large-auto">11A</div>
                          <div class="cell large-auto">12A</div>
                          <div class="cell large-auto">1P</div>
                          <div class="cell large-auto">2P</div>
                          <div class="cell large-auto">3P</div>
                          <div class="cell large-auto">4P</div>
                          <div class="cell large-auto">5P</div>--->
                        </div>
                      </th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
			
			<div class="schedule">
              <div class="grid-x">
                <table class="unstriped">
                  <thead>
                    <tr>
                      <th width="15%" class="staff-name">
                        <select class="select">
                          <option value="firstname">First Name</option>
                          <option value="lastname">Last Name</option>
                          <option value="starttime">Start Time</option>
                        </select>
                      </th>
                      <th width="80%">
                        <div class="grid-x work-hours-mode">
                          <div class="cell large-auto">9A</div>
                          <div class="cell large-auto">10A</div>
                          <div class="cell large-auto">11A</div>
                          <div class="cell large-auto">12A</div>
                          <div class="cell large-auto">1P</div>
                          <div class="cell large-auto">2P</div>
                          <div class="cell large-auto">3P</div>
                          <div class="cell large-auto">4P</div>
                          <div class="cell large-auto">5P</div>
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
                    <p><span class="published-date"><?php echo date('F d, Y', strtotime($lastpublishdate)); ?></span><a href="#" class="button small" data-open="scheduleConfirmation">Save</a></p>
					<input type='hidden' name='schedule_start' id='schedule_start' value="<?php echo $schedule_start;?>"> 
					<input type='hidden' name='pagename' id='pagename' value="<?php echo $pagename;?>">
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
