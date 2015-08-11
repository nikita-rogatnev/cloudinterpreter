var ajax = null;

function zopimconnect(token)
{
	$('zopimstatus').set ('html', 'Connecting to Zopim ...');
	var zopimSSL = '';
	var zopimUsername = $('zopimUsername').value;
	var zopimPassword = $('zopimPassword').value;
	var urlrequest ='zopimPassword='+zopimPassword+'&zopimUsername='+zopimUsername+'&'+token+'=1';
	if ($('jform_params_zopim_id')) {
		zopimSSL = $('jform_params_zopim_id').value;
		urlrequest += '&zopimSSL='+zopimSSL;
		$('jform_params_zopim_id').value = '';
		ajax = new Request
		({
			url: url,
			onComplete:function Finish(response){
				zopimresponse(response)
			}
		}).post(urlrequest);
	} else {
		zopimSSL = $('paramszopim_id').value;
		urlrequest += '&zopimSSL='+zopimSSL;
		$('paramszopim_id').value = '';
		ajax = new Ajax
		(   
			url,
			{   
				method:"post",
				data: urlrequest,
				onComplete:function Finish(response){
					zopimresponse(response)
				}   
			}   
			).request();
	}
	return;
}

function zopimresponse(response)
{
	try {	
		if ($('jform_params_zopim_id')) {
			var resp=JSON.decode(response);
		} else {
			var resp=Json.evaluate(response);
		}
	}
	catch (e) {
		$('zopimstatus').set ('html', 'JSON failed');
		return;
	}

	if (resp.error) {
		$('zopimstatus').set ('html', resp.error);
		return;
	}

	$('zopimstatus').set ('html', 'Successfully retrieved widget id. Please click on the save or apply button above.');

	if ($('jform_params_zopim_id')) {
		$('jform_params_zopim_id').value = resp.account_key;
	} else {
		$('paramszopim_id').value = resp.account_key;
	}
}