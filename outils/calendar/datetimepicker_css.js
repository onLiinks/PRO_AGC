var winCal;var dtToday;var Cal;var MonthName;var WeekDayName1;var WeekDayName2;var exDateTime;var selDate;var calSpanID="calBorder";var domStyle=null;var cnLeft="0";var cnTop="0";var xpos=0;var ypos=0;var calHeight=0;var CalWidth=208;var CellWidth=30;var TimeMode=24;var StartYear=2009;var EndYear=3;var CalPosOffsetX=8;var CalPosOffsetY=8;var SpanBorderColor="#FFF";var SpanBgColor="#FFFFFF";var MonthYearColor="#cc0033";var WeekHeadColor="#004796";var SundayColor="#FFFF33";var SaturdayColor="#FFFF33";var WeekDayColor="white";var FontColor="#004796";var TodayColor="#C0F64F";var SelDateColor="#8DD53C";var YrSelColor="#cc0033";var MthSelColor="#7C4199";var HoverColor="#004796";var DisableColor="#999966";var CalBgColor="";var WeekChar=2;var DateSeparator="-";var ShowLongMonth=true;var ShowMonthYear=true;var ThemeBg="";var PrecedeZero=true;var MondayFirstDay=true;var UseImageFiles=true;var DisableBeforeToday=false;var imageFilesPath="../ui/images/";var MonthName=["Janvier","F�vrier","Mars","Avril","Mai","Juin","Juillet","Ao�t","Septembre","Octobre","Novembre","D�cembre"];var WeekDayName1=["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"];var WeekDayName2=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];function Calendar(a,b){this.Date=a.getDate();this.Month=a.getMonth();this.Year=a.getFullYear();this.Hours=a.getHours();if(a.getMinutes()<10){this.Minutes="0"+a.getMinutes()}else{this.Minutes=a.getMinutes()}if(a.getSeconds()<10){this.Seconds="0"+a.getSeconds()}else{this.Seconds=a.getSeconds()}this.MyWindow=winCal;this.Ctrl=b;this.Format="ddMMyyyy";this.Separator=DateSeparator;this.ShowTime=false;this.Scroller="DROPDOWN";if(a.getHours()<12){this.AMorPM="AM"}else{this.AMorPM="PM"}this.ShowSeconds=false}Calendar.prototype.GetMonthIndex=function(b){for(var a=0;a<12;a+=1){if(MonthName[a].substring(0,3).toUpperCase()===b.toUpperCase()){return a}}};Calendar.prototype.IncYear=function(){if(Cal.Year<=dtToday.getFullYear()+EndYear){Cal.Year+=1}};Calendar.prototype.DecYear=function(){if(Cal.Year>StartYear){Cal.Year-=1}};Calendar.prototype.IncMonth=function(){if(Cal.Year<=dtToday.getFullYear()+EndYear){Cal.Month+=1;if(Cal.Month>=12){Cal.Month=0;Cal.IncYear()}}};Calendar.prototype.DecMonth=function(){if(Cal.Year>=StartYear){Cal.Month-=1;if(Cal.Month<0){Cal.Month=11;Cal.DecYear()}}};Calendar.prototype.SwitchMth=function(a){Cal.Month=parseInt(a,10)};Calendar.prototype.SwitchYear=function(a){Cal.Year=parseInt(a,10)};Calendar.prototype.SetHour=function(a){var c,b,e=new RegExp("^\\d\\d"),d=new RegExp("\\d");if(TimeMode===24){c=23;b=0}else{if(TimeMode===12){c=12;b=1}else{alert("TimeMode can only be 12 or 24")}}if((e.test(a)||d.test(a))&&(parseInt(a,10)>c)){a=b}else{if((e.test(a)||d.test(a))&&(parseInt(a,10)<b)){a=c}}if(d.test(a)){a="0"+a}if(e.test(a)&&(parseInt(a,10)<=c)&&(parseInt(a,10)>=b)){if((TimeMode===12)&&(Cal.AMorPM==="PM")){if(parseInt(a,10)===12){Cal.Hours=12}else{Cal.Hours=parseInt(a,10)+12}}else{if((TimeMode===12)&&(Cal.AMorPM==="AM")){if(a===12){a-=12}Cal.Hours=parseInt(a,10)}else{if(TimeMode===24){Cal.Hours=parseInt(a,10)}}}}};Calendar.prototype.SetMinute=function(a){var c=59,d=0,e=new RegExp("\\d"),b=new RegExp("^\\d{1}$"),g=new RegExp("^\\d{2}$"),f=0;if((g.test(a)||e.test(a))&&(parseInt(a,10)>c)){a=d}else{if((g.test(a)||e.test(a))&&(parseInt(a,10)<d)){a=c}}f=a+"";if(b.test(a)){f="0"+f}if((g.test(a)||e.test(a))&&(parseInt(a,10)<=59)&&(parseInt(a,10)>=0)){Cal.Minutes=f}};Calendar.prototype.SetSecond=function(b){var e=59,f=0,g=new RegExp("\\d"),d=new RegExp("^\\d{1}$"),c=new RegExp("^\\d{2}$"),a=0;if((c.test(b)||g.test(b))&&(parseInt(b,10)>e)){b=f}else{if((c.test(b)||g.test(b))&&(parseInt(b,10)<f)){b=e}}a=b+"";if(d.test(b)){a="0"+a}if((c.test(b)||g.test(b))&&(parseInt(b,10)<=59)&&(parseInt(b,10)>=0)){Cal.Seconds=a}};Calendar.prototype.SetAmPm=function(a){this.AMorPM=a;if(a==="PM"){this.Hours=parseInt(this.Hours,10)+12;if(this.Hours===24){this.Hours=12}}else{if(a==="AM"){this.Hours-=12}}};Calendar.prototype.getShowHour=function(){var a;if(TimeMode===12){if(parseInt(this.Hours,10)===0){this.AMorPM="AM";a=parseInt(this.Hours,10)+12}else{if(parseInt(this.Hours,10)===12){this.AMorPM="PM";a=12}else{if(this.Hours>12){this.AMorPM="PM";if((this.Hours-12)<10){a="0"+((parseInt(this.Hours,10))-12)}else{a=parseInt(this.Hours,10)-12}}else{this.AMorPM="AM";if(this.Hours<10){a="0"+parseInt(this.Hours,10)}else{a=this.Hours}}}}}else{if(TimeMode===24){if(this.Hours<10){a="0"+parseInt(this.Hours,10)}else{a=this.Hours}}}return a};Calendar.prototype.getShowAMorPM=function(){return this.AMorPM};Calendar.prototype.GetMonthName=function(b){var a=MonthName[this.Month];if(b){return a}else{return a.substr(0,3)}};Calendar.prototype.GetMonDays=function(){var a=[31,28,31,30,31,30,31,31,30,31,30,31];if(Cal.IsLeapYear()){a[1]=29}return a[this.Month]};Calendar.prototype.IsLeapYear=function(){if((this.Year%4)===0){if((this.Year%100===0)&&(this.Year%400)!==0){return false}else{return true}}else{return false}};Calendar.prototype.FormatDate=function(b){var a=this.Month+1;if(PrecedeZero===true){if((b<10)&&String(b).length===1){b="0"+b}if(a<10){a="0"+a}}switch(this.Format.toUpperCase()){case"DDMMYYYY":return(b+DateSeparator+a+DateSeparator+this.Year);case"DDMMMYYYY":return(b+DateSeparator+this.GetMonthName(false)+DateSeparator+this.Year);case"MMDDYYYY":return(a+DateSeparator+b+DateSeparator+this.Year);case"MMMDDYYYY":return(this.GetMonthName(false)+DateSeparator+b+DateSeparator+this.Year);case"YYYYMMDD":return(this.Year+DateSeparator+a+DateSeparator+b);case"YYMMDD":return(String(this.Year).substring(2,4)+DateSeparator+a+DateSeparator+b);case"YYMMMDD":return(String(this.Year).substring(2,4)+DateSeparator+this.GetMonthName(false)+DateSeparator+b);case"YYYYMMMDD":return(this.Year+DateSeparator+this.GetMonthName(false)+DateSeparator+b);default:return(b+DateSeparator+(this.Month+1)+DateSeparator+this.Year)}};function GenCell(d,b,g,c){var a,f,h,e;if(!d){a=""}else{a=d}if(g===undefined){g=CalBgColor}if(c!==undefined){h=c}else{h=true}if(Cal.ShowTime){e=" "+Cal.Hours+":"+Cal.Minutes;if(Cal.ShowSeconds){e+=":"+Cal.Seconds}if(TimeMode===12){e+=" "+Cal.AMorPM}}else{e=""}if(a!==""){if(h===true){if(Cal.ShowTime===true){f="<td id='c"+a+"' class='calTD' style='text-align:center;cursor:pointer;background-color:"+g+"' onmousedown='selectDate(this,"+a+");'>"+a+"</td>"}else{f="<td class='calTD' style='text-align:center;cursor:pointer;background-color:"+g+"' onmouseover='changeBorder(this, 0);' onmouseout=\"changeBorder(this, 1, '"+g+"');\" onClick=\"javascript:callback('"+Cal.Ctrl+"','"+Cal.FormatDate(a)+"');\">"+a+"</td>"}}else{f="<td style='text-align:center;background-color:"+g+"' class='calTD'>"+a+"</td>"}}else{f="<td style='text-align:center;background-color:"+g+"' class='calTD'>&nbsp;</td>"}return f}function RenderCssCal(A){if(typeof A==="undefined"||A!==true){A=false}var o,w,c="",l="",C="",h,u,r,n,z=0,q,a=[],m,x,t=false,d="35px",g,k,p,f,y,b,v,B,s;calHeight=0;C="<span style='cursor:auto;'>";o="<table style='background-color:"+CalBgColor+";width:200px;padding:0;margin:5px auto 5px auto'><tbody>";o+="<tr><td colspan='7'><table border='0' width='200px' cellpadding='0' cellspacing='0'><tr>";if(Cal.Scroller==="DROPDOWN"){o+="<td align='center'><select name='MonthSelector' onChange='javascript:Cal.SwitchMth(this.selectedIndex);RenderCssCal();'>";for(u=0;u<12;u+=1){if(u===Cal.Month){n="Selected"}else{n=""}o+="<option "+n+" value="+u+">"+MonthName[u]+"</option>"}o+="</select></td>";o+="<td align='center'><select name='YearSelector' size='1' onChange='javascript:Cal.SwitchYear(this.value);RenderCssCal();'>";for(u=StartYear;u<=(dtToday.getFullYear()+EndYear);u+=1){if(u===Cal.Year){n='selected="selected"'}else{n=""}o+="<option "+n+" value="+u+">"+u+"</option>\n"}o+="</select></td>\n";calHeight+=30}else{if(Cal.Scroller==="ARROW"){if(UseImageFiles){o+="<td><img onmousedown='javascript:Cal.DecYear();RenderCssCal();' src='"+imageFilesPath+"cal_fastreverse.gif' width='13px' height='9' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'></td>\n";o+="<td><img onmousedown='javascript:Cal.DecMonth();RenderCssCal();' src='"+imageFilesPath+"cal_reverse.gif' width='13px' height='9' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'></td>\n";o+="<td width='70%' class='calR' style='color:"+YrSelColor+"'>"+Cal.GetMonthName(ShowLongMonth)+" "+Cal.Year+"</td>";o+="<td><img onmousedown='javascript:Cal.IncMonth();RenderCssCal();' src='"+imageFilesPath+"cal_forward.gif' width='13px' height='9' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'></td>\n";o+="<td><img onmousedown='javascript:Cal.IncYear();RenderCssCal();' src='"+imageFilesPath+"cal_fastforward.gif' width='13px' height='9' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'></td>\n";calHeight+=22}else{o+="<td><span id='dec_year' title='reverse year' onmousedown='javascript:Cal.DecYear();RenderCssCal();' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white; color:"+YrSelColor+"'>-</span></td>";o+="<td><span id='dec_month' title='reverse month' onmousedown='javascript:Cal.DecMonth();RenderCssCal();' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'>&lt;</span></td>\n";o+="<td width='70%' class='calR' style='color:"+YrSelColor+"'>"+Cal.GetMonthName(ShowLongMonth)+" "+Cal.Year+"</td>\n";o+="<td><span id='inc_month' title='forward month' onmousedown='javascript:Cal.IncMonth();RenderCssCal();' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'>&gt;</span></td>\n";o+="<td><span id='inc_year' title='forward year' onmousedown='javascript:Cal.IncYear();RenderCssCal();'  onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white; color:"+YrSelColor+"'>+</span></td>\n";calHeight+=22}}}o+="</tr></table></td></tr>";if(ShowMonthYear&&Cal.Scroller==="DROPDOWN"){o+="<tr><td colspan='7' class='calR' style='color:"+MonthYearColor+"'>"+Cal.GetMonthName(ShowLongMonth)+" "+Cal.Year+"</td></tr>";calHeight+=19}o+="<tr><td colspan=\"7\"><table style='border-spacing:1px;border-collapse:separate;'><tr>";if(MondayFirstDay===true){a=WeekDayName2}else{a=WeekDayName1}for(u=0;u<7;u+=1){o+="<td style='background-color:"+WeekHeadColor+";width:"+CellWidth+"px;color:#FFFFFF' class='calTD'>"+a[u].substr(0,WeekChar)+"</td>"}calHeight+=19;o+="</tr>";h=new Date(Cal.Year,Cal.Month);h.setDate(1);q=h.getDay();if(MondayFirstDay===true){q-=1;if(q===-1){q=6}}w="<tr>";calHeight+=19;for(u=0;u<q;u+=1){w=w+GenCell();z=z+1}for(r=1;r<=Cal.GetMonDays();r+=1){if((z%7===0)&&(r>1)){w=w+"<tr>"}z=z+1;if(DisableBeforeToday===true&&((r<dtToday.getDate())&&(Cal.Month===dtToday.getMonth())&&(Cal.Year===dtToday.getFullYear())||(Cal.Month<dtToday.getMonth())&&(Cal.Year===dtToday.getFullYear())||(Cal.Year<dtToday.getFullYear()))){m=GenCell(r,false,DisableColor,false)}else{if(Cal.Year>(dtToday.getFullYear()+EndYear)){m=GenCell(r,false,DisableColor,false)}else{if((r===dtToday.getDate())&&(Cal.Month===dtToday.getMonth())&&(Cal.Year===dtToday.getFullYear())){m=GenCell(r,true,TodayColor)}else{if((r===selDate.getDate())&&(Cal.Month===selDate.getMonth())&&(Cal.Year===selDate.getFullYear())){m=GenCell(r,true,SelDateColor)}else{if(MondayFirstDay===true){if(z%7===0){m=GenCell(r,false,SundayColor)}else{if((z+1)%7===0){m=GenCell(r,false,SaturdayColor)}else{m=GenCell(r,null,WeekDayColor)}}}else{if(z%7===0){m=GenCell(r,false,SaturdayColor)}else{if((z+6)%7===0){m=GenCell(r,false,SundayColor)}else{m=GenCell(r,null,WeekDayColor)}}}}}}}w=w+m;if((z%7===0)&&(r<Cal.GetMonDays())){w=w+"</tr>";calHeight+=19}}if(z%7!==0){while(z%7!==0){w=w+GenCell();z=z+1}}w=w+"</table></td></tr>";if(Cal.ShowTime===true){x=Cal.getShowHour();if(Cal.ShowSeconds===false&&TimeMode===24){t=true;d="10px"}c="<tr><td colspan='7' style=\"text-align:center;\"><table border='0' width='199px' cellpadding='0' cellspacing='0'><tbody><tr><td height='5px' width='"+d+"'>&nbsp;</td>";if(t&&UseImageFiles){c+="<td style='vertical-align:middle;'><table cellspacing='0' cellpadding='0' style='line-height:0pt;width:100%;'><tr><td style='text-align:center;'><img onclick='nextStep(\"Hour\", \"plus\");' onmousedown='startSpin(\"Hour\", \"plus\");' onmouseup='stopSpin();' src='"+imageFilesPath+"cal_plus.gif' width='13px' height='9px' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'></td></tr><tr><td style='text-align:center;'><img onclick='nextStep(\"Hour\", \"minus\");' onmousedown='startSpin(\"Hour\", \"minus\");' onmouseup='stopSpin();' src='"+imageFilesPath+"cal_minus.gif' width='13px' height='9px' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'></td></tr></table></td>\n"}c+="<td width='22px'><input type='text' name='hour' maxlength=2 size=1 style=\"WIDTH:22px\" value="+x+' onkeyup="javascript:Cal.SetHour(this.value)">';c+="</td><td style='font-weight:bold;text-align:center;'>:</td><td width='22px'>";c+="<input type='text' name='minute' maxlength=2 size=1 style=\"WIDTH: 22px\" value="+Cal.Minutes+' onkeyup="javascript:Cal.SetMinute(this.value)">';if(Cal.ShowSeconds){c+="</td><td style='font-weight:bold;'>:</td><td width='22px'>";c+="<input type='text' name='second' maxlength=2 size=1 style=\"WIDTH: 22px\" value="+Cal.Seconds+' onkeyup="javascript:Cal.SetSecond(parseInt(this.value,10))">'}if(TimeMode===12){g=(Cal.AMorPM==="AM")?"Selected":"";k=(Cal.AMorPM==="PM")?"Selected":"";c+="</td><td>";c+='<select name="ampm" onChange="javascript:Cal.SetAmPm(this.options[this.selectedIndex].value);">\n';c+="<option "+g+' value="AM">AM</option>';c+="<option "+k+' value="PM">PM<option>';c+="</select>"}if(t&&UseImageFiles){c+="</td>\n<td style='vertical-align:middle;'><table cellspacing='0' cellpadding='0' style='line-height:0pt;width:100%'><tr><td style='text-align:center;'><img onclick='nextStep(\"Minute\", \"plus\");' onmousedown='startSpin(\"Minute\", \"plus\");' onmouseup='stopSpin();' src='"+imageFilesPath+"cal_plus.gif' width='13px' height='9px' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'></td></tr><tr><td style='text-align:center;'><img onmousedown='startSpin(\"Minute\", \"minus\");' onmouseup='stopSpin();' onclick='nextStep(\"Minute\",\"minus\");' src='"+imageFilesPath+"cal_minus.gif' width='13px' height='9px' onmouseover='changeBorder(this, 0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'></td></tr></table>"}c+="</td>\n<td align='right' valign='bottom' width='"+d+"px'></td></tr>";c+="<tr><td colspan='7' style=\"text-align:center;\"><input style='width:60px;font-size:12px;' onClick='javascript:closewin(\""+Cal.Ctrl+'");\'  type="button" value="OK">&nbsp;<input style=\'width:60px;font-size:12px;\' onClick=\'javascript: winCal.style.visibility = "hidden"\' type="button" value="Cancel"></td></tr>'}else{c+="\n<tr>\n<td colspan='7' style=\"text-align:right;\">";if(UseImageFiles){l+="<img onmousedown='javascript:closewin(\""+Cal.Ctrl+"\"); stopSpin();' src='"+imageFilesPath+"cal_close.gif' width='16px' height='14px' onmouseover='changeBorder(this,0)' onmouseout='changeBorder(this, 1)' style='border:1px solid white'></td>"}else{l+="<span id='close_cal' title='close'onmousedown='javascript:closewin(\""+Cal.Ctrl+"\");stopSpin();' onmouseover='changeBorder(this, 0)'onmouseout='changeBorder(this, 1)' style='border:1px solid white; font-family: Arial;font-size: 10pt;'>x</span></td>"}l+="</tr>"}l+="</tbody></table></td></tr>";calHeight+=31;l+="</tbody></table>\n</span>";p="function callback(id, datum) {";p+=" var CalId = document.getElementById(id);if (datum=== 'undefined') { var d = new Date(); datum = d.getDate() + '/' +(d.getMonth()+1) + '/' + d.getFullYear(); } window.calDatum=datum;CalId.value=datum;";p+=" if(Cal.ShowTime){";p+=" CalId.value+=' '+Cal.getShowHour()+':'+Cal.Minutes;";p+=" if (Cal.ShowSeconds)  CalId.value+=':'+Cal.Seconds;";p+=" if (TimeMode === 12)  CalId.value+=''+Cal.getShowAMorPM();";p+="}if(CalId.onchange===true) CalId.onchange();winCal.style.visibility='hidden';";p+="winCal.style.visibility='hidden';updateDureePlanning()}";if(ypos>calHeight){ypos=ypos-calHeight}if(!winCal){f=document.getElementsByTagName("head")[0];y=document.createElement("script");y.type="text/javascript";y.language="javascript";y.text=p;f.appendChild(y);b=".calTD {font-family: verdana; font-size: 12px; text-align: center; border:0; }\n";b+=".calR {font-family: verdana; font-size: 12px; text-align: center; font-weight: bold;}";v=document.createElement("style");v.type="text/css";v.rel="stylesheet";if(v.styleSheet){v.styleSheet.cssText=b}else{B=document.createTextNode(b);v.appendChild(B)}f.appendChild(v);s=document.createElement("span");s.id=calSpanID;s.style.position="absolute";s.style.left=(xpos+CalPosOffsetX)+"px";s.style.top=(ypos-CalPosOffsetY)+"px";s.style.width=CalWidth+"px";s.style.border="solid 1pt "+SpanBorderColor;s.style.padding="0";s.style.cursor="move";s.style.backgroundColor=SpanBgColor;s.style.zIndex=100;document.body.appendChild(s);winCal=document.getElementById(calSpanID)}else{winCal.style.visibility="visible";winCal.style.Height=calHeight;if(A===true){winCal.style.left=(xpos+CalPosOffsetX)+"px";winCal.style.top=(ypos-CalPosOffsetY)+"px"}}winCal.innerHTML=C+o+w+c+l;return true}function NewCssCal(p,k,m,n,u,j){dtToday=new Date();Cal=new Calendar(dtToday);if(n!==undefined){if(n){Cal.ShowTime=true}else{Cal.ShowTime=false}if(u){u=parseInt(u,10)}if(u===12||u===24){TimeMode=u}else{TimeMode=24}if(j!==undefined){if(j){Cal.ShowSeconds=true}else{Cal.ShowSeconds=false}}else{Cal.ShowSeconds=false}}if(p!==undefined){Cal.Ctrl=p}if(k!==undefined){Cal.Format=k.toUpperCase()}else{Cal.Format="DDMMYYYY"}if(m!==undefined){if(m.toUpperCase()==="ARROW"){Cal.Scroller="ARROW"}else{Cal.Scroller="DROPDOWN"}}exDateTime=document.getElementById(p).value;if(exDateTime){var t=exDateTime.indexOf(DateSeparator,0),s=exDateTime.indexOf(DateSeparator,parseInt(t,10)+1),i,f,d,h,q,o,b,l,r,e,c,g=parseInt(Cal.Format.toUpperCase().lastIndexOf("M"),10)-parseInt(Cal.Format.toUpperCase().indexOf("M"),10)-1,a="";if(Cal.Format.toUpperCase()==="DDMMYYYY"||Cal.Format.toUpperCase()==="DDMMMYYYY"){if(DateSeparator===""){d=exDateTime.substring(2,4+g);h=exDateTime.substring(0,2);q=exDateTime.substring(4+g,8+g)}else{if(exDateTime.indexOf("D*")!==-1){d=exDateTime.substring(8,11);h=exDateTime.substring(0,2);q="20"+exDateTime.substring(11,13)}else{d=exDateTime.substring(t+1,s);h=exDateTime.substring(0,t);q=exDateTime.substring(s+1,s+5)}}}else{if(Cal.Format.toUpperCase()==="MMDDYYYY"||Cal.Format.toUpperCase()==="MMMDDYYYY"){if(DateSeparator===""){d=exDateTime.substring(0,2+g);h=exDateTime.substring(2+g,4+g);q=exDateTime.substring(4+g,8+g)}else{d=exDateTime.substring(0,t);h=exDateTime.substring(t+1,s);q=exDateTime.substring(s+1,s+5)}}else{if(Cal.Format.toUpperCase()==="YYYYMMDD"||Cal.Format.toUpperCase()==="YYYYMMMDD"){if(DateSeparator===""){d=exDateTime.substring(4,6+g);h=exDateTime.substring(6+g,8+g);q=exDateTime.substring(0,4)}else{d=exDateTime.substring(t+1,s);h=exDateTime.substring(s+1,s+3);q=exDateTime.substring(0,t)}}else{if(Cal.Format.toUpperCase()==="YYMMDD"||Cal.Format.toUpperCase()==="YYMMMDD"){if(DateSeparator===""){d=exDateTime.substring(2,4+g);h=exDateTime.substring(4+g,6+g);q=exDateTime.substring(0,2)}else{d=exDateTime.substring(t+1,s);h=exDateTime.substring(s+1,s+3);q=exDateTime.substring(0,t)}}}}}if(isNaN(d)){o=Cal.GetMonthIndex(d)}else{o=parseInt(d,10)-1}if((parseInt(o,10)>=0)&&(parseInt(o,10)<12)){Cal.Month=o}b=/^\d{4}$/;if(b.test(q)){if((parseInt(q,10)>=StartYear)&&(parseInt(q,10)<=(dtToday.getFullYear()+EndYear))){Cal.Year=parseInt(q,10)}}if((parseInt(h,10)<=Cal.GetMonDays())&&(parseInt(h,10)>=1)){Cal.Date=h}if(Cal.ShowTime===true){if(TimeMode===12){a=exDateTime.substring(exDateTime.length-2,exDateTime.length);Cal.AMorPM=a}i=exDateTime.indexOf(":",0);f=exDateTime.indexOf(":",(parseInt(i,10)+1));if(i>0){l=exDateTime.substring(i,i-2);Cal.SetHour(l);r=exDateTime.substring(i+1,i+3);Cal.SetMinute(r);e=exDateTime.substring(f+1,f+3);Cal.SetSecond(e)}else{if(exDateTime.indexOf("D*")!==-1){l=exDateTime.substring(2,4);Cal.SetHour(l);r=exDateTime.substring(4,6);Cal.SetMinute(r)}}}}selDate=new Date(Cal.Year,Cal.Month,Cal.Date);RenderCssCal(true)}function closewin(d){if(Cal.ShowTime===true){var b=dtToday.getFullYear()+EndYear;var a=(Cal.Date<dtToday.getDate())&&(Cal.Month===dtToday.getMonth())&&(Cal.Year===dtToday.getFullYear())||(Cal.Month<dtToday.getMonth())&&(Cal.Year===dtToday.getFullYear())||(Cal.Year<dtToday.getFullYear());if((Cal.Year<=b)&&(Cal.Year>=StartYear)&&(Cal.Month===selDate.getMonth())&&(Cal.Year===selDate.getFullYear())){if(DisableBeforeToday===true){if(a===false){callback(d,Cal.FormatDate(Cal.Date))}}else{callback(d,Cal.FormatDate(Cal.Date))}}}var c=document.getElementById(d);c.focus();winCal.style.visibility="hidden"}function changeBorder(b,a,c){if(a===0){b.style.background=HoverColor;b.style.borderColor="black";b.style.cursor="pointer"}else{if(c){b.style.background=c}else{b.style.background="white"}b.style.borderColor="white";b.style.cursor="auto"}}function selectDate(b,a){Cal.Date=a;selDate=new Date(Cal.Year,Cal.Month,Cal.Date);b.style.background=SelDateColor;RenderCssCal()}function pickIt(c){var f,d,e,a;if(document.addEventListener){f=c.target.id;if(f.indexOf(calSpanID)!==-1){d=document.getElementById(f);cnLeft=c.pageX;cnTop=c.pageY;if(d.offsetLeft){cnLeft=(cnLeft-d.offsetLeft);cnTop=(cnTop-d.offsetTop)}}xpos=(c.pageX);ypos=(c.pageY)}else{f=event.srcElement.id;cnLeft=event.offsetX;cnTop=(event.offsetY);e=document.documentElement;a=document.body;xpos=event.clientX+(e.scrollLeft||a.scrollLeft)-(e.clientLeft||0);ypos=event.clientY+(e.scrollTop||a.scrollTop)-(e.clientTop||0)}if(f.indexOf(calSpanID)!==-1){domStyle=document.getElementById(f).style}if(domStyle){domStyle.zIndex=100;return false}else{domStyle=null;return}}function dragIt(a){if(domStyle){if(document.addEventListener){domStyle.left=(event.clientX-cnLeft+document.body.scrollLeft)+"px";domStyle.top=(event.clientY-cnTop+document.body.scrollTop)+"px"}else{domStyle.left=(a.clientX-cnLeft+document.body.scrollLeft)+"px";domStyle.top=(a.clientY-cnTop+document.body.scrollTop)+"px"}}}function nextStep(a,b){if(a==="Hour"){if(b==="plus"){Cal.SetHour(Cal.Hours+1);RenderCssCal()}else{if(b==="minus"){Cal.SetHour(Cal.Hours-1);RenderCssCal()}}}else{if(a==="Minute"){if(b==="plus"){Cal.SetMinute(parseInt(Cal.Minutes,10)+1);RenderCssCal()}else{if(b==="minus"){Cal.SetMinute(parseInt(Cal.Minutes,10)-1);RenderCssCal()}}}}}function startSpin(a,b){document.thisLoop=setInterval(function(){nextStep(a,b)},125)}function stopSpin(){clearInterval(document.thisLoop)}function dropIt(){stopSpin();if(domStyle){domStyle=null}}document.onmousedown=pickIt;document.onmousemove=dragIt;document.onmouseup=dropIt;