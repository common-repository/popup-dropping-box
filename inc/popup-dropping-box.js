/*
GNU General Public License v2 or later

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

License URI	: http://www.gnu.org/licenses/gpl-2.0.html

*/

function popupdroppingbox(options){
	this.closebutton='<div style="position:absolute;top:4px;right:4px;cursor:pointer"><img src="closebox.gif" title="Close box" /></div>';
	this.s=jQuery.extend({fx:'easeOutBounce', fxtime:500, freq:'always', showduration:0, pos:['center','center'], deferred:0.5}, options)
	var thisbox=this
	this.s.source=(!jQuery.isArray(this.s.source))? [this.s.source] : this.s.source
	this.closebutton = '<div style="position:absolute;top:4px;right:4px;cursor:pointer"><img src="' + this.s.closeimage + '" title="Close" /></div>';
	this.$closebutton=jQuery(this.closebutton).hide().click(function(){thisbox.hide()})
	var loadbox=(this.s.deferred=="onclick")? false: true
	this.s.freqispersist=!isNaN(parseInt(this.s.freq))
	if ((this.s.freq=="session" || this.s.freqispersist) && popupdroppingbox.routines.getCookie(this.s.source[0])){ 
		loadbox=false
		if (popupdroppingbox.routines.getCookie(this.s.source[0]+'_freq')!=this.s.freq){
			popupdroppingbox.routines.setCookie(this.s.source[0], '', -1) 
			loadbox=true
		}
	}
	jQuery(function($){ 
		thisbox.init($, thisbox.s, loadbox)
	})
}

popupdroppingbox.prototype={

	show:function(pos){
		var $=jQuery, $contentbox=this.$contentbox.css({display:'block'}), s=this.s
		if (typeof pos=="undefined")
			var pos=s.pos
		var winmeasure={w:$(window).width(), h:$(window).height(), left:$(document).scrollLeft(), top:$(document).scrollTop()} 
		var boxmeasure={w:$contentbox.outerWidth(), h:$contentbox.outerHeight()}
		var finalpos=[]
		$.each(pos, function(i, val){
			if (val<0){ 
				finalpos[i]=(i==0)? winmeasure.left+winmeasure.w-boxmeasure.w+val : winmeasure.top+winmeasure.h-boxmeasure.h+val
			}
			else if (val=="center"){
				finalpos[i]=(i==0)? winmeasure.left+winmeasure.w/2-boxmeasure.w/2 : winmeasure.top+winmeasure.h/2-boxmeasure.h/2
			}
			else {
				finalpos[i]=val;
			}
		})
		$contentbox.css({left:finalpos[0], top:winmeasure.top-boxmeasure.h-10, visibility:'visible'}).animate({top:finalpos[1]}, s.fxduration, s.fx)
	},

	hide:function(){
		this.$contentbox.hide()
		this.$closebutton.hide()
	},

	init:function($, s, loadcheck){
		var thisbox=this
		this.$contentbox=(s.source.length==1)? $(s.source[0]).css({position:'absolute', visibility:'hidden', top:0}).addClass(s.cssclass) : ""
		function selectiveshow(){
			if (loadcheck==false)
				return
			$(document.body).append( thisbox.$contentbox )
			thisbox.$contentbox.append(thisbox.$closebutton).hover( 
				function(){
					thisbox.$closebutton.stop(true,true).fadeIn()
				},
				function(){
					thisbox.$closebutton.stop(true,true).fadeOut()
				}
			) 
			function selectivesetcookie(){
				if (s.freq=="session" || s.freqispersist){
					popupdroppingbox.routines.setCookie(s.source[0], 'yes', s.freq)
					popupdroppingbox.routines.setCookie(s.source[0]+'_freq', s.freq, s.freq)
				}
			} 
			if (s.deferred>0) 
				setTimeout(function(){thisbox.show(s.pos); selectivesetcookie()}, s.deferred*1000)
			else if (s.deferred==0){
				thisbox.show(s.pos)
				selectivesetcookie()
			}
			if (s.showduration>0)
				setTimeout(function(){thisbox.hide()}, s.deferred*1000+s.showduration*1000)
		} 
		if (s.source.length==2){ 
			$.ajax({
				url: s.source[1].replace(/^http:\/\/[^\/]+\//i, "http://"+window.location.hostname+"/"), 
				dataType:'html',
				error:function(ajaxrequest){
					alert('Error fetching Ajax content\nServer Response: '+ajaxrequest.responseText)
				},
				success:function(content){
					thisbox.$contentbox=$(content).addClass(s.cssclass).css({position:'absolute', visibility:'hidden', top:0}).appendTo(document.body)
					selectiveshow()
				}
			})

		}
		else{
			selectiveshow()
		}
	}
}

popupdroppingbox.routines={

	getCookie:function(Name){ 
		var re=new RegExp(Name+"=[^;]+", "i"); 
		if (document.cookie.match(re)) 
			return document.cookie.match(re)[0].split("=")[1] 
		return null
	},

	setCookie:function(name, value, duration){
		var expirestr='', expiredate=new Date()
		if (typeof duration!="undefined"){ 
			var offsetmin=parseInt(duration) * (/hr/i.test(duration)? 60 : /day/i.test(duration)? 60*24 : 1)
			expiredate.setMinutes(expiredate.getMinutes() + offsetmin)
			expirestr="; expires=" + expiredate.toUTCString()
		}
		document.cookie = name+"="+value+"; path=/"+expirestr
	}
}
