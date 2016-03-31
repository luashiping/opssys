var msgProcess = {
	boxvalue:'',
	boxname:'checkbox',
	symbol:',',
	getBox:function(name) {
		box_value = '';
		box = $("input[name='"+name+"\[\]']");
		lengths = box.length;
		for(i=0;i<lengths;i++) {
			if(box[i].checked == true) {
				box_value = box_value != '' ? (box_value + this.symbol + box[i].value) : box[i].value;
			}
		}
		this.boxvalue = box_value;
	},

	moreProcess:function(action) {
		$("#processdiv").show();
		moveit("processdiv");
		this.boxvalue = '';
		this.getBox(this.boxname);
		if(this.boxvalue != '') {
			getdata={checkbox:this.boxvalue};
			admin_url = 'http://admin.gtobal.com';
			url = admin_url + action + '?callback=?';
			$.getJSON(url,getdata,function(data){
				$.pagination.get_list();
				$("#processdiv").hide();
				$(".attention").show();
				$("#id_succed").html(data.message);
				setTimeout(function(){$(".attention").hide();}, 3000);
			});
		} else {
			alert('请选择信息后再操作!');
		}
	},

	box_checked:function(number) {
		var box = $("input[name='checkbox[]']");
		box[number].checked = true;
	}
}