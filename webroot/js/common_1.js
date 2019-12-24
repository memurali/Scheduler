$(function(){
      $('.staffRow').change(function(){
        $('tr').removeClass('checked');
        if($(this).is(':checked')){
          $(this).closest('tr').addClass('checked');
          var radioValue = $("input[name='staff']:checked").val();
          $("#search_name").val(radioValue);
        }
      });
	  $('.slider').on('moved.zf.slider', function()			
		{
	  		
			/*** seeting values & reintialize scheduler div ***/
			var min = $('#slider_start').val();
			var max = $('#slider_end').val();
			var span = $("[class='staff checked'] [class='cell large-auto available']");
			var total_hrs=span.length;
			var total_map=Math.floor(100/(total_hrs*4));
			var startval = $("[class='staff checked'] [id='startval']").val();
			var endval = $("[class='staff checked'] [id='endval']").val();
			
			var strsplit_time = startval.split(" ");
			var strtime = strsplit_time[1];		
			var strhourmin = strsplit_time[0].split(":");
			var strhour = parseInt(strhourmin[0]);
			var strmin = parseInt(strhourmin[1]);
			
			
			var endsplit_time = endval.split(" ");
			var endtime = endsplit_time[1];		
			var endhourmin = endsplit_time[0].split(":");
			var endhour = parseInt(endhourmin[0]);
			var endmin = parseInt(endhourmin[1]);
			
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
			
			
			/****  forward moving ****/
			if(min!=0)
			{
				var map = Math.floor(min/total_map);
				if(map>0)
				{
					
					var span = Math.floor(map/4);
					var cell = map%4;
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
					for(var i=1; i<=span; i++)
					{
						if(i==span)
						{
							if(cell>0)
							{
								startval = cell*15;
								var schedule_class = "scheduled start-"+startval;
								strmin = startval;
								if(i==1)
									newhour = strhour;
								else
									newhour = newhour-1;
								k=i;
								j=i-1;
							
							}
							else
							{
								var schedule_class = '';
								newhour=strhour+span;
								strmin='00';
								k=i+1;
								j=i;
							}
							
						}
						else
						{
							var schedule_class = '';
							newhour=strhour+span;
							j=i;
						}
						
						if(newhour>=12)
							strtime = 'P';
						
						cal_strhour = (newhour>12)?(newhour-12):newhour;
						$("[class='staff checked'] [id='span"+j+"']").text('');
						$("[class='staff checked'] [id='span"+k+"']").text(cal_strhour+':'+strmin+' '+strtime);
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
					var span_diff = total_hrs - span;
					/*** for last span***/
					if(span==0)
					{
						span_diff=total_hrs-1;
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
					for(var i=total_hrs; i>span_diff; i--)
					{
						if(i==total_hrs)
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
								j=i-1;
							}
							else
							{
								var schedule_class = '';
								endmin='00';
								k=i-1;
								j=i;
							}
						
						}
						else
						{
							var schedule_class = '';
							newhour=newhour;
							j=i;
						}
						if(newhour==0)
							endtime='P';
						else if(newhour<0)
							endtime='A';
						cal_endhour = (newhour<=0)?(newhour+12):newhour;
						$("[class='staff checked'] [id='span"+j+"']").text('');
						$("[class='staff checked'] [id='span"+k+"']").text(cal_endhour+':'+endmin+' '+endtime);
						$("[class='staff checked'] [id='span"+i+"']").attr( "class",schedule_class);
						m++
					}
				}
			}
		
		});
    });
function split()
{
	alert('split');
	for(var k=1; k<=8; k++)
	{
		$("[class='staff checked'] [id='span"+k+"']").attr( "class","scheduled");
	}
}