var ResponsiveVoice = function(){
	
	var self = this;

	
	// Ourn own collection of voices
	var responsivevoices = [
		{name: 'UK English Female', 		voiceIDs: [3,5,1,6,7,8] },	
		{name: 'UK English Male', 			voiceIDs: [0,4,2,6,7,8] },
		{name: 'US English Female',			voiceIDs: [39,40,41,42,43,44] },
		{name: 'Spanish Female',			voiceIDs: [19,16,17,18,20,15] },
		{name: 'French Female',				voiceIDs: [21,22,23,26] },		
		{name: 'Deutsch Female',			voiceIDs: [27,28,29,30,31,32] },		
		{name: 'Italian Female',			voiceIDs: [33,34,35,36,37,38] },				
		{name: 'Hungarian Female',			voiceIDs: [9,10,11] },
		{name: 'Serbian Male',				voiceIDs: [12] },
		{name: 'Croatian Male',				voiceIDs: [13] },
		{name: 'Bosnian Male',				voiceIDs: [14] },
		{name: 'Fallback UK Female',	 	voiceIDs: [8] }		

	];
	
	//All voices available on every system and device
	var voicecollection = [
		{name: 'Google UK English Male'},										//0 male uk android/chrome
		{name: 'Agnes'},														//1 female us safari mac
		{name: 'Daniel Compact'},												//2 male us safari mac
		{name: 'Google UK English Female'},										//3 female uk android/chrome
		{name: 'en-GB', rate: 0.5, pitch: 1}, 									//4 male uk IOS
		{name: 'en-AU', rate: 0.5, pitch: 1},									//5 female english IOS
		{name: 'inglés Reino Unido'},											//6 spanish english android
		{name: 'English United Kingdom'},										//7 english english android
		{name: 'Fallback en-GB Female', lang: 'en-GB', 	fallbackvoice: true},	//8 fallback english female
		{name: 'Eszter Compact'},												//9 Hungarian mac 
		{name: 'hu-HU', rate: 0.4},														//10 Hungarian iOS
		{name: 'Fallback Hungarian', 	lang: 'hu', 	fallbackvoice:true},	//11 Hungarian fallback
		{name: 'Fallback Serbian', 		lang: 'sr', 	fallbackvoice:true},	//12 Serbian fallback
		{name: 'Fallback Croatian',		lang: 'hr', 	fallbackvoice:true},	//13 Croatian fallback		
		{name: 'Fallback Bosnian',		lang: 'bs', 	fallbackvoice:true},	//14 Bosnian fallback	
		
		{name: 'Fallback Spanish',		lang: 'es', 	fallbackvoice:true},	//15 Spanish fallback						
		{name: 'Spanish Spain'},												//16 female es android/chrome		
		{name: 'español España'},												//17 female es android/chrome	
		{name: 'Diego Compact', rate: 0.3},										//18 male es mac
		{name: 'Google Español'},												//19 male es chrome				
		{name: 'es-ES', rate: 0.3},												//20 male es iOS
		
		{name: 'Google Français'},												//21 FR chrome				
		{name: 'French France'},												//22 android/chrome		
		{name: 'francés Francia'},												//23 android/chrome	
		{name: 'Virginie Compact', rate: 0.5},									//24 mac
		{name: 'fr-FR', rate: 0.5},												//25 iOS		
		{name: 'Fallback French',		lang: 'fr', 	fallbackvoice:true},	//26 fallback								

		{name: 'Google Deutsch'},												//27 DE chrome				
		{name: 'German Germany'},												//28 android/chrome		
		{name: 'alemán Alemania'},												//29 android/chrome	
		{name: 'Yannick Compact', rate: 0.5},									//30 mac
		{name: 'de-DE', rate: 0.5},												//31 iOS		
		{name: 'Fallback Deutsch',		lang: 'de', 	fallbackvoice:true},	//32 fallback			
		
		{name: 'Google Italiano'},												//33 DE chrome				
		{name: 'Italian Italy'},												//34 android/chrome		
		{name: 'italiano Italia'},												//35 android/chrome	
		{name: 'Paolo Compact', rate: 0.5},										//36 mac
		{name: 'it-IT', rate: 0.5},												//37 iOS		
		{name: 'Fallback Italian',		lang: 'it', 	fallbackvoice:true},	//38 fallback								
		
		{name: 'Google US English'},											//39 DE chrome				
		{name: 'English United States'},										//40 android/chrome		
		{name: 'inglés Estados Unidos'},										//41 android/chrome	
		{name: 'Vicki'},														//42 mac
		{name: 'en-US', rate: 0.5},												//43 iOS		
		{name: 'Fallback English',		lang: 'en-US', 	fallbackvoice:true},	//44 fallback										
		
	];	
	
	var systemvoices;
	
	var CHARACTER_LIMIT = 100;
	var VOICESUPPORT_ATTEMPTLIMIT = 5;
	var voicesupport_attempts = 0;
	var fallbackMode = false;
	
	this.fallback_playing = false;
	this.fallback_parts = null;
	this.fallback_part_index = 0;
	this.fallback_audio = null;
	
	//Wait until system voices are ready and trigger the event OnVoiceReady
	if (typeof speechSynthesis != 'undefined') {
		speechSynthesis.onvoiceschanged = function() {
	
			systemvoices = window.speechSynthesis.getVoices();
	
			if (self.OnVoiceReady!=null) {
				self.OnVoiceReady.call();	
			}
		};
	}
	
	this.default_rv = responsivevoices[0];
	
	
	
	this.OnVoiceReady = null;
	
	
	//We should use jQuery if it's available
	if (typeof $ === 'undefined') {
		document.addEventListener('DOMContentLoaded',function(){
			init();
		});
	}else{
		
		$(document).ready(function() {
			init();
		});	
	}
	
	function init() {

		
		if (typeof speechSynthesis === 'undefined') {

			enableFallbackMode();
			
		} else {


			//Waiting a few ms before calling getVoices() fixes some issues with safari on IOS as well as Chrome
			setTimeout(function(){
				var gsvinterval = setInterval(function() {
		
					var v = window.speechSynthesis.getVoices();
					
					if (v.length==0 && (systemvoices==null || systemvoices.length==0)) {
						console.log('Voice support NOT ready');
						
						voicesupport_attempts++;
						if (voicesupport_attempts > VOICESUPPORT_ATTEMPTLIMIT) {
							//We don't support voices. Using fallback
							
							clearInterval(gsvinterval);
							
							enableFallbackMode();
						}
			
					}else{
	
						console.log('Voice support ready');
						
						clearInterval(gsvinterval);
						
						systemvoices = v;
						
						mapRVs();
						
						if (self.OnVoiceReady!=null)
							self.OnVoiceReady.call();				
			
					}		
					
				},100);	
			},100);
		}
	}
	
	function enableFallbackMode() {

		fallbackMode = true;
		console.log('Voice not supported. Using fallback mode');

		mapRVs();

		if (self.OnVoiceReady!=null)
			self.OnVoiceReady.call();
		
		
	}
	
	
	this.getVoices = function() {
	
		//Create voices array
		
		var v = [];
		
		for (var i=0; i<responsivevoices.length; i++) {
			v.push( { name: responsivevoices[i].name });
		}
		
		return v;
		
	}
	
	
	this.speak = function(text, voicename) {
		
		
		//Support for multipart text (there is a limit on characters)
		var multipartText = [];
		
		if (text.length>CHARACTER_LIMIT) {
			
			var tmptxt = text;
			
			while(tmptxt.length>CHARACTER_LIMIT) {
			
				//Split by common phrase delimiters
				var p = tmptxt.search(/[:!?.;]+/);
				var part = '';
				
				//Coludn't split by priority characters, try commas
				if (p==-1 || p>=CHARACTER_LIMIT ) {
					p = tmptxt.search(/[,]+/);
				}

				//Couldn't split by normal characters, then we use spaces
				if (p==-1 || p>=CHARACTER_LIMIT) {

					var words = tmptxt.split(' ');

					for (var i=0; i<words.length; i++) {
						
						if (part.length + words[i].length +1 >CHARACTER_LIMIT)
							break;
							
						part += (i!=0?' ':'') + words[i];

					}
					
				} else {
				
					part = tmptxt.substr(0, p+1);
					
				}
				
				tmptxt = tmptxt.substr(part.length, tmptxt.length-part.length);			

				multipartText.push(part);
				//console.log(part.length + " - " + part);
					
			}
			
			//Add the remaining text
			if (tmptxt.length>0) {
				multipartText.push(tmptxt);	
			}
		
		}else{
			
			//Small text
			multipartText.push(text);	
		}
		
		
		//Find system voice that matches voice name
		var rv;
		
		if (voicename==null) {
			rv = self.default_rv;
		}else{
			rv = getResponsiveVoice(voicename);
		}
		
		var profile = {};



		
		//Map was done so no need to look for the mapped voice
		if (rv.mappedProfile!=null) {
			
			profile = rv.mappedProfile;
			
		}else{
			
			profile.systemvoice = getMatchedVoice(rv);
			profile.collectionvoice = {};
			
			if (profile.systemvoice==null) {
				console.log('ERROR: No voice found for: ' + voicename);
				return;
			}
		}
	
	
		if (profile.collectionvoice.fallbackvoice==true) {
			fallbackMode = true;
			self.fallback_parts = [];
		}else{
			fallbackMode = false;	
		}
	
	
		//Play multipart text
		for (var i=0; i<multipartText.length; i++) {
			
			if (!fallbackMode) {
				//Use SpeechSynthesis
				
				//Create msg object
				var msg = new SpeechSynthesisUtterance();
				msg.voice = profile.systemvoice;
				msg.voiceURI = profile.systemvoice.voiceURI;
				msg.volume = profile.collectionvoice.volume || profile.systemvoice.volume || 1; // 0 to 1
				msg.rate = profile.collectionvoice.rate || profile.systemvoice.rate || 1; // 0.1 to 10
				msg.pitch = profile.collectionvoice.pitch || profile.systemvoice.pitch || 1; //0 to 2*/
				msg.text = multipartText[i];
				msg.lang = profile.collectionvoice.lang || profile.systemvoice.lang;
				msg.onend = self.OnFinishedPlaying;
				msg.onerror = function(e){
					console.log('Error');
					console.log(e);
				};
				//console.log(msg);				
	
				speechSynthesis.speak(msg);			
				
			}else{

				var url = 'http://www.corsproxy.com/translate.google.com/translate_tts?ie=UTF-8&q=' + multipartText[i] + '&tl=' + profile.collectionvoice.lang || profile.systemvoice.lang || 'en-US';
				
				var audio = new Audio(url);
				audio.playbackRate = 1;
				audio.preload = 'auto';
				audio.volume = profile.collectionvoice.volume || profile.systemvoice.volume || 1; // 0 to 1;
				self.fallback_parts.push(audio);
				
				
				
			}
					

		}
		
		if (fallbackMode)
			self.fallback_startPlaying();
		
	}
	
	this.fallback_startPlaying = function() {
		
		//console.log('start playing');
		
		self.fallback_part_index = 0;
		
		//console.log(self.fallback_parts);
		
		self.fallback_finishedplaying();
		
		
		
	}
	
	this.fallback_finishedplaying = function(e) {
		//console.log('chunk ended');
		self.fallback_audio = self.fallback_parts[self.fallback_part_index];
		//console.log(self.fallback_audio);
		
		
		
		//self.fallback_audio.addEventListener('error', function(e){ console.log('error'); console.log(e)});
		//self.fallback_audio.addEventListener('progress', function(e){ console.log('progress'); this.play();});
		//self.fallback_audio.addEventListener('loadstart', function(e){ console.log('loadstart'); console.log(e)});		
		//self.fallback_audio.load();
		self.fallback_audio.play();
		
		//audio.addEventListener('play', utterance.onstart);	
				
		self.fallback_part_index ++;
		
		if (self.fallback_part_index < self.fallback_parts.length) {
			
			self.fallback_audio.addEventListener('ended', self.fallback_finishedplaying);
		}
		
	}
	
	this.cancel = function() {
		
		if (fallbackMode)
			self.fallback_audio.pause();
		else
			speechSynthesis.cancel();	
	}
	
	
	this.voiceSupport = function() {
	
		return ('speechSynthesis' in window);
		
	}
	
	this.OnFinishedPlaying = function(event) {
		
	}
	
	//Set default voice to use when no voice name is supplied to speak()
	this.setDefaultVoice = function(voicename) {
		
		var vr = getResponsiveVoice(voicename);
		
		if (vr!=null) {
			self.default_vr = vr;	
		}
		
	}
	
	//Map responsivevoices to system voices
	function mapRVs() {

		for (var i=0; i<responsivevoices.length; i++) {
			
			var rv = responsivevoices[i];
			
			for (var j=0; j<rv.voiceIDs.length; j++) {
				
				var vcoll = voicecollection[rv.voiceIDs[j]];
				
				if (vcoll.fallbackvoice != true) {		// vcoll.fallbackvoice would be null instead of false
					
					// Look on system voices
					var v = getSystemVoice(vcoll.name);
					if (v!=null) {
						rv.mappedProfile = {
							systemvoice: v,
							collectionvoice: vcoll
						};
						console.log("Mapped " + rv.name + " to " + v.name);
						break;
					}
					
				}else {
					
					//Pick the fallback voice
					rv.mappedProfile = {
						systemvoice: {},
						collectionvoice: vcoll
					};
					console.log("Mapped " + rv.name + " to " + vcoll.lang + " fallback voice");					
					break;
				
				}
			}
		}		

		
	}
	
	
	//Look for the voice in the system that matches the one in our collection
	function getMatchedVoice(rv) {
	
		for (var i=0; i<rv.voiceIDs.length; i++) {
			var v = getSystemVoice(voicecollection[rv.voiceIDs[i]].name);
			if (v!=null)
				return v;	
		}
		
		return null;
		
	}
	
	function getSystemVoice(name) {
		
		if (typeof systemvoices === 'undefined') return null;
		
		for (var i=0; i<systemvoices.length; i++) {
			if (systemvoices[i].name == name)
				return systemvoices[i];
		}
		
		return null;
		
	}
	
	function getResponsiveVoice(name) {

		for (var i=0; i<responsivevoices.length; i++) {
			if (responsivevoices[i].name == name) {
				return responsivevoices[i];
			}
		}
		
		return null;
		
	}
	
}
var responsiveVoice = new ResponsiveVoice();