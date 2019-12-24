$(function(){ 

	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	  };
	})();
	set_individual_tothrs();
	
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
	
	$('#yes-no').on('change',function(){
		var currentdate = $('#currentdate').val();
		var val = $(this).is(':checked') ? 'checked' : 'unchecked';
		if(val=='unchecked')
			var time =['9A','10A','11A','12P','1P','2P','3P','4P','5P'];
		else
			var time =['12A','1A','2A','3A','4A','5A','6A','7A','8A','9A','10A','11A','12P','1P','2P','3P','4P','5P','6P','7P','8P','9P','10P','11P'];	

		var data='';
		for(var i=0;i<time.length;i++)
		{
			data+='<div class="cell large-auto">'+time[i]+'</div>';
		}
		$('[class="grid-x work-hours-mode"]').html(data);
		
		$.ajax({								
			type: 'POST',	
			data: {action:'24hrs',currentdate:currentdate,type:val},
			url: 'schedulerday',
			dataType: "text",
			success: function (result)
			{
				setajax_response(result);
			}
		});
		
	});	

	$('#today').on('click',function(){
		var day = new Date();
		var currentdate = day.getDay();
		var val = $(this).is(':checked') ? 'checked' : 'unchecked';			
		$.ajax({								
			type: 'POST',	
			data: {action:'today',currentdate:currentdate,type:val},
			url: 'schedulerday',
			dataType: "text",
			success: function (result)
			{
				setajax_response(result);
			}
		});		
	});
	  
	$('#proj_sale').keyup(function() 
	{
		delay(function(){
			var sale = $('#proj_sale').val();
			if(sale>0)
			{
				var labor_cost = $('#labor-cost').text();
				var percent = parseInt((parseInt(labor_cost)/parseInt(sale))*100);
				if(percent!=0)
				$('#labor-percentage').text(percent);
			}
		},1000);
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
	  
	   var val = $('#yes-no').is(':checked') ? 'checked' : 'unchecked';
	   if($('#action').val()=='')		  
		   $('#action').val('next');
	   
	   if($('#action').val()=='next')
		   if($('#currentdate').val()<7)
				$('#currentdate').val(parseInt($('#currentdate').val())+1);
			else
				return false;
	   else	
			if($('#currentdate').val()>1)
				$('#currentdate').val(parseInt($('#currentdate').val())-1);
			else
			{
				$('#action').val('next');
				return false;
			}
		
	   var currentdate= $('#currentdate').val();
	   var action= $('#action').val();
	  	jQuery.ajax({								
			type: 'POST',	
			data: {action:action,currentdate:currentdate,type:val},
			url: 'schedulerday',
			dataType: "text",
			success: function (result)
			{										
				setajax_response(result);
			}
		});
    });
		
	delay(function()
	{
		$('.slider').on('moved.zf.slider', function()			
		{	 
			var starthour = $("[class='staff checked'] [id='startval']").val();
			var endhour = $("[class='staff checked'] [id='endval']").val();
			avail_hrs=new Array();
			scheduled_hours=new Array(starthour,endhour);
			
			var newscheduled_hours=slider(starthour,endhour);
			if(newscheduled_hours[0]>scheduled_hours[0] && newscheduled_hours[1]==scheduled_hours[1])
				avail_hrs=new Array([scheduled_hours[0],newscheduled_hours[0]]);
			if(newscheduled_hours[0]==scheduled_hours[0] && newscheduled_hours[1]<scheduled_hours[1])
				avail_hrs=new Array([newscheduled_hours[1],scheduled_hours[1]]);
			if(newscheduled_hours[0]>scheduled_hours[0] && newscheduled_hours[1]<scheduled_hours[1])
				avail_hrs=new Array([scheduled_hours[0],newscheduled_hours[0]],[newscheduled_hours[1],scheduled_hours[1]]);
			
			var avail_hrs = JSON.stringify(avail_hrs);
			$("#avail_arr").val(avail_hrs);
			
			// onclick changes in slider moving time
			var getstrhr = splitTime(newscheduled_hours[0]);
			var getendhr = splitTime(newscheduled_hours[1]);
			
			for(var i=getstrhr[0];i<getendhr[0];i++)
			{
				$("[class='staff checked'] [id='span"+i+"']").attr( "onclick","scroll_split('"+newscheduled_hours[0]+"','"+newscheduled_hours[1]+"','avail_default')");
			}
			sethours();
			get_all_tothrs();
		}); 
		
	},1000);	
	
});

function get_all_tothrs()
{
	var hr =0;
	var min =0;
	$(".total-hours").each(function(){
		var eachhr = $(this).text();
		if(eachhr!=0)
		{
			var eachhr_arr = splitTime(eachhr);
			hr+= parseInt(eachhr_arr[0]);
			if(eachhr_arr[1]!=''||eachhr_arr[1]!=NaN)
				min+=parseInt(eachhr_arr[1]);
		}
	});
	hr+= Math.floor(min / 60);
	re_min = min % 60;
	if(re_min=='0')
		re_min = 00;
	$('#all_tot_hrs').text(hr+':'+re_min);
	$('#labor-hour').text(hr+':'+re_min);
}
function sethours()
{
	var basicpay = $("[class='staff checked'] [id='basicpay']").val();
	var initial_time = $("[class='staff checked'] [class='total-hours']").text();
	var initial_timearr = splitTime(initial_time);
	var intial_pay=initial_timearr[0]*parseInt(basicpay)+initial_timearr[1]/60*parseInt(basicpay);
	$('#initialpay').val(intial_pay);
	var tothrs_len = $("[class='staff checked'] [class='scheduled']").length;
	var split_tothrs_len = $("[class='staff checked'] [class='split scheduled']").length;
	tothrs_len = parseInt(tothrs_len)+parseInt(split_tothrs_len);
	var totmin_len = $("[class='staff checked'] [class^='scheduled ']").length;
	var split_totmin_len = $("[class='staff checked'] [class^='split scheduled ']").length;
	var totmin =0;
	$("[class='staff checked'] [class^='scheduled ']").each(function(){
	  var getmins= $(this).text();
	  var getminarr = splitTime(getmins);
	  if($('#slider_start').val()!=0)
		totmin+=60-parseInt(getminarr[1]);
	  else if($('#slider_end').val()!=100)
		totmin+=parseInt(getminarr[1]);
	  else
		totmin+=parseInt(getminarr[1]);
	  
	})
	$("[class='staff checked'] [class^='split scheduled ']").each(function(){
	  var getmins= $(this).text();
	  var getminarr = splitTime(getmins);
	  if($('#slider_start').val()!=0)
		totmin+=60-parseInt(getminarr[1]);
	  else if($('#slider_end').val()!=100)
		totmin+=parseInt(getminarr[1]);
	  else
		totmin+=parseInt(getminarr[1]);
	  
	})
	
	var extra_hours = Math.floor(totmin / 60);
	var hours = parseInt(extra_hours)+parseInt(tothrs_len);
	var minutes = totmin % 60;
	if(minutes!=0)
		var time = hours+":"+minutes;
	else
		var time = hours+":00";
	var new_pay=hours*parseInt(basicpay)+minutes/60*parseInt(basicpay);
	$("[class='staff checked'] [id='tothr']").text(time);
	var prev_cost = $('#labor-cost').text();
	//console.log('prev_cost  '+prev_cost+' intial_pay  '+' prev_cost'+prev_cost);
	var new_cost = (prev_cost-parseInt(intial_pay))+parseInt(new_pay);
	$('#labor-cost').text(new_cost);
}
function slider(startval,endval)
{
	
	if(startval!='' && endval!='')
	{
		/*** setting values & reintialize scheduler div ***/
		var min = $('#slider_start').val();
		var max = $('#slider_end').val();
		var class_name=$("[class='staff checked'] [id='class_name']").val();
		var timeStart = new Date('07/07/2019 ' + startval).getHours();
		var timeEnd = new Date('07/07/2019 ' + endval).getHours();
		var total_hrs = timeEnd - timeStart; 
		
		var reint_lim = total_hrs;
		var slotwidth = $('#slotwidth').val(); // for 15 min interval
		var slot = Math.floor(60/slotwidth)
		var total_map=Math.floor(100/(total_hrs*slot)); 
		
		var strsplit_time = splitTime(startval);
		var strhour =  parseInt(strsplit_time[0]);
		var strmin = parseInt(strsplit_time[1]);
		
		var endsplit_time = splitTime(endval);
		var endhour =  parseInt(endsplit_time[0]);
		var endmin = parseInt(endsplit_time[1]);
		var inc = strhour;
		if(endmin==0)
			reint_endhour = endhour-1;
		else
			reint_endhour = endhour;
		for(var k=strhour; k<=reint_endhour; k++)
		{
			$("[class='staff checked'] [id='span"+k+"']").attr( "class",class_name);
			$("[class='staff checked'] [id='span"+k+"']").text('');
			
			if(k==strhour && k==parseInt(reint_endhour))
			{
				if(strmin>0)
					$("[class='staff checked'] [id='span"+k+"']").attr( "class",class_name+" start-"+strmin);
				$("[class='staff checked'] [id='span"+k+"']").text(tConvert(endval));
				
			}
			else if(k==strhour)
			{
				if(strmin>0)
					$("[class='staff checked'] [id='span"+k+"']").attr( "class",class_name+" start-"+strmin);
				$("[class='staff checked'] [id='span"+k+"']").text(tConvert(startval));
				
			}
			else if(k==parseInt(reint_endhour))
			{
				if(endmin>0)
					$("[class='staff checked'] [id='span"+k+"']").attr( "class",class_name+" end-"+endmin);
				$("[class='staff checked'] [id='span"+k+"']").text(tConvert(endval));
				
			}
			
		}
		
		/****  forward moving ****/
		if(min!=0)
		{
			var inc = strhour;
			var strnewhour='';
			var map = Math.floor(min/total_map);
			if(map>0)
			{
				var span = Math.floor(map/slot);
				var cell = map%slot;
				/** condition for 1st span **/
				if(span==0)
				{
					span=1;
					cell=map;
					lim_span = strhour;
				}
				else
				{
					if(cell>0)
						span=span+1;
					lim_span = parseInt(span)+parseInt(strhour)-1;	
				}
				/** Get slider start in split ****/
				for(var i=inc; i<=lim_span; i++)
				{
					if(i==lim_span)
					{
						if(cell>0)
						{
							start__val = cell*slotwidth;
							var schedule_class = class_name+" start-"+start__val;
							strnewhour = strnewhour-1;
							if(strnewhour<0)
								strnewhour = strhour;
							if(strmin>0)
								if(parseInt(strnewhour)==parseInt(strhour) && parseInt(start__val)<parseInt(strmin))
									return false;
							strmin = start__val;
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
					if(parseInt(strhour)<=j && parseInt(endhour)>=j)
						$("[class='staff checked'] [id='span"+j+"']").text('');
					if(parseInt(strhour)<=k && parseInt(endhour)>k)
					{
						if(strnewhour.toString().length == 1) 
							strnewhour = "0" + strnewhour;
						$("[class='staff checked'] [id='span"+k+"']").text(tConvert(strnewhour+':'+strmin));
					}
						
					$("[class='staff checked'] [id='span"+i+"']").attr( "class",schedule_class);
				}
			}
			var start_hour = strnewhour+':'+strmin;
		}
		
		/****  Backward moving ****/
		if(max<100)
		{				
			var diff = 100-max;
			var map = Math.floor(diff/total_map);
			if(map>0)
			{
								
				var span = Math.floor(map/slot);
				var cell = map%slot;
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
							endval = slotwidth*(slot-cell)
							var schedule_class = class_name+" end-"+endval;
							if(endmin>0)
								if(parseInt(newhour)==parseInt(endhour) && parseInt(endval)>parseInt(endmin))
									return false;
							endmin = endval;
							k=parseInt(newhour);
							j=parseInt(newhour)+1;
						}
						else
						{
							var schedule_class = 'avail_schedule';
							endmin='00';
							k=parseInt(newhour)-1;
							
							if(i==dec)
								j=parseInt(newhour);
							else
								j=parseInt(newhour)+1;
						}
					
					}
					else
					{
						var schedule_class = 'avail_schedule';
						newhour=newhour;
						j=parseInt(newhour)+1;
						k=parseInt(newhour);
					}
					if(parseInt(strhour)<=j && parseInt(endhour)>=j)
						$("[class='staff checked'] [id='span"+j+"']").text('');
				    if(parseInt(strhour)<=k && parseInt(endhour)>=k)
						$("[class='staff checked'] [id='span"+k+"']").text(tConvert(newhour+':'+endmin));
					$("[class='staff checked'] [id='span"+newhour+"']").attr( "class",schedule_class);
					m++
					
				}
				var end_hour=newhour+':'+endmin;
				
			}
		}
		if(start_hour==undefined)
			start_hour=startval;
		if(end_hour==undefined)
			end_hour=endval;
		schedule_hrs=new Array(start_hour,end_hour);
		return schedule_hrs;
	}
		 
} 
function split()
{
	
	var scheduled_array_tot_avail=$("#avail_arr").val();
	scheduled_array_tot_avail=jQuery.parseJSON(scheduled_array_tot_avail);
	for(var i=0; i<scheduled_array_tot_avail.length; i++)
	{
		var getstrhr = splitTime(scheduled_array_tot_avail[i][0]);
		var getendhr = splitTime(scheduled_array_tot_avail[i][1]);

		if(getstrhr[1]>0)
			var strhr = parseInt(getstrhr[0])+1;
		else
			var strhr = parseInt(getstrhr[0]);
		var endhr = parseInt(getendhr[0]);
		for(var j=strhr; j<endhr; j++)
		{
			if(strhr.toString().length == 1) 
				strhr = "0" + strhr;
			$("[class='staff checked'] [id='span"+j+"']").attr( "class","split scheduled");
			if(j==strhr && strhr==parseInt(endhr)-1)
				$("[class='staff checked'] [id='span"+j+"']").text(tConvert(endhr+":00"));
			else if(j==strhr )
				$("[class='staff checked'] [id='span"+j+"']").text(tConvert(strhr+":00"));
			else if(j==parseInt(endhr)-1)
				$("[class='staff checked'] [id='span"+j+"']").text(tConvert(parseInt(endhr)+":00"));
			
			$("[class='staff checked'] [id='span"+j+"']").attr( "onclick","scroll_split('"+strhr+":00','"+endhr+":00','split')");
		}
		
	}
	sethours();
}
function scroll_split(startval,endval,type)
{
	
	if(type=='split')
		$("[class='staff checked'] [id='class_name']").val('split scheduled');
	else
		$("[class='staff checked'] [id='class_name']").val('scheduled');
	$("[class='staff checked'] [id='startval']").val(startval);
	$("[class='staff checked'] [id='endval']").val(endval);
	Foundation.reInit($(".slider"));
} 
function splitTime(timeval)
{
	var hourmin = timeval.split(":");
	var hour = parseInt(hourmin[0]);
	var min = parseInt(hourmin[1]);
	var array = new Array(hour,min);
	return(array);
}
function tConvert (time) 
{
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
						if (xval[1].toLowerCase() > yval[1].toLowerCase())
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
function set_individual_tothrs(){
	var avlhr = 0;
	$(".total-hours").each(function(){
		var getavlhr = $('#totavlhr'+avlhr).val();
		$(this).text(getavlhr);
		avlhr++;
	});
	var date = $('#print_date').val();
	$('#showdate').text(date);
}
function setajax_response(result)
{
	var json=jQuery.parseJSON(result);	
	$('#table_body').html(json['tablebody']);
	$('#action').val('');
	$('#labor-hour').text(json['tothrs']);
	$('#labor-cost').text(json['totcost']);
	$('#all_tot_hrs').text(json['tothrs']);
	set_individual_tothrs();
}
function save()
{
	
}

