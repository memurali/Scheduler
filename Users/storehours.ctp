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
    <div class="reveal tiny text-center" id="timeFormat" data-reveal>
      <h2>Time Format</h2>
      <form>
        <div class="grid-container">
          <div class="grid-x grid-padding-x">
            <div class="medium-8 cell">
              <label class="text-left">Store Hours</label>
              <div class="grid-x grid-padding-x">
                <div class="small-6 cell">
                  <input type="time">
                </div>
                 <div class="small-6 cell to">
                  <input type="time">
                </div>
              </div>
            </div>
            <div class="medium-4 cell text-left">
              <label>24 Hour Format</label>
              <div class="switch">
                <input class="switch-input" id="exampleSwitch" type="checkbox" name="exampleSwitch">
                <label class="switch-paddle" for="exampleSwitch"></label>
              </div>
            </div>
          </div>
          <div class="grid-x">
            <div class="cell">
              <div class="button-group expanded">
                <a class="button secondary" data-close>Cancel</a>
                <a class="button">Save</a>
              </div>
            </div>
          </div>
        </div>
      </form>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
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
  </head>
  <body class="app left-sub-menu">
    <div class="off-canvas-wrapper">
      <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
        <h1>Cold Stone</h1>
        <div class="logo" style="background: url(https://i.ya-webdesign.com/images/cold-stone-creamery-logo-png-17.png)"></div>
        <ul class="vertical menu">
            <li><?php echo $this->Html->link("Dashboard", array('controller' => 'users','action' => 'index'),array('class'=>'home'));?></li>
			<li><?php echo $this->Html->link("Published Schedules", array('controller' => 'users','action' => 'publishedday'),array('class'=>'published'));?></li>
			<li><?php echo $this->Html->link("Scheduler", array('controller' => 'users','action' => 'schedulerday'),array('class'=>'scheduler active'));?></li>
			<li><?php echo $this->Html->link("Store Hours", array('controller' => 'users','action' => 'storehours'),array('class'=>'store-hours active'));?></li>
			<li><?php echo $this->Html->link("Employees", array('controller' => 'users','action' => 'employees'),array('class'=>'employees'));?></li>
		</ul>
      </div>
      <div class="off-canvas-content" data-off-canvas-content>
        <div class="grid-x grid-padding-x">
          <div class="top-bar">
            <div class="top-bar-left">
              <ul class="menu">
                <li><button class="menu-icon" type="button" data-toggle="offCanvas"></button></li>
                <li><h1>Store Hours</h1></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="grid-container">
            <div class="grid-x grid-padding-x">
              <div class="large-3 cell">
                <div class="callout dark-bg-form">
                  <?php 
					echo $this->Form->create(false, array(
										'url' => array('controller' => 'users', 'action' => 'storehours'),
										'id' => 'frmsave_hrs',
										'method'=>'POST'
					));
					?>
                    <div class="grid-x grid-padding-x">
                      <div class="medium-12 cell">
                        <div class="grid-x">
                          <div class="small-12 cell">
                            <h3>Store Hours</h3>
                          </div>
                        </div>
                        <div class="grid-x">
                          <div class="large-12 cell">
							<?php 
							$sch_start = $businesshrs[0]['o']['Schedule_start_day'];
							?>
                            <label>What day would you like to start your work schedule?
                              <br />
                              <br />
                              <select name='schedule_start'>
                                <option value="Monday" <?php echo $sch_start=='Monday'?'selected':'';?>>Monday</option>
                                <option value="Tuesday" <?php echo $sch_start=='Tuesday'?'selected':'';?>>Tuesday</option>
                                <option value="Wednesday" <?php echo $sch_start=='Wednesday'?'selected':'';?>>Wednesday</option>
                                <option value="Thursday" <?php echo $sch_start=='Thursday'?'selected':'';?>>Wednesday</option>
                                <option value="Friday" <?php echo $sch_start=='Friday'?'selected':'';?>>Friday</option>
                                <option value="Saturday" <?php echo $sch_start=='Saturday'?'selected':'';?>>Saturday</option>
                                <option value="Sunday" <?php echo $sch_start=='Sunday'?'selected':'';?>>Sunday</option>
                              </select>
                            </label>
                          </div>
                        </div>
						<?php
							for($i=0; $i<count($businesshrs); $i++)
							{
								$id[]=$businesshrs[$i]['b']['Id'];
								$Weekday[]=$businesshrs[$i]['b']['Weekday'];
								$Starttime[]=$businesshrs[$i]['b']['Starttime'];
								$Endtime[]=$businesshrs[$i]['b']['Endtime'];
							}
						?>
                        <div class="grid-x grid-padding-x">
							<?php 
							$daysarr=['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
							for($i=0; $i<count($daysarr); $i++)
							{?>
								<div class="medium-12 cell">
									<label class="text-left"><?php echo $daysarr[$i];?></label>
									<div class="grid-x grid-padding-x">
										<div class="small-6 cell">
											<?php 
											$index = array_search($daysarr[$i],$Weekday);
											echo "<input type=time name=start_".$i." id=start_".$i." value= '".$Starttime[$index]."' required>";
											?>
										</div>
										<div class="small-6 cell to">
											<?php 
											$index = array_search($daysarr[$i],$Weekday);
											echo "<input type=time name=end_".$i." id=end_".$i." value= '".$Endtime[$index]."' required>";
											?>
										</div>
									</div>
								</div>
							
							<?php
							}
							?>
                        </div> 
                        <div class="grid-x">
                          <div class="large-12 cell">
                            <a class="button expanded" onclick='save_businesshrs();'>Save</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php echo $this->Form->end(); ?>
                </div>
              </div>
              <div class="large-9 cell data-pull">
                <div class="callout">
                  <div class="grid-x grid-padding-x">
                    <div class="medium-12 cell">
                      <h3>Custom</h3>
                      <?php 
						echo $this->Form->create(false, array(
											'url' => array('controller' => 'users', 'action' => 'storehours'),
											'id' => 'save_holiday',
											'method'=>'POST'
						));
						?>
                        <div class="grid-x grid-padding-x">
                          <div class="large-5 cell">
                            <div class="grid-x grid-padding-x">
                              <div class="small-5 cell">
                                <div class="grid-x">
                                  <div class="large-12 cell">
                                    <label>Date</label>
                                  </div>
                                  <div class="large-12 cell">
                                    <input type="date" id='holiday_date' name='holiday_date'>
									<input type="hidden" id='holidayid' name='holidayid'>
                                  </div>
                                </div>
                              </div>
                              <div class="small-7 cell">
                                <div class="grid-x">
                                  <div class="large-12 cell">
                                    <label>Note</label>
                                  </div>
                                  <div class="large-12 cell">
                                    <input type="text" id='holiday_note' name='holiday_note' value=''>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="large-4 cell">
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <div class="grid-x">
                                  <div class="large-12 cell">
                                    <label>Open</label>
                                  </div>
                                  <div class="large-12 cell">
                                    <input type="time" id="timeopen" name="timeopen">
                                  </div>
                                </div>
                              </div>
                              <div class="small-6 cell to">
                                <div class="grid-x">
                                  <div class="large-12 cell">
                                    <label>Close</label>
                                  </div>
                                  <div class="large-12 cell">
                                    <input type="time" id="timeclose" name="timeclose">
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="large-3 cell">
                            <div class="grid-x grid-padding-x">
                              <div class="small-3 cell">
                                <div class="grid-x">
                                  <div class="large-12 cell">
                                    <label>Closed</label>
                                  </div>
                                  <div class="large-12 cell">
                                    <input id="checkbox_closed" name="checkbox_closed" type="checkbox"><label for="checkbox_closed"></label>
                                  </div>
                                </div>
                              </div>
                              <div class="small-6 cell">
                                <a onclick="save_holiday();" class="button expanded">Save</a>
                              </div>
                            </div>
                          </div>
                        </div>
						<?php echo $this->Form->end(); ?>
                      </div>
                    </div>
                  </div>
                  <div class="list">
                    <div class="grid-x custom-dates">
                      <table class="unstriped">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Note</th>
                            <th>Open</th>
                            <th>Close</th>
                            <th class="text-center">Closed</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id='tbody'>
                          <!---<tr>
                            <td>Wednesday, December 28, 2019</td>
                            <td>Christmas Day</td>
                            <td>9A</td>
                            <td>5P</td>
                            <td class="repeat"></td>
                            <td>
                              <div class="button-group tiny float-right">
                                <a class="button secondary">Edit</a>
                                <a class="button alert">Delete</a>
                              </div>
                            </td>
                          </tr>------>
						  <?php echo $holiday_tbody;?>
                        </tbody>
                      </table>
                      <div class="large-12 cell">
                        <nav aria-label="Paginationt">
                          <ul class="pagination">
                            <!---<li class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li>
                            <li class="current"><span class="show-for-sr">You're on page</span> 1</li>
                            <li><a href="#" aria-label="Page 2">2</a></li>
                            <li><a href="#" aria-label="Page 3">3</a></li>
                            <li><a href="#" aria-label="Page 4">4</a></li>
                            <li class="ellipsis" aria-hidden="true"></li>
                            <li><a href="#" aria-label="Page 12">12</a></li>
                            <li><a href="#" aria-label="Page 13">13</a></li>
                            <li class="pagination-next"><a href="#" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li>--->
							<li class="pagination-previous">
							<?php
                              echo $this->Paginator->prev('Previous' . __(''), array(), null,array('class' => 'pagination-previous disabled'));
                            ?>  
                           
							</li>
							<!--<li class="current"><span class="show-for-sr">You're on page</span> 1</li>--->
							   
							<li><?php
							   echo $this->Paginator->numbers(array('separator' => '<li>'));    
							   ?>
							</li>
							<li class="pagination-next"><?php 
							   echo $this->Paginator->next(__('Next') . '', array(), null,array('class' => 'pagination-next disabled'));
							   ?>
							   <span class="show-for-sr">page</span></a>
							</li>
                          </ul>
                        </nav>
                      </div>
                    </div>
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
