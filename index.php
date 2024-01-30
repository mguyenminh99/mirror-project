<!doctype html>
<html>
	<head>
		<meta charset=utf8>
		<title>mirror github</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<style>
			* {
				margin: 0;
				padding: 0;
				box-sizing: border-box;
			}
			body {
				font: 16px/1.5 Arial;
				background: #eee;
			}
			#wrap {
				background: #eee;
				border: 5px solid #fff;
				box-shadow: 2px 2px 10px 1px #bbb;
				width: 80%;
				margin: 50px auto;
			}
			#wrap:after {
				display: block;
				content: "";
				clear: both;
			}
			#left {
				width: 30%;
				float: left;
				padding: 10px;
			}
			#right {
				width: 70%;
				float: left;
				padding: 10px;
			}
			#online {
				min-height: 400px;
				background: #fff;
			}
			#boxChat {
				min-height: 400px;
				background: #fff;
			}
			#submit {
			}
			input, textarea {
				margin-top: 10px;
				width: 100%;
				border-radius: 5px;
				padding: 5px 10px;
				box-shadow: inset 1px 1px 5px #ccc;
				border: 1px solid #bbb;
				line-height: 1.5;
			}
			#send, #join, #leave {
				background: darkgreen;
				color: #fff;
				padding: 5px 10px;
				border-radius: 5px;
				cursor: pointer;
				border: outset 1px #bbb;
				float: right;
				margin: 5px;
			}
			#leave {
				background: darkred;
				float: left;
			}
			textarea {
				resize: none;
				font-family: Arial;
			}
			.online {
				padding: 10px;
				border-bottom: 1px dashed #bbb;
				cursor: pointer;
			}
			.online:hover {
				background: rgba(0,0,0,.1);
			}
			.online:before {
				content: "";
				display: inline-block;
				width: 10px;
				height: 10px;
				border-radius: 50%;
				background: green;
				margin-right: 5px;
				vertical-align: middle;
			}
			#boxChat {
				padding: 10px;
				max-height: 400px;
				overflow: auto;
			}
			.msg {
				clear: both;
			}
		</style>
	</head>
	<body>
		<div id=wrap>
			<div id=left>
				<div id=online></div>
				<input id=name placeholder="Tên" />
				<div id=join>Join</div>
				<div id=leave>Leave</div>
			</div>
			<div id=right>
				<div id=boxChat></div>
				<div id=submit>
					<textarea id=msg placeholder="Chat All" rows=4></textarea>
					<div id=send>Send</div>
				</div>
			</div>
		</div>
		<script>
			$(function(){
				USER2 = "undefined";
				user2Name = "";
				$("#online").on("click",".online",function(){
					USER2 = $(this).attr("id");
					user2Name = $("#"+USER2).text();
					if (USER2 != ID){
						$("#msg").attr("placeholder","Thả thính " +user2Name);
						clearInterval(MSG);
						$("#boxChat").empty();
						GETPRIVATEMSG = function(me){
							clearInterval(PMSG)
							$.get("chat.php",{action:"getPrivateMsg" , id2:USER2}, function(data){
								var bottom = $("#boxChat").scrollTop()+$("#boxChat").outerHeight()==$("#boxChat")[0].scrollHeight;
								$("#boxChat").trigger("loadMsg",[data]); // Hiển thị message
								if (bottom || me==true) { // Nếu đang cuộn ở dưới cùng hoặc tôi vừa gửi message
									$("#boxChat").animate({
										scrollTop:$("#boxChat")[0].scrollHeight
									});
								}
								PMSG = setInterval(GETPRIVATEMSG,3000);
							},"json");
						};
						// $.get("chat.php", {action: "getUser2Name" , id2:USER2}, function(dataName){
						// 	console.log(dataName);
						// },"json");
						GETPRIVATEMSG();
					}
					else{
						USER2 = "undefined";
						$("#boxChat").empty();
						GETMSG();
					}
				});
				$("#msg").on("keypress",function(e){
					if (e.which==13) { // Khi gõ enter
						if (!e.shiftKey) {
							$("#send").click();
							return false;
						}
					}
				});
				$("#boxChat").on("loadMsg",function(e,data,method){
					var box = $(this),
						firstRow = box.children(":first");
					box[method||"append"](
						data.map(function(v){
							if (!box.children("#"+v.idchat).length) {
								var date = new Date(v.time),
									fullDate = TWO(date.getHours())+":"+TWO(date.getMinutes())+" "+TWO(date.getDate())+"/"+TWO(date.getMonth()+1)+"/"+date.getFullYear(),
									content = $.trim(v.content).replace(/ +/g," ").replace(/\n+/g,"\n");
								return $("<div>",{ id:v.idchat, class:"msg" }).css({
									display:content?undefined:"none"
								}).append(
									$("<b>",{ text:v.username }).css({
										color:ID==v.id ? "red" : undefined
									}),
									$("<c>",{ text:": " }),
									$("<c>",{ }).css({
										whiteSpace:"pre-wrap"
									}).html(content),
									$("<c>",{ class:"time", text:RECENT(date), time:date.getTime() }).css({
										float:"right",
										color:"#666",
										fontSize:".9em"
									})
								);
							}
						})
					);
					if (method=="prepend") { // Khi cuộn lên trên hiển thị message cũ
						var sum = 0,
							rows = firstRow.prevAll(":visible");
						for (var i=0;i<rows.length;i++) {
							sum += rows.eq(i).outerHeight();
						}
						box.scrollTop(sum);
					}
				});
				ACTIVE = function(){
					var onl = function(){
						$.get("chat.php",{ action:"active" },function(status){
							if (status==1) {
								
							} else { // Không active online đc
								console.log("failed to active");
							}
						});
						GETONLINE();
					};
					ONL = setInterval(onl,10000);
					onl();
				};
				// Lấy ID và NAME của mình
				$.get("chat.php",{ action:"getID" },function(data){
					ID = data.id;
					NAME = data.name;
					$("#name").val(NAME);
					ACTIVE(); // Cho tôi online với
					GETMSG();
					$("#boxChat").on("scroll",function(){
						// console.log("scroll 1");
					}).on("scroll",function scroll2(){
						// console.log("scroll 2");
						var box = $(this);
						console.log(box);
						if (box.scrollTop()==0) {
							to = setTimeout(function(){
								var firstID = box.children(":first").attr("id");
								$.get("chat.php",{ action:"olderMsg", id:firstID, id2:USER2 },function(data){
									console.log(USER2);
									if (data.length) {
										box.trigger("loadMsg",[data,"prepend"]);
									} else { // Hết message cũ rồi
										box.off("scroll",scroll2); // Hủy event scroll
									}
								},"json");
							},500);
						} else {
							if (typeof to!="undefined")
								clearTimeout(to);
						}
					});
				},"json");
				GETONLINE = function(){
					$.get("chat.php",{ action:"getOnline" },function(data){
						$("#online").empty().append(
							data.map(function(v,i){
								return $("<div>",{ id:v.id, class:"online", text:v.username });
							})
						);
					},"json");
				};
				$("#join").click(function(){
					var name = $("#name").val();
					if (name) {
						$.post("chat.php",{ action:"rename", name:name },function(status){
							if (status==1) { // Lưu tên thành công
								NAME = name;
								if (!$("#online #"+ID).length) {
									$("#online").append(
										$("<div>",{ id:ID, class:"online", text:NAME })
									);
								}
								ACTIVE(); // Cho tôi online với
							} else {
								alert(status);
							}
						});
					} else {
						alert("Vui lòng điền tên");
					}
				});
				$("#leave").click(function(){
					clearInterval(ONL);
					$("#online #"+ID).remove();
				});
				$("#send").click(function(){
					var msg = $.trim($("#msg").val());
					if (msg) {
						if (NAME) { // Đã có tên
							$.post("chat.php",{ action:"send", msg:msg , id2:USER2 },function(status){
								if (status>0) { // Gửi tin nhắn thành công
								console.log(USER2);
									if (USER2 == "undefined")
										GETMSG(true); // Tải message mới
									else
										GETPRIVATEMSG(true);	
									$("#msg").val("");
								} else {
									alert(status);
								}
							});
						} else {
							alert("Vui lòng nhập tên của bạn");
						}
					} else {
						alert("Vui lòng nhập nội dung");
					}
				});
				var time; // Thời gian message cuối cùng
				var lastmsg; // ID message cuối cùng
				var MSG;
				var PMSG;
				TWO = function(value){ // Return chuỗi 2 ký tự
					if (value<10)
						value = "0"+value*1;
					return value;
				};
				RECENT = function(date){
					var sec = parseInt(((new Date).getTime()-date.getTime())/1000),
						min = parseInt(sec/60),
						hour = parseInt(min/60),
						day = parseInt(hour/24),
						month = parseInt(day/30),
						year = parseInt(month/12);
					return (year ? year+" years ago" : month ? month+" months ago" : day ? day+" days ago" : hour ? hour+" hours ago" : min ? min+" mins ago" : sec>20 ? "About a min" : " Just now");
				};
				GETMSG = function(me){
					clearInterval(MSG);
					// console.log(USER2);
					$.get("chat.php",{ action:"getMsg" },function(data){
						// console.log(USER2);
						var bottom = $("#boxChat").scrollTop()+$("#boxChat").outerHeight()==$("#boxChat")[0].scrollHeight;
						$("#boxChat").trigger("loadMsg",[data]); // Hiển thị message
						if (bottom || me==true) { // Nếu đang cuộn ở dưới cùng hoặc tôi vừa gửi message
							$("#boxChat").animate({
								scrollTop:$("#boxChat")[0].scrollHeight
							});
						}
						MSG = setInterval(GETMSG,3000);
					},"json");
				};
				RESETTIME = setInterval(function(){
					$("#boxChat .time").map(function(){
						$(this).text(RECENT(new Date($(this).attr("time")*1)));
					});
				},1000*20);
			});
		</script>
	</body>
</html>