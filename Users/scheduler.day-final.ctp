<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundation for Sites</title>
    <link rel="stylesheet" href="css/foundation.css">
    <link rel="stylesheet" href="css/app.css">
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
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
	
	$('.pokemonRed').change(function(){
		$('tr').removeClass('checked');
		if($(this).is(':checked')){
			$(this).closest('tr').addClass('checked');
			var radioValue = $("input[name='pokemon']:checked").val();
			$("#search_name").val(radioValue);
		}
	});
	
  });
</script>
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
              <div class="small-3 cell">
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
              <div class="small-7 cell">
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
              <div class="small-2 cell">
                <div class="callout" data-equalizer-watch>
                  <div class="grid-x grid-padding-x">
                    <div class="medium-12 cell">
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
            <div class="grid-x">
              <div class="large-12 cell">
                <h2 id="" class="date">February 17, 2019<span class="label">Valentines Day</span></h2>
              </div>
            </div>  
            <div class="grid-x">
              <div class="large-8 cell">
                <div class="grid-x">
                  <div class="small-3 cell">
                    <form>
                      <div class="input-group search-staff">
                        <input class="input-group-field" type="text" id="search_name" value="Allen Abesamis">
                        <div class="input-group-button">
                          <a href="#" class="button"></a>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="large-9 cell">
                    <div class="grid-x grid-padding-x">
                      <div class="small-4 cell">
                        <div class="availability float-left">
                          <h2>9A-5P</h2>
                        </div>
                      </div>
                      <div class="small-5 cell">
                        <div class="slider" data-slider data-initial-start="25" data-initial-end="75">
                          <span class="slider-handle" data-slider-handle role="slider" tabindex="1"></span>
                          <span class="slider-fill" data-slider-fill></span>
                          <span class="slider-handle" data-slider-handle role="slider" tabindex="1"></span>
                          <input type="hidden">
                          <input type="hidden">
                        </div>
                      </div>
                      <div class="small-3 cell">
                        <ul class="menu float-left callout cal-menu">
                          <li><a href="#">Split</a></li>
                          <li><a href="#">Clear</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="large-4 cell">
                <ul class="menu float-right callout cal-menu">
                  <li><a href="#" data-open="timeFormat">Time Format</a></li>
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
                </ul>
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
                          <option value="lastname">Start Time</option>
                        </select>
                      </th>
                      <th width="80%">
                        <div class="grid-x">
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
                  <tbody>
                    <tr class="staff checked">
                      <td id="" class="name"><input type="radio" name="pokemon" value="Allen Abesamis" class="pokemonRed" required checked>Allen Abesamis</input><span id="" class="total-hours">0</span><img class="image-profile" src="https://bit.ly/2RGt5B9"></td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name"><input type="radio" name="pokemon" value="Ayden Abesamis" class="pokemonRed" required>Ayden Abesamis</input><span id="" class="total-hours">0</span></td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name"><input type="radio" name="pokemon" value="Jerome de Joya" class="pokemonRed" required>Jerome de Joya<span id="" class="total-hours">0</span></td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name"><input type="radio" name="pokemon" value="Michael Urata" class="pokemonRed" required>Michael Urata<span id="" class="total-hours">0</span></td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name"><input type="radio" name="pokemon" value="Monica Abelardo" class="pokemonRed" required>Monica Abelardo<span id="" class="total-hours">0</span></td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name"><input type="radio" name="pokemon" value="Monica Abelardo" class="pokemonRed" required>Monica Abelardo<span id="" class="total-hours">0</span></td>
                      <td></td>
                    </tr>
                    <tr class="staff">
                      <td id="" class="name"><input type="radio" name="pokemon" value="Monica Abelardo" class="pokemonRed" required>Monica Abelardo<span id="" class="total-hours">0</span></td>
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
                    <p><span class="published-date">February 3, 2018 </span><a href="#" class="button small" data-open="scheduleConfirmation">Publish</a></p>
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
