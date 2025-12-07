document.addEventListener('DOMContentLoaded', () => {
	var getloc=document.getElementById("getloc").value;
	if(getloc=='yes')
	{
		getgeolc();
	}
	loadccode();
	var ccode=document.getElementById('ccode').value;
    var phone=document.getElementById('phone').value;
	if(phone.length>0)
	{
		document.getElementById('nextbtn').click();
	}
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
function ssdiv(x)
{
	document.getElementById("ssdiv1").style.display="none";
	document.getElementById("ssdiv2").style.display="none";
	document.getElementById("ssdiv3").style.display="none";
	document.getElementById("ssdiv4").style.display="none";
	document.getElementById("ssdiv"+x).style.display="block";
}
function ssnext()
{
	M.Toast.dismissAll();
	document.getElementById("load_sp").innerHTML = "Loading...";
	var ccode = document.getElementById("ccode").value;
	var phone = document.getElementById("phone").value;
	var xv=document.getElementById("otpxv").value;
	if(phone.length<1)
	{
		document.getElementById("load_sp").innerHTML="";
		M.toast({html: 'Mobile number is empty', classes: 'rounded pink darken-4 semi-bold'});
	} else if(ccode.length<1)
	{
		document.getElementById("load_sp").innerHTML="";
		M.toast({html: 'Country code is empty', classes: 'rounded pink darken-4 semi-bold'});
	} else
	{
		var formData = new FormData();
		formData.append("ccode", ccode);
		formData.append("phone", phone);
		formData.append("xv", xv);
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				res = this.responseText;
				document.getElementById("load_sp").innerHTML="";
				if(res=="exist")
				{
					ssdiv(3);
					document.getElementById("lgtit").innerHTML='Account Login';
					document.getElementById("passhead").innerHTML="Enter your password";
					document.getElementById("chentbtn").value="loginbtn";
					document.getElementById("password").focus();
				} else if(res=="noexist")
				{
					ssdiv(4);
					var tempredir='/user/?logred='+encodeURIComponent(logred);
					document.getElementById("ssdiv4").innerHTML = '<div class="center semi-bold"><p class="teal-text text-darken-2 bold">Phone number does not exists. Create new account</p><p class="purple-text text-darken-4">Redirecting...</p><p>OR</p><p><a href="'+tempredir+'" class="btn-small purple darken-2">Click here</a></p></div>';
					setTimeout(function(){ window.location.href = tempredir; }, 2000);
				} else
				{
					if(xv!='reset')
					{
						document.getElementById("lgtit").innerHTML='Create Account';
						document.getElementById("passhead").innerHTML="Create New Password";
						document.getElementById("sregbtn").style.display="block";
						document.getElementById("slgbtn").style.display="none";
					}
					resarr = res.split("%|%");
					secresp = resarr[0];
					okresp = resarr[1];
					if(okresp=="ok")
					{
						ssdiv(2);
						document.getElementById("chentbtn").value="verifotpbtn";
						M.toast({html: 'OTP has been sent', classes: 'rounded teal darken-4 semi-bold'});
						document.getElementById("resend_sp").innerHTML="<p class='center'>Resend in <span class='bold' id='cntdwn'></span></p>";
						document.getElementById("cntsecs").value=secresp;
						myVar = setInterval(myTimer, 1000);
						document.getElementById("otp").focus();
						function myTimer()
						{
							t=Number(document.getElementById("cntsecs").value);
							var m = Math.floor(t / 60);
							var s = t % 60;
							m = m < 10 ? '0' + m : m;
							s = s < 10 ? '0' + s : s;
							document.getElementById('cntdwn').innerHTML = m + ':' + s;
							t -= 1;
							document.getElementById("cntsecs").value=t;
							if(t<=0)
							{
								clearInterval(myVar);
								document.getElementById("resend_sp").innerHTML="<p class='center'><button class='btn-flat semi-bold pink-text text-darken-4' onclick='ssnext();'>Resend OTP</button></p>";
							}
						}
					}else
					{
						M.toast({html: 'OTP failed. Check the mobile number or try again', classes: 'rounded pink darken-4 semi-bold'});
						document.getElementById("resend_sp").innerHTML="<p class='center'><button class='btn semi-bold grey-text text-darken-4 grey lighten-2' onclick='ssnext();' id='resotpbtn'>Resend</button></p>";
						document.getElementById("chentbtn").value="resotpbtn";
					}
				}
			}
		};
		xmlhttp.open("POST", "/user/dynamic/ssnext", true); 
		xmlhttp.send(formData); 
	}
}
function verifotp() 
{
	document.getElementById("verif_sp").innerHTML = "Verifying...";
	var ccode = document.getElementById("ccode").value;
	var phone = document.getElementById("phone").value;
	var otp = document.getElementById("otp").value;
	var xv=document.getElementById("otpxv").value;
	if(ccode!="" && phone!="" && otp!="")
	{
		var formData = new FormData();
		formData.append("ccode", ccode);
		formData.append("phone", phone);
		formData.append("otp", otp);
		formData.append("xv", xv);
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var res = this.responseText;
				document.getElementById("verif_sp").innerHTML = "";
				if(res=="ok")
				{
					ssdiv(3);
					if(xv!='reset')
					{
						document.getElementById("chentbtn").value="registerbtn";
					} else
					{
						document.getElementById("chentbtn").value="resetbtn";
					}
					document.getElementById("password").focus();
				} else
				{
					M.toast({html: 'OTP does not match. Please check and try again', classes: 'rounded pink darken-4 semi-bold'});
					document.getElementById("otp").focus();
				}
			}
		};
		xmlhttp.open("POST", "/user/dynamic/verifyotp", true);
		xmlhttp.send(formData);
	} else
	{
		M.toast({html: 'Enter the OTP', classes: 'rounded pink darken-4 semi-bold'});
		document.getElementById("verif_sp").innerHTML = "";
	}
}
function logsubfm(loc,formval)
{
	allow=logbfsub(formval);
	if(allow=="no"){ return; }
	var elements = document.getElementsByClassName(formval);
	var formData = new FormData(); 
	for(var i=0; i<elements.length; i++)
	{
		if(elements[i].value=='' && elements[i].hasAttribute('required'))
		{
			allow=logmissub(elements[i],formval);
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
			logaftsub(res,formval);
		}
	}
	xmlhttp.open("POST", loc, true); 
	xmlhttp.send(formData);
}
function logbfsub(formval)
{
	document.getElementById(formval+"ld").innerHTML='Submitting...';
	M.Toast.dismissAll();
}
function logaftsub(res,formval)
{
	var logred=document.getElementById("logred").value;
	var xv=document.getElementById("otpxv").value;
	document.getElementById(formval+"ld").innerHTML='';
	if(res=="OK")
	{
		if(xv=='reset')
		{
			M.toast({html: 'Password reset successfully', classes: 'rounded teal darken-4 semi-bold'});
			document.getElementById("ssdiv4").innerHTML = '<div class="center semi-bold"><p class="teal-text text-darken-2 bold">Password reset successfully</p><p class="purple-text text-darken-4">Redirecting...</p><p>OR</p><p><a href="'+logred+'" class="btn-small purple darken-2">Click here</a></p></div>';
		} else
		{
			if(formval=="loginfm")
			{
				M.toast({html: 'Login successful', classes: 'rounded teal darken-4 semi-bold'});
				document.getElementById("ssdiv4").innerHTML = '<div class="center semi-bold"><p class="teal-text text-darken-2 bold">Login successful</p><p class="purple-text text-darken-4">Redirecting...</p><p>OR</p><p><a href="'+logred+'" class="btn-small purple darken-2">Click here</a></p></div>';
			} else if(formval=="regfm")
			{
				M.toast({html: 'Account created successfully', classes: 'rounded teal darken-4 semi-bold'});
				document.getElementById("ssdiv4").innerHTML = '<div class="center semi-bold"><p class="teal-text text-darken-2 bold">Account created successfully</p><p class="purple-text text-darken-4">Redirecting...</p><p>OR</p><p><a href="'+logred+'" class="btn-small purple darken-2">Click here</a></p></div>';
			}
		}
		ssdiv(4);
		setTimeout(function(){ window.location.href = logred; }, 2000);
	} else if(res!="")
	{
		document.getElementById(formval+"ld").innerHTML=res;
		M.toast({html: res, classes: 'rounded pink darken-4 semi-bold'});
	} else
	{
		document.getElementById(formval+"ld").innerHTML='Something went wrong. Please contact support team.';
		M.toast({html: 'Something went wrong. Please contact support team.', classes: 'rounded pink darken-4 semi-bold'});
	}
}
function logmissub(elem,formval)
{
	document.getElementById(formval+"ld").innerHTML='';
	var name = elem.getAttribute('data-title');
	M.toast({html: name+' is required', classes: 'rounded pink darken-4 semi-bold'});
	return "no";
}
function chpassword(val)
{
	if (val.length < 8 || val.length > 80) 
	{
		document.getElementById("passx1").style.color='#e53935'; 
	} else
	{
		document.getElementById("passx1").style.color='#43a047';
	}
	var regtest1 = new RegExp(/[A-Za-z]+/);
	if(!regtest1.test(val))
	{
		document.getElementById("passx2").style.color='#e53935'; 
	} else
	{
		document.getElementById("passx2").style.color='#43a047';
	}
	var regtest2 = new RegExp(/[0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-]+/);
	if(!regtest2.test(val))
	{
		document.getElementById("passx3").style.color='#e53935'; 
	} else
	{
		document.getElementById("passx3").style.color='#43a047';
	}
}
function showpass()
{
	var xpass = document.getElementById('password');
	if(xpass.type=='password')
	{
		xpass.type='text';
		document.getElementById('passvisib').src='/images/icon/visibility.png';
	} else
	{
		xpass.type='password';
		document.getElementById('passvisib').src='/images/icon/visibility_off.png';
	}
}
var xpass = document.getElementById("password");
xpass.addEventListener('keyup', function(e)
{
	if (e.getModifierState("CapsLock"))
	{
		document.getElementById("pass_sp").innerHTML='<p class="center pink-text text-darken-2 font-size-1-1 semi-bold">CAPS LOCK IS ON</p>';
	} else
	{
		document.getElementById("pass_sp").innerHTML='';
	}
});
xpass.addEventListener('focus', function() {
	if(document.getElementById("chentbtn").value!='loginbtn')
	{
		document.getElementById("passxdiv").style.display='block';
	}
});
xpass.addEventListener('focusout', function() {
	document.getElementById("passxdiv").style.display='none';
	document.getElementById("pass_sp").innerHTML='';
});