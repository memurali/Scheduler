<?php  
	echo $this->Html->css('foundation.css');
	echo $this->Html->css('app.css');
	echo $this->Html->script('jquery.js');
	echo $this->Html->script('app.js');
	echo $this->Html->script('common.js');	
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
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
    <div class="reveal tiny text-center" id="scheduleConfirmation" data-reveal>
      <h2>Publish</h2>
      <p class="week-schedule">February 17, 2019</p>
      <div class="button-group expanded">
        <a class="button secondary" data-close>Cancel</a>
        <a class="button">Publish</a>
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
                <li><h1>Scheduler</h1></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="grid-container">
            <div class="grid-x grid-padding-x data-pull" data-equalizer data-equalize-on="medium" id="test-eq">
              <div class="large-3 cell">
                <div class="callout" data-equalizer-watch>
                  <form>
                    <div class="grid-x grid-padding-x">
                      <div class="medium-12 cell">
                        <label>Projected Sales Of The Week
                          <div class="input-group">
                            <span class="input-group-label">$</span>
                            <input class="input-group-field" type="number">
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
                    <div class="callout" data-equalizer-watch>
                      <div class="grid-x grid-padding-x">
                        <div class="medium-12 cell">
                          <label>Today</label>
                          <div class="grid-x grid-padding-x small-up-3">
                            <div class="cell hours">
                              <p>100</p>
                            </div>
                            <div class="cell labor-cost">
                              <p>100</p>
                            </div>
                            <div class="cell labor-percentage">
                              <p>78</p>
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
                          <label>2018 Sale</label>
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
                <h2 id="" class="date">February 17, 2019<span class="label">Valentines Day</span></h2>
              </div>
            </div> 
            <form>
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
                          <ul class="menu float-left callout cal-menu">
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
                    <li><a id="prevbtn" class="previous"></a></li>
                    <li><a id="calendarbtn" class="calendar"></a></li>
                    <li><a id="nextbtn" name="nextbtn" class="next"></a></li>
                    <li><a href="#">Today</a></li>
                    <li>
                      <select>
                        <option value="day">Day</option>
                        <option value="week">Week</option>
                      </select>
                    </li>
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
                </div>  
              </div>
            </form>
			<input type="hidden" id="currentdate" name="currentdate" value="<?php echo $nextdate; ?>" />
			<input type="hidden" id="nextdate" name="nextdate" value="<?php echo $nextdate; ?>" />
			<input type="hidden" id="prvdate" name="nextdate" value="<?php echo $prvdate; ?>" />
			<input type="hidden" id="action" name="action" value="next" />
            <div class="schedule">
              <div class="grid-x">
                <table class="unstriped">
                  <thead>
                    <tr>
                      <th width="15%" class="staff-name">
                        <select class="select">
						  <option value="">Arrange By</option>
                          <option value="firstname">First Name</option>
                          <option value="lastname">Last Name</option>
						  <option value="">Start Time</option>
                        </select>
                      </th>
                      <th width="80%">
                        <div class="grid-x work-hours-mode">
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
                          <div class="cell large-auto">12p</div>
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
                    <p>Total Hours:<span id=""></span></p>
                  </div>
                   <div class="small-6 cell text-right">
                    <p><span class="published-date">February 3, 2018 </span><a href="#" class="button small" data-open="scheduleConfirmation">Publish</a></p>
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
