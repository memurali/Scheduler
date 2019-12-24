<?php

error_reporting(0);
class UsersController extends AppController
{ 
	var $helpers = array('Paginator'); 
	public $uses = array('Tblemployee_availability','Tblusers','Tblschedule','Tblbusinesshours','Tblholidays','Tblorganization','Tblorg_attribute','Tbluser_attribute','Tblrole','Tbluser_role');
	public $components = array('Paginator');
	
	
	public function beforeFilter()
    {
        $this->Auth->allow('index','dashboardweek','schedulerday','storehours','foundation','publishedday','publishedweek','signup','employees');
	}
	function index()
	{		
		$this->Session->write('orgid', 1);
		$this->Session->write('userid', 1);
		$this->layout = false;
		$orgid = $this->Session->read('orgid');
		$schedule_start_day = parent::getschedulestart($orgid);
		$daynum = date('N', strtotime($schedule_start_day));
		
		if($this->request->is('ajax'))
		{ 
			if($this->request->data['action']=='published')
			{
				$date = $this->request->data['publishdate'];
				$selpub_query ="SELECT Date FROM `tblschedule` WHERE DAYNAME(Date)='".$schedule_start_day."' AND 
					MONTH(Date) = MONTH('".$date."') AND Publish='YES' 
					GROUP BY `Date` ORDER BY `Date` DESC";
				$publisharr = $this->Tblschedule->query($selpub_query);
				if(count($publisharr)>0)
				{
					foreach($publisharr as $publish)
					{
						$publisheddate = $publish['tblschedule']['Date'];
						$list.= '<li><a href="./?selecteddate='.$publisheddate.'">'.'<span>'.date('F d,Y', strtotime($publisheddate)).'</span> - <span>'.date('F d,Y', strtotime('+6 day', strtotime($publisheddate))).'</span></a></li>';
						
					}
				}
				echo $list;
				exit;
			}
		}
		
		//theader section
		$today = time();
		$wday = date('w', $today);
		$reqdate = $_REQUEST['selecteddate'];
		for($i=0;$i<=6;$i++)
		{
			$newdaynum = $daynum+$i;
			if($reqdate=='')
				$date= date('Y-m-d', $today - ($wday - $newdaynum)*86400); 
			else
				$date = date('Y-m-d',strtotime('+'.$i.' day', strtotime($reqdate)));
			$dates[] = $date;
			$day = strtoupper(date("D", strtotime($date))).' '.date("d", strtotime($date));
			$sel_query = "SELECT * FROM `tblholidays` WHERE `Holiday_date`='".$date."' and Orgid=1";
			$holiday_arr=$this->Tblemployee_availability->query($sel_query);
			if(count($holiday_arr)>0)
				$holiday= $holiday_arr[0]['tblholidays']['Holiday_description'];
			else
				$holiday= '';
						
			if($date>date('Y-m-d'))
				$thead.='<th width="50"><a href="./users/schedulerday?selecteddate='.$date.'">'.$day.'</a></th>';
			else if($date<date('Y-m-d'))
				$thead.='<th width="50" class="past"><a href="./users/schedulerday?selecteddate='.$date.'">'.$day.'</a></th>';
			else
				$thead.='<th width="50" class="today"><a href="./users/schedulerday?selecteddate='.$date.'">'.$day.'<span class="day-note">'.$holiday.'</span></a></th>';
		}
		
		//tbody section				
		$select="SELECT s.*,a.Userid,a.Employee_First_Name,a.Employee_Last_Name,c.Rate 
				FROM tblrole c, tbluser_role d,tblusers a LEFT JOIN tblschedule s ON
				a.Userid=s.Employeeid WHERE a.Status ='Y' AND
				d.Userid = a.Userid AND d.Roleid = c.Roleid 
				order by a.Userid,s.Date Asc";		
		$viewselect=$this->Tblschedule->query($select);
		
		for($i=0;$i<count($viewselect);$i++)
		{
			$empid=$viewselect[$i]['a']['Userid'];
			$date=$viewselect[$i]['s']['Date'];
			
			if($empid1==$empid && $date==$date1)
			{
				$date=$date1;
				$empid=$empid1;
				$slot++;
			}
			else
			{
				$slot=1;
			}
			$emp_view[$empid]['Emp_name']=$viewselect[$i]['a'][Employee_First_Name].' '.$viewselect[$i]['a'][Employee_Last_Name];
			$starttime = $viewselect[$i]['s'][StartTime];
			$endtime = $viewselect[$i]['s'][Endtime];
			if (in_array($date, $dates))
				$time_diff= abs(strtotime($endtime) - strtotime($starttime)) / 3600;
			else
				$time_diff= 0;	
			$emp_view[$empid]['totalhrs']+=$time_diff;
			$emp_view[$empid]['Basicpay']=$viewselect[$i]['c'][Rate];
			$emp_view[$empid][$date][$slot][0]=$starttime; 
			$emp_view[$empid][$date][$slot][1]=$endtime;			
			$date1=$date;
			$empid1=$empid;
			$time_diff= 0;
		}
		
		// shows drafts dates
		$draft_qry = "SELECT Date,DAYNAME(`Date`) as lastday FROM `tblschedule` ORDER by Date DESC limit 1";
		$draft_arr = $this->Tblschedule->query($draft_qry);
		if(count($draft_arr)>0)
		{
			$lastdate = $draft_arr[0]['tblschedule']['Date'];
			$lastday = $draft_arr[0][0]['lastday'];
			$nxtdate_after = date('Y-m-d', strtotime('+1 day', strtotime($lastdate)));
			$nxtday = date("l", strtotime($nxtdate_after));
			$nxt_schedule_start = date('Y-m-d', strtotime('next '.$schedule_start_day, strtotime($lastdate)));
			$date_from = strtotime($nxtdate_after);
			$date_to = strtotime($nxt_schedule_start);
			if($nxtday!=$schedule_start_day)
			{
				for ($i=$date_from; $i<$date_to; $i+=86400) 
				{  
					$draftsarr[] = date("Y-m-d", $i);  
				} 
			}
		}
		$view_tablebody=parent::view_usertable($emp_view,$dates);
		$this->set('tbody',$view_tablebody[0]);
		$this->set('laborcost',$view_tablebody[1]);	
		$this->set('laborhours',$view_tablebody[2]);
		$this->set('currentweek',date('F d', strtotime($dates[0])).' – '.date('F d, Y', strtotime($dates[6])));
		$this->set('thead',$thead);	
		$this->set('drafts',$draftsarr);	
		$this->set('schedulestart',$schedule_start_day);
		
		/** show published and unpublished schedules ***/
		$selunpub_query ="SELECT Date FROM `tblschedule` WHERE DAYNAME(Date)='".$schedule_start_day."'
						  AND Publish='' GROUP BY `Date` ORDER BY `Date` ASC";
		$unpublisharr = $this->Tblschedule->query($selunpub_query);
		foreach($unpublisharr as $unpublish)
		{
			$unpublisheddate[] = $unpublish['tblschedule']['Date'];
		}
		
		
		$selpub_query ="SELECT Date FROM `tblschedule` WHERE DAYNAME(Date)='".$schedule_start_day."' AND 
					MONTH(Date) = MONTH(CURRENT_DATE()) AND Publish='YES' 
					GROUP BY `Date` ORDER BY `Date` DESC";
		$publisharr = $this->Tblschedule->query($selpub_query);
		foreach($publisharr as $publish)
		{
			$publisheddate[] = $publish['tblschedule']['Date'];
		}
		
		$this->set('publisheddates',$publisheddate);
		$this->set('unpublisheddates',$unpublisheddate);
	}
	function dashboardweek()
	{
		$this->Session->write('orgid', 1);
		$this->Session->write('userid', 1);
	}
	function schedulerday()
	{
		$this->Session->write('orgid', 1);
		$this->Session->write('userid', 1);
		$this->layout = false;		
		if($this->request->is('ajax'))
		{ 
			if($this->request->data['action']=='publish')
			{
				$date = $this->request->data['date'];
				for($d=0; $d<=6; $d++)
				{
					$dateval = date('Y-m-d', strtotime('-'.$d.' day', strtotime($date)));
					$update_qry = "UPDATE `tblschedule` SET `Publish`='Yes' WHERE `Date` = '".$dateval."'";
					$this->Tblschedule->query($update_qry);
				}
				exit;
			}
			else if($this->request->data['action']=='next'||'prev'||'24hrs'||'today'||'calendar')
			{
				
				$dateval = $this->request->data['dateval'];
				$type = $this->request->data['type'];	
				$day=$this->request->data['currentdate'];
				//saving process
				if($this->request->data['action']=='next'||'prev'||'save')
				{
					$data=$this->request->data['json_data'];
					$schedule_saving=parent::schedule_saving($data,$dateval);
				}
				if($this->request->data['action']=='save')
					$newdateval = date('Y-m-d', strtotime($dateval));
				if($this->request->data['action']=='next')
					$newdateval = date('Y-m-d', strtotime('+1 day', strtotime($dateval)));
				if($this->request->data['action']=='prev')
					$newdateval = date('Y-m-d', strtotime('-1 day', strtotime($dateval)));
				if($this->request->data['action']=='24hrs')
					$newdateval = date('Y-m-d', strtotime($dateval));
				if($this->request->data['action']=='calendar')
					$newdateval = date('Y-m-d', strtotime($dateval));
				if($this->request->data['action']=='today')
				{
					$week_num = date('W');
					$timestamp    = strtotime(date('Y') . '-W' . $week_num . '-' . $day);
					$newdateval = date('Y-m-d', $timestamp);
				}
				//echo $this->request->data['action'];
				//exit;
				$emp_avail = $this->constrctarr($newdateval,$day,'all');
				
				//view employee
				$create_tablebody=parent::create_usertable($emp_avail,$day,$type,$newdateval);
				$this->set('create_tablebody',$create_tablebody[0]);
				$myObj->tablebody = $create_tablebody[0];	
				$myObj->tothrs = $create_tablebody[1];
				$myObj->totcost=$create_tablebody[2];
				$myJSON = json_encode($myObj);
				echo $myJSON;
				exit;
				
			}		
			
		}
		else
		{
			$orgid = $this->Session->read('orgid');
			$schedule_start_day = parent::getschedulestart($orgid);
			$schedule_start_daynum = date('N', strtotime($schedule_start_day));
			if($_REQUEST['selecteddate']=='')
			{
				$day = $schedule_start_daynum;
				$select = "SELECT Date FROM `tblschedule` 
								WHERE DAYNAME(Date)='".$schedule_start_day."'
								ORDER BY `Date` DESC LIMIT 1";
				$select_arr = $this->Tblschedule->query($select);
				if(count($select_arr)>0)
				{
					$lastdate = $select_arr[0]['tblschedule']['Date'];
					$dateval = date('Y-m-d',strtotime('+7 day', strtotime($lastdate)));
				}
				else
				{
					$week_num = date('W');
					$timestamp    = strtotime(date('Y') . '-W' . $week_num . '-' . $day);
					$dateval = date('Y-m-d', $timestamp);
				}
			}
			else
			{
				$dateval = $_REQUEST['selecteddate'];
				$day = date('N', strtotime($dateval));
			}
			/** To get last published date **/
			$lastpublishdate = parent::getlastpublished();
			
			$emp_avail = $this->constrctarr($dateval,$day,'all');
			$this->set('date',$day);
			$create_tablebody=parent::create_usertable($emp_avail,$day,'',$dateval);
			$this->set('create_tablebody',$create_tablebody[0]);
			$this->set('totalhour',$create_tablebody[1]);
			$this->set('totalcost',round($create_tablebody[2],2));
			$this->set('schedule_start',$schedule_start_daynum);
			$this->set('lastpublishdate',$lastpublishdate);
			$this->set('dateval',$dateval);
			$this->set('pagename', $this->request->params['action']);
		}
		
		
	}
	
	/*** funtion to construct array  ***/
	function constrctarr($date,$dayval,$mode)
	{
		if($mode=='published')
			$cond = 'AND a.Publish!=""';
		else
			$cond = '';
		$select = "SELECT b.Employee_First_Name,b.Employee_Last_Name,b.Userid, 
					b.Status,b.DateCreated,a.Employeeid,a.Starttime, 
					a.Endtime,a.Date FROM tblschedule a, 
					tblusers b WHERE a.Employeeid = b.Userid and 
					a.Date = '".$date."' ".$cond." 
					order by a.Employeeid,a.Starttime asc";
		$exeselect=$this->Tblschedule->query($select);
		$morerole = 'yes';
		if(count($exeselect)==0)
		{
			if($mode=='all')
			{
				$select='SELECT b.Employee_First_Name,b.Employee_Last_Name,
						b.Userid, b.Status,b.DateCreated,a.Employeeid,
						a.Starttime, a.Endtime,a.Weekday,c.Rolename, c.Rate 
						FROM tblemployee_availability a, tblusers b,tblrole c, tbluser_role d 
						WHERE a.Employeeid = b.Userid AND c.Roleid = d.Roleid AND 
						d.Userid = b.Userid AND b.Status="Y" AND d.Status = "Y"
						order by a.Employeeid,a.Weekday,a.Starttime asc';
				$exeselect=$this->Tblemployee_availability->query($select);
				$morerole = '';
			}
		}
		for($i=0;$i<count($exeselect);$i++)
		{
			$empid=$exeselect[$i]['a'][Employeeid];
			if($exeselect[$i]['a']['Date']=='')
				$day=$exeselect[$i]['a'][Weekday];
			else
			{
				$day=$dayval;
				$emp_avail[$empid]['scheduledate']=$exeselect[$i]['a']['Date'];
			}
			if($empid1==$empid && $day==$day1)
			{
				$day=$day1;
				$empid=$empid1;
				$slot++;
			}
			else
			{
				$slot=1;
			}
			
			$startTime=$exeselect[$i]['a'][Starttime];
			$EndTime=$exeselect[$i]['a'][Endtime];
			$emp_avail[$empid]['Emp_name']=$exeselect[$i]['b'][Employee_First_Name].' '.$exeselect[$i]['b'][Employee_Last_Name];
			if($morerole=='')
			{
				$emp_avail[$empid][$day]['Role']=$exeselect[$i]['c'][Rolename];
				$emp_avail[$empid][$day]['Basicpay']=$exeselect[$i]['c'][Rate];
			}
			else
			{
				$sel_role_qry = "SELECT d.*,c.Rolename,c.Rate FROM tblrole c,tbluser_role d 
								WHERE c.Roleid=d.Roleid AND `Userid`=".$empid;
				$sel_role_arr = $this->Tblrole->query($sel_role_qry);
				if(count($sel_role_arr)>1)
				{
					$dateval = $exeselect[$i]['a']['Date'];
					foreach($sel_role_arr as $selrole)
					{
						$datetime = $selrole['d']['Date_creted'];
						$dateval = date('Y-m-d', strtotime($datetime));
						if(strtotime($dateval)<=strtotime($date))
						{
							$emp_avail[$empid][$day]['Role']=$selrole['c'][Rolename];
							$emp_avail[$empid][$day]['Basicpay']=$selrole['c'][Rate];
							break;
						}
						
					}
				}
				else
				{
					$emp_avail[$empid][$day]['Role']=$sel_role_arr[0]['c'][Rolename];
					$emp_avail[$empid][$day]['Basicpay']=$sel_role_arr[0]['c'][Rate];
				}
				
				
			}
			//echo $exeselect[$i]['b'][Employee_First_Name].'rolename  '.$emp_avail[$empid][$day]['Role'].'  '.$emp_avail[$empid][$day]['Basicpay'].'<br>';
			$emp_avail[$empid][$day][$slot][0]=$startTime; 
			$emp_avail[$empid][$day][$slot][1]=$EndTime;
			
			$day1=$day;
			$empid1=$empid;
			//$slot1=$slot;
				
		}
		return $emp_avail;
	}
	function storehours()
	{
		$this->layout = false;	
		$this->Session->write('orgid', 1);
		$this->Session->write('userid', 1);
		$orgid = $this->Session->read('orgid');
		
		$sel_qry = "SELECT o.`Schedule_start_day`,b.* 
					FROM `tblorganization` o,tblbusinesshours b 
					WHERE o.`Orgid`=b.`Orgid` AND b.`Orgid`=".$orgid;
		$sel_arr =  $this->Tblbusinesshours->query($sel_qry);
		$this->set('businesshrs',$sel_arr);
		
		$holiday_tbody = parent::setholiday();
		$this->set('holiday_tbody',$holiday_tbody);
		$this->Paginator->settings = $this->paginate= array('table' => 'tblholidays','order' => array('Tblholidays.Holiday_date' => 'ASC'),'limit' => 2);
		$data = $this->Paginator->paginate('Tblholidays');
		if($this->request->is('ajax'))
		{
			if($this->request->data['action']=='save_businesshrs')
			{
				parse_str($_POST[ 'formdata' ], $formval);
				$schedule_start = $formval['schedule_start'];
				$update_qry = "UPDATE `tblorganization` SET `Schedule_start_day`='".$schedule_start."' 
								WHERE `Orgid`=".$orgid;
				$this->Tblorganization->query($update_qry);
				
				$daysarr=['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
				$select_hrs_qry = "SELECT * FROM `tblbusinesshours` WHERE Orgid=".$orgid;
				$sel_hrs_arr = $this->Tblbusinesshours->query($select_hrs_qry);
				if(count($sel_hrs_arr)==0)
				{
					for($j=0; $j<count($daysarr); $j++)
					{
						$insert_query = "INSERT INTO `tblbusinesshours`(`Orgid`, `Weekday`, `Starttime`, `Endtime`) 
								  VALUES (".$orgid.",'".$daysarr[$j]."','".$formval['start_'.$j]."','".$formval['end_'.$j]."')";
						$this->Tblbusinesshours->query($insert_query);	  
					}
				}
				else
				{
					for($j=0; $j<count($daysarr); $j++)
					{
						$update_query = "UPDATE `tblbusinesshours` SET `Starttime`='".$formval['start_'.$j]."',
										`Endtime`='".$formval['end_'.$j]."' WHERE 
										`Weekday`='".$daysarr[$j]."' AND Orgid =".$orgid;
						$this->Tblbusinesshours->query($update_query);
					}
				}
			}
			if($this->request->data['action']=='save_holiday')
			{
				parse_str($_POST[ 'formdata' ], $formval);
				$holidayid = $formval['holidayid'];
				$openhr = $formval['timeopen'];
				$closehr = $formval['timeclose'];
				$checkval = $formval['checkbox_closed'];
				if($holidayid=='')
				{
					if($checkval=='on')
					{
						$insert_qry = "INSERT INTO `tblholidays`(`Orgid`, `Holiday_description`, `Holiday_date`) 
										VALUES (".$orgid.",'".mysql_escape_string($formval['holiday_note'])."','".$formval['holiday_date']."')";
						
					}
					else
					{
						$insert_qry = "INSERT INTO `tblholidays`(`Orgid`, `Holiday_description`, 
															`Holiday_date`, `Open_time`, `Close_time`) 
								   VALUES (".$orgid.",'".$formval['holiday_note']."',
											'".$formval['holiday_date']."','".$openhr."','".$closehr."')";
					}
					$this->Tblholidays->query($insert_qry);
				}
				else
				{
					if($checkval=='on')
					{
						$update_qry = "UPDATE `tblholidays` SET 
									`Holiday_description`='".mysql_escape_string($formval['holiday_note'])."',
									`Holiday_date`='".$formval['holiday_date']."' WHERE `HolidayId`=".$holidayid;
					}
					else
					{
						$update_qry = "UPDATE `tblholidays` SET 
									`Holiday_description`='".mysql_escape_string($formval['holiday_note'])."',
									`Holiday_date`='".$formval['holiday_date']."',`Open_time`='".$openhr."',
									`Close_time`='".$closehr."' WHERE `HolidayId`=".$holidayid;
					}
					$this->Tblholidays->query($update_qry);
				}
				$holiday_tbody = parent::setholiday();
				echo $holiday_tbody;
				exit;
			}
			if($this->request->data['action']=='editholiday')
			{
				if($this->request->data['id']!='')
				{
					$holidayid = $this->request->data['id'];
					$sel_qry = "SELECT * FROM `tblholidays` WHERE `HolidayId`=".$holidayid;
					$holidayarr = $this->Tblholidays->query($sel_qry);
					$holidayid = $holidayarr[0]['tblholidays']['HolidayId'];
					$holidaydate = $holidayarr[0]['tblholidays']['Holiday_date'];
					$holidaydesc = $holidayarr[0]['tblholidays']['Holiday_description'];
					$holidayopen = $holidayarr[0]['tblholidays']['Open_time'];
					$holidayclose = $holidayarr[0]['tblholidays']['Close_time'];
					$myObj->holidaydate = $holidaydate;	
					$myObj->holidaydesc = $holidaydesc;
					$myObj->holidayopen = $holidayopen;
					$myObj->holidayclose = $holidayclose;
					$myJSON = json_encode($myObj);
					echo $myJSON;
				}
			}
			if($this->request->data['action']=='deleteholiday')
			{
				if($this->request->data['id']!='')
				{
					$holidayid = $this->request->data['id'];
					$del_qry = "DELETE FROM `tblholidays` WHERE `HolidayId`=".$holidayid;
					$this->Tblholidays->query($del_qry);
					
					$holiday_tbody = parent::setholiday();
					echo $holiday_tbody;
					exit;
				}
			}
			exit;
		}
		
		
		
	}
	function foundation()
	{
		
	}
	function publishedday()
	{
		$this->Session->write('orgid', 1);
		$this->Session->write('userid', 1);
		$this->layout = false;
		$orgid = $this->Session->read('orgid');
		$schedule_start_day = parent::getschedulestart($orgid);
		$schedule_start_daynum = date('N', strtotime($schedule_start_day));
		$sel_qry = "SELECT Date FROM `tblschedule` 
								WHERE DAYNAME(Date)='".$schedule_start_day."'
								AND Publish!=''
								ORDER BY `Date` DESC LIMIT 1";
		$sel_arr = $this->Tblschedule->query($sel_qry);
		$date = $sel_arr[0]['tblschedule']['Date'];
		$daynum = date('N', strtotime($schedule_start_day));
		if($this->request->is('ajax'))
		{ 
			if($this->request->data['action']=='next'||'prev'||'24hrs'||'today' )
			{
				
				$dateval = $this->request->data['dateval'];
				$type = $this->request->data['type'];					
				$day=$this->request->data['currentdate'];
				
				if($this->request->data['action']=='next')
					$newdateval = date('Y-m-d', strtotime('+1 day', strtotime($dateval)));
				if($this->request->data['action']=='prev')
					$newdateval = date('Y-m-d', strtotime('-1 day', strtotime($dateval)));
				if($this->request->data['action']=='24hrs'||'calendar')
					$newdateval = date('Y-m-d', strtotime($dateval));
				if($this->request->data['action']=='today')
				{
					$week_num = date('W');
					$timestamp    = strtotime(date('Y') . '-W' . $week_num . '-' . $day);
					$newdateval = date('Y-m-d', $timestamp);
				}
				$emp_avail = $this->constrctarr($newdateval,$day,'published');
				
				//view employee
				$create_tablebody=parent::create_usertable($emp_avail,$day,$type,$newdateval);
				$this->set('create_tablebody',$create_tablebody[0]);
				$myObj->tablebody = $create_tablebody[0];	
				$myObj->tothrs = $create_tablebody[1];
				$myObj->dateval = $newdateval;
				$myJSON = json_encode($myObj);
				echo $myJSON;
				exit;
				
			}
		}
		$schedulearr = $this->constrctarr($date,$daynum,'published');
		$create_tablebody=parent::create_usertable($schedulearr,$daynum,'',$date);
		$last_published_date = parent::getlastpublished();
		
		$this->set('lastpublishdate',$last_published_date);
		$this->set('create_tablebody',$create_tablebody[0]);
		$this->set('totalhour',$create_tablebody[1]);
		$this->set('schedule_start',$schedule_start_daynum);
		$this->set('pagename', $this->request->params['action']);
		$this->set('date',$daynum);
		$this->set('dateval',$date);
		
	}
	function publishedweek()
	{
		$this->Session->write('orgid', 1);
		$this->Session->write('userid', 1);
		$this->layout = false;
		$orgid = $this->Session->read('orgid');
		$schedule_start_day = parent::getschedulestart($orgid);
		$reqdate = $_REQUEST['date'];
		if(date('l',strtotime($reqdate))!=ucfirst($schedule_start_day))
			$schedule_start = date('Y-m-d', strtotime('previous '.$schedule_start_day, strtotime($reqdate)));
		else
			$schedule_start = $reqdate;
		
		$prevday = date('l',strtotime("-1 days",strtotime($schedule_start_day)));
		if(date('l',strtotime($reqdate))!= $prevday)
			$schedule_end = date('Y-m-d', strtotime('next '.$prevday, strtotime($reqdate)));
		else
			$schedule_end = $reqdate;
		
		$sel_qry="SELECT s.*,a.Userid,a.Employee_First_Name,a.Employee_Last_Name FROM tblusers a 
				  LEFT JOIN tblschedule s ON a.Userid=s.Employeeid WHERE `Date` 
				  BETWEEN '".$schedule_start."' AND '".$schedule_end."' AND
                  s.Publish!='' AND				  
				  a.Status ='Y' order by a.Userid,s.Date Asc";//a.Basicpay
		$viewselect = $this->Tblschedule->query($sel_qry);
		
		/**** thead section ****/
		for($i=0;$i<=6;$i++)
		{
			$date = date('Y-m-d',strtotime('+'.$i.' day', strtotime($schedule_start)));
			$dates[] = $date;
			$day = strtoupper(date("D", strtotime($date))).' '.date("d", strtotime($date));
			$sel_query = "SELECT * FROM `tblholidays` WHERE `Holiday_date`='".$date."' and Orgid=1";
			$holiday_arr=$this->Tblemployee_availability->query($sel_query);
			if(count($holiday_arr)>0)
				$holiday= $holiday_arr[0]['tblholidays']['Holiday_description'];
			else
				$holiday= '';
						
			if($date>date('Y-m-d'))
				$thead.='<th width="50"><a href="./users/schedulerday?selecteddate='.$date.'">'.$day.'</a></th>';
			else if($date<date('Y-m-d'))
				$thead.='<th width="50" class="past"><a href="./users/schedulerday?selecteddate='.$date.'">'.$day.'</a></th>';
			else
				$thead.='<th width="50" class="today"><a href="./users/schedulerday?selecteddate='.$date.'">'.$day.'<span class="day-note">'.$holiday.'</span></a></th>';
		}
		
		/**** tbody section ****/	
		if(count($viewselect)>0)
		{
			for($i=0;$i<count($viewselect);$i++)
			{
				$empid=$viewselect[$i]['a']['Userid'];
				$date=$viewselect[$i]['s']['Date'];
				
				if($empid1==$empid && $date==$date1)
				{
					$date=$date1;
					$empid=$empid1;
					$slot++;
				}
				else
				{
					$slot=1;
				}
				$emp_view[$empid]['Emp_name']=$viewselect[$i]['a'][Employee_First_Name].' '.$viewselect[$i]['a'][Employee_Last_Name];
				$starttime = $viewselect[$i]['s'][StartTime];
				$endtime = $viewselect[$i]['s'][Endtime];
				if (in_array($date, $dates))
					$time_diff= abs(strtotime($endtime) - strtotime($starttime)) / 3600;
				else
					$time_diff= 0;	
				$emp_view[$empid]['totalhrs']+=$time_diff;
				//$emp_view[$empid]['Basicpay']=$viewselect[$i]['a'][Basicpay];
				$emp_view[$empid][$date][$slot][0]=$starttime; 
				$emp_view[$empid][$date][$slot][1]=$endtime;			
				$date1=$date;
				$empid1=$empid;
				$time_diff= 0;
			}
		}
		else
			$errormsg = 'There is no published schedules for that date';
		if($errormsg!='')
			$this->set('error',$errormsg);
		$view_tablebody=parent::view_usertable($emp_view,$dates);
		$this->set('tbody',$view_tablebody[0]);
		$this->set('thead',$thead);	
		$this->set('laborhours',$view_tablebody[2]);
		$this->set('schedule_start',$schedule_start);
		$this->set('schedule_end',$schedule_end);
		$last_published_date = parent::getlastpublished();
		$this->set('lastpublishdate',$last_published_date);
		$this->set('pagename', $this->request->params['action']);
	}
	function signup()
	{
		
	}
	function employees()
	{
		$this->layout = false;
		$this->Session->write('orgid', 1);
		$orgid = $this->Session->read('orgid');
		$date = date("Y-m-d H:i:s"); 
		if($this->request->is('post'))
		{ 
			if($this->request->data['emp_fname']!='')
			{ 
				$fname = $this->request->data['emp_fname'];
				$lname = $this->request->data['emp_lname'];
				$addr = $this->request->data['emp_addr'];
				$city = $this->request->data['emp_city'];
				$state = $this->request->data['emp_state'];
				$zip = $this->request->data['emp_zip'];
				$phone = $this->request->data['emp_phone'];
				$email = $this->request->data['emp_email'];
				$status = $this->request->data['emp_status'];
				$roleid = $this->request->data['sel_role'];
				if($status=='on')
					$status = 'Y';
				else
					$status = 'N';
				$basicpay = $this->request->data['emp_basicpay'];
				if($_FILES['profileimg']['name']!='')
				{
					$profileimg = addslashes(file_get_contents($_FILES["profileimg"]["tmp_name"]));
				}
				$insert_qry = "INSERT INTO `tblusers`( `Orgid`, `Employee_First_Name`, `Employee_Last_Name`, 
													`Profile_Img`, `Address`, `Email`, `Cell`, 
													`City`, `State`, `Zip`, `Status`) 
											VALUES (".$orgid.",'".$fname."','".$lname."',
													'".$profileimg."','".$addr."','".$email."',".$phone.",
													'".$city."','".$state."',".$zip.",'".$status."')";
				$this->Tblusers->query($insert_qry);
				
				$userid = parent::recentuser();	
				$ins_role_qry = "INSERT INTO `tbluser_role`(`Userid`, `Roleid`, `Status`) 
								 VALUES (".$userid.",".$roleid.",'Y')";
				$this->Tbluser_role->query($ins_role_qry);
	
				if($this->request->data['customcount']!='')
				{
					$userid = parent::recentuser();					
					$customcount = $this->request->data['customcount'];
					for($i=0; $i<$customcount; $i++)
					{
						$attrid = $this->request->data['custom'.$i];
						$sel_attr_type = "SELECT `KeypairType` FROM `tblorg_attribute` WHERE `Attributeid`=".$attrid;
						$sel_attr_arr = $this->Tblorg_attribute->query($sel_attr_type);
						$attr_type = $sel_attr_arr[0]['tblorg_attribute']['KeypairType'];
						
											
						if($this->request->data['custom_'.$attrid]!='')
							$attrval=$this->request->data['custom_'.$attrid];
						else
							$attrval='';
						if($attr_type=='file')
						{
							$attrval=$_FILES['custom_'.$attrid]['name'];
							$upload_img="Customfield_Files/".$userid.'_'.$attrval;
							move_uploaded_file($_FILES['custom_'.$attrid]["tmp_name"],$upload_img);
						}
						$insert_qry = "INSERT INTO `tbluser_attribute`(`Attrid`, `Userid`, `Userattr_val`) 
									VALUES (".$attrid.",".$userid.",'".$attrval."')";
							
						$this->Tbluser_attribute->query($insert_qry);
					}
				}
			}
			if($this->request->data['update_fname']!='')
			{
				$userid = $this->request->data['userid'];
				$fname = $this->request->data['update_fname'];
				$lname = $this->request->data['update_lname'];
				$addr = $this->request->data['update_addr'];
				$city = $this->request->data['update_city'];
				$state = $this->request->data['update_state'];
				$zip = $this->request->data['update_zip'];
				$phone = $this->request->data['update_cell'];
				$email = $this->request->data['update_email'];
				$status = $this->request->data['edit_status'];
				$roleid = $this->request->data['editsel_role'];
				if($status=='on')
					$status = 'Y';
				else
					$status = 'N';
				
				if($_FILES['updateprofile']['name']!='')
				{
					$profileimg = addslashes(file_get_contents($_FILES["updateprofile"]["tmp_name"]));
					
					$update_qry = "UPDATE `tblusers` SET `Employee_First_Name`='".$fname."',
							  `Employee_Last_Name`='".$lname."',`Profile_Img`='".$profileimg."',
							  `Address`='".$addr."',`Email`='".$email."',`Cell`=".$phone.",
							  `City`='".$city."',`State`='".$state."',`Zip`=".$zip.",
							  `Status`='".$status."' WHERE `Userid`=".$userid;
				}
				else
				{
					$update_qry = "UPDATE `tblusers` SET `Employee_First_Name`='".$fname."',
							  `Employee_Last_Name`='".$lname."',
							  `Address`='".$addr."',`Email`='".$email."',`Cell`=".$phone.",
							  `City`='".$city."',`State`='".$state."',`Zip`=".$zip.",
							  `Status`='".$status."' WHERE `Userid`=".$userid;
							  
				}					
				
				$this->Tblusers->query($update_qry);
				
				$sel_role_qry = "SELECT tblrole.*,tbluser_role.* FROM `tblrole`,tbluser_role WHERE 
								tbluser_role.Roleid = tblrole.Roleid AND 
								tbluser_role.Userid = ".$userid." AND tbluser_role.Status = 'Y'";
				$sel_role_arr = $this->Tblrole->query($sel_role_qry);
					
				if($sel_role_arr[0]['tbluser_role']['Roleid']!=$roleid)	
				{					
					$update_role_qry = "UPDATE `tbluser_role` SET `Status`='N',`Date_Updated`= '".$date."'
										WHERE `Userid`=".$userid." AND `Status`='Y'";
					$this->Tbluser_role->query($update_role_qry);
					
					$ins_role_qry = "INSERT INTO `tbluser_role`(`Userid`, `Roleid`, `Status`) 
									 VALUES (".$userid.",".$roleid.",'Y')";
					$this->Tbluser_role->query($ins_role_qry);
				}
				if($this->request->data['editcustomcount']!='')
				{
					$customcount = $this->request->data['editcustomcount'];
					for($i=0; $i<$customcount; $i++)
					{
						$attrid = $this->request->data['editcustom'.$i];
						
						if($this->request->data['editcustom_'.$attrid]!='')
							$attrval=$this->request->data['editcustom_'.$attrid];
						else
							$attrval='';
						if($_FILES['editcustom_'.$attrid]['name']!='')
						{
							$attrval=$_FILES['editcustom_'.$attrid]['name'];
							$upload_img="Customfield_Files/".$userid.'_'.$attrval;
							move_uploaded_file($_FILES['editcustom_'.$attrid]["tmp_name"],$upload_img);
						}
						$update_qry = "UPDATE `tbluser_attribute` SET `Userattr_val` = '".$attrval."' 
										WHERE Userattrid=".$attrid;
						$this->Tbluser_attribute->query($update_qry);
					}
				}
				else if($this->request->data['customcount']!='')
				{
					$customcount = $this->request->data['customcount'];
					for($i=0; $i<$customcount; $i++)
					{
						$attrid = $this->request->data['custom'.$i];
						$sel_attr_type = "SELECT `KeypairType` FROM `tblorg_attribute` WHERE `Attributeid`=".$attrid;
						$sel_attr_arr = $this->Tblorg_attribute->query($sel_attr_type);
						$attr_type = $sel_attr_arr[0]['tblorg_attribute']['KeypairType'];
						
											
						if($this->request->data['custom_'.$attrid]!='')
							$attrval=$this->request->data['custom_'.$attrid];
						else
							$attrval='';
						if($attr_type=='file')
						{
							$attrval=$_FILES['custom_'.$attrid]['name'];
							$upload_img="Customfield_Files/".$userid.'_'.$attrval;
							if($attrval!='')
								move_uploaded_file($_FILES['custom_'.$attrid]["tmp_name"],$upload_img);
						}
						$insert_qry = "INSERT INTO `tbluser_attribute`(`Attrid`, `Userid`, `Userattr_val`) 
										VALUES (".$attrid.",".$userid.",'".$attrval."')";
														
						$this->Tbluser_attribute->query($insert_qry);
						
					}
				}
				
			}
			
		}
		if($this->request->is('ajax'))
		{ 
			if($this->request->data['action']=='customsave')
			{
				
				if($this->request->data['mode']=='insert')
				{
					parse_str($_POST[ 'formdata' ], $formval);
					$query = "INSERT INTO `tblorg_attribute`(`Orgid`, `Keypair`, `KeypairType`,`KeypairValue`) 
									VALUES (".$orgid.",'".$formval['customfieldname']."','".$formval['customfield_type']."','".mysql_escape_string($formval['fieldval'])."')";
					
				}
				else if($this->request->data['mode']=='edit')
				{
					parse_str($_POST[ 'formdata' ], $formval);
					$query = "UPDATE `tblorg_attribute` SET 
								`Keypair`='".$formval['editcustomname']."',
								`KeypairType`='".$formval['editcustomfield_type']."',
								`KeypairValue`='".mysql_escape_string($formval['editfieldval'])."' WHERE 
								`Attributeid`=".$formval['editattribute_id'];
				}
				else if($this->request->data['mode']=='delete')
				{
					$query = "DELETE FROM `tblorg_attribute` WHERE `Attributeid`=".$this->request->data['attrid'];
				}
				
				$this->Tblorg_attribute->query($query);
				$customfieldarr = parent::getcustomfields();
				if(count($customfieldarr)>0)
				{
					for($i=0; $i<count($customfieldarr); $i++)
					{
						$response.= '<div class="callout">';
							$response.= '<label>'.$customfieldarr[$i]['tblorg_attribute']['Keypair'].'</label>';
							$response.= '<a class="edit-custom-field" onclick=editcustom('.$customfieldarr[$i]['tblorg_attribute']['Attributeid'].');></a>';
						$response.= '</div>';
					}
					echo $response;
					
				}
			}
			if($this->request->data['action']=='editcustom')
			{
				$attrid = $this->request->data['attrid'];
				$edit_qry = "SELECT * FROM `tblorg_attribute` WHERE `Attributeid`=".$attrid;
				$editarr = $this->Tblorg_attribute->query($edit_qry);
				foreach($editarr as $editval)
				{
					$keypair = $editval['tblorg_attribute']['Keypair'];
					$keypair_type = $editval['tblorg_attribute']['KeypairType'];
					$keypair_val = $editval['tblorg_attribute']['KeypairValue'];
				}
				$myObj->keypair = $keypair;	
				$myObj->keypair_type = $keypair_type;
				$myObj->keypair_val=$keypair_val;
				$myJSON = json_encode($myObj);
				echo $myJSON;
			}
			if($this->request->data['action']=='editempview')
			{
				$userid = $this->request->data['userid'];
				$sel_user_qry = "SELECT * FROM `tblusers` WHERE `Userid`=".$userid;
				$sel_user_arr = $this->Tblusers->query($sel_user_qry);
				
				$sel_attrval_qry = "SELECT tbluser_attribute.*, tblorg_attribute.* FROM `tbluser_attribute` 
								LEFT OUTER JOIN tblorg_attribute ON tbluser_attribute.Attrid=tblorg_attribute.Attributeid 
								WHERE tbluser_attribute.Userid =".$userid." ORDER BY tblorg_attribute.DateCreated DESC";
				$sel_role_qry = "SELECT tblrole.*,tbluser_role.* FROM `tblrole`,tbluser_role WHERE 
								tbluser_role.Roleid = tblrole.Roleid AND 
								tbluser_role.Userid = ".$userid." AND tbluser_role.Status = 'Y'";
							
				$sel_attrval_arr = $this->Tbluser_attribute->query($sel_attrval_qry);
				$sel_roleval_arr = $this->Tbluser_role->query($sel_role_qry);
				if(count($sel_attrval_arr)==0)
				{
					$sel_custom_qry = "SELECT * FROM tblorg_attribute WHERE Orgid=".$orgid." ORDER BY DateCreated DESC";
					$customfieldarr = $this->Tbluser_attribute->query($sel_custom_qry);
				}
				
				$fname = $sel_user_arr[0]['tblusers']['Employee_First_Name'];
				$lname = $sel_user_arr[0]['tblusers']['Employee_Last_Name'];
				$profileimg = $sel_user_arr[0]['tblusers']['Profile_Img'];
				$addr = $sel_user_arr[0]['tblusers']['Address'];
				$email = $sel_user_arr[0]['tblusers']['Email'];
				$cell = $sel_user_arr[0]['tblusers']['Cell'];
				$city = $sel_user_arr[0]['tblusers']['City'];
				$state = $sel_user_arr[0]['tblusers']['State'];
				$zip = $sel_user_arr[0]['tblusers']['Zip'];
				$active = $sel_user_arr[0]['tblusers']['Status'];
				$roleval = $sel_roleval_arr[0]['tblrole']['Rolename'];
				$rate = $sel_roleval_arr[0]['tblrole']['Rate'];
				if($active=='Y')
					$checked = 'checked';
				else
					$checked = 'unchecked';
				$rolearr = parent::getrole();
				
				$response.="<div class='grid-container text-left'>
								<div class='grid-x grid-padding-x'>
									<div class='large-4 cell'>
									  <div class=profile-image-upload style='background-image: url(data:image/jpeg;base64,".base64_encode($profileimg).")'></div>
									  <input type=file name=updateprofile id=updateprofile>
									</div>
									<div class='large-8 cell'>
									  <div class=grid-x>
										<div class='large-12 cell'>
										  <label>First Name</label>
										  <input type=hidden name=userid id=userid value=".$userid.">
										  <input type=text name=update_fname id=update_fname value=".$fname.">
										</div>
										<div class='large-12 cell'>
										  <label>Last Name</label>
										  <input type=text name=update_lname id=update_lname value=".$lname.">
										</div>
									  </div>
									</div>
									<div class='large-12 cell'>
										<div class='grid-x grid-padding-x'>
											<div class=large-12 cell>
											  <label>Address</label>
												<input type=text name=update_addr id=update_addr value=".$addr.">
											</div>
											<div class='large-5 cell'>
											  <label>City</label>
												<input type=text name=update_city id=update_city value=".$city.">
											</div>
											<div class='large-3 cell'>
											  <label>State</label>
												<input type=text name=update_state id=update_state value=".$state.">
											</div>
											<div class='large-4 cell'>
											  <label>Zip</label>
												<input type=text name=update_zip id=update_zip value=".$zip.">
											</div>
										</div>
									</div>  
									<div class='large-12 cell'>
										<div class='grid-x grid-padding-x'>
											<div class='large-6 cell'>
											  <label>Phone Number</label>
												<input type=text name=update_cell id=update_cell value=".$cell.">
											</div>
											<div class='large-6 cell'>
											  <label>Email</label>
												<input type=email name=update_email id=update_email value=".$email.">
											</div>
										</div>
									</div>
									<div class='large-12 cell'>
										<div class='grid-x grid-padding-x'>
											<div class='large-6 cell'>
											  <label>Status</label>
												<input type=checkbox id=edit_status name=edit_status ".$checked."> Active
											  
											</div>
											<div class='large-6 cell'>
											  <label>Role</label>
												<select name=editsel_role id=editsel_role onchange=roleselect(this.value);>";
												foreach ($rolearr as $role)
												{
													if($roleval==$role['tblrole']['Rolename'])
														$selected = 'selected';
													else
														$selected = '';
													$response.="<option id=".$role['tblrole']['Rate']." value=".$role['tblrole']['Roleid']."  ".$selected.">".$role['tblrole']['Rolename']."</option>";
												}
												$response.="</select>
											</div>
											<div class='large-6 cell'>
												<label>Rate</label>
												<input type='number' id='rate' name='rate' value=".$rate." readonly>
											</div>
										</div>
									</div>
								</div>  
								<hr />
								<div class='grid-x grid-padding-x'>
									<div class='large-12 cell'>
										<h3>Custom Fields</h3>
										<div class=grid-x>";
										if(count($sel_attrval_arr)>0)
										{
											$response.= '<input type=hidden name=editcustomcount id=editcustomcount value='.count($sel_attrval_arr).'>';
											for($i=0; $i<count($sel_attrval_arr); $i++)
											{
												$attrid = $sel_attrval_arr[$i]['tbluser_attribute']['Userattrid'];
												$custype = $sel_attrval_arr[$i]['tblorg_attribute']['KeypairType'];
												$custval = $sel_attrval_arr[$i]['tblorg_attribute']['KeypairValue'];
												$custname = $sel_attrval_arr[$i]['tblorg_attribute']['Keypair'];
												$attrval = $sel_attrval_arr[$i]['tbluser_attribute']['Userattr_val'];
												$response.= '<input type=hidden id=editcustom'.$i.' name=editcustom'.$i.' value='.$attrid.'>';
												$response.= "<div class='large-12 cell'>";
												$response.= "<label>".$custname."</label>";
												if(strpos($custval,',')!='')
													$optionval = explode(",",$custval);
												if($custype=='text'||$custype=='number'||$custype=='date'||$custype=='file')
												{
													$response.='<input type='.$custype.' id=editcustom_'.$attrid.' name=editcustom_'.$attrid.' value="'.$attrval.'">';
													if($custype=='file')
														$response.= "<a href='".$this->webroot ."Customfield_Files/".$userid.'_'.$attrval."'>".$attrval."</a>";
												}
												else if($custype=='dropdown')
												{
													$response.='<select name=editcustom_'.$attrid.' id=editcustom_'.$attrid.'>';
														foreach($optionval as $option)
														{
															if($option==$attrval)
																$selected = 'selected';
															else
																$selected = '';
															$response.='<option value='.$option.' '.$selected.'>'.$option.'</option>';
														}
													$response.='</select>';
													
												}
												else if($custype=='radio')
												{
													foreach($optionval as $option)
													{
														if($option==$attrval)
															$checked = 'checked';
														else
															$checked = '';
														$response.='<input type='.$custype.' name=editcustom_'.$attrid.' name=editcustom_'.$attrid.' '.$checked.'>'.$option.'<br>';
													}
												}
												else if($custype=='checkbox')
												{
													$response.='<input type='.$custype.' name=custom_'.$attrid.' name=custom_'.$attrid.'>'.$custname;
												}
												else if($custype=='textarea')
												{
													$response.='<textarea name=editcustom_'.$attrid.' id=editcustom_'.$attrid.'>'.$attrval.'</textarea>';
												}
													$response.="</div>";
													
											}
										}
										else
										{
											if(count($customfieldarr)>0)
											{
												$response.='<input type=hidden name=customcount id=customcount value='.count($customfieldarr).'>';
												for($i=0; $i<count($customfieldarr); $i++)
												{
													
													$attrid = $customfieldarr[$i]['tblorg_attribute']['Attributeid'];
													$custype = $customfieldarr[$i]['tblorg_attribute']['KeypairType'];
													$custval = $customfieldarr[$i]['tblorg_attribute']['KeypairValue'];
													$response.='<input type=hidden id=custom'.$i.' name=custom'.$i.' value='.$attrid.'>';
													if(strpos($custval,',')!='')
														$optionval = explode(",",$custval);
													$response.= '<div class="large-12 cell">';
														$response.='<label>'.$customfieldarr[$i]['tblorg_attribute']['Keypair'].'</label>';
														if($custype=='text'||$custype=='number'||$custype=='date'||$custype=='file')
														{
															$response.='<input type='.$custype.' id=custom_'.$attrid.' name=custom_'.$attrid.'>';
															
														}
														else if($custype=='dropdown')
														{
															$response.='<select name=custom_'.$attrid.' id=custom_'.$attrid.'>';
																foreach($optionval as $option)
																{
																	$response.='<option value='.$option.'>'.$option.'</option>';
																}
															$response.= '</select>';
															
														}
														else if($custype=='checkbox'||'radio')
														{
															foreach($optionval as $option)
															{
																$response.='<input type='.$custype.' name=custom_'.$attrid.' name=custom_'.$attrid.'>'.$option.'<br>';
															}
														}
														else if($custype=='textarea')
														{
															$response.='<textarea name=custom_'.$attrid.' id=custom_'.$attrid.'></textarea>';
														}
													$response.= '</div>';
												}
											}
										}
									$response.="</div>
									</div>
								</div>
								<div class='button-group expanded'>
									<a class=button  onclick='update_emp(".$userid.");' data-close>Save</a>
								</div>
							</div>";
						echo $response;
			}
			exit;
		}
		
		$role_arr = parent::getrole();
		$this->set('rolearr',$role_arr);
		
		$view_allemp =  parent::getallemp();
		$this->set('viewemp',$view_allemp[0]);
		$this->set('viewcustom',$view_allemp[1]);
		$customfieldarr = parent::getcustomfields();
		$this->set('customfieldarr',$customfieldarr);
		
		
	}
	
	
}
?>