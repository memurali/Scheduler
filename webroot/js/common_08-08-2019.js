$(function(){ 

	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	  };
	})();
	//search employees
	$("#search_name").keypress(function(e) {
		if(e.which == 13) {
			jQuery('#search_name').keyup();
		}
	});
			
	$('#search_name').keyup(function() 
	{
		delay(function(){
			var arrange=$('#search_name').val();
			var query = arrange.toLowerCase();				
			sortTable('search',query);
		},200);
	});
	//Arrange employees
	$('.select').on('change', function() {	
	  sortTable('arrange',this.value);  
	});
	  
	//staff click and slider Reinitialize
	$(document).on('click','.staffRow', function() {
	    $('tr').removeClass('checked');
        if($(this).is(':checked')){
		   $(this).closest('tr').addClass('checked');
		   Foundation.reInit($(".slider"));
           var radioValue = $("input[name='staff']:checked").val();
		   $("#search_name").val(radioValue);
        }
		
    });
	//Clear all the Staff  
	$('#clear_btn').click(function(){
		    var min = $('#slider_start').val();
			var max = $('#slider_end').val();
			
			var span = $("[class='staff checked'] [class='cell large-auto available']");
			var total_hrs=span.length;//8
			var total_map=Math.floor(100/(total_hrs*4));//3
			var startval = $("[class='staff checked'] [id='startval']").val();
			var endval = $("[class='staff checked'] [id='endval']").val();
			
			var split = $("[class='staff checked'] [id='splitval']").val();
			if(split!='')
			{
				var inc = split;
				var startval = $("[class='staff checked'] [id='split']").text();
			}
			else
				var inc = 1;
			//console.log(startval);
			var strsplit_time = splitTime(startval);
			var strhour =  parseInt(strsplit_time[0]);
			var strmin = parseInt(strsplit_time[1]);
			var strtime = strsplit_time[2];
			//console.log('p'+strtime);
			var endsplit_time = splitTime(endval);
			var endhour =  parseInt(endsplit_time[0]);
			var endmin = parseInt(endsplit_time[1]);
			var endtime = endsplit_time[2];
			var spanhr = strhour;
		   reinit(inc,total_hrs,spanhr,strmin,startval,endval,endmin);
		   Foundation.reInit($(".slider"));
	});
	//Previous and Next process	
	$('#prevbtn').click(function(){
		  $('#action').val('prev');
		  $('.next').click();
	});	
	
	$(document).on('click','.next', function() {		
	   var currentdate= $('#currentdate').val();
	   var action= $('#action').val();
	   if(action=='')
		   $('#action').val('next');
	   var action= $('#action').val();
			jQuery.ajax({								
			type: 'POST',	
			data: {action:action,currentdate:currentdate},
			url: 'schedulerday',
			dataType: "text",
			success: function (result)
			{										
				$('#table_body').html(result);
				$('#action').val('');
			}
			});
      });
		
	delay(function(){
		$('.slider').on('moved.zf.slider', function()			
		{	  		
			var startval = $("[class='staff checked'] [id='startval']").val();
			var endval = $("[class='staff checked'] [id='endval']").val();
			if(startval!='' && endval!='')
			{
				/*** setting values & reintialize scheduler div ***/
				var min = $('#slider_start').val();
				var max = $('#slider_end').val();
				
				/*var span = $("[class='staff checked'] [class='cell large-auto available']");
				var total_hrs=span.length;//8*/
				var split = $("[class='staff checked'] [id='splitval']").val();
				if(split!='')
				{
					var total_hrs=split
					var inc = $("[class='staff checked'] [id='startspan'").val();
					var reint_lim = parseInt(total_hrs)+parseInt(inc)-1;
					
				}
				else
				{
					var span = $("[class='staff checked'] [class='cell large-auto available']");
					var total_hrs=span.length;
					var inc = 1;
					var reint_lim = total_hrs;
				}
				var total_map=Math.floor(100/(total_hrs*4)); 
				
				var strsplit_time = splitTime(startval);
				var strhour =  parseInt(strsplit_time[0]);
				var strmin = parseInt(strsplit_time[1]);
				
				var endsplit_time = splitTime(endval);
				var endhour =  parseInt(endsplit_time[0]);
				var endmin = parseInt(endsplit_time[1]);
				
				var spanhr = strhour;
				for(var k=inc; k<=reint_lim; k++)
				{
					$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled");
					$("[class='staff checked'] [id='span"+k+"']").text('');
					
					if(k==inc)
					{
						if(strmin>0)
							$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled start-"+strmin);
						$("[class='staff checked'] [id='span"+k+"']").text(tConvert(startval));
						
						$("[class='staff checked'] [class='cell large-auto available'] [id='hideval"+k+"']").val(startval);
					}
					else if(k==reint_lim)
					{
						if(endmin>0)
							$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled end-"+endmin);
						$("[class='staff checked'] [id='span"+k+"']").text(tConvert(endval));
						$("[class='staff checked'] [class='cell large-auto available'] [id='hideval"+k+"']").val(endval);
					}
					else
					{
						$("[class='staff checked'] [class='cell large-auto available'] [id='hideval"+k+"']").val(spanhr+':00 ');
					}
					spanhr++;
				}
				
				/****  forward moving ****/
				if(min!=0)
				{
					var strnewhour='';
					var map = Math.floor(min/total_map);//4
					if(map>0)
					{
						var span = Math.floor(map/4);//1
						var cell = map%4;//0
						/** condition for 1st span **/
						if(span==0)
						{
							span=1
							cell=map;
						}
						else
						{
							if(cell>0)
								span=span+1;
								
						}
						/** Get slider start in split ****/
						if(split!='')
							lim_span = (span>1)?(parseInt(span)+parseInt(inc)-1):inc;
						else
							lim_span = span;
						for(var i=inc; i<=lim_span; i++)
						{
							if(i==lim_span)
							{
								if(cell>0)
								{
									start__val = cell*15;
									var schedule_class = "scheduled start-"+start__val;
									strmin = start__val;
									strnewhour = strnewhour-1;
									if(strnewhour<0)
										strnewhour = strhour;
									k=i;
									j=i-1;
									
								}
								else
								{
									var schedule_class = 'avail_schedule';
									strnewhour=strhour+span;
									strmin='00';
									k=parseInt(i)+1;
									j=i;
									
								}
								
							}
							else
							{
								var schedule_class = 'avail_schedule';
								strnewhour=strhour+span;
								j=i;
							}
							//console.log(' j val'+j+' i val  '+i);
							if(j>=inc && j<=lim_span)
								$("[class='staff checked'] [id='span"+j+"']").text('');
							if(k<=lim_span)
								$("[class='staff checked'] [id='span"+k+"']").text(tConvert(strnewhour+':'+strmin));
							$("[class='staff checked'] [id='span"+i+"']").attr( "class",schedule_class);
						}
					}
				}
				
				/****  Backward moving ****/
				if(max<100)
				{				
					var diff = 100-max;
					var map = Math.floor(diff/total_map);
					if(map>0)
					{
										
						var span = Math.floor(map/4);
						var cell = map%4;
						if(split!='')
							var dec = $("[class='staff checked'] [id='endspan'").val();
						else
							var dec = total_hrs;
						var span_diff = dec - span;
						/*** for last span***/
						if(span==0)
						{
							span_diff=dec-1;
							cell=map;
						}
						else
						{
							if(cell>0)
								span_diff = span_diff-1;
						}
						if(endmin==0)
							endhour = endhour-1;
						else
							endhour = endhour;
						var m=0;
						for(var i=dec; i>span_diff; i--)
						{
							if(i==dec)
								newhour = endhour;
							else
								newhour = newhour-1;
							if(i==(span_diff+1))
							{
								if(cell>0)
								{
									switch (cell)
									{
										case 1: 
											endval = 15*3;
											break;
										case 2: 
											endval = 15*2;
											break;
										case 3: 
											endval = 15*1;
									}
									var schedule_class = "scheduled end-"+endval;
									endmin = endval;
									k=i;
									j=i+1;
								}
								else
								{
									var schedule_class = 'avail_schedule';
									endmin='00';
									k=i-1;
									
									if(i==dec)
										j=i;
									else
										j=i+1;
								}
							
							}
							else
							{
								var schedule_class = 'avail_schedule';
								newhour=newhour;
								j=i+1;
								k=i;
							}
							$("[class='staff checked'] [id='span"+j+"']").text('');
							$("[class='staff checked'] [id='span"+k+"']").text(tConvert(newhour+':'+endmin));
							$("[class='staff checked'] [id='span"+i+"']").attr( "class",schedule_class);
							m++
						}
					}
				}
			}
		
		});
		
	 },1000);	
	
});
function splitslider(start_endval,kval,start_spanid,end_spanid)
{
	Foundation.reInit($(".slider"));
	var split_time = start_endval.split("-");
	$("[class='staff checked'] [id='startval']").val(split_time[0]);
	$("[class='staff checked'] [id='endval']").val(split_time[1]);
	var tothr = $("[class='staff checked'] [id='tothr["+kval+"]'").val();
	$("[class='staff checked'] [id='splitval'").val(tothr);
	$("[class='staff checked'] [id='startspan'").val(start_spanid);
	$("[class='staff checked'] [id='endspan'").val(end_spanid);
}
function assign_variable()
{
	/*** setting values & reintialize scheduler div ***/
			
	var span = $("[class='staff checked'] [class='cell large-auto available']");
	var total_hrs=span.length;//8
	
	var startval = $("[class='staff checked'] [id='startval']").val();
	var endval = $("[class='staff checked'] [id='endval']").val();
	
	var arr=new Array(total_hrs,startval,endval);
	
	return arr;
}
//scheduled_reintialize
function reinit(inc,total_hrs,spanhr,strmin,startval,endval,endmin)
{
	for(var k=inc; k<=total_hrs; k++)
	{
		
		var newspanhr = (spanhr>12)?(spanhr-12):spanhr;
		var newspantime = (spanhr>=9 && spanhr<=12)?'AM':'PM';
		$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled");
		$("[class='staff checked'] [id='span"+k+"']").text('');
		
		if(k==inc)
		{
			if(strmin>0)
				$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled start-"+strmin);
			$("[class='staff checked'] [id='span"+k+"']").text(startval);
			
			$("[class='staff checked'] [class='cell large-auto available'] [id='hideval"+k+"']").val(startval);
		}
		else if(k==total_hrs)
		{
			if(endmin>0)
				$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled end-"+endmin);
			$("[class='staff checked'] [id='span"+k+"']").text(endval);
			$("[class='staff checked'] [class='cell large-auto available'] [id='hideval"+k+"']").val(endval);
		}
		else
		{
			$("[class='staff checked'] [class='cell large-auto available'] [id='hideval"+k+"']").val(newspanhr+':00 '+newspantime);
		}
		spanhr++;
	}
		
}
function split()
{
	//alert('split...');
	var startval = $("[class='staff checked'] [id='startval']").val();
	var endval = $("[class='staff checked'] [id='endval']").val();
	var span_schedule = $("[class='staff checked'] [class^='scheduled']");
	var span_avail = $("[class='staff checked'] [class='cell large-auto available']");
    var avail_remain_schedule = $("[class='staff checked'] [class='avail_schedule']");

	
	var schedule_hrs = span_schedule.length;
	var avail_hrs = span_avail.length;
	var avail_remain_schedule_length = avail_remain_schedule.length;
	var scheduled_array_old=new Array();
	var scheduled_array_tot_avail=new Array();

	for(var s=0;s<schedule_hrs;s++)
	{
		var spanid=span_schedule[s].id;
		var span_Num = spanid.match(/\d+/);
		scheduled_array_old[s]=span_Num;
	}
	for(var s=0; s<avail_remain_schedule_length; s++)
	{
		var spanid=avail_remain_schedule[s].id;
		var span_Num = spanid.match(/\d+/);
		scheduled_array_tot_avail[s]=span_Num;
	}
	if(avail_hrs>schedule_hrs)
	{
		var last_spanid=span_schedule[schedule_hrs-1].id;
		var span_Num = last_spanid.match(/\d+/);
		var inc_span=parseInt(span_Num)+1;
		var className = $("[class='staff checked'] [id='span"+inc_span+"']").attr('class');
		for(var k=0; k<scheduled_array_tot_avail.length; k++)
		{
			var getTime = $("[class='staff checked'] [class='cell large-auto available'] [id='hideval"+scheduled_array_tot_avail[k]+"']").val();
			$("[class='staff checked'] [id='span"+scheduled_array_tot_avail[k]+"']").text(getTime);
			var split_getTime = splitTime(getTime);
			var getmin = parseInt(split_getTime[1]);
			if(getmin>0)
				$("[class='staff checked'] [id='span"+scheduled_array_tot_avail[k]+"']").attr( "class","scheduled end-"+getmin+" split");
			else
				$("[class='staff checked'] [id='span"+scheduled_array_tot_avail[k]+"']").attr( "class","scheduled split");
			$("[class='staff checked'] [id='span"+scheduled_array_tot_avail[k]+"']").attr( "onclick","scroll_split("+scheduled_array_tot_avail[k]+")");
		}
		
	}
}
function scroll_split(spanid)
{
	$("#action").val('split');
	Foundation.reInit($(".slider"));
	var incspan = spanid+1;
	var decspan = spanid-1;
	var NxtclassName = $("[class='staff checked'] [id='span"+incspan+"']").attr('class');
	var PrevclassName = $("[class='staff checked'] [id='span"+decspan+"']").attr('class');
	var splitTime = getsplitSpanTime(NxtclassName,PrevclassName,spanid);
	
}
function splitTime(timeval)
{
	var split_time = timeval.split(" ");
	var time = split_time[1];		
	var hourmin = split_time[0].split(":");
	var hour = parseInt(hourmin[0]);
	var min = parseInt(hourmin[1]);
	var array = new Array(hour,min);
	return(array);
}
function timefor(total_hrs,strmin,startval,endmin,endval)
{
	for(var k=1; k<=total_hrs; k++)
	{
		$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled");
		$("[class='staff checked'] [id='span"+k+"']").text('');
		if(k==1)
		{
			if(strmin>0)
				$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled start-"+strmin);
			$("[class='staff checked'] [id='span"+k+"']").text(startval);
		}
		else if(k==total_hrs)
		{
			if(endmin>0)
				$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled end-"+endmin);
			$("[class='staff checked'] [id='span"+k+"']").text(endval);
		}
	}
}
//Arrange employees
function sortTable(type,sortby) 
{
	var table, rows, switching, i, x, y, shouldSwitch;
	table = document.getElementById("table_body");
	switching = true;
	while (switching) 
	{
		switching = false;
		rows = table.rows;
		for (i = 0; i <(rows.length); i++) 
		{
			shouldSwitch = false;
			if(type=='arrange')
			{
				if(sortby=='firstname'||sortby=='lastname')
				{
					var x = rows[i].getElementsByTagName("td")[0].getElementsByTagName("input")[0].value;
					var y = rows[i + 1].getElementsByTagName("td")[0].getElementsByTagName("input")[0].value;
					if(sortby=='firstname')
					{
						var xval = x.split(" ");
						var yval = y.split(" ");
						if (xval[0].toLowerCase() > yval[0].toLowerCase())
						{				
							shouldSwitch = true;
							break;
						}
					}
					if(sortby=='lastname')
					{
						var xval = x.split(" ");
						var yval = y.split(" ");
						if (xval[1].toLowerCase() < yval[1].toLowerCase())
						{
							shouldSwitch = true;
							break;
						}
					}
				}
				if(sortby=="starttime")
				{
					if(rows[i].contains(rows[i].getElementsByTagName("td")[1].getElementsByTagName("input")[0])!=false)
					{
						var x =rows[i].getElementsByTagName("td")[1].getElementsByTagName("input")[0].value;
					}
					else
					{
						var x ='0000';
					}
					if(rows[i + 1].contains(rows[i + 1].getElementsByTagName("td")[1].getElementsByTagName("input")[0])!=false)
					{
						var y =rows[i + 1].getElementsByTagName("td")[1].getElementsByTagName("input")[0].value;
					}
					else
					{
						var y ='0000';
					}
					var xval = converttime(x);
					var yval = converttime(y);
					if (parseInt(xval) > parseInt(yval))
					{					
						shouldSwitch = true;
						break;
					}				
				}
			}
			if(type=='search')
			{
				var x = rows[i].getElementsByTagName("td")[0].getElementsByTagName("input")[0].value;
				var y = rows[i + 1].getElementsByTagName("td")[0].getElementsByTagName("input")[0].value;
				var xval = x.toLowerCase().indexOf(sortby);
				var yval = y.toLowerCase().indexOf(sortby);
				if(xval < yval)
				{
					shouldSwitch = true;
					break;
				}
			}
		}
		if (shouldSwitch) {
		rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
		switching = true;
		}
	}
}
function converttime(val)
{
	if(val!='0000')
	{
		var time = val.split(" ");
		var format = time[1];
		var formattime = time[0].split(":");
		var hrs = parseInt(formattime[0]);
		var mnts = parseInt(formattime[1]);	
		if (format == "PM" && hrs < 12) hrs = hrs + 12;
		if (format == "AM" && hrs == 12) hrs = hrs - 12;
		var hours = hrs.toString();
		var minutes = mnts.toString();
		if (hrs < 10) hours = "0" + hours;
		if (mnts < 10) minutes = "0" + minutes;
		var timing = hours+minutes;
	}
	else
		var timing =val;
	return timing;
}
function getsplitSpanTime(NxtclassName,PrevclassName,spanid)
{
	alert(NxtclassName+'     '+PrevclassName+'       '+spanid);
	var looping = '';
	var loopstart = '';
	var loopend = '';
	var start_val = '';
	var end_val = '';
	if(NxtclassName=='scheduled split' && PrevclassName!='scheduled split')
	{
		loopstart = spanid;
		start_val =$("[class='staff checked'] [id='hideval"+spanid+"']").val();
		loopend = $("[class='staff checked'] [class^='scheduled']").length;
		looping = 'increment';
		alert('case  1');
	}
	else if(NxtclassName!='scheduled split' && PrevclassName=='scheduled split')
	{
		loopstart = spanid;
		loopend = 1;
		end_val = $("[class='staff checked'] [id='hideval"+spanid+"']").val();
		looping = 'decrement';
		alert('case  2');
	}
	else if(NxtclassName!='scheduled split' && PrevclassName!='scheduled split')
	{
		if(spanid==$("[class='staff checked'] [class^='scheduled']").length)
		{
			start_val = $("[class='staff checked'] [id='hideval"+(spanid-1)+"']").val();
			end_val =  $("[class='staff checked'] [id='hideval"+(spanid)+"']").val();
		}
		else
		{
			start_val = $("[class='staff checked'] [id='hideval"+spanid+"']").val();
			end_val =  $("[class='staff checked'] [id='hideval"+(spanid+1)+"']").val();
		}
		
		alert('case  3');
	}
	else if(NxtclassName=='scheduled split' && PrevclassName=='scheduled split')
	{
		alert('case  4');
		for(var j=spanid-1; j>=1; j--)
		{
			if($("[class='staff checked'] [id='span"+j+"']").attr('class')!='scheduled split')
			{
				start_val = $("[class='staff checked'] [id='hideval"+(j+1)+"']").val();
				loopstart = j+1;
				break;
			}
			
		}
		loopend = $("[class='staff checked'] [class^='scheduled']").length;
		looping = 'increment';
	}
	if(start_val=='' || end_val=='')
	{
		alert('if part'+'  loopstart   '+loopstart+'  loopend '+loopend+'  looping  '+looping);
		if(looping=='increment')
		{
			for(var k=loopstart; k<=loopend; k++)
			{
				if(k==$("[class='staff checked'] [class^='scheduled']").length)
				{
					end_val = $("[class='staff checked'] [id='hideval"+k+"']").val();
					break;
				}
				if($("[class='staff checked'] [id='span"+k+"']").attr('class')!='scheduled split')
				{
					end_val = $("[class='staff checked'] [id='hideval"+(k-1)+"']").val();
					break;
				}
			}
		}
		else if(looping=='decrement')
		{
			for(var k=loopstart; k>=loopend; k--)
			{
				alert(k);
				if(k==1)
				{
					start_val = $("[class='staff checked'] [id='hideval"+k+"']").val();
					break;
				}
				else if($("[class='staff checked'] [id='span"+k+"']").attr('class')!='scheduled split')
				{
					start_val = $("[class='staff checked'] [id='hideval"+(k+1)+"']").val();
					break;
				}
				
			}
			
		}
	}
	alert(start_val+'  '+end_val);
}

function tConvert (time) {
  // Check correct time format and split into components
	time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
	if (time.length > 1) 
	{ // If time format correct
		time = time.slice (1);  // Remove full string match value
		time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
		time[0] = +time[0] % 12 || 12; // Adjust hours
	}
	return (time.join ('')); // return adjusted time or original string
}