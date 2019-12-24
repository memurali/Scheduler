$(function(){ 

	var delay = (function(){
	  var timer = 0;
	  return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	  };
	})();
	
	//search employees in scheduler page
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
	
	//search employees in employees page
	$("#empsearch_name").keypress(function(e) {
		if(e.which == 13) {
			jQuery('#empsearch_name').keyup();
		}
	});
			
	$('#empsearch_name').keyup(function() 
	{
		delay(function(){
			var arrange=$('#empsearch_name').val();
			var query = arrange.toLowerCase();				
			sortTableEmployee('search',query);
		},200);
	});
	//Arrange employees
	$('.select').on('change', function() {	
	  sortTable('arrange',this.value);  
	});
	
	//Arrange employees
	$('#select_emp').on('change', function() {	
	    sortTableEmployee('arrange',this.value);  
	});
	
	$('#yes-no').on('change',function(){
		var currentdate = $('#currentdate').val();
		var val = $(this).is(':checked') ? 'checked' : 'unchecked';
		var dateval = $('#dateval').val();
		$.ajax({								
			type: 'POST',	
			data: {action:'24hrs',dateval:dateval,currentdate:currentdate,type:val},
			url: $('#pagename').val(),
			dataType: "text",
			success: function (result)
			{
				setajax_response(result);
				businesshrs();
			}
		});
		
	});	

	$('#today').on('click',function(){
		var day = new Date();
		var currentdate = day.getDay();
		$('#currentdate').val(currentdate);
		var dateval = $('#dateval').val();
		var val = $('#yes-no').is(':checked') ? 'checked' : 'unchecked';			
		$.ajax({								
			type: 'POST',	
			data: {action:'today',dateval:dateval,currentdate:currentdate,type:val},
			url: $('#pagename').val(),
			dataType: "text",
			success: function (result)
			{
				setajax_response(result);
				businesshrs();
			}
		});		
	});
	$('#popup_close').on('click',function(){
		popupclose();
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
		$('.slider').removeClass('disabled');
        if($(this).is(':checked')){
		   $(this).closest('tr').addClass('checked');
		   $("[class='slider'][class='slider-fill']").css('min-width','100%');
		   Foundation.reInit($(".slider"));
		   var radioValue = $("input[name='staff']:checked").val();
		   $("#search_name").val(radioValue);
		}
		
    });
	$(document).on('change','#seldate', function() {
		var date  = $('#seldate').val();
		$.ajax
		({								
			type: 'POST',	
			data: {action:'published',publishdate:date},
			url: '',
			dataType: "text",
			success: function (data)
			{					
				$('#publish_ul').html(data);
			}
		});
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
			
			var strsplit_time = splitTime(startval);
			var strhour =  parseInt(strsplit_time[0]);
			var strmin = parseInt(strsplit_time[1]);
			var strtime = strsplit_time[2];
			
			var endsplit_time = splitTime(endval);
			var endhour =  parseInt(endsplit_time[0]);
			var endmin = parseInt(endsplit_time[1]);
			var endtime = endsplit_time[2];
			var spanhr = strhour;
			var class_name=$("[class='staff checked'] [id='class_name']").val();
		    Foundation.reInit($(".slider"));
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
	});
	//Previous and Next process	
	$('#prevsaveyes').click(function(){
		  $('#action').val('prev');
		  savefunc();
	});	
	
	//Previous process in publishedday
	$('#published_prev').click(function(){
		  $('#action').val('prev');
		  savefunc();
	});	
	
	//Save process	
	$('#savedraft').click(function(){
		$('#action').val('save');
		savefunc();
	});	
	$('#btnpublish').click(function(){
		if(parseInt($('#currentdate').val())+1== $('#schedule_start').val())
		{
			$('#action').val('save');
			var dateval = $('#dateval').val();
			savefunc();
			jQuery.ajax({								
				type: 'POST',	
				data: {action:'publish',date:dateval},
				url: 'schedulerday',
				dataType: "text",
				success: function (data)
				{					
					alert('published successfully');
				}
			});
		}
		else
		{
			alert("You can't publish here, you can publish only in end of the week ");
			$('#action').val('save');
			savefunc();
		}
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
		
	},1000)
	
	$('#checkbox_closed').click(function() {
		if($(this).is(":checked")) 
		{
			$('#timeopen').val('');
			$('#timeclose').val('');
			$('#timeopen').prop("disabled", true );
			$('#timeclose').prop("disabled", true );
		}
		else
		{
			$('#timeopen').prop("disabled", false );
			$('#timeclose').prop("disabled", false );
		}
	});
	
	/**** employees page ****/
	$('#addcustom').click(function() {
		var formdata = $('#addcustomfrm').serialize();
		if($('#customfieldname').val()=='')
		{
			alert('You must enter custom fields');
			return false;
		}
		var attrval = $('#fieldval').attr('placeholder');
		if(attrval.indexOf('mandatory') > -1)
		{
			if($('#fieldval').val()=='')
			{
				alert('You must enter fieldvalue');
				$('#fieldval').focus();
				return false;
			}
		}
		jQuery.ajax
		({								
			type: 'POST',	
			data: {action:'customsave',mode:'insert',formdata:formdata},
			url: 'employees',
			dataType: "text",
			success: function (data)
			{					
				$('#custom_response').html(data);
				$('#customfieldname').val('');
				$('#customfield_type').val('');
				$('#fieldval').attr('placeholder','');
				$('#fieldval').val('');
			}
		});
	});
});




function savefunc()
{
	var dateval = $('#dateval').val();	
	if($('#pagename').val()=='schedulerday')
	{
		var emp_avail=[];
		var emp_count=$(".empcount").val();
		for(var emp=0;emp<emp_count;emp++)
		{
			var get_slot_timing=$("[id='staff_"+emp+"'] [class^='scheduled']").text();
			var empid= $("[id='empid_"+emp+"']").val();
			var split_get_slot_timing=get_slot_timing.split("M");
			var slot=Math.floor((split_get_slot_timing.length-1));
			var slot_val=Math.floor((split_get_slot_timing.length-1)/2);
			//alert('slot = '+slot);
			var j=1;
			for(var i=0;i<slot;i++)
			{
				if(i!=0)
				emp_avail+='#';
				split_get_slot_timing[i]=split_get_slot_timing[i].replace('-','');
				split_get_slot_timing[i+1]=split_get_slot_timing[i+1].replace('-','')
				emp_avail+=empid+'|'+j+'|'+split_get_slot_timing[i]+'|'+split_get_slot_timing[i+1];
				i= i+1;
				j++;
			}
			if(get_slot_timing!='')
				emp_avail+='&';
			
		}
		document.getElementById('save_scheduled_array').value=emp_avail;
		var json_data=document.getElementById('save_scheduled_array').value;
	}
	else
	{
		var json_data='';
	}
	var val = $('#yes-no').is(':checked') ? 'checked' : 'unchecked';
	if($('#action').val()!='save')
	{
		if($('#action').val()=='')		  
		   $('#action').val('next');
		
		if($('#action').val()=='next')
		{
			$('#currentdate').val(parseInt($('#currentdate').val())+1);
			if($('#currentdate').val()>7)
				$('#currentdate').val(1);
			if($('#currentdate').val()== $('#schedule_start').val())
			{	
				alert("You can't go to next week ");
				$('#currentdate').val(parseInt($('#currentdate').val())-1);
				/*if($('#pagename').val()=='publishedday')
					return false;*/
				$('#action').val('save');
			}
		}
		else
		{	
			$('#currentdate').val(parseInt($('#currentdate').val())-1);
			if($('#currentdate').val()== 0)
				$('#currentdate').val(7);
			if(parseInt($('#currentdate').val())+1 == $('#schedule_start').val())
			{
				$('#action').val('next');
				alert("You can't go to previous week");
				$('#currentdate').val(parseInt($('#currentdate').val())+1);
				/*if($('#pagename').val()=='publishedday')
					return false;*/
				$('#action').val('save');
			}
		}	
	}
	
	var currentdate= $('#currentdate').val();
	var action= $('#action').val();
	jQuery.ajax({								
		type: 'POST',	
		data: {action:action,dateval:dateval,currentdate:currentdate,type:val,json_data:json_data},
		url: $('#pagename').val(),
		dataType: "text",
		success: function (result)
		{					
			setajax_response(result);
			businesshrs();
		}
	});
}
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
function popupclose()
{
	$('#popup1').css('display','none');
	$('#slider_popup').css('display','none');
}
function sethours()
{
	var basicpay = $("[class='staff checked'] [id='basicpay']").val();
	var initial_time = $("[class='staff checked'] [class='total-hours']").text();
	var initial_timearr = splitTime(initial_time);
	var intial_pay=initial_timearr[0]*parseInt(basicpay)+initial_timearr[1]/60*parseInt(basicpay);
	$('#initialpay').val(intial_pay);
	var tothrs_len = $("[class='staff checked'] [class='scheduled']").length;
	var split_tothrs_len = $("[class='staff checked'] [class='scheduled split']").length;
	tothrs_len = parseInt(tothrs_len)+parseInt(split_tothrs_len);
	var totmin =0;
	$("[class='staff checked'] [class^='scheduled ']").each(function(){
		var class_name = this.className;
		if(class_name.indexOf('split') == -1)
		{
			var classname = this.className;
			var classname_min = classname.match(/\d+/);
			if(class_name.indexOf('start')>-1)
				totmin+=60-parseInt(classname_min);
			if(class_name.indexOf('end')>-1)
				totmin+=parseInt(classname_min);
		}
	  
	})
	$("[class='staff checked'] [class^='scheduled split ']").each(function(){
		var classname = this.className;
		var classname_min = classname.match(/\d+/);
		if(classname.indexOf('start')>-1)
			totmin+=60-parseInt(classname_min);
		if(classname.indexOf('end')>-1)
			totmin+=parseInt(classname_min);
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
	var new_cost = ((prev_cost-parseInt(intial_pay))+parseInt(new_pay)).toFixed(2);
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
				$("[class='staff checked'] [id='span"+k+"']").text(tConvert(endval));
				if(strmin>0)
					$("[class='staff checked'] [id='span"+k+"']").attr( "class",class_name+" start-"+strmin);
				else
					$("[class='staff checked'] [id='span"+k+"']").text(tConvert(startval)+'-'+tConvert(endval));
				
				
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
					
					
					
					$("[class='staff checked'] [id='span"+i+"']").attr( "class",schedule_class);
					
					
					var nxtclass = $("[class='staff checked'] [id='span"+(parseInt(k)+1)+"']").attr('class');
					var prevclass = $("[class='staff checked'] [id='span"+(parseInt(k)-1)+"']").attr('class');
					if(strnewhour.toString().length == 1) 
							strnewhour = "0" + strnewhour;
					if(nxtclass==undefined)
						nxtclass = '';
					if(prevclass==undefined)
						prevclass = '';
					if((nxtclass.indexOf('scheduled') > -1) || (prevclass.indexOf('scheduled') > -1))
						var show_time = tConvert(strnewhour+':'+strmin); 
					else
					{
						var exist_text = $("[class='staff checked'] [id='span"+parseInt(k)+"']").text();
						if(exist_text.indexOf('-') > -1)
						{
							var explode = exist_text.split('-');
							exist_text = explode[1];
						}
						var show_time = tConvert(strnewhour+':'+strmin)+'-'+exist_text;
					}
					if(parseInt(strhour)<=j && parseInt(endhour)>=j)
						$("[class='staff checked'] [id='span"+j+"']").text('');
					if(parseInt(strhour)<=k && parseInt(endhour)>k)
					{
						
						$("[class='staff checked'] [id='span"+k+"']").text(show_time);
					}
						
					
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
					
					$("[class='staff checked'] [id='span"+newhour+"']").attr( "class",schedule_class);
					
					var nxtclass = $("[class='staff checked'] [id='span"+(parseInt(k)+1)+"']").attr('class');
					var prevclass = $("[class='staff checked'] [id='span"+(parseInt(k)-1)+"']").attr('class');
					if(newhour.toString().length == 1) 
							newhour = "0" + newhour;
					if(nxtclass==undefined)
						nxtclass = '';
					if(prevclass==undefined)
						prevclass = '';
					if(newhour.toString().length == 1) 
						newhour = "0" + newhour;
					if((nxtclass.indexOf('scheduled') > -1) || (prevclass.indexOf('scheduled') > -1))
						var show_time = tConvert(newhour+':'+endmin);
					else
					{
						var existing_text = $("[class='staff checked'] [id='span"+parseInt(k)+"']").text();
						if(existing_text.indexOf('-') > -1)
						{
							var explode = existing_text.split('-');
							existing_text = explode[0];
						}
						var show_time = existing_text+'-'+tConvert(newhour+':'+endmin);
						
					}
					if(parseInt(strhour)<=j && parseInt(endhour)>=j)
						$("[class='staff checked'] [id='span"+j+"']").text('');
				    if(parseInt(strhour)<=k && parseInt(endhour)>=k)
						$("[class='staff checked'] [id='span"+k+"']").text(show_time);
					
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
	else
	{
		$('#popup1').css('display','block');
		$('#slider_popup').css('display','block');
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
			$("[class='staff checked'] [id='span"+j+"']").attr( "class","scheduled split");
			if(j==strhr && strhr==parseInt(endhr)-1)
				$("[class='staff checked'] [id='span"+j+"']").text(tConvert(strhr+":00")+'-'+tConvert(endhr+":00"));
			else if(j==strhr)
				$("[class='staff checked'] [id='span"+j+"']").text(tConvert(strhr+":00"));
			else if(j==parseInt(endhr)-1)
				$("[class='staff checked'] [id='span"+j+"']").text(tConvert(parseInt(endhr)+":00"));
			
			$("[class='staff checked'] [id='span"+j+"']").attr( "onclick","scroll_split('"+strhr+":00','"+endhr+":00','split')");
		}
		
	}
	$("[class='staff checked'] [id='startval']").val('');
	$("[class='staff checked'] [id='endval']").val('');
	sethours();
	get_all_tothrs();
}
function scroll_split(startval,endval,type)
{
	
	if(type=='split')
		$("[class='staff checked'] [id='class_name']").val('scheduled split');
	else
		$("[class='staff checked'] [id='class_name']").val('scheduled');
	$("[class='staff checked'] [id='startval']").val(startval);
	$("[class='staff checked'] [id='endval']").val(endval);
	$('.slider').removeClass('disabled');
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
//Arrange employees
function sortTableEmployee(type,sortby) 
{
	var table, rows, switching, i, x, y, shouldSwitch;
	table = document.getElementById("emptable_body");
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
					var xval = x.split(" ");
					var yval = y.split(" ");
					if(sortby=='firstname')
					{
						if (xval[0].toLowerCase() > yval[0].toLowerCase())
						{				
							shouldSwitch = true;
							break;
						}
					}
					if(sortby=='lastname')
					{
						if (xval[1].toLowerCase() > yval[1].toLowerCase())
						{
							shouldSwitch = true;
							break;
						}
					}
				}
				if(sortby=="mostrecent")
				{
					var x = rows[i].getElementsByTagName("td")[0].getElementsByTagName("input")[1].value;
					var y = rows[i + 1].getElementsByTagName("td")[0].getElementsByTagName("input")[1].value;
					if(new Date(y) > new Date(x))
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
	$('#popup_date').text(date);
	var holiday = $('#holiday').val();
	$('.label').text(holiday);
}
function set_individual_tothrs_published()
{
	var avlhr = 0;
	$(".total-hours").each(function(){
		var getavlhr = $('#totavlhr'+avlhr).val();
		$(this).text(getavlhr);
		avlhr++;
	});
	var date = $('#publish_print_date').val();
	$('#showdate').text(date);
	var holiday = $('#holiday').val();
	$('.label').text(holiday);
	var dateval = $('#dateval').val();
	$('#weekopt').val('publishedweek?date='+dateval);
	var newurl = "../users/schedulerday?selecteddate="+dateval;
	$('#edithref').attr("href",newurl);
	
}
function setajax_response(result)
{	
	var json=jQuery.parseJSON(result);	
	$('#table_body').html(json['tablebody']);
	$('#action').val('');
	$('#labor-hour').text(json['tothrs']);
	$('#labor-cost').text(json['totcost']);
	$('#all_tot_hrs').text(json['tothrs']);
	if($('#pagename').val()=='schedulerday')
		set_individual_tothrs();
	if($('#pagename').val()=='publishedday')
		set_individual_tothrs_published();
	
}
function businesshrs()
{
	var storeOpen = $('#store_open').val();
	var storeClose = $('#store_close').val();
	var splitopen = splitTime(storeOpen);
	var splitclose = splitTime(storeClose);
	$('#businesshr').html('');
	if(splitclose[1]>0)
		var endhr = parseInt(splitclose[0])+1;
	else
		var endhr = parseInt(splitclose[0]);
	$('#businesshr').html('');
	for(var hr=parseInt(splitopen[0]); hr<=parseInt(endhr); hr++)
	{
		if(hr>0)
			var hr_time = (hr>=12)?((hr-12==0)?12:hr-12)+'P' : hr+'A';
		else
			var hr_time = '12A';
		$('#businesshr').append('<div class="cell large-auto">'+hr_time+'</div>');
	}
}
function calendar_change()
{
	var caldate = $('#calendardate').val();
	if(caldate!='')
	{
		if($('#pagename').val()=='publishedweek')
		{
			var newdate=new Date(caldate);
			var date = new Date(newdate.getTime() - (newdate.getTimezoneOffset() * 60000 ))
                    .toISOString()
                    .split("T")[0];
			window.location.href = 'publishedweek?date='+date;
		}
		else
		{
			var currentdate = new Date(caldate).getDay();
			$('#currentdate').val(currentdate);
			var dateval = caldate;
			var val = $('#yes-no').is(':checked') ? 'checked' : 'unchecked';
			$.ajax({								
				type: 'POST',	
				data: {action:'calendar',dateval:dateval,currentdate:currentdate,type:val},
				url: $('#pagename').val(),
				dataType: "text",
				success: function (result)
				{
					setajax_response(result);
					businesshrs();
				}
			});
		}
	}
}
function save_businesshrs()
{
	var formdata = $('#frmsave_hrs').serialize();
	$.ajax
	({								
		type: 'POST',	
		data: {action:'save_businesshrs',formdata:formdata},
		url: 'storehours',
		dataType: "text",
		success: function (result)
		{
			location.reload();
		}
	});
}
function save_holiday()
{
	if($('#holiday_date').val()=='')
	{
		alert('You must select holiday date');
		$('#holiday_date').focus();
		return false;
	}
	else if($('#holiday_note').val()=='')
	{
		alert('You must enter note value');
		$('#holiday_note').focus();
		return false;
	}
	if($('#checkbox_closed').is(':checked'))
	{
		$('#timeopen').prop("disabled", true );
		$('#timeclose').prop("disabled", true );
		
	}
	else
	{
		$('#timeopen').prop("disabled", false );
		$('#timeclose').prop("disabled", false );
		if($('#timeopen').val()=='')
		{
			alert('You must enter opening time');
			$('#timeopen').focus();
			return false;
		}
		if($('#timeclose').val()=='')
		{
			alert('You must enter closing time');
			$('#timeclose').focus();
			return false;
		}	
		
	}
	
	var formdata = $('#save_holiday').serialize();
	$.ajax
	({								
		type: 'POST',	
		data: {action:'save_holiday',formdata:formdata},
		url: 'storehours',
		dataType: "text",
		success: function(result)
		{
			alert('Saved successfully');
			$('#tbody').html(result);
		}
	});
	
}
function editholiday(id)
{
	$.ajax
	({								
		type: 'POST',	
		data: {action:'editholiday',id:id},
		url: 'storehours',
		dataType: "text",
		success: function(result)
		{
			var json = jQuery.parseJSON(result);
			$('#holidayid').val(id);
			$('#holiday_date').val(json.holidaydate);
			$('#holiday_note').val(json.holidaydesc);
			if(json.holidayopen==null && json.holidayclose==null)
			{
				$('#timeopen').val('');
				$('#timeclose').val('');
				$('#timeopen').attr('disabled',true);
				$('#timeclose').attr('disabled',true);
				$('#checkbox_closed').attr('checked',true);
			}
			else
			{
				$('#timeopen').attr('disabled',false);
				$('#timeclose').attr('disabled',false);
				$('#checkbox_closed').attr('checked',false);
				$('#timeopen').val(json.holidayopen);
				$('#timeclose').val(json.holidayclose);
			}
		}
	});
}
function deleteholiday(id)
{
	var confirmval = confirm('Are you want to delete holiday');
	if(confirmval==true)
	{
		$.ajax
		({								
			type: 'POST',	
			data: {action:'deleteholiday',id:id},
			url: 'storehours',
			dataType: "text",
			success: function(result)
			{
				alert('Deleted successfully');
				$('#tbody').html(result);
			}
		});
	}
	else
	{
		return false;
	}
}


/*** employees page ***/
function fieldtype_change(fieldtype,mode)
{
	switch (fieldtype) {
	  case 'text':
		attrval = "Give varchar length mandatory";
		break;
	  case 'number':
		attrval = "Give number format like int,decimal,float";
		break;
	  case 'checkbox':
		attrval = "Yes/No type";
		break;
	  case 'file':
		attrval = "Blob type";
		break;
	  case 'radio':
		attrval = "Give more than one options with comma separated mandatory";
		break;
	  case 'date':
		attrval = "Date format must be in MM/DD/YYY";
		break;
	  case 'dropdown':
		attrval = "Give dropdown options with comma separated mandatory";
		break;
	  case 'textarea':
		attrval = "Give varchar length here mandatory";
		
	}
	if(mode=='add')
	{
		$('#fieldval').val('');
		$('#fieldval').attr('placeholder',attrval);
	}
	else
	{
		$('#editfieldval').val('');
		$('#editfieldval').attr('placeholder',attrval);
	}
}

function editcustom(attrid)
{
	$('#popup3').css('display','block');
	$('#customField').css('display','block');
	$('#attrid').val(attrid);
	$('#editattribute_id').val(attrid);
	$.ajax
	({								
		type: 'POST',	
		data: {action:'editcustom',attrid:attrid},
		url: 'employees',
		dataType: "text",
		success: function(result)
		{
			var json=jQuery.parseJSON(result);	
			$('#editcustomname').val(json['keypair']);
			$('#editcustomfield_type').val(json['keypair_type']);
			if(json['keypair_val']!='')
				$('#editfieldval').val(json['keypair_val']);
			else
				$('#editfieldval').attr('placeholder','None');
			$('#editcustom_save').attr('onclick','editcustomsave('+attrid+');');
		}
	});
}
function editcustomsave(attrid)
{
	var formdata = $('#editcustomfrm').serialize();
	if($('#editcustomname').val()=='')
		{
			alert('You must enter custom field name');
			return false;
		}
		var attrval = $('#editfieldval').attr('placeholder');
		if(attrval!=undefined)
		{
			if(attrval.indexOf('mandatory') > -1)
			{
				if($('#editfieldval').val()=='')
				{
					alert('You must enter fieldvalue');
					$('#editfieldval').focus();
					return false;
				}
			}
		}
		jQuery.ajax
		({								
			type: 'POST',	
			data: {action:'customsave',mode:'edit',formdata:formdata},
			url: 'employees',
			dataType: "text",
			success: function (data)
			{					
				alert('Updated successfully');
				$('#custom_response').html(data);
				$('#popup3').css('display','none');
				$('#customField').css('display','none');
			}
		});
}
function closeeditcustom()
{
	$('#popup3').css('display','none');
	$('#customField').css('display','none');
}
function closeeditempview()
{
	$('#popup1').css('display','none');
	$('#editEmployee').css('display','none');
}
function customdelete()
{
	var attrid = $('#editattribute_id').val();
	jQuery.ajax
	({								
		type: 'POST',	
		data: {action:'customsave',mode:'delete',attrid:attrid},
		url: 'employees',
		dataType: "text",
		success: function (data)
		{					
			alert('Deleted successfully');
			$('#custom_response').html(data);
			$('#popup3').css('display','none');
			$('#customField').css('display','none');
			location.reload();
		}
	});
}
function savestaff()
{
	if($('#emp_fname').val()=='')
	{
		alert('Enter first name');
		$('#emp_fname').focus();
		return false;
	}
	if($('#emp_lname').val()=='')
	{
		alert('Enter first name');
		$('#emp_lname').focus();
		return false;
	}
	if($('#emp_addr').val()=='')
	{
		alert('Enter address');
		$('#emp_addr').focus();
		return false;
	}
	if($('#emp_city').val()=='')
	{
		alert('Enter city');
		$('#emp_city').focus();
		return false;
	}
	if($('#emp_state').val()=='')
	{
		alert('Enter state');
		$('#emp_state').focus();
		return false;
	}
	if($('#emp_zip').val()=='')
	{
		alert('Enter zip');
		$('#emp_zip').focus();
		return false;
	}
	if($('#emp_phone').val()=='')
	{
		alert('Enter phone');
		$('#emp_phone').focus();
		return false;
	}
	if($('#emp_email').val()=='')
	{
		alert('Enter email');
		$('#emp_email').focus();
		return false;
	}
	else
		$('#addstaff').submit();
}
function editempview(id)
{ 
	$('#popup1').css('display','block');
	$('#editEmployee').css('display','block');
	jQuery.ajax
	({								
		type: 'POST',	
		data: {action:'editempview',userid:id},
		url: 'employees',
		dataType: "text",
		success: function (data)
		{					
			$('#editempfrm').html(data);
		}
	});
	
}
function update_emp(userid)
{
	if($('#update_fname').val()=='')
	{
		alert('Enter first name');
		$('#update_fname').focus();
		return false;
	}
	if($('#update_lname').val()=='')
	{
		alert('Enter first name');
		$('#update_lname').focus();
		return false;
	}
	if($('#update_addr').val()=='')
	{
		alert('Enter address');
		$('#update_addr').focus();
		return false;
	}
	if($('#update_city').val()=='')
	{
		alert('Enter city');
		$('#update_city').focus();
		return false;
	}
	if($('#update_state').val()=='')
	{
		alert('Enter state');
		$('#update_state').focus();
		return false;
	}
	if($('#update_zip').val()=='')
	{
		alert('Enter zip');
		$('#update_zip').focus();
		return false;
	}
	if($('#update_cell').val()=='')
	{
		alert('Enter phone');
		$('#update_cell').focus();
		return false;
	}
	if($('#update_email').val()=='')
	{
		alert('Enter email');
		$('#update_email').focus();
		return false;
	}
	else
		$('#editempfrm').submit();
	
}

function roleselect(id)
{
	var rate = jQuery('#sel_role').find('option[value="' + id + '"]').attr('id');
	$('#rate').val(rate);
}