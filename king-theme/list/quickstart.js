(function( $ ){
		$("*[data-embed]").each(function(){
			$this 			= $( this );
			if( !$this.attr("href") );
		});

		$("*[data-embed]").click(function(){
			embedScript 	= getEmbedScript( $(this).data("embed") );
			$(this).replaceWith(embedScript);
		});

		function getEmbedScript( url ) {
			var service 	= false;

        var services    = {
			gif         : /(https*:\/\/[-\[\]\{\}\(\)\%_\/.a-zA-Z0-9+]+\.(gif))[^< ]*/,
			img         : /(https*:\/\/[-\[\]\{\}\(\)\%_\/.a-zA-Z0-9+]+\.(png|jpg|webp|jpeg|bmp))[^< ]*/,	
            youtube     : /https{0,1}:\/\/w{0,3}\.*youtube\.com\/watch\?\S*v=([A-Za-z0-9_-]+)[^< ]*/,
            vimeo       : /https{0,1}:\/\/w{0,3}\.*vimeo\.com\/([0-9]+)[^< ]*/,
            twitter       : /https{0,1}:\/\/w{0,3}\.*twitter\.com\/i\/status\/([0-9]+)[^< ]*/,
			vine        : /https{0,1}:\/\/w{0,3}\.*vine\.co\/v\/([A-Za-z0-9_-]+)[^< ]*/,
			metacafe       : /https{0,1}:\/\/w{0,3}\.*metacafe\.com\/watch\/([0-9]+)\/([a-z0-9_]+)[^< ]*/,
			instagram       : /https{0,1}:\/\/w{0,3}\.*instagram\.com\/p\/([A-Za-z0-9_-]+)[^< ]*/,
			dailymotion       : /https{0,1}:\/\/w{0,3}\.*dailymotion\.com\/video\/([A-Za-z0-9]+)[^< ]*/,
			mailru       : /https{0,1}:\/\/w{0,3}\.*my.mail.ru\/mail\/([\-\_\/.a-zA-Z0-9]+)[^< ]*/,
			soundcloud       : /https{0,1}:\/\/w{0,3}\.*soundcloud\.com\/([-\%_\/.a-zA-Z0-9]+\/[-\%_\/.a-zA-Z0-9]+)[^< ]*/,
			facebook       : /https{0,1}:\/\/w{0,3}\.*facebook\.com\/video\.php\?\S*v=([A-Za-z0-9_-]+)[^< ]*/,
			mp4         : /(https*:\/\/[-\[\]\{\}\(\)\%_\/.a-zA-Z0-9+]+\.(mp4))[^< ]*/,
			webm         : /(https*:\/\/[-\[\]\{\}\(\)\%_\/.a-zA-Z0-9+]+\.(webm))[^< ]*/,

        }
        var scripts     = {
            gif         : '<img src="{{videoID}}" width="100%" height="auto"></img>',
			img         : '<img src="{{videoID}}" width="100%" height="auto"></img>',			
            youtube     : '<iframe width="100%" height="450" src="https://www.youtube.com/embed/{{videoID}}" frameborder="0" allowfullscreen></iframe>',
            vimeo       : '<iframe src="https://player.vimeo.com/video/{{videoID}}" width="100%" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
            twitter       : '<amp-twitter class="no-bottom" width="100%" height="281" layout="responsive" data-tweetid="{{videoID}}" data-cards="hidden"></amp-twitter>',
            vine        : '<iframe class="qstart_iframe" src="https://vine.co/v/{{videoID}}/embed/simple" width="100%" height="600" frameborder="0"></iframe>',
			metacafe       : '<embed flashVars="playerVars=showStats=no|autoPlay=no" src="https://www.metacafe.com/fplayer/{{videoID}}/{{videoID[1]}}.swf" width="100%" height="337" wmode="transparent" allowFullScreen="true" allowScriptAccess="always" name="Metacafe_$1" pluginspage="https://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>',
			instagram        : '<iframe src="//instagram.com/p/{{videoID}}/embed/" width="100%"height="600" frameborder="0" scrolling="no" allowtransparency="true"></iframe>',
			dailymotion        : '<iframe frameborder="0" width="100%" height="339" src="https://www.dailymotion.com/embed/video/{{videoID}}?wmode=transparent"></iframe>',
			mailru        : '<iframe src="https://videoapi.my.mail.ru/videos/embed/mail/{{videoID}}" width="100%" height="339" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
			soundcloud        : '<iframe width="100%" height="600" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https://soundcloud.com/{{videoID}}&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>',
			facebook        : '<iframe class="fb-video" width="100%" height="450"  data-allowfullscreen="true" src="https://m.facebook.com/video/video.php?v={{videoID}}"></iframe>',
			mp4        : '<video id="example_video_1" class="video-js vjs-default-skin" controls preload="auto" width="100%" height="450" poster=""  data-setup=\'{"example_option":true}\'> <source src="{{videoID}}" type=\'video/mp4\' /> </video>',
			webm        : '<video id="example_video_1" class="video-js vjs-default-skin" controls preload="auto" width="100%" height="450" poster=""  data-setup=\'{"example_option":true}\'> <source src="{{videoID}}" type=\'video/webm\' /> </video>',
			
        }

			for( var a in services )
			{
				var videoID = url.match( services[a] );
				if( videoID ) return format( scripts[a] , { videoID : videoID[1] } );
			}
		}

		function format (t, d) {
	        return t.replace(/{{([^{}]*)}}/g, function (a, b) {
	            var r = d[b];
	            return typeof r === 'string' || typeof r === 'number' ? r : a;
	        });
		}		
		$(document).ajaxStop(function() {

		$("*[data-embed]").each(function(){
			$this 			= $( this );
			if( !$this.attr("href") );
		});

		$("*[data-embed]").click(function(){
			embedScript 	= getEmbedScript( $(this).data("embed") );
			$(this).replaceWith(embedScript);
		});

		function getEmbedScript( url ) {
			var service 	= false;

        var services    = {
			gif         : /(https*:\/\/[-\[\]\{\}\(\)\%_\/.a-zA-Z0-9+]+\.(gif))[^< ]*/,
			img         : /(https*:\/\/[-\[\]\{\}\(\)\%_\/.a-zA-Z0-9+]+\.(png|jpg|webp|jpeg|bmp))[^< ]*/,	
            youtube     : /https{0,1}:\/\/w{0,3}\.*youtube\.com\/watch\?\S*v=([A-Za-z0-9_-]+)[^< ]*/,
            vimeo       : /https{0,1}:\/\/w{0,3}\.*vimeo\.com\/([0-9]+)[^< ]*/,
            twitter       : /https{0,1}:\/\/w{0,3}\.*twitter\.com\/i\/status\/([0-9]+)[^< ]*/,
			vine        : /https{0,1}:\/\/w{0,3}\.*vine\.co\/v\/([A-Za-z0-9_-]+)[^< ]*/,
			metacafe       : /https{0,1}:\/\/w{0,3}\.*metacafe\.com\/watch\/([0-9]+)\/([a-z0-9_]+)[^< ]*/,
			instagram       : /https{0,1}:\/\/w{0,3}\.*instagram\.com\/p\/([A-Za-z0-9_-]+)[^< ]*/,
			dailymotion       : /https{0,1}:\/\/w{0,3}\.*dailymotion\.com\/video\/([A-Za-z0-9]+)[^< ]*/,
			mailru       : /https{0,1}:\/\/w{0,3}\.*my.mail.ru\/mail\/([\-\_\/.a-zA-Z0-9]+)[^< ]*/,
			soundcloud       : /https{0,1}:\/\/w{0,3}\.*soundcloud\.com\/([-\%_\/.a-zA-Z0-9]+\/[-\%_\/.a-zA-Z0-9]+)[^< ]*/,
			facebook       : /https{0,1}:\/\/w{0,3}\.*facebook\.com\/video\.php\?\S*v=([A-Za-z0-9_-]+)[^< ]*/,

        }
        var scripts     = {
            gif         : '<img src="{{videoID}}" width="100%" height="auto"></img>',
			img         : '<img src="{{videoID}}" width="100%" height="auto"></img>',			
            youtube     : '<iframe width="100%" height="450" src="https://www.youtube.com/embed/{{videoID}}" frameborder="0" allowfullscreen></iframe>',
            vimeo       : '<iframe src="https://player.vimeo.com/video/{{videoID}}" width="100%" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
            twitter       : '<amp-twitter class="no-bottom" width="100%" height="281" layout="responsive" data-tweetid="{{videoID}}" data-cards="hidden"></amp-twitter>',
            vine        : '<iframe class="qstart_iframe" src="https://vine.co/v/{{videoID}}/embed/simple" width="100%" height="600" frameborder="0"></iframe>',
			metacafe       : '<embed flashVars="playerVars=showStats=no|autoPlay=no" src="https://www.metacafe.com/fplayer/{{videoID}}/{{videoID[1]}}.swf" width="100%" height="337" wmode="transparent" allowFullScreen="true" allowScriptAccess="always" name="Metacafe_$1" pluginspage="https://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>',
			instagram        : '<iframe src="//instagram.com/p/{{videoID}}/embed/" width="100%"height="600" frameborder="0" scrolling="no" allowtransparency="true"></iframe>',
			dailymotion        : '<iframe frameborder="0" width="100%" height="339" src="https://www.dailymotion.com/embed/video/{{videoID}}?wmode=transparent"></iframe>',
			mailru        : '<iframe src="https://videoapi.my.mail.ru/videos/embed/mail/{{videoID}}" width="100%" height="339" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
			soundcloud        : '<iframe width="100%" height="600" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https://soundcloud.com/{{videoID}}&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>',
			facebook        : '<iframe class="fb-video" width="100%" height="450"  data-allowfullscreen="true" src="https://m.facebook.com/video/video.php?v={{videoID}}"></iframe>',
			
        }

			for( var a in services )
			{
				var videoID = url.match( services[a] );
				if( videoID ) return format( scripts[a] , { videoID : videoID[1] } );
			}
		}

		function format (t, d) {
	        return t.replace(/{{([^{}]*)}}/g, function (a, b) {
	            var r = d[b];
	            return typeof r === 'string' || typeof r === 'number' ? r : a;
	        });
		}

		});
		
	})( jQuery );