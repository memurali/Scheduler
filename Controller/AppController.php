<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	
	// added the debug toolkit
	// sessions support
	// authorization for login and logut redirect
	public $components = array(
		'DebugKit.Toolbar',
		'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'users', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'index'),
			'authError' => 'You must be logged in to view this page.',
			'loginError' => 'Invalid Username or Password entered, please try again.'
 
        ));
	
	// only allow the login controllers only
	public function beforeFilter() {
        $this->Auth->allow('index');
    }
	
	public function isAuthorized($user) {
		// Here is where we should verify the role and give access based on role
		
		return true;
	}
	public $uses = array('Tblemployee_availability','Tblusers','Tblholidays','Tblbusinesshours','Tblorganization','Tblorg_attribute','Tbluser_attribute');
	public function schedule_saving($data,$current_date)
	{
		$data=explode('&',$data);
			for($i=0;$i<count($data)-1;$i++)
			{
				$slot_data=explode('#',$data[$i]);
				for($j=0;$j<count($slot_data);$j++)
				{
					$data_value=explode('|',$slot_data[$j]);
					if($data_value[1]=='')
						$data_value[1]=1;
					$select="select * from tblschedule where Employeeid=".$data_value[0]." and 
					Slot_No=".$data_value[1]." and  Date='".$current_date."' " ;
					//echo $select;
					//exit;
					$exeselect=$this->Tblschedule->query($select);
					$StartTime=date("H:i", strtotime($data_value[2].'M'));
					$EndTime=date("H:i", strtotime($data_value[3].'M'));
					
					if(count($exeselect)==0)
					{
						$insert="INSERT INTO `tblschedule` 
						(`id`, `Date`, `StartTime`, `Endtime`, `Slot_No`,`Employeeid`) 
						VALUES ('', '$current_date', '$StartTime',
						'$EndTime','$data_value[1]','$data_value[0]')";
						//echo '<br>'.$insert;
						$exeinsert=$this->Tblschedule->query($insert);
					}
					else
					{
						$update="update tblschedule set 
								StartTime='".$StartTime."' ,  
								Endtime='".$EndTime."' 
								where Employeeid=".$data_value[0]." and
								Slot_No=".$data_value[1]."  and  
								Date='".$current_date."'
								";
								//echo '<br>'.$update;
						$exeupdate=$this->Tblschedule->query($update);
					}
				}
			}
			
	}
	//scheduler Next previous process
	public function create_usertable($emp_avail,$day,$type,$dateval)
	{
		$key = array_keys($emp_avail);
		$timestamp    = strtotime($dateval);
		$print_date = date('F d,Y', $timestamp);
		$publish_print_date = date('l',$timestamp).'-'.date('F d,Y', $timestamp);
		
		$dayname = date('l', strtotime($dateval));
		$sel_hrs_qry = "SELECT * FROM `tblbusinesshours` WHERE `Weekday` = '".$dayname."' and Orgid=1";
		$businesshrs_arr = $this->Tblbusinesshours->query($sel_hrs_qry);
		$store_open = $businesshrs_arr[0]['tblbusinesshours']['Starttime'];
		$store_close = $businesshrs_arr[0]['tblbusinesshours']['Endtime'];
		
		
		$cus_open = '';
		$cus_close = '';
		$sel_query = "SELECT * FROM `tblholidays` WHERE `Holiday_date`='".$dateval."' and Orgid=1";
		$holiday_arr=$this->Tblemployee_availability->query($sel_query);
		if(count($holiday_arr)>0)
		{
			$holiday= $holiday_arr[0]['tblholidays']['Holiday_description'];
			$opentime = $holiday_arr[0]['tblholidays']['Open_time'];
			$closetime = $holiday_arr[0]['tblholidays']['Close_time'];
			if($opentime==NULL && $closetime==NULL)
			{
				$fullholiday = 'Yes';
			}
			else
			{
				$fullholiday = '';
				$cus_open = $opentime;
				$cus_close = $closetime;
			}
			
		}
		else
			$holiday= '';
		
		if($fullholiday=='')
		{
			for($i=0;$i<count($emp_avail);$i++)
			{
				$slotarr=array();
				if($emp_avail[$key[$i]]['scheduledate']!='')
				{
					$timestamp    = strtotime($emp_avail[$key[$i]]['scheduledate']);
					$print_date = date('F d,Y', $timestamp);
					$publish_print_date = date('l',$timestamp).'-'.date('F d,Y', $timestamp);
					$dateval = date('Y-m-d', $timestamp);
				}
				$contruct_tablebody[$i].=' <tr class="staff" id="staff_'.$i.'">
					<td id="" class="name">
					<input type="radio"  name="staff" value="'.$emp_avail[$key[$i]]['Emp_name'].'" class="staffRow">'.$emp_avail[$key[$i]]['Emp_name'].'<span id="tothr" class="total-hours">0</span>
					<input type="hidden" class="empcount" value="'.count($emp_avail).'" />
					<input type="hidden" id="empid_'.$i.'" value="'.$key[$i].'" />
					<img class="image-profile" src="https://bit.ly/2RGt5B9">
					</td>';
					
					if($emp_avail[$key[$i]][$day]=='')
						$tdclass = 'availability not-available';
					else
						$tdclass = 'availability';
					
					$contruct_tablebody[$i].='<td class="'.$tdclass.'">
					<div class="grid-x">';
					$k=1;
					if($type=='checked')
					{
						$starthr = date('h:i A', strtotime("00:00"));
						$Endhr = date('h:i A', strtotime("23:00"));
					}
					else
					{
						if($cus_open=='')
						{
							$starthr = date('h:i A', strtotime($store_open));
							$Endhr = date('h:i A', strtotime($store_close));
						}
						if($cus_open!='')
						{
							$starthr = date('h:i A', strtotime($cus_open));
							$Endhr = date('h:i A', strtotime($cus_close));
						}
					}
					$tothr=0;
					$all_start_val='';
					$all_end_val='';
					
					if(date('i', strtotime($Endhr))>0)
						$loopend = date('G', strtotime($Endhr))+1;
					else
						$loopend = date('G', strtotime($Endhr));
					for($j=date('G', strtotime($starthr));$j<=$loopend;$j++)
					{					
						$j_date = date('h:i A', strtotime($j.":00"));
						$avl_start = '';
						$avl_end='';
						
						if($emp_avail[$key[$i]][$day][$k][0]!='' && $emp_avail[$key[$i]][$day][$k][1]!='')
						{
							
							$avl_start = $emp_avail[$key[$i]][$day][$k][0];
							$avl_end = $emp_avail[$key[$i]][$day][$k][1];
							if($cus_open!='')
							{
								if($emp_avail[$key[$i]]['scheduledate']=='')
								{
									if(strtotime($emp_avail[$key[$i]][$day][$k][0])<strtotime($cus_open))
										$avl_start = $cus_open;
									if(strtotime($emp_avail[$key[$i]][$day][$k][1])>strtotime($cus_close))
										$avl_end = $cus_close;
								}
							}
							
						}
										
						if((date('G', strtotime($j_date))) == date('G', strtotime($avl_start)))
							$starttime=date("g:i A", strtotime($avl_start));
						else
							$starttime='';
						$endtime=date("g:i A", strtotime($avl_end));
							
						$startval=date("H:i", strtotime($avl_start));
						$endval = date("H:i", strtotime($avl_end));
						
						$startmin=date("i", strtotime($avl_start));
						$endmin = date("i", strtotime($avl_end));
							
									
						// for sorting with starttime
						$contruct_tablebody[$i].='<input type=hidden value="'.$startval.'">';
						$j_hr = date('G', strtotime($j_date));
						$start_hr = date('G', strtotime($avl_start));
						$end_hr = date('G', strtotime($avl_end));
						if($startmin=='00')
							$startclass = 'scheduled';
						else
							$startclass = 'scheduled start-'.$startmin;
						$kend_hr = $end_hr;
						if($endmin=='00')
						{
							$endclass = 'scheduled';
							$end_hr = $end_hr-1;
						}
						else
							$endclass = 'scheduled end-'.$endmin;
						
						if(($j_hr >=$start_hr) && ($j_hr <= $end_hr))
						{
						
							$contruct_tablebody[$i].= '<div class="cell large-auto available">';
							if($j_hr==$start_hr)
								$contruct_tablebody[$i].= '<span class="availability-time">
									<span class="start">'.rtrim($starttime,'M').'</span><span class="end">'.rtrim($endtime,'M').'</span>
								</span>';
							
							if($j_hr == $end_hr)
							{
								$time_diff = abs(strtotime($endval) - strtotime($startval)) / 3600;
								if($k==1)
								{
									$tot_time_diff=$time_diff;
									$hrarr[$i]=$tot_time_diff;
									$payarr[$i]=$emp_avail[$key[$i]][$day]['Basicpay'];
								}
								else
								{
									$tot_time_diff+=$time_diff;
									$hrarr[$i]=$tot_time_diff;
									$payarr[$i]=$emp_avail[$key[$i]][$day]['Basicpay'];
								
								}
								if($startmin=='00' && $endmin=='00' && $time_diff==1)
									$showendtime = $starttime.'-'.date("g:i A", strtotime($avl_end));
								else
									$showendtime = date("g:i A", strtotime($avl_end));
																							
								$contruct_tablebody[$i].= '<span class="'.$endclass.'" onclick="scroll_split(\'' .$startval.'\',\'' .$endval.'\',\'' .$endval.'\');" id=span'.$j.'>'.$showendtime.'</span></div>';
								
							}
							else
							{	
								if($starttime!='' && $startmin!='00')
									$contruct_tablebody[$i].= '<span class="'.$startclass.'" onclick="scroll_split(\'' .$startval.'\',\'' .$endval.'\',\'' .$endval.'\');" id=span'.$j.'>'.$starttime.'</span></div>';
								else
									$contruct_tablebody[$i].= '<span class="scheduled" onclick="scroll_split(\'' .$startval.'\',\'' .$endval.'\',\'' .$endval.'\');" id=span'.$j.'>'.$starttime.'</span></div>';
							}
							$tothr++;
						}
						else
						{
							$contruct_tablebody[$i].='<div class="cell large-auto"></div>';
						}
						
						if($j_hr == $kend_hr)
							$k++;
						
					}
					
					if($k==2)
					{
						if($cus_open=='')
						{
							$contruct_tablebody[$i].='<input type=hidden name=startval id=startval value="'.date("H:i", strtotime($emp_avail[$key[$i]][$day][$k-1][0])).'">
												  <input type=hidden name=endval id=endval value="'.date("H:i", strtotime($emp_avail[$key[$i]][$day][$k-1][1])).'">';
						}
						else
						{
							$contruct_tablebody[$i].='<input type=hidden name=startval id=startval value="'.date("H:i", strtotime($cus_open)).'">
												  <input type=hidden name=endval id=endval value="'.date("H:i", strtotime($cus_close)).'">';
						}
					}
					else
					{
						$contruct_tablebody[$i].='<input type=hidden name=startval id=startval value="">
						<input type=hidden name=endval id=endval value="">';
					}
									
					$contruct_tablebody[$i].='<input type=hidden name=splitval id=splitval>
											  <input type=hidden name=basicpay id=basicpay value='.$emp_avail[$key[$i]][$day]['Basicpay'].'>
											  <input type=hidden name=role id=role value='.$emp_avail[$key[$i]][$day]['Role'].'>
											  <input type="hidden" id="class_name" name="class_name" value="scheduled" />';
											  
					$contruct_tablebody[$i].='</div>
											</td>
											</tr>';
			}// end i loop
		}
				
		$contruct_tablebody[].='<input type="hidden" id="avail_arr" name="avail_arr" value="" />
								<input type="hidden" id="dateval" name="dateval" value="'.$dateval.'" />
								<input type="hidden" id="print_date" name="print_date" value="'.$print_date.'" />
								<input type="hidden" id="publish_print_date" name="publish_print_date" value="'.$publish_print_date.'" />
								<input type="hidden" id="initialpay" name="initialpay" value="" />
								<input type="hidden" id="holiday" name="holiday" value="'.$holiday.'" />
								<input type="hidden" id="store_open" name="store_open" value="'.date("H:i", strtotime($starthr)).'">
								<input type="hidden" id="store_close" name="store_close" value="'.date("H:i", strtotime($Endhr)).'">
								<input type=hidden id=slotwidth name=slotwidth value=15>';// for 15 minutes gap
		/*** to calculate total hours and cost ***/
		foreach($hrarr as $key=>$hr)
		{
			$tothour+=$hr;
			$hour = sprintf('%02d:%02d', (int) $hr, fmod($hr, 1) * 60);
			$contruct_tablebody[].='<input type="hidden" id="totavlhr'.$key.'" name="totavlhr'.$key.'" value="'.$hour.'" />';
			$timeparts=explode(':',$hour);
			$pay=$timeparts[0]*$payarr[$key]+$timeparts[1]/60*$payarr[$key];
			$totpay+=$pay;
		}
		$tothr = sprintf('%02d:%02d', (int) $tothour, fmod($tothour, 1) * 60);
		return array($contruct_tablebody,$tothr,$totpay);	
	}//end function
	
	//view usertable start function
	public function view_usertable($emp_view,$dates)
	{
		$key = array_keys($emp_view);
		for($i=0;$i<count($emp_view);$i++)
		{
			$tbody[$i].='<tr class="staff">';
			$tbody[$i].='<td id="" class="name"><input type="hidden"  name="staff" value="'.$emp_view[$key[$i]]['Emp_name'].'"
								class="staffRow"/>'.$emp_view[$key[$i]]['Emp_name'].'<span id="" class="total-hours">
								'.round($emp_view[$key[$i]]['totalhrs'],2).'</span></td>';
			if($emp_view[$key[$i]]['totalhrs']!=0)
			{
				$hrarr[]=$emp_view[$key[$i]]['totalhrs'];
				$payarr[]=$emp_view[$key[$i]]['Basicpay'];
			}
			
			for($j=0;$j<count($dates);$j++)
			{
				
				$k = date('N', strtotime($dates[$j]));
				$select = "SELECT * FROM tblemployee_availability a where Employeeid='".$key[$i]."' AND Weekday='".$k."'
							ORDER BY a.Starttime asc";//%H:%i"
				$availselect=$this->Tblemployee_availability->query($select);
				
				$avail = array();
				$slot=0;
				if(sizeof($availselect)>0)
				{
					for($a=0;$a<count($availselect);$a++)
					{
						$empid=$availselect[$a]['a'][Employeeid];
						$day=$availselect[$a]['a'][Weekday];
						if($empid1==$empid && $day=$day1)
						{
							$day=$day1;
							$empid=$empid1;
							$slot++;
						}
						else
						{
							$slot=1;
						}
						$strttime=$availselect[$a]['a'][Starttime];
						$endtime=$availselect[$a]['a'][Endtime];
						$avail[$empid][$dates[$j]][$slot][0]=$strttime; 
						$avail[$empid][$dates[$j]][$slot][1]=$endtime;					
						$day1=$day;
						$empid1=$empid;
					}					
				}
				//print_r($avail);echo '<br>';
				$date= date('Y-m-d');				
				$dateval=round(strtotime($dates[$j]) - strtotime($date)) / (60*60*24);
				if($dateval<0)
					$past = ' past';
				else if($dateval>=0)
					$past = '';			
				
				$span ='';
				if(array_key_exists($dates[$j], $emp_view[$key[$i]]))	
				{
					if(sizeof($emp_view[$key[$i]][$dates[$j]])>1)
					{
						for($d=2;$d<=sizeof($emp_view[$key[$i]][$dates[$j]]);$d++)
						{
							$span.='<span class="split">'.$emp_view[$key[$i]][$dates[$j]][$d][0].' - '.$emp_view[$key[$i]][$dates[$j]][$d][1].'</span>';
						}							
						$tbody[$i].='<td class="working'.$past.'"><a href="./users/schedulerday?selecteddate='.$dates[$j].'">'.$emp_view[$key[$i]][$dates[$j]][1][0].' - '.$emp_view[$key[$i]][$dates[$j]][1][1].''.$span.'</a></td>';
					}
					else
					{
						$tbody[$i].='<td class="working'.$past.'"><a href="./users/schedulerday?selecteddate='.$dates[$j].'">'.$emp_view[$key[$i]][$dates[$j]][1][0].' - '.$emp_view[$key[$i]][$dates[$j]][1][1].'</a></td>';
					}					
				}
				else if(array_key_exists($dates[$j], $avail[$key[$i]]))
				{
					if(sizeof($avail[$key[$i]][$dates[$j]])>1)
					{
						for($d=2;$d<=sizeof($avail[$key[$i]][$dates[$j]]);$d++)
						{
							$avail_span.='<span class="split">'.$avail[$key[$i]][$dates[$j]][$d][0].' - '.$avail[$key[$i]][$dates[$j]][$d][1].'</span>';
						}
						$tbody[$i].='<td class="available'.$past.'"><a href="'.$_SERVER[REQUEST_URI].'users/schedulerday?selecteddate='.$dates[$j].'">'.$avail[$key[$i]][$dates[$j]][1][0].' - '.$avail[$key[$i]][$dates[$j]][1][1].''.$avail_span.'</a></td>';
					}
					else
					{
						$tbody[$i].='<td class="available'.$past.'"><a href="./users/schedulerday?selecteddate='.$dates[$j].'">'.$avail[$key[$i]][$dates[$j]][1][0].' - '.$avail[$key[$i]][$dates[$j]][1][1].'</a></td>';
					}
				}
				else
				{
					$tbody[$i].='<td class="off'.$past.'"><a href="#"></a></td>';
					 
				}
				
			}
			
			$tbody[$i].='</tr>';
		}
		
		//labor cost and labor hours
		for($l=0;$l<count($hrarr);$l++)
		{
			$tothour+=$hrarr[$l];
			$hour = sprintf('%02d:%02d', (int) $hrarr[$l], fmod($hrarr[$l], 1) * 60);
			$pay=$hour*$payarr[$l];
			$totpay+=$pay;
		}
		$tothr = sprintf('%02d:%02d', (int) $tothour, fmod($tothour, 1) * 60);
		
		return array($tbody,$totpay,$tothr);
	}
	//end function
	
	function getschedulestart($orgid)
	{	
		$schedule_start_qry = 'SELECT `Schedule_start_day` FROM `tblorganization` WHERE `Orgid`='.$orgid;
		$schedule_start_arr = $this->Tblorganization->query($schedule_start_qry);
		$schedule_start_day = $schedule_start_arr[0]['tblorganization']['Schedule_start_day'];
		return  $schedule_start_day;
		
	}
	
	/*** get last published date ****/
	function getlastpublished()
	{
		$last_publish_qry = "SELECT `Date` FROM `tblschedule` WHERE `Publish`='Yes' ORDER BY `Date` DESC LIMIT 1";
		$lastpublish_arr = $this->Tblschedule->query($last_publish_qry);
		return $lastpublishdate = $lastpublish_arr[0]['tblschedule']['Date'];
	}
	
	/*** show holidays in storehours page *********/
	function  setholiday()
	{
	
		$holidayarr =$this->Tblholidays->find('all', array(
								'order' => array('Tblholidays.Holiday_date' => 'ASC')
							));
		$this->Paginator->settings = $this->paginate= array('table' => 'tblholidays','order' => array('Tblholidays.Holiday_date' => 'ASC'),'limit' => 2);
		$holidayarr = $this->Paginator->paginate('Tblholidays');
		for($h=0; $h<count($holidayarr); $h++)
		{
			$holidayid = $holidayarr[$h]['Tblholidays']['HolidayId'];
			$holidaydate = $holidayarr[$h]['Tblholidays']['Holiday_date'];
			$holidaydesc = $holidayarr[$h]['Tblholidays']['Holiday_description'];
			$holidayopen = $holidayarr[$h]['Tblholidays']['Open_time'];
			$holidayclose = $holidayarr[$h]['Tblholidays']['Close_time'];
			$tbody.='<tr>';
				$tbody.= '<td>'.date('l',strtotime($holidaydate)).','.date('F d, Y', strtotime($holidaydate)).'</td>';
				$tbody.= '<td>'.$holidaydesc.'</td>';
				if($holidayopen!='')
					$holidayopen = date('h:i A', strtotime($holidayopen));
				else
					$holidayopen = '';
				
				$tbody.= '<td>'.$holidayopen.'</td>';
									
				if($holidayclose!='')
					$holidayclose = date('h:i A', strtotime($holidayclose));
				else
					$holidayclose = '';
				
				$tbody.= '<td>'.$holidayclose.'</td>';
				if($holidayopen=='' && $holidayclose=='')
					$tbody.= '<td class="repeat"></td>';
				else
					$tbody.= '<td></td>';
				$tbody.= '<td>
                            <div class="button-group tiny float-right">
                                <a class="button secondary" onclick=editholiday('.$holidayid.');>Edit</a>
                                <a class="button alert" onclick=deleteholiday('.$holidayid.');>Delete</a>
                            </div>
                       </td>';
				
			$tbody.= '</tr>';
		}
		return $tbody;
	}
	function getcustomfields()
	{
		$orgid = $this->Session->read('orgid');
		$sel_qry = "SELECT * FROM `tblorg_attribute` WHERE `Orgid`=".$orgid." ORDER BY `DateCreated` DESC";
		$selcustom_arr = $this->Tblorg_attribute->query($sel_qry);
		return $selcustom_arr;
	}
	function getallemp()
	{
		$orgid = $this->Session->read('orgid');
		
		$this->Paginator->settings = $this->paginate= array('table' => 'tblusers','conditions'=>array('Tblusers.Orgid='.$orgid),'order' => array('Tblusers.Employee_First_Name' => 'ASC'),'limit' => 5);//array('Tblusers.Orgid'=>$orgid)
		$sel_emp_arr = $this->Paginator->paginate('Tblusers');
			
				
		$sel_custom_qry = "SELECT tbluser_attribute.* FROM `tbluser_attribute` 
							LEFT OUTER JOIN tblorg_attribute ON 
							tbluser_attribute.Attrid=tblorg_attribute.Attributeid 
							WHERE tblorg_attribute.Orgid=".$orgid." ORDER BY tbluser_attribute.Userid ASC";
		$sel_custom_arr = $this->Tbluser_attribute->query($sel_custom_qry);
		return array($sel_emp_arr,$sel_custom_arr);
	}
	function recentuser()
	{
		$sel_userid_qry = "SELECT `Userid` FROM `tblusers` ORDER BY `DateCreated` DESC LIMIT 1";
		$sel_userid_arr = $this->Tblusers->query($sel_userid_qry);
		$userid = $sel_userid_arr[0]['tblusers']['Userid'];
		return $userid;
	}
	function getrole()
	{
		$orgid = $this->Session->read('orgid');
		$role_qry = "SELECT * FROM `tblrole` WHERE `Orgid` =".$orgid." ORDER BY `Rolename` ASC";
		$role_arr = $this->Tblrole->query($role_qry);
		return $role_arr;
	}
}

