document.addEventListener('DOMContentLoaded', () => {
var dropinst = M.Dropdown.init(document.getElementById('emioptbtn'), {'constrainWidth':false,'coverTrigger':false,'alignment':'right'});
loadccode();progstepch();
});
document.addEventListener("keyup", function(event) 
{
	event.preventDefault();
	var btnname = document.getElementById("chentbtn").value;
	if (event.key !== undefined) {
		if(event.key=='Enter')
		{
			document.getElementById(btnname).click();
		}
	} else if (event.code !== undefined) {
		if(event.code=='Enter')
		{
			document.getElementById(btnname).click();
		}
	} else if (event.keyCode !== undefined) {
		if(event.keyCode==13)
		{
			document.getElementById(btnname).click();
		}
	}
});
function getgeolc()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var res = this.responseText;
			resarr = res.split("|");
			ccname = resarr[0];
			ccode = resarr[1];
			document.getElementById("ccode").value=ccode;
			document.getElementById("ccodep").innerHTML=ccname+" (+"+ccode+")";
		}
	}
	xmlhttp.open("GET", "/user/dynamic/getgeolc", true);
	xmlhttp.send();
}
function loadccode()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var res = this.responseText;
			document.getElementById("ccodelist").innerHTML=res;
		}
	}
	xmlhttp.open("GET", "/user/dynamic/loadccode", true); 
	xmlhttp.send(); 
}
function setxccode(ccode,iso)
{
	document.getElementById("ccode").value=ccode;
	document.getElementById("ccodep").innerHTML=iso+" (+"+ccode+")";
}
function shpaydiv(n)
{
	if(document.getElementById("paydiv1"))
	{
		document.getElementById("paydiv1").style.display='none';
	}
	if(document.getElementById("paydiv2"))
	{
		document.getElementById("paydiv2").style.display='none';
	}
	if(document.getElementById("paydiv3"))
	{
		document.getElementById("paydiv3").style.display='none';
	}
	document.getElementById("paydiv"+n).style.display='block';
}
function animcoup(x)
{
	e=document.getElementById("mod_fees");
	e.style.fontSize ='0rem';
	setTimeout(() => {
		e.style.fontSize ='1rem';
	}, 500);
	setTimeout(() => {
		if(x!='normal')
		{
			e.innerHTML = x;
			setTimeout(() => {
				var couponmod = M.Modal.getInstance(document.getElementById("couponmod"));
				couponmod.close();
			}, 2000);
		}
		e.style.fontSize ='2rem';
	}, 500);      
}
function hfshother(y)
{
	if(y=='y')
	{
		document.getElementById('hfshdiv').style.display='block';
	} else
	{
		document.getElementById('hfshdiv').style.display='none';
	}
}
function beforesubmit(formval)
{
	document.getElementById(formval+"ld").innerHTML='Submitting...';
	M.Toast.dismissAll();
}
function aftersubmit(res,formval)
{
	if(formval=='crtform')
	{
		progstepval(4);
	} else
	{
		document.getElementById(formval+"ld").innerHTML='';
		document.getElementById("chentbtn").value="";
		if(res=="OK")
		{
			progstepval(3);
		} else if(res!="")
		{
			M.toast({html: res, classes: 'rounded pink darken-4 semi-bold'});
		} else
		{
			M.toast({html: 'Submission failed. Please check the required fields and network connectivity', classes: 'rounded pink darken-4 semi-bold'});
		}
	}
}
function missingform(elem,formval)
{
	document.getElementById(formval+"ld").innerHTML='';
	var name = elem.getAttribute('data-title');
	M.toast({html: name+' is required', classes: 'rounded pink darken-4 semi-bold'});
	return "no";
}
function progstepval(x)
{
	document.getElementById('progstep').value=x;
	progstepch();
}
function progstepch()
{
	var progstep=document.getElementById('progstep').value;
	document.getElementById("showamt").style.display="none";
	document.getElementById("stx1").style.display="none";
	document.getElementById("stx2").style.display="none";
	document.getElementById("stx3").style.display="none";
	document.getElementById("stx4").style.display="none";
	if(progstep==1)
	{
		document.getElementById("stx1").style.display="block";
	} else if(progstep==2)
	{
		document.getElementById("showamt").style.display="block";
		document.getElementById("stx2").style.display="block";
		document.getElementById("chentbtn").value="preformbtn";
	} else if(progstep==3)
	{
		document.getElementById("showamt").style.display="block";
		document.getElementById("stx3").style.display="block";
		document.getElementById("chentbtn").value="";
	} else if(progstep==4)
	{
		document.getElementById("stx4").style.display="block";
		document.getElementById("chentbtn").value="";
		var progst4=document.getElementById('progst4').value;
		var ulink=document.getElementById('ulink').value;
		if(progst4==1)
		{
			document.getElementById("cxenrlrn").innerHTML='<p class="line-height-2 grey-text text-darken-2 padding-top-10 padding-bottom-10">You have successfully registered for the course.</p><p class="bold"><a href="/course/'+ulink+'/learn" class="btn pink darken-4 waves-effect waves-light semi-bold" style="width:200px; max-width:100%">Learn Course</a></p><p class="font-size-1-6 cyan-text text-darken-4 bold">Course Enrolled!</p><p class="center"><img src="/payment/image/paid-successful.gif" style="width:300px; max-width:100%"></p>';
		} else if(progst4==2)
		{
			document.getElementById("cxenrlrn").innerHTML='<p class="line-height-2 grey-text text-darken-2 padding-top-10 padding-bottom-10">You have successfully registered for the course.</p><p class="bold"><a href="/course/'+ulink+'/learn" class="btn purple darken-4 waves-effect waves-light semi-bold" style="width:200px; max-width:100%">Learn Course</a></p><p class="font-size-1-6 cyan-text text-darken-4 bold">Course Enrolled!</p><p class="center"><img src="/payment/image/paid-successful.gif" style="width:300px; max-width:100%"></p><p class="line-height-2 grey-text text-darken-2 padding-top-20">To pay the remaining fees click here.</p><p class=""><a href="" onclick="payxagain(); return false;" class="btn pink darken-4 waves-effect waves-light semi-bold" style="width:200px; max-width:100%">Remaining Payment</a></p><p class="line-height-2 grey-text text-darken-2 padding-top-10">OR</p>';
		} else if(progst4==4)
		{
			document.getElementById("cxenrlrn").innerHTML='<p class="font-size-1-6 red-text text-darken-4 bold">Account Suspended!</p><p class="center"><img src="/course/image/account-suspended.jpg" style="width:300px; max-width:100%"></p><p class="line-height-2 grey-text text-darken-2 padding-top-10 padding-bottom-10">Please contact our support team for more details on why you are banned.</p><p class=""><a href="/contact/" class="btn red darken-4 waves-effect waves-light semi-bold" style="width:200px; max-width:100%">Contact Us</a></p><br>';
		} else if(progst4==6)
		{
			document.getElementById("cxenrlrn").innerHTML='<p class="font-size-1-6 cyan-text text-darken-4 bold">Pre-Registered!</p><p class="font-size-1-2 line-height-2-5 blue-grey-text text-darken-4 padding-top-10 padding-bottom-10">You will be notified once the next batch has been started.</p><p class="center"><img src="/payment/image/paid-successful.gif" style="width:300px; max-width:100%"></p>';
		}
	}
}
function couponch()
{
	var ytcoupon=document.getElementById("ytcoupon").value;
	if(ytcoupon.length<1){document.getElementById("ytcoupon").value='MEGAOFF';}
	var course_id=document.getElementById("course_id").value;
	var selemi=document.getElementById("selemi").value;
	document.getElementById("couperr").innerHTML = 'Loading...';
	var formData = new FormData();
	formData.append("ytcoupon", ytcoupon);
	formData.append("course_id", course_id);
	formData.append("selemi", selemi);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var res = this.responseText;
			resobj=JSON.parse(res);
			if(resobj.status=='INVALID')
			{
				M.toast({html: 'Invalid coupon code', classes: 'rounded pink darken-4 semi-bold'});
				document.getElementById("couperr").innerHTML = '<p class="pink-text text-darken-2">Invalid coupon code. It may not suit for your course and your account</p>';
			} else if(resobj.status=='EXPIRED')
			{
				M.toast({html: 'Expired coupon code', classes: 'rounded pink darken-4 semi-bold'});
				document.getElementById("couperr").innerHTML = '<p class="pink-text text-darken-2">This coupon code is expired.</p>';
			} else if(resobj.status=='HIGH')
			{
				M.toast({html: 'Coupon with a lower value', classes: 'rounded pink darken-4 semi-bold'});
				document.getElementById("couperr").innerHTML = '<p class="pink-text text-darken-2">You have applied a lower valued discount, but we have applied the best one for you.</p>';
			} else if(resobj.status=='CHANGE')
			{
				M.toast({html: 'Coupon code applied', classes: 'rounded teal darken-4 semi-bold'});
				document.getElementById("couperr").innerHTML = '<p class="purple-text text-darken-4 semi-bold">'+resobj.coupval+'% OFFER</p>';
				if(document.getElementById("scoupval1"))
				{
					document.getElementById("scoupval1").innerHTML = resobj.coupval;
				}
				if(document.getElementById("scoupval2"))
				{
					document.getElementById("scoupval2").innerHTML = resobj.coupval;
				}
				if(document.getElementById("m_fees"))
				{
					document.getElementById("m_fees").innerHTML=resobj.calcfees;
				}
				document.getElementById("fullfees").innerHTML = resobj.calcfees;
				document.getElementById("coupval").value = resobj.coupval;
				if(document.getElementById("emifees"))
				{
					document.getElementById("emifees").innerHTML=resobj.emifees;
				}
				if(document.getElementById("emimin"))
				{
					document.getElementById("emimin").innerHTML=resobj.emimin;
				}
				if(document.getElementById("emiopt"))
				{
					document.getElementById("emiopt").innerHTML=resobj.emilist;
				}
				animcoup(resobj.calcfees);
			}
		}
	}
	xmlhttp.open("post", "/course/dynamic/regcouponch", true); 
	xmlhttp.send(formData); 
}
function selemi(emi,emifees)
{
	document.getElementById('emifees').innerHTML = emifees;
	document.getElementById('sltdemi').style.display = 'block';
	document.getElementById('selemi').value=emi;
	var course_id=document.getElementById("course_id").value;
	var formData = new FormData();
	formData.append("course_id", course_id);
	formData.append("emi", emi);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var res = this.responseText;
			resobj=JSON.parse(res);
			if(resobj.emifees && document.getElementById("emifees"))
			{
				document.getElementById("emifees").innerHTML=resobj.emifees;
			}
		}
	}
	xmlhttp.open("post", "/course/dynamic/regcouponch", true); 
	xmlhttp.send(formData); 
}
function crsform(loc,formval)
{
	allow=beforesubmit(formval);
	if(allow=="no"){ return; }
	var elements = document.getElementsByClassName(formval);
	var formData = new FormData(); 
	for(var i=0; i<elements.length; i++)
	{
		if(elements[i].value=='' && elements[i].hasAttribute('required'))
		{
			allow=missingform(elements[i],formval);
			if(allow=="no"){ return; }
		}
		formData.append(elements[i].name, elements[i].value);
	}
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
			var res = this.responseText;
			if(formval=='formval')
			{
				window.location.reload();
			}
			aftersubmit(res,formval);
		}
	}
	xmlhttp.open("POST", loc, true); 
	xmlhttp.send(formData);
}
function chterms(e)
{
	if(e.checked)
	{
		document.getElementById('terms').value="on";
	} else
	{
		document.getElementById('terms').value="";
	}
}
function emipayfunc()
{
	var selemi=document.getElementById('selemi').value;
	if(selemi.length>0)
	{
	var ulink=document.getElementById('ulink').value;
	var link='/course/checkout?ulink='+ulink+'&mode=2';
	window.location.href = link;
	} else
	{
	M.toast({html: 'Pick an EMI plan first', classes: 'pink darken-4 semi-bold'});
	}
}
function aftermei()
{
	progstepval(3);
	shpaydiv(2);
	document.getElementById('showamt').style.display='none';
	document.getElementById('payswt1').style.display='none';
	document.getElementById('payswt2').style.display='none';
	document.getElementById('payswt3').style.display='none';
	document.getElementById('payswthd').innerHTML='Pay Next EMI<br><br>';
	document.getElementById('payswtbk').style.display='none';
	document.getElementById('emicoupbtn').style.display='none';
}
function regnext()
{
	var ccode=document.getElementById('ccode').value;
	var phone=document.getElementById('phone').value;
	var ulink=document.getElementById('ulink').value;
	var logred=encodeURIComponent('/course/'+ulink+'/register');
		window.location.href = '/user/?ccode='+ccode+'&phone='+phone+'&logred='+logred;
}
function payxagain()
{
	document.getElementById('progstep').value=3;
	progstepch();
}
function pre_register(x)
{
	if(x=='yes')
	{
		var formData = new FormData();
		var course_id=document.getElementById("course_id").value;
		formData.append("course_id", course_id);
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function()
		{
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
			{
				var res = this.responseText;
				if(res=='OK')
				{
					document.getElementById('progstep').value=4;
					document.getElementById('progst4').value=6;
					progstepch();
				} else
				{
					M.toast({html: 'Something went wrong. Please contact support team', classes: 'pink darken-4 semi-bold'});
				}
			}
		}
		xmlhttp.open("POST", '/course/dynamic/pre-register', true); 
		xmlhttp.send(formData);
	} else
	{
		window.location.href = '/course/';
	}
}