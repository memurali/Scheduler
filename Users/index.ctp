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
  </head>
  <body id="dashboard" class="app left-sub-menu">
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
		  <li><?php echo $this->Html->link("Payment", array('controller' => 'Payments','action' => 'shopping_cart'),array('class'=>'employees'));?></li>		  
        </ul>
      </div>
      <div class="off-canvas-content" data-off-canvas-content>
        <div class="grid-x grid-padding-x">
          <div class="top-bar">
            <div class="top-bar-left">
              <ul class="menu">
                <li><button class="menu-icon" type="button" data-toggle="offCanvas"></button></li>
                <li><h1>Dashboard</h1></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="grid-container">
            <div class="grid-x grid-padding-x">
              <div class="large-3 cell history">
                <div class="callout dark-bg-form">
                  <div class="grid-x grid-padding-x">
                    <div class="large-12 cell">
                      <div class="input-group calendar-search">
                        <input class="input-group-field" type="date" id='seldate'>
                        <div class="input-group-button">
                          <a href="#" class="button"></a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="grid-x grid-padding-x">
                    <div class="large-12 cell">
						<label>Drafts</label>
						<ul class="no-bullet">
						<?php
						if(count($drafts)>0)
						{
							foreach($drafts as $draftval)
							{
								echo '<li>'.$this->Html->link(date('F d,Y', strtotime($draftval)), array('controller' => 'users','action' => 'schedulerday?selecteddate='.$draftval.'')).'</li>';
							}
						}
						?>
                        </ul>
                    </div>
                    <div class="large-12 cell">
                      <label>UnPublished Schedules</label>
                      <ul class="no-bullet">
                        <!---<li>
                          <a href="#">
                            <span>February 17, 2019</span> - <span>February 23, 2019</span>
                            <span class="label alert float-right">Upcoming</span>
                          </a>
                        </li>
                        <li>
                          <a href="#">
                            <span>February 24, 2019</span> - <span>March 2, 2019</span>
                          </a>
                        </li>--->
						<?php
						if(count($unpublisheddates)>0)
						{
							$nextweek=date("Y-m-d", strtotime('next '.$schedulestart));
							foreach($unpublisheddates as $unpublisheddate)
							{
								
								echo '<li>';
									echo $this->html->link(date('F d,Y', strtotime($unpublisheddate)).'-'.date('F d,Y', strtotime('+6 day', strtotime($unpublisheddate))), array('controller' => '','action' => ''.'?selecteddate='.$unpublisheddate));
									if($unpublisheddate==$nextweek)
										echo '<span class="label alert float-right">Upcoming</span>';
									else
										echo '';
								echo '</li>';
							}
						}
						?>
                      </ul>
                    </div>
                    <div class="large-12 cell">
                      <label>Published Schedules</label>
                      <ul class="no-bullet" id="publish_ul">
                        <?php
						if(count($publisheddates)>0)
						{
							foreach($publisheddates as $publisheddate)
							{
								echo '<li>';
									echo $this->html->link(date('F d,Y', strtotime($publisheddate)).'-'.date('F d,Y', strtotime('+6 day', strtotime($publisheddate))),array('controller' => '','action' => '?selecteddate='.$publisheddate));
								echo '</li>';
							}
						}
						?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="large-9 cell">
                <div class="grid-x grid-padding-x data-pull">
                  <div class="small-3 cell">
                    <div class="callout projected-sales" data-equalizer-watch>
                      <div class="grid-x grid-padding-x">
                        <div class="large-12 cell">
                          <label>Last Week Projected Sales
                            <div class="input-group">
                              <span class="input-group-label">$</span>
                              <input id="proj_sale" class="input-group-field" type="number" >
                            </div>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="small-9 cell">
                    <div class="callout data" data-equalizer-watch>
                      <div class="grid-x grid-padding-x">
                        <div class="medium-12 cell">
                          <label>Last Week</label>
                          <div class="grid-x grid-padding-x small-up-3">
                             <div class="cell hours">
                              <p><span id='labor-hour'><?php echo $laborhours;?><span></p>
                            </div>
                            <div class="cell labor-cost">
                              <p><span id='labor-cost'><?php echo $laborcost;?></span></p>
                            </div>
                            <div class="cell labor-percentage">
                              <p><span id='labor-percentage'></span></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="grid-x">
                  <div class="large-12 cell">
                    <div class="callout schedule-quick-view">
                      <div class="grid-x grid-padding-x">
                        <div class="large-6 cell">
                          <h3><?php echo $currentweek;?></h3>
                        </div>
                        <div class="large-6 cell text-right">
                          <a href="#" class="button">View</a>
                        </div>
                      </div>
                      <div class="schedule week">
                        <div class="grid-x">
                          <table class="unstriped">
                            <thead>
                              <tr>
                                <th width="20%" class="staff-name">
                                  <select class="select">
                                    <option value="firstname">First Name</option>
                                    <option value="lastname">Last Name</option>
                                    <option value="starttime">Start Time</option>
                                  </select>
                                </th>
								<?php echo $thead;?>
                              </tr>
                            </thead>
                            <tbody id="table_body">
                              <?php 
								for($i=0;$i<=count($tbody);$i++)
								{
									echo $tbody[$i];
								} 
							   ?>
							   
                            </tbody>
                          </table>
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
