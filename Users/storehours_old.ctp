<?php
	echo $this->Html->css('foundation.css');
	echo $this->Html->css('app.css');
	echo $this->Html->script('jquery.js');	
	echo $this->Html->script('what-input.js');
	echo $this->Html->script('foundation.js'); 
	echo $this->Html->script('app.js');		      
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
                <li><h1>Store Hours</h1></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="grid-container">
            <div class="grid-x">
              <div class="large-12 cell">
                <h2 id="" class="date">Time Format</h2>
              </div>
            </div> 
            <div class="grid-x grid-padding-x data-pull">
              <div class="large-4 cell">
                <div class="callout">
                  <form>
                    <div class="grid-x grid-padding-x">
                      <div class="medium-12 cell">
                        <div class="grid-x">
                          <div class="small-6 cell">
                            <h3>Store Hours</h3>
                          </div>
                          <div class="small-6 cell text-right">
                            <a href="#" class="button">Save</a>
                          </div>
                        </div>
                        <div class="grid-x grid-padding-x">
                          <div class="medium-12 cell">
                            <label class="text-left">Monday</label>
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <input type="time">
                              </div>
                               <div class="small-6 cell to">
                                <input type="time">
                              </div>
                            </div>
                          </div>
                          <div class="medium-12 cell">
                            <label class="text-left">Tuesday</label>
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <input type="time">
                              </div>
                               <div class="small-6 cell to">
                                <input type="time">
                              </div>
                            </div>
                          </div>
                          <div class="medium-12 cell">
                            <label class="text-left">Wednesday</label>
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <input type="time">
                              </div>
                               <div class="small-6 cell to">
                                <input type="time">
                              </div>
                            </div>
                          </div>
                          <div class="medium-12 cell">
                            <label class="text-left">Thursday</label>
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <input type="time">
                              </div>
                               <div class="small-6 cell to">
                                <input type="time">
                              </div>
                            </div>
                          </div>
                          <div class="medium-12 cell">
                            <label class="text-left">Friday</label>
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <input type="time">
                              </div>
                               <div class="small-6 cell to">
                                <input type="time">
                              </div>
                            </div>
                          </div>
                          <div class="medium-12 cell">
                            <label class="text-left">Saturday</label>
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <input type="time">
                              </div>
                               <div class="small-6 cell to">
                                <input type="time">
                              </div>
                            </div>
                          </div>
                          <div class="medium-12 cell">
                            <label class="text-left">Sunday</label>
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <input type="time">
                              </div>
                               <div class="small-6 cell to">
                                <input type="time">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="large-8 cell">
                <div class="callout">
                  <div class="grid-x grid-padding-x">
                    <div class="medium-12 cell">
                      <h3>Custom</h3>
                      <label>Date</label>
                      <form>
                        <div class="grid-x grid-padding-x">
                          <div class="large-4 cell">
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <input type="date">
                              </div>
                              <div class="small-6 cell">
                                <input type="text">
                              </div>
                            </div>
                          </div>
                          <div class="large-4 cell">
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <input type="time">
                              </div>
                               <div class="small-6 cell to">
                                <input type="time">
                              </div>
                            </div>
                          </div>
                          <div class="large-4 cell">
                            <div class="grid-x grid-padding-x">
                              <div class="small-6 cell">
                                <fieldset class="large-7 cell">
                                  <input id="checkbox1" type="checkbox"><label for="checkbox1">Repeat</label>
                                </fieldset>
                              </div>
                              <div class="small-6 cell">
                                <a href="#" class="button expanded">Add</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="grid-x custom-dates">
                    <table class="unstriped">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Note</th>
                          <th>Open</th>
                          <th>Close</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Wednesday, December 28, 2019</td>
                          <td>Christmas Day</td>
                          <td>9A</td>
                          <td>5P</td>
                          <td>
                            <div class="button-group tiny float-right">
                              <a class="button secondary">Edit</a>
                              <a class="button alert">Delete</a>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="large-12 cell">
                      <nav aria-label="Paginationt">
                        <ul class="pagination">
                          <li class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li>
                          <li class="current"><span class="show-for-sr">You're on page</span> 1</li>
                          <li><a href="#" aria-label="Page 2">2</a></li>
                          <li><a href="#" aria-label="Page 3">3</a></li>
                          <li><a href="#" aria-label="Page 4">4</a></li>
                          <li class="ellipsis" aria-hidden="true"></li>
                          <li><a href="#" aria-label="Page 12">12</a></li>
                          <li><a href="#" aria-label="Page 13">13</a></li>
                          <li class="pagination-next"><a href="#" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li>
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

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
      $('.staffRow').change(function(){
        $('tr').removeClass('checked');
        if($(this).is(':checked')){
          $(this).closest('tr').addClass('checked');
          var radioValue = $("input[name='staff']:checked").val();
          $("#search_name").val(radioValue);
        }
      });
    
    });
  </script>
  </body>
</html>
