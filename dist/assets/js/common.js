var Utils={
	customLoader: {
        loader: "#loading",
        disable_loader: false,
        hide: false,
        callback: false,
        global: true
    },
	getAction:function(url,data,func,loader){
		if(!loader){
			loader=Utils.customLoader
		}
		Utils.attachLoader(loader);
		if(!data){
			data={};
		}
		
		jQuery.get(url,data,function(r){
		e=Utils.handleAjaxError(r);
		
		if(!e) return;
		if(func){func(r);}
		
		});
		
	
	},
	postAction:function(url,data,func,loader){
		if(!loader){
			loader = Utils.customLoader
		}
		Utils.attachLoader(loader);
		if(!data){
			data={};
		}
		jQuery.post(url,data,function(r){
		
		e=Utils.handleAjaxError(r);
		
		if(!e) return;
		if(func){func(r);}
		
		});
	
	},
	loadAction: function(container,url,data,func,loader){
		
		if(!loader){
			loader=Utils.customLoader
		}
		Utils.attachLoader(loader);
		if(!data){
			data={};
		}
		
		jQuery.get(url,data,function(r){
		
		e=Utils.handleAjaxError(r);
		
		if(!e)return;
		$(container).html(r);
		if(func)func(r);
		
		});
		
	
	
	},
	handleAjaxError: function(d) {
		
        if (!d) {
            return true
        }
        if (d.match(/_ERROR/)) {
            var b = d.replace(/.*<!--_ERROR-->([\s\S]*?)<!--_ERROR-->.*/mg, "$1");
            alert(b);
            return false
        } else if(d.match(/_REDIRECT/)) {
        	
            var a = d.replace(/.*<!--_REDIRECT-->(.*?)<!--_REDIRECT-->.*/m, "$1");
			window.location.href =a;
            return false
        }else if(d.match(/_LOGIN_REQUIRED/)){
		
			login_require_box();
			return false;
		
		}
		
		
        return true
    },
	attachLoader: function(a) {
       
        if (!a.loader) {
            a.loader = Utils.customLoader.loader
        }
        if (a.callback) {
            a.callback(false)
        }
        $(a.loader).ajaxSend(function() {
            if (!a.disable_loader) {
                $(this).show()
            }
        });
        $(a.loader).ajaxComplete(function() {
            if (!a.disable_loader) {
                $(this).hide()
            }
            Utils.detachLoader(a)
        });
        $(a.loader).ajaxStop(function() {
            if (!a.disable_loader) {
                $(this).hide()
            }
            Utils.detachLoader(a)
        });
        $(a.loader).ajaxError(function(d, c, b) {
            Utils.handleAjaxError(c.responseText);
            return false
        });
        if (a.hide) {
            $(a.hide).ajaxSend(function() {
                $(a.hide).hide()
            });
            $(a.hide).ajaxComplete(function() {
                $(a.hide).show()
            });
            $(a.hide).ajaxStop(function() {
                $(a.hide).show()
            })
        }
    },
	detachLoader: function(a) {
        if (!a.loader) {
            a.loader = Utils.customLoader.loader
        }
        if (a.hide) {
            $(a.hide).unbind("ajaxSend");
            $(a.hide).unbind("ajaxComplete");
            $(a.hide).unbind("ajaxStop")
        }
        $(a.loader).unbind("ajaxSend");
        $(a.loader).unbind("ajaxComplete");
        $(a.loader).unbind("ajaxStop");
        $(a.loader).unbind("ajaxError");
        if (a.callback) {
            a.callback(true)
        }
     
    }
};


var Login={

	_submit:function()
	{
		var login=$("#login").val();
		var password=$("#password").val();
		var remember=$("remember").val();
		if(!/^[-a-z0-9_-]{4,20}$/.test(login)||!/^[-a-z0-9_-]{4,20}$/.test(password))
		{
		   alert('username or password is invalid!');
		   return false;
		}
		
		Utils.postAction('/ucp/login',$('#loginForm').serialize(),function(data){
		
		if(data==1)
		{
			$("#login_form").hide();
			Utils.loadAction('#user','/ucp/userpanel');
		}
		});
		return false;
	},
	_show:function(e)
	{
		
		if(e ==undefined){e=window.event;}
		$("#login_form").show();
		$("#login").focus();
		if(e.preventDefault)
		{
			e.preventDefault();
		}
		else
		{
			e.defaultValue=false;
		}
		
	},
	_close:function()
	{
		$("#login_form").hide();
	},
   _logout:function(e)
   {
		if(e ==undefined){e=window.event;}
		if(e.preventDefault)
		{
			e.preventDefault();
		}
		else
		{
			e.defaultValue=false;
		}
		Utils.loadAction('#user','/ucp/logout');
   }
   
   

};

function login_require_box()
{
	alert('Please login to continue...');
	window.scrollTo(1000,0);
	$('#login_form').show();
	$("#login").focus();

}

$(function(){

	if($('#network_box').length>0)
	{
		$("#network_box .pageBar>a,#feed_box .pageBar>a").live("click",function(e){
			e.preventDefault();
			a=this.href;
			if($(this).parents("#network_box").length > 0)
				Utils.loadAction("#network_box",a);
			else 
				Utils.loadAction("#feed_box",a);
			});
		$(".pg").live("click",function(e){
			c='#'+$(this).parent().attr("id").substring(4);
			a=this.href;
			Utils.loadAction(c,a);
			e.preventDefault();
			});	
	}
	
	if($("#bio_box").length > 0)
	{
		$("#bio_box .unfollow,#bio_box .f_req").click(function(e){
			e.preventDefault();
			a = this.href;
			Utils.postAction(a,$("#friendForm").serialize(),function(data){
				if(data == '1')
					window.location.reload();
			});
		});
		$("#bio_box .unfollow").mouseout(function(){
			$(this).hide();
			$("#bio_box .following").show();
		})
		$("#bio_box .following").mouseover(function(){
			$(this).hide();
			$("#bio_box .unfollow").show();
		});
		
	}
	

	
	$("#q").click(function(){
	   if($("#q").val().indexOf('Search Business In')>=0)
	   {
			$("#q").val('');
			$("#q").css("color","#000000");
	   }
	
	});
	$("#s_submit").click(function(){
		q=jQuery.trim($("#q").val());
		if(q.indexOf('Search Business In')>=0||q=='')
		{
			return false;
		}
		$("#searchbox").submit();
	
	});
	$("#fq").click(function(){
	   if($("#fq").val().indexOf('Search Topics In')>=0)
	   {
			$("#fq").val('');
			$("#fq").css("color","#000000");
	   }
	
	});
	$("#fq_submit").click(function(){
		q=jQuery.trim($("#fq").val());
		if(q.indexOf('Search Topics In')>=0||q=='')
		{
			return false;
		}
		$("#forum_search_form").submit();
	
	});
	if($(".rating-block").length>0)
	{
		$(".star-1,.star-2,.star-3,.star-4,.star-5").mouseover(function(){
		    
			var rating=parseInt($("#rating").val());
			if(rating>0)
			{
				$(".star-"+rating).removeClass("active-star");
			}
			$(this).addClass("active-star");
			$(".rating-hint").html($(this).attr("title"));
		
		});
		$(".star-1,.star-2,.star-3,.star-4,.star-5").mouseout(function(){
			$(this).removeClass("active-star");
			$(".rating-hint").html("");
			var rating=parseInt($("#rating").val());
			if(rating>0)
			{
				$(".star-"+rating).addClass("active-star");
				$(".rating-hint").html($(".star-"+rating).attr("title"));
			}
			
		
		});
		$(".star-1,.star-2,.star-3,.star-4,.star-5").click(function(){
			
			$("#rating").val($(this).attr("rating-value"));
		
		});
	}
	if($('#flashmsg').length>0)
		$(".alert .close").click(function(){
			$(this).parent().fadeOut("slow");
		});
	$('.send_flower').click(function(){
		   
			var ids = $(this).attr('id').substring(12).split('_');
			var uid = ids[0];
			var bizid = ids[1];
			var id = ids[2];
			var type = ids[3];
			
			Utils.loadAction("#flower_holder_"+id,'/flower/send/'+bizid+'/'+id+'/'+type,{},function(){
				if($('#user_flower_'+uid).length>0)
				{
					if(!($('#user_flower_'+uid).hasClass('user_flower')))
					{
						$('#user_flower_'+uid).addClass('user_flower');
					}
					Utils.loadAction('#user_flower_'+uid,'/flower/user/'+uid);
				}
			});
	});
	$('.required_login').click(function(event){
		event.preventDefault();
		var href= $(this).attr('href');
		Utils.getAction("/ucp/is_logged_in",{},function(d){
			if(d!=1)
			{
				login_require_box();
				
			}
			else{
				window.location.href=href;
			}
			
		});
	});
	if($('.flag').length>0)
	{
		$(".flag").colorbox({initialWidth:100,initialHeight:50,scrolling:false,inline:true,href:"#report_box",onComplete:function(){
			if($(this).hasClass('required_login'))
			{
				$.colorbox.close();
				return;
			}
			$("#report_form input[name=url]").val($(this).attr('href'));
		},onClosed:function(){$('#comment').val('');$("#msg").html('')}});
		
		$("#report_bt").click(function(event){
			event.preventDefault();
			var comment = $.trim($('#comment').val());
			if(comment.length==0)
			{
				$("#msg").html('The reason can\'t be blank');
				return;
			}
			
			Utils.postAction($("#report_form").attr('action'),$("#report_form").serialize(),function(data)
			{
				if(data ==1)
				{
					alert('Thank You! Your report has been sent successfully');
					window.location.reload();
				}
				else
				{
					$("#msg").html(data);
				}
			});

		});
	}
	if($('.fix_location').length>0)
		$(".fix_location").colorbox({initialWidth:100,initialHeight:50,scrolling:false,inline:true,href:"#map",title:"Drag and drop the map marker to correct the location",onComplete:fix_location_map});
	//
	if($(".delete_forum_post").length>0)
		$(".delete_forum_post").click(function(event){
			event.preventDefault();
			if(!confirm("Are you sure you want to delete this?"))
			{
				return;
			}
			
			var href= $(this).attr('href');
			
			Utils.getAction(href,{},function(d){
				
				if(d==1)
				{
					var href_str = href.split('/');
					var pid = href_str[href_str.length-1];
					$("#li_"+pid).remove();
					
				}
			});
		});
	//
	
	});
	
	function replyTo(name,content_id)
	{
		var div = $("#"+content_id);
		var content = "@" + name + " "+div.val();
		div.focus();
		div.val(content);
		
	}
