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
	<script>
	$(document).ready(function() {
		var i =1;
		$.each($('.reveal-overlay'), function (index, value) {
			$(this).attr('id', 'popup'+i);
			i++;
		});
		
	});
	</script>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foundation for Sites</title>
    <div class="reveal text-center" id="editEmployee" data-reveal>
		<h2>Edit Employee</h2>
		<?php 
		echo $this->Form->create(false, array(
							'url' => array('controller' => 'Users', 'action' => 'employees'),
							'name' => 'editempfrm',
							'id' => 'editempfrm',
							'method'=>'POST',
							'type' => 'file'
		));
		?>	
		<?php echo $this->Form->end(); ?>
		<button class="close-button" data-close aria-label="Close modal" type="button" onclick="closeeditempview();">
			<span aria-hidden="true">&times;</span>
		</button>
    </div>
	<div class="reveal text-center" id="AddEmployee" data-reveal>
		<h2>Add Employee</h2>
		<?php 
		echo $this->Form->create(false, array(
							'url' => array('controller' => 'Users', 'action' => 'employees'),
							'name' => 'addstaff',
							'id' => 'addstaff',
							'method'=>'POST',
							'type' => 'file'
		));
		?>
		<div class="grid-container text-left">
          <div class="grid-x grid-padding-x">
            <div class="large-4 cell">
              <div class="profile-image-upload" style="background-image: url(https://bit.ly/2RGt5B9"></div>
              <label for="exampleFileUpload" class="button small secondary expanded">Upload File</label>
              <input type="file" id="profileimg" name="profileimg"><!---class="show-for-sr"--->
			</div>
            <div class="large-8 cell">
              <div class="grid-x">
                <div class="large-12 cell">
                  <label>First Name</label>
                  <input type="text" id='emp_fname' name='emp_fname'>
				</div>
              </div>
				<div class="large-12 cell">
				  <label>Last Name</label>
				  <input type="text" id='emp_lname' name='emp_lname'>
				</div>
            </div>
            <div class="large-12 cell">
              <div class="grid-x grid-padding-x">
                <div class="large-12 cell">
                  <label>Address</label>
                    <input type="text" id='emp_addr' name='emp_addr'>
                </div>
                <div class="large-5 cell">
                  <label>City</label>
                    <input type="text" id='emp_city' name='emp_city'>
                </div>
                <div class="large-3 cell">
                  <label>State</label>
                    <input type="text" id='emp_state' name='emp_state'>
                </div>
                <div class="large-4 cell">
                  <label>Zip</label>
                    <input type="number" id='emp_zip' name='emp_zip'>
                </div>
              </div>
            </div>  
            <div class="large-12 cell">
              <div class="grid-x grid-padding-x">
                <div class="large-6 cell">
                  <label>Phone Number</label>
                    <input type="tel" id='emp_phone' name='emp_phone'>
                  
                </div>
                <div class="large-6 cell">
                  <label>Email </label>
                    <input type="email" id='emp_email' name='emp_email'>
                </div>
              </div>
            </div>
			<div class="large-12 cell">
              <div class="grid-x grid-padding-x">
                <div class="large-6 cell">
                  <label>Status</label>
                    <input type="checkbox" id='emp_status' name='emp_status'> Active
                  
                </div>
                <div class="large-6 cell">
                  <label>Role</label>
                    <?php
					echo '<select name=sel_role id=sel_role onchange=roleselect(this.value);>';
					foreach ($rolearr as $role)
					{
						echo '<option id='.$role['tblrole']['Rate'].' value='.$role['tblrole']['Roleid'].'>'.$role['tblrole']['Rolename'].'</option>';
					}
					echo '</select>';
					?>
				</div>
				<div class="large-6 cell">
					<label>Rate</label>
					<input type='number' id='rate' name='rate' value='<?php echo $rolearr[0]['tblrole']['Rate'];?>' readonly>
				</div>
              </div>
            </div>
          </div>  
          <hr />
          <div class="grid-x grid-padding-x">
            <div class="large-12 cell">
              <h3>Custom Fields</h3>
              <div class="grid-x">
			    <?php
					if(count($customfieldarr)>0)
					{
						echo '<input type=hidden name=customcount id=customcount value='.count($customfieldarr).'>';
						for($i=0; $i<count($customfieldarr); $i++)
						{
							
							$attrid = $customfieldarr[$i]['tblorg_attribute']['Attributeid'];
							$custype = $customfieldarr[$i]['tblorg_attribute']['KeypairType'];
							$custval = $customfieldarr[$i]['tblorg_attribute']['KeypairValue'];
							echo '<input type=hidden id=custom'.$i.' name=custom'.$i.' value='.$attrid.'>';
							if(strpos($custval,',')!='')
								$optionval = explode(",",$custval);
							echo '<div class="large-12 cell">';
								echo '<label>'.$customfieldarr[$i]['tblorg_attribute']['Keypair'].'</label>';
								if($custype=='text'||$custype=='number'||$custype=='date'||$custype=='file')
								{
									echo '<input type='.$custype.' id=custom_'.$attrid.' name=custom_'.$attrid.'>';
									
								}
								else if($custype=='dropdown')
								{
									echo '<select name=custom_'.$attrid.' id=custom_'.$attrid.'>';
										foreach($optionval as $option)
										{
											echo '<option value='.$option.'>'.$option.'</option>';
										}
									echo '</select>';
									
								}
								else if($custype=='radio')
								{
									foreach($optionval as $option)
									{
										echo '<input type='.$custype.' name=custom_'.$attrid.' name=custom_'.$attrid.'>'.$option.'<br>';
									}
								}
								else if($custype=='checkbox')
								{
									echo '<input type='.$custype.' name=custom_'.$attrid.' name=custom_'.$attrid.'>'.$customfieldarr[$i]['tblorg_attribute']['Keypair'];
								}
								else if($custype=='textarea')
								{
									echo '<textarea name=custom_'.$attrid.' id=custom_'.$attrid.'></textarea>';
								}
							echo '</div>';
						}
					}
				?>
               
              </div>
            </div>
          </div>
          <div class="button-group expanded">
            <a class="button" onclick='savestaff();'>Save</a>
          </div>
        </div>
      <?php echo $this->Form->end(); ?>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
	
	
    <div class="reveal tiny text-center" id="customField" data-reveal>
      <h2>Edit Custom Field</h2>
      <form id='editcustomfrm' name='editcustomfrm'>
        <div class="grid-container text-left">
          <div class="grid-x grid-padding-x">
            <div class="large-12 cell">
              <div class="grid-x grid-padding-x">
                <div class="large-12 cell">
                  <label>Field Name</label>
                    <input type="text" id='editcustomname' name='editcustomname'>
					<input type="hidden" id='editattribute_id' name='editattribute_id'>
                </div>
                <div class="large-12 cell">
                  <label>Field Type</label>
				  <select name="editcustomfield_type" id="editcustomfield_type" onchange='fieldtype_change(this.value,"edit");' >
					<option value="text">Text</option>
					<option value="number">Number</option>
					<option value="checkbox">Checkbox</option>
					<option value="file">File</option>
					<option value="radio">Radio</option>
					<option value="date">Date</option>
					<option value="dropdown">Dropdown</option>
					<option value="textarea">Textarea</option>
                  </select>
                </div>
                <div class="large-12 cell">
                  <label>Field Value</label>
                    <textarea id='editfieldval' name='editfieldval'></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="button-group expanded">
            <a id='editcustom_save' class="button" data-close>Save</a>
            <a class="button alert" data-open="customconfirmDelete">Delete</a>
          </div>
        </div>
      </form>
      <button class="close-button" data-close aria-label="Close modal"  onclick='closeeditcustom();' type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="reveal tiny text-center" id="customconfirmDelete" data-reveal>
      <h2>Delete</h2>
      <p>This action cannot be undone</p>
      <form>
        <div class="grid-container text-left">
          <div class="grid-x grid-padding-x">
            <div class="large-12 cell">
              <div class="button-group expanded">
                <a class="button secondary" data-close>Cancel</a>
                <a class="button alert" onclick="customdelete();" data-close>Delete</a>
              </div>
            </div>
          </div>
        </div>
      </form>
      <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </head>
  <body id="employees" class="app right-sub-menu ">
    <div class="off-canvas-wrapper">
      <div class="off-canvas position-left" id="offCanvas" data-off-canvas>
        <h1>Cold Stone</h1>
        <div class="logo" style="background: url(https://i.ya-webdesign.com/images/cold-stone-creamery-logo-png-17.png)"></div>
        <ul class="vertical menu">
          <li><?php echo $this->Html->link("Dashboard", array('controller' => 'users','action' => 'index'),array('class'=>'home'));?></li>
		  <li><?php echo $this->Html->link("Published Schedules", array('controller' => 'users','action' => 'publishedday'),array('class'=>'published'));?></li>
		  <li><?php echo $this->Html->link("Scheduler", array('controller' => 'users','action' => 'schedulerday'),array('class'=>'scheduler'));?></li>
          <li><?php echo $this->Html->link("Store Hours", array('controller' => 'users','action' => 'storehours'),array('class'=>'store-hours'));?></li>
          <li><?php echo $this->Html->link("Employees", array('controller' => 'users','action' => 'employees'),array('class'=>'employees active'));?></li>		  
		  <li><?php echo $this->Html->link("Payment", array('controller' => 'Payments','action' => 'shopping_cart'),array('class'=>'employees'));?></li>		  
        </ul>
      </div>
      <div class="off-canvas position-right" id="offCanvas2" data-off-canvas>
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
                <li><h1>Employees</h1></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="grid-container">
            <div class="grid-x grid-padding-x">
              <div class="large-9 cell">  
                <div class="grid-x">
                  <div class="large-12 cell">
                    <div class="grid-x grid-padding-x">
                      <div class="medium-3 cell">
                        <form>
                          <div class="input-group search-staff">
                            <input class="input-group-field" type="text" id="empsearch_name">
                            <div class="input-group-button">
                              <a href="#" class="button"></a>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="cell auto text-right">
                        <div class="grid-x grid-padding-x">
                          <div class="medium-12 cell">
                            <a data-open="AddEmployee"  class="button add-staff">New Staff</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="list">
                  <div class="grid-x">
                    <table>
                      <thead>
                        <tr>
                          <th width="20%" class="staff-name">
                            <select id='select_emp' name='select_emp'>
                              <option value="firstname">First Name</option>
                              <option value="lastname">Last Name</option>
                              <option value="mostrecent">Most Recent</option>
                            </select>
                          </th>
                          <th>Address</th>
                          <th>Phone Number</th>
                          <th>Email</th>
						  <?php
						  if(count($customfieldarr)>0)
						  {
							for($i=0; $i<3; $i++)
							{
								if($customfieldarr[$i]['tblorg_attribute']['Keypair']!='')
								{
									echo '<th>'.$customfieldarr[$i]['tblorg_attribute']['Keypair'].'</th>';
									$attridarr[$i]= $customfieldarr[$i]['tblorg_attribute']['Attributeid'];
								}
								
							}
						  }?>
						  <th width="43px"></th>
                        </tr>
                      </thead>
                      <tbody id='emptable_body'>
                        <?php
						if(count($viewemp)>0)
						{
							foreach($viewemp as $emp)
							{
								echo '<tr class="staff">';
								    echo '<td  class=name>
											<input type=hidden  name=employee value="'.$emp['Tblusers']['Employee_First_Name'].' '.$emp['Tblusers']['Employee_Last_Name'].'">
											<input type=hidden  name=date_create id=date_create value="'.$emp['Tblusers']['DateCreated'].'">
											'.$emp['Tblusers']['Employee_First_Name'].' '.$emp['Tblusers']['Employee_Last_Name'].'<img src="data:image/jpeg;base64,'.base64_encode($emp['Tblusers']['Profile_Img']).'" />
											</td>';
								    echo '<td class=address>'.$emp['Tblusers']['Address']	.'
											<span class="address-info">
											  <span class="city">'.$emp['Tblusers']['City'].'</span>
											  <span class="state">'.$emp['Tblusers']['State'].'</span>
											  <span class="zipcode">'.$emp['Tblusers']['Zip'].'</span>
											</span>
										</td>';
								    echo '<td>'.$emp['Tblusers']['Cell'].'</td>';
								    echo '<td>'.$emp['Tblusers']['Email'].'</td>';
									if(count($viewcustom)>0)
								    {
										foreach ($attridarr as $attrid)
										{
											
											for($j=0; $j<count($viewcustom); $j++)
											{
												if($attrid == $viewcustom[$j]['tbluser_attribute']['Attrid'])
												{
													if($emp['Tblusers']['Userid']==$viewcustom[$j]['tbluser_attribute']['Userid'])
													{
														echo '<td>'.$viewcustom[$j]['tbluser_attribute']['Userattr_val'].'</td>';
													}
													
												}
											}
										}
								    }
									
								    echo "<td><a onclick=editempview(".$emp['Tblusers']['Userid']."); class=more></a></td>";
								echo "</tr>";
							}
						}?>
                        
                      </tbody>
                    </table>
                  </div> 
                </div>
                <div class="grid-x">
                  <div class="large-12 cell">
                    <nav aria-label="Pagination">
						<ul class="pagination">
							<li class="pagination-previous">
								<?php
								  echo $this->Paginator->prev('Previous' . __(''), array(), null,array('class' => 'pagination-previous disabled'));
								?>  
							</li>
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
              <div class="large-3 cell dark-bg-form">
                <form id='addcustomfrm' name='addcustomfrm'>
                  <div class="add-custom-fields" data-accordion data-allow-all-closed="true">
                    <div class="accordion-item is-active" data-accordion-item>
                      <a href="#" class="accordion-title"><h3>Add Custom Fields</h3></a>
                      <div class="accordion-content" data-tab-content>
                        <div class="grid-x">
                          <div class="large-12 cell">
                            <label>Field Name</label>
                              <input type="text" name="customfieldname" id="customfieldname">
							  <input type="hidden" name="attrid" id="attrid" value=''>
                          </div>
                          <div class="large-12 cell">
                            <label>Field Type</label>
                              <select name="customfield_type" id="customfield_type" onchange='fieldtype_change(this.value,"add");' >
                                <option value="text">Text</option>
								<option value="number">Number</option>
                                <option value="checkbox">Checkbox</option>
								<option value="file">File</option>
								<option value="radio">Radio</option>
								<option value="date">Date</option>
								<option value="dropdown">Dropdown</option>
								<option value="textarea">Textarea</option>
                              </select>
                          </div>
                          <div class="large-12 cell">
                            <label>Field Value</label>
                              <textarea id='fieldval' name='fieldval'  placeholder="Give varchar length mandatory"></textarea>
                          </div>
                          <div class="large-12 cell">
                            <a id='addcustom' class="button expanded">Add</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="callout custom-fields">
                    <div class="callout custom-field-order">
						<div id='custom_response'>
						  <?php
							if(count($customfieldarr)>0)
							{
								for($i=0; $i<count($customfieldarr); $i++)
								{
									echo '<div class="callout">';
										echo '<label>'.$customfieldarr[$i]['tblorg_attribute']['Keypair'].'</label>';
										echo '<a  class="edit-custom-field" onclick=editcustom('.$customfieldarr[$i]['tblorg_attribute']['Attributeid'].'); ></a>';//data-open="customField"
									echo '</div>';
								}
							}
						  ?>
						</div>
                      <a href="#" class="button hollow expanded">Update</a>
                    </div>
                  </div>
                </form>
              </div>  
            </div>  
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
