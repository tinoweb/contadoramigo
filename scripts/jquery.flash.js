/*
 * Flash v1.2.0
 * Release Date: October 3, 2009
 * 
 * Copyright (c) 2009 Stephen Belanger
 *
 * Dual licensed under the MIT and GPL licenses.
 * http://docs.jquery.com/License
 */
if(jQuery)(
	function(jQuery){
		jQuery.fn.extend({
			// Create flash object
			flash:function(options) {
				var has;
				var cv;
				var ie;
				
				// Create attr/param strings
				function attr(name,value) { return ' '+name+'="'+value+'"'; }
				function param(name,value) { return '<param name="'+name+'" value="'+value+'" />'; }
				
				// get flash version
				if (navigator.plugins && navigator.plugins.length) {
					var plugin = navigator.plugins['Shockwave Flash'];
					if (plugin) {
						has = true;
						if (plugin.description)
							cv = plugin.description.replace(/([a-zA-Z]|\s)+/, "").replace(/(\s+r|\s+b[0-9]+)/, ".").split(".");
					}
					if (navigator.plugins['Shockwave Flash 2.0']) {
						has = true;
						cv = '2.0.0.11';
					}
				} else { // if IE
					try{
						var axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
					}catch(e){
						try {
							// Flash 6 was lame and didn't support GetVariable properly. >.>
							var axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
							cv = [6,0,21]; has = true;
						} catch(e) {};
						try {
							axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
						} catch(e) {};
					}
					if (axo != null) {
						cv = axo.GetVariable("$version").split(" ")[1].split(",");
						has = true;
						ie = true;
					}
				}
				
				// Loop through all supplied elements (multi-object support--yay!)
				jQuery(this).each(function(){
					// Merge supplied settings with defaults
					s = jQuery.extend({
						id		: jQuery(this).attr('id'), // ID
						src		: null, // Path to swf
						width	: null, // Width
						height	: null, // Height
						vars	: null, // Additional variables to pass to Flash
						color	: null, // Background color
						quality	: null, // Quality
						wmode	: null, // Window mode
						access	: null, // Set to "always" to allow script access across domains
						express	: null, // Path to express install swf
						classid	: 'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000', // For IE support.
						codebase: 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=', // Ditto.
						plugin	: 'http://get.adobe.com/flashplayer', // Download Firefox plugin if missing
						mime	: 'application/x-shockwave-flash', // Tells the browser what kind of object it is
						version	: '9.0.24' // Minimum Flash version
					}, options);
					
					// Only swap if user has flash installed.
					if(has) {
						// Parse required version
						var rv = s.version.split('.');
						
						// If less than minimum version and express install swf is set; replace src swf
						if (s.express) {
							for (var i in cv) {
								if ( parseInt(cv[i]) > parseInt(rv[i]) ) {
									break;
								}
								if ( parseInt(cv[i]) < parseInt(rv[i]) ) {
									s.src = s.express;
								}
							}
						}
						
						// Convert Flash variables to parameter string
						s.vars = s.vars ? unescape(jQuery.param(s.vars)) : null;
						
						// Open Object
						var o = '<object';
						if(ie) {
							o += s.classid	? attr('classid', s.classid)	: '';
							o += s.codebase	? attr('codebase', s.codebase+rv.join(','))	: '';
						} else {
							o += s.plugin	? attr('pluginspage', s.plugin)	: '';
						}
						o += s.id		? attr('id', s.id)			: '';
						o += s.src		? attr('data', s.src)		: '';
						o += s.mime		? attr('type', s.mime)		: '';
						o += s.width	? attr('width', s.width)	: jQuery(this).attr('width');
						o += s.height	? attr('height', s.height)	: jQuery(this).attr('height');
						o += '>';
						
						// Add param elements
						o += s.src		? param('movie', s.src)					: '';
						o += s.color	? param('bgcolor', s.color)				: '';
						o += s.quality	? param('quality', s.quality)			: '';
						o += s.access	? param('allowscriptaccess', s.access)	: '';
						o += s.vars		? param('flashvars', s.vars)			: '';
						o += s.wmode	? param('wmode', s.wmode)				: '';
						
						// Close Object
						o += '</object>';
						
						// Replace
						jQuery(this).replaceWith(o);
					}
					
					return this;
				});
			}
		})
	}
)(jQuery);