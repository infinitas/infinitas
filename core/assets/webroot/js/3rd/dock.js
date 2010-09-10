/** @preserve jquery.jqDock.js v1.5
 */
/*
 * jqDock jQuery plugin
 * Version : 1.5
 * Author : Roger Barrett
 * Date : April 2010
 *
 * Inspired by:
 *   iconDock jQuery plugin
 *   http://icon.cat/software/iconDock
 *   version: 0.8 beta
 *   date: 2/05/2007
 *   Copyright (c) 2007 Isaac Roca & icon.cat (iroca@icon.cat)
 *   Dual licensed under the MIT-LICENSE.txt and GPL-LICENSE.txt
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Dual licensed under the MIT-LICENSE.txt and GPL-LICENSE.txt
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Change Log :
 * v1.5
 *    - bugfix : the label click handler was not returning false, so clicks on labels were being notified to links (not images) twice
 *    - new option, setLabel (default false), as a function called when initialising the label contents for each menu item
 *    - added an extra layer - div.jqDockLabelText - inside div.jqDockLabel to facilitate positional 'tweaking' of the label without having to resort to the setLabel option
 *    - new option, flow (default false), allowing the auto-centering to be disabled and the dock wrapper element to auto-size to precisely contain the dock
 *    - new option, idle (default 0), as the number of milliseconds of idle time after the mouse has left the menu before the dock goes to sleep and the docksleep event is triggered (on the original menu element)
 *    - new option, onSleep, as a function which is called with scope (this) of the original menu element when an optional number of milliseconds (the idle option) has elapsed since the mouse left the menu; returning false will prevent the dock from going to sleep
 *    - new option, onWake, as a function which is called with scope (this) of the original menu element when dock is 'nudged' awake, but only if dock was asleep at the time; returning false will prevent the dock waking up (stays asleep)
 *    - new option, onReady, as a function which is called with scope (this) of the orginal menu element when dock has been initialised and is ready for display; returning false will prevent the dock being displayed
 *    - new custom event, dockshow, which is triggered on the original menu element when the dock has been completely initialised; this won't be triggered if the onReady() call returns false
 *    - new custom event, docksleep, which is triggered on the original menu element following the onSleep() call, unless the onSleep() call returns false
 *    - new custom event, dockwake, which is triggered on the original menu element following the onWake() call, unless the onWake() call returns false
 *    - added listener for custom event - docknudge - on the original menu element, which *has* to be triggered by the calling program in order to (try to) wake the dock from a sleep
 *    - added listener for custom event - dockidle - on the original menu element, which can be triggered by the calling program to (try to) put the dock to sleep
 *    - added 2 commands to jqDock() function - jqDock('nudge') and jqDock('idle') - which do the same thing as triggering the respective docknudge and dockidle events (but synchronously)
 *    - jqDock no longer hides the original menu element, since most likely usage is to pre-hide it to prevent 'flicker'; also now copes with visibility:hidden (as well as display:none)
 *    - labels no longer get jqDockMouseN class
 * v1.4
 *    - bugfix : in IE8, non-statically positioned child elements do not inherit opacity, so fadeIn did not work correctly
 *    - new option, fadeLayer (default ''), allows the fade-in to be switched from the original menu element down to either the
 *      div.jqDockWrap or div.jqDock layer
 * v1.3
 *    - new option, inactivity (default 0), allowing auto-collapse after a specified period (mouse on dock)
 *    - new option, fadeIn (default 0), allowing initialised menu to be faded in over a specified period (as opposed to an instant show)
 *    - new option, step (default 50), which is the interval between animation steps
 *    - default size increased to 48 (from 36)
 *    - default distance increased to 72 (from 54)
 *    - default duration reduced to 300 ms (from 500 ms)
 *    - better 'best guess' for maximum dimensions of Dock
 *    - handle integer options being passed in as strings (eg. size:'48' instead of size:48)
 *    - the wrapper div now has width, height, and a class
 *    - all menu items are double-wrapped now in 2 divs 
 *    - double-wrap resolves ie8 horizontal float problem
 *    - dimensioning switched from image to innermost of the item's double-wrap
 *    - labels now assigned per menu item instead of one for the entire dock
 *    - labels within anchors so clicking activates anchor
 *    - labels are always created, regardless of option setting
 *    - default label position changed from 'tc' to 'tl' for any alignment except 'top' (labels='br') and 'left' (labels='tr')
 *    - events switched from mouseover/out to mouseenter/leave
 * v1.2
 *    - Fixes for Opera v9.5 - many thanks to Rubel Mujica
 * v1.1
 *    - some speed optimisation within the functions called by the event handler
 *    - added positioning of labels (top/middle/bottom and left/center/right)
 *    - added click handler to label (triggers click event on related image)
 *    - added jqDockLabel(Link|Image) class to label, depending on type of current image
 *    - updated demo and documentation for label positioning and clicking on labels
 */
(function($, window){
if(!$.jqDock){ //can't see why it should be, but it doesn't hurt to check
	var TRBL = ['Top', 'Right', 'Bottom', 'Left']
		, AXES = ['Major', 'Minor']
		, MOUSEEVENTS = ['mouseenter','mousemove','mouseleave']
		, VANILLA = [
				'<div style="position:relative;padding:0;'
			, 'margin:0;border:0 none;background-color:transparent;'
			, '">'
			]
		, VERTHORZ = { //note : lead and trail are indexes into TRBL
				v: { wh:'height', xy:1, tl:'top', lead:0, trail:2, inv:'h' } //Opts.align = left/center/right
			, h: { wh:'width', xy:0, tl:'left', lead:3, trail:1, inv:'v' } //Opts.align = top/middle/bottom
			}
		, DOCKS = []
		, XY = [0, 0] //mouse position from left, mouse position from top
		, EMPTYFUNC = function(){}
/** tests to see if an image has an alt attribute that looks like an image path, returning it if found, else false
 * @private
 * @param {element} el Image element
 * @return {string|boolean} Image path or false
 */
		, ALT_IMAGE = function(el){
				var alt = $(el).attr('alt');
				return (alt && (/\.(gif|jpg|jpeg|png)$/i).test(alt)) ? alt : false;
			}
/** returns integer numeric of leading digits in string argument
 * @private
 * @param {string} x String representation of an integer
 * @return {integer} Number
 */
		, AS_INTEGER = function(x){
				var r = parseInt(x, 10);
				return isNaN(r) ? 0 : r;
			}
/** clears a specified timeout timer
 * @private
 * @param {Dock} Dock Dock object
 * @param {string} x Timer to clear
 */
		, CLEAR_TIMER = function(Dock, x){
				if(Dock[x]){
					window.clearTimeout(Dock[x]);
					Dock[x] = null;
				}
			}
/** translates (without affecting) XY[0] or XY[1] into an offset within div.jqDock
 * note: doing it this way means that all attenuation is against the initial (shrunken) image positions,
 * but it saves having to find every image's offset() each time the cursor moves or an image changes size!
 * @private
 * @param {Dock} Dock Dock object
 * @return {integer} Translated mouse offset
 */
		, DELTA_XY = function(Dock){
				var VH = VERTHORZ[Dock.Opts.vh] //convenience
					, rtn = 0
					, el = Dock.Elem[Dock.Current]
					, p;
				if(el){
					p = el.Pad[VH.lead] + el.Pad[VH.trail]; //element's user-specified padding
					//get the difference between the cursor position and the leading edge of the current element's outer wrapper,
					//multiply by the full/shrunken ratio, and add the element's pre-calculated offset within div.jqDock...
					rtn = Math.floor((XY[VH.xy] - el.Wrap.parent().offset()[VH.tl]) * (p + el.Initial) / (p + el.Major)) + el.Offset;
				}
				return rtn;
			}
/** returns a dock index as indicated by the numeric suffix to the element's id attribute
 * @private
 * @param {element} el Element to test
 * @return {integer} Dock index, -1 if not found
 */
		, DOCK_INDEX_FROM_ID = function(el){
				return el ? 1 * ( (el.id || '').match(/^jqDock(\d+)$/) || [0,-1] )[1] : -1;
			}
/** finds a given IMG's entry within the Elem arrays, across all Docks
 * @private
 * @param {element} el Element to search for
 * @return {object|boolean} False if not found
 */
		, FIND_IMAGE = function(el){
				var cont = true
					, id = DOCKS.length
					, idx;
				while(cont && id--){
					idx = DOCKS[id].Elem.length;
					while(cont && idx--){
						cont = DOCKS[id].Elem[idx].Img[0] !== el;
					}
				}
				return cont ? !cont : DOCKS[id].Elem[idx];
			}
/** the onload handler for images; stores width/height, and runs initDock() (on a timeout) if all images for a dock are loaded
 * @private
 * @this {element} The image element
 * @param {object} ev jQuery event object
 */
		, IMAGE_ONLOAD = function(ev){
				//store 'large' width and height...
				var dock = DOCKS[ev.data.id]
					, el = dock.Elem[ev.data.idx];
				el.height = this.height;
				el.width = this.width;
				if(++dock.Loaded >= dock.Elem.length){ //check to see if all images are loaded...
					window.setTimeout(function(){ $.jqDock.initDock(ev.data.id); }, 0);
				}
			}
/** returns an item index as indicated by the numeric suffix to the closest jqDockMouse-classed element
 * @private
 * @param {element} el Element to start from
 * @param {element} context Element that the provider of the item index must be within
 * @return {integer} Item index, -1 if not found
 */
		, ITEM_INDEX_FROM_CLASS = function(el, context){
				var m;
				while(el && el.ownerDocument && el !== context){
					m = el.className.toString().match(/jqDockMouse(\d+)/);
					if(m){
						return 1 * m[1];
					}
					el = el.parentNode;
				}
				return -1;
			}
/** returns an object containing width and height, with the one NOT represented by 'dim'
 * being calculated proportionately
 * if horizontal menu then attenuation is along horizontal (x) axis, thereby setting the new
 * dimension for width, so the one to keep in proportion is height; and vice versa for
 * vertical menus, obviously!
 * @private
 * @param {object} el Element of Elem array
 * @param {integer} dim Image dimension
 * @param {string} vh Vertical or horizontal
 * @return {object} The provided dimension and the proportioned dimension (width and height, but not necessarily respectively!)
 */
		, KEEP_PROPORTION = function(el, dim, vh){
				var r = {}
					, vhwh = VERTHORZ[vh].wh //convenience
					, invwh = VERTHORZ[VERTHORZ[vh].inv].wh //convenience
					;
				r[vhwh] = dim;
				r[invwh] = Math.round(dim * el[invwh] / el[vhwh]);
				return r;
			}
/** a label click handler that triggers its related image's click handler
 * @private
 * @this {element} The DOM element (label) the handler was bound to
 */
		, LABEL_CLICK = function(){
				$(this).prev('img').trigger('click');
				return false;
			}
/** shows/hides a label
 * @private
 * @param {object} Dock Dock object
 * @param {integer} [show] Show label
 */
		, LABEL_SHOW = function(Dock, show){
				var item = Dock.Elem[Dock.Current];
				if(item && Dock.Opts.labels){
					item.Label.el[item.Label.txt && show ? 'show' : 'hide']();
				}
			}
/** re-positions a label if needed
 *  only labels with middle and/or center alignment need re-positioning because css handles the corners
 * @private
 * @param {object} Dock Dock object
 * @param {integer} show Whether to show the label or not (from Dock.Xpand, if supplied)
 */
		, POSITION_LABEL = function(Dock, show){
				var labels = Dock.Opts.labels
					, VH = VERTHORZ[Dock.Opts.vh]
					, el = Dock.Elem[Dock.Current]
					, i, j, label, labelElem;
				if(el && labels){
					label = el.Label;
					labelElem = label.el;
					//check to see if the information required for a middle/centred label has already been gathered...
					//note : middle/centred labels can not be set up while the dock is display:none
					if(label.mc){
						label.mc = 0;
						//if labels are being aligned middle and/or centre then we need to find any user-styled padding and width/height, and
						//store the overall dimensions (incl. padding) for this image's label, so that we don't need to do this next time...
						for(i in VERTHORZ){
							label[i] = labelElem[VERTHORZ[i].wh]();
							for(j in {lead:1, trail:1}){
								label[i] += AS_INTEGER(labelElem.css('padding' + TRBL[VERTHORZ[i][j]]));
							}
						}
					}
					//note: if vertically or horizontally centred then centre is based on the IMAGE only
					//note : .xy is 0 on horizontal menus, 1 on vertical menus (and vice versa for [.inv].xy!)...
					if(labels.charAt(0) == 'm'){
						labelElem.css({top: Math.floor((el[AXES[VERTHORZ[VH.inv].xy]] - label.v) / 2)});
					}
					if(labels.charAt(1) == 'c'){
						labelElem.css({left: Math.floor((el[AXES[VH.xy]] - label.h) / 2)});
					}
				}
				if(show){
					LABEL_SHOW(Dock, 1); //show
				}
			}
/** removes ALL text nodes from the menu, so that we don't get spacing issues between menu elements
 * @private
 * @param {element} el DOM Element
 * @recursive
 */
		, REMOVE_TEXT = function(el){
				var i = el.childNodes.length, j;
				while(i){
					j = el.childNodes[--i];
					if(j.childNodes && j.childNodes.length){
						REMOVE_TEXT(j);
					}else if(j.nodeType == 3){
						el.removeChild(j);
					}
				}
			}
/** initial display of the menu, copes with visibility:hidden as well as display:none
 * @private
 * @param {object} Dock Dock object
 */
		, REVEAL_MENU = function(Dock){
				Dock.Menu.css({visibility:'visible'}).show();
			}
/** if appropriate, sets an idle timer to trigger a dockidle
 * @private
 * @param {object} Dock Dock object
 */
		, SET_IDLER = function(Dock){
				var idleDelay = Dock.Opts.idle;
				if(idleDelay){
					CLEAR_TIMER(Dock, 'Idler');
					Dock.Idler = window.setTimeout(function(){
							Dock.Menu.trigger('dockidle');
						}
						, idleDelay);
				}
			}
/** create and append the label; unless the label uses middle/center alignment, this is all the label setup required
 *  any label setting involving middle/center gets handled in POSITION_LABEL()
 * @private
 * @param {Dock} Dock Dock object
 * @param {object} item Menu item object
 * @param {integer} indx Index of menu item within menu
 */
		, SET_LABEL = function(Dock, item, indx){
				var op = Dock.Opts //convenience
					, labels = op.labels //convenience
					, label = item.Label //convenience
					, posBottom, posRight
					;
				label.txt = op.setLabel.call(Dock.Menu[0], item.Title, indx);
				label.mc = label.mc && !!label.txt;
				//labels always get created, but will only be shown if they have content (and labels are enabled!)
				label.el = $('<div class="jqDockLabel jqDockLabel' + item.Link + 
						'" style="position:absolute;margin:0;"><div class="jqDockLabelText">' + label.txt + '</div></div>')
					.hide().insertAfter(item.Img); //insert after the image element
				if(labels && label.txt){
					posBottom = labels.charAt(0) == 'b';
					posRight = labels.charAt(1) == 'r';
					//position the label and give a click handler...
					label.el.css({
							top:    posBottom ? 'auto' : 0
						, left:   posRight  ? 'auto' : 0
						, bottom: posBottom ? 0 : 'auto'
						, right:  posRight  ? 0 : 'auto'
						}).click(LABEL_CLICK); //NB: the click handler returns false!
				}
			}
/** calculates the image sizes according to the current (translated) position of the cursor within div.jqDock
 * result stored in Final for each menu element
 * @private
 * @param {integer} id Dock index
 * @param {integer} [mxy] Translated cursor offset in main axis
 */
		, SET_SIZES = function(id, mxy){
				var Dock = DOCKS[id] //convenience
					, op = Dock.Opts //convenience
					, wh = VERTHORZ[op.vh].wh //convenience
					, i = Dock.Elem.length
					, el, ab;
				 //if not forced, use current translated cursor position (main axis)...
				mxy = mxy || DELTA_XY(Dock);
				while(i){
					el = Dock.Elem[--i];
					ab = Math.abs(mxy - el.Centre);
					//if we're smack on or beyond the attenuation distance then set to the min dim
					//ensure Final ends up as an integer to avoid 'flutter'
					el.Final = ab < op.distance 
						? el[wh] - Math.floor((el[wh] - el.Initial) * Math.pow(ab, op.coefficient) / op.attenuation) 
						: el.Initial;
				}
			}
/** dummy function, simply returns labelText (for when options.setLabel is not provided)
 * @private
 * @this {element} original menu element
 * @param {string} labelText Current label text for menu option
 * @param {integer} indx Index of the menu option within the menu
 * @return {string} labelText
 */
		, TRANSFORM_LABEL = function(labelText, indx){
				return labelText;
			}
/** sets the css for an individual image wrapper to effect its change in size
 * 'dim' is the new value for the main axis dimension as specified in VERTHORZ[Opts.vh].wh, so
 * the margin needs to be applied to the inverse dimension!
 * note: 'force' is only set when called from initDock() to do the initial shrink
 * @private
 * @param {integer} id Dock index
 * @param {integer} idx Image index
 * @param {integer} dim Main axis dimension of image
 * @param {boolean} force Force change even if no size difference
 */
		, CHANGE_SIZE = function(id, idx, dim, force){
				var Dock = DOCKS[id] //convenience
					, el = Dock.Elem[idx] //convenience
					, op = Dock.Opts //convenience
					, yard = Dock.Yard //convenience
					, VH = VERTHORZ[op.vh] //convenience
					, invVH = VERTHORZ[VH.inv] //convenience
					, srcDiff = el.src != el.altsrc
					, bdr, css, diff
					;
				if(force || el.Major != dim){
					//horizontal menus in IE quirks mode require border widths (if any) of the Dock to be added to the Dock's main axis dimension...
					bdr = ($.boxModel || op.vh == 'v') ? 0 : Dock.Border[VH.lead] + Dock.Border[VH.trail];
					//switch image source to large, if (a) it's different to small source, and (b) this is the first step of an expansion...
					if(srcDiff && !force && el.Major == el.Initial){
						el.Img[0].src = el.altsrc;
					}
					Dock.Spread += dim - el.Major; //adjust main axis dimension of dock
					css = KEEP_PROPORTION(el, dim, op.vh);
					diff = op.size - css[invVH.wh];
					//add minor axis margins according to alignment...
					//note: where diff is an odd number of pixels, for 'middle' or 'center' alignment put the odd pixel in the 'lead' margin
					switch(op.align){
						case 'top': case 'left': //set bottom/right margin
							css['margin' + TRBL[invVH.trail]] = diff;
							break;
						case 'middle': case 'center' : //set top/left and bottom/right margins
							css['margin' + TRBL[invVH.lead]] = (diff + (diff % 2)) / 2;
							css['margin' + TRBL[invVH.trail]] = (diff - (diff % 2)) / 2;
							break;
						default: // = case 'bottom': case 'right' : //set top/left margin
							css['margin' + TRBL[invVH.lead]] = diff;
					}
					//set dock's main axis dimension (if it's changed, or if force and this is first menu item)...
					if (dim != el.Major || (force && !idx)) {
						if(op.flow){
							//if we ARE running flow, then the wrapper dimensions must be set so as to precisely contain the dock...
							yard.parent()[VH.wh](Dock.Spread + Dock.Border[VH.lead] + Dock.Border[VH.trail]);
						}
						yard[VH.wh](Dock.Spread + bdr);
					}
					//change image wrapper size and margins...
					el.Wrap.css(css);
					//set dock's main axis 'lead' offset (not negative!)...
					if(!op.flow){
						//if we are NOT running flow (which is the default) then the dock needs to be centered within its wrapper...
						yard.css(VH.tl, Math.floor(Math.max(0, (Dock[VH.wh] - Dock.Spread) / 2)));
					}
					//reposition the label if need be...
					if(Dock.OnDock){
						POSITION_LABEL(Dock);
					}
					//store new dimensions...
					el.Major = dim; //main axis
					el.Minor = css[invVH.wh]; //minor axis
					//switch image source to small, if (a) it's different to large source, and (b) this was the last step of a shrink...
					if(srcDiff && !force && dim == el.Initial){
						el.Img[0].src = el.src;
					}
					css = null;
				}
			}
/** modifies the target sizes in proportion to 'duration' if still within the 'duration' period following a mouseenter
 * calls CHANGE_SIZE() for each menu element (if more than Opts.step ms since mouseenter)
 * @private
 * @param {integer} id Dock index
 * @param {boolean} revers For shrinking (from OFF_DOCK())
 */
		, FACTOR_SIZES = function(id, revers){
				var Dock = DOCKS[id] //convenience
					, op = Dock.Opts //convenience
					, VH = VERTHORZ[op.vh]
					, lapse = op.duration + op.step
					, i = 0 //must go through the elements in logical order
					, factor, el, sz;
				if(Dock.Stamp){
					lapse = (new Date()).getTime() - Dock.Stamp;
					//there's no point continually checking Date once op.duration has passed...
					if(lapse >= op.duration){
						Dock.Stamp = 0;
					}
				}
				if(lapse > op.step){ //only if more than Opts.step ms have passed since last mouseenter/leave
					factor = lapse < op.duration ? lapse / op.duration : 0;
					while(i < Dock.Elem.length){
						el = Dock.Elem[i];
						sz = (el.Final - el.Initial) * factor;
						if(revers){ //revers is for shrinking, where we're going from Final->Initial instead of Initial->Final
							sz = factor ? Math.floor(el.Final - sz) : el.Initial;
						}else{
							sz = factor ? Math.floor(el.Initial + sz) : el.Final;
						}
						CHANGE_SIZE(id, i++, sz);
					}
					//tweak 'best guess':
					//having changed all item sizes within the dock, if Spread is greater than main axis dimension, adjust wrap dimension...
					if(Dock.Spread > Dock[VH.wh]){
						Dock.Yard.parent()[VH.wh](Dock.Spread + Dock.Border[VH.lead] + Dock.Border[VH.trail]);
						Dock[VH.wh] = Dock.Spread;
					}
				}
			}
/** called when cursor goes outside menu, and checks for completed shrinking of all menu elements
 * calls FACTOR_SIZES() (with revers set) on any menu element that has not finished shrinking
 * calls itself on a timer to complete the shrinkage
 * @private
 * @param {integer} id Dock index
 * @param {boolean} noIdle Can idler be set
 */
		, OFF_DOCK = function(id, noIdle){
				var Dock = DOCKS[id] //convenience
					, el = Dock.Elem
					, i = el.length
					;
				if(!Dock.OnDock){
					while((i--) && el[i].Major <= el[i].Initial){}
					//this is here for no other reason than that early versions of Opera seem to leave 
					//a 'shadow' residue of the expanded image unless/until Delta is recalculated!...
					DELTA_XY(Dock);
					if(i < 0){ //complete
						//reset everything back to 'at rest' state...
						i = el.length;
						while(i--){
							el[i].Major = el[i].Final = el[i].Initial;
						}
						Dock.Current = -1;
						if(!noIdle){
							SET_IDLER(Dock);
						}
					}else{
						FACTOR_SIZES(id, true); //set revers
						window.setTimeout(function(){ OFF_DOCK(id, noIdle); }, Dock.Opts.step);
					}
				}
			}
/** checks for completed expansion (if OnDock)
 * if not completed, runs SET_SIZES(), FACTOR_SIZES(), and then itself on a timer
 * @private
 * @param {integer} id Dock index
 */
		, OVER_DOCK = function(id){
				var Dock = DOCKS[id] //convenience
					, el = Dock.Elem
					, i = el.length;
				if(Dock.OnDock){
					while((i--) && el[i].Major >= el[i].Final){}
					if(i < 0){ //complete
						Dock.Xpand = 1;
						LABEL_SHOW(Dock, 1); //show
					}else{
						SET_SIZES(id);
						FACTOR_SIZES(id);
						window.setTimeout(function(){ OVER_DOCK(id); }, Dock.Opts.step);
					}
				}
			}
/** actions for any type of mouse event
 * @private
 * @param {integer} etype Type of event as index into MOUSEEVENTS array
 * @param {integer} id Dock id
 * @param {integer} idx Menu item id or -1
 * @param {boolean} fake Set if called as a result of inactivity
 */
		, DO_MOUSE = function(etype, id, idx, fake){
				var Dock = DOCKS[id] //convenience
					, el = Dock.Elem //convenience
					, i = el.length;
				switch(etype){
					case 0: //mouseenter
						Dock.OnDock = 1;
						if(Dock.Current >= 0 && Dock.Current !== idx){
							LABEL_SHOW(Dock); //hide
						}
						Dock.Current = idx;
						POSITION_LABEL(Dock, Dock.Xpand);
						Dock.Stamp = (new Date()).getTime();
						SET_SIZES(id);
						OVER_DOCK(id); //sets Xpand when complete
						break;
					case 1: //mousemove
						if(idx !== Dock.Current){ //mousemove from one item onto another
							LABEL_SHOW(Dock); //hide
							Dock.Current = idx;
						}
						POSITION_LABEL(Dock, Dock.Xpand);
						if(Dock.OnDock && Dock.Xpand){
							SET_SIZES(id);
							FACTOR_SIZES(id);
						}
						break;
					case 2: //mouseleave
						CLEAR_TIMER(Dock, 'Inactive');
						Dock.OnDock = Dock.Xpand = 0;
						LABEL_SHOW(Dock); //hide
						Dock.Stamp = (new Date()).getTime();
						while(i--){ //just in case...
							el[i].Final = el[i].Major;
						}
						OFF_DOCK(id, !!fake); //clears Current when complete
						break;
					default:
				}
			}
/** handler for all bound mouse events (move/enter/leave)
 * @private
 * @this {element}
 * @param {object} ev jQuery Event object
 * @return {boolean} false
 */
		, MOUSE_HANDLER = function(ev){
				var dockId = DOCK_INDEX_FROM_ID(this)
					, Dock = DOCKS[dockId]
					, idx = Dock ? ITEM_INDEX_FROM_CLASS(ev.target, this) : -1
					, doMse = -1
					, onDock
					;
				if(Dock){
					if(Dock.Asleep){ //buffer it...
						Dock.Sleeper = {
								target:ev.target
							, type:ev.type
							, pageX:ev.pageX
							, pageY:ev.pageY
							};
					}else{
						onDock = Dock.OnDock;
						CLEAR_TIMER(Dock, 'Idler');
						XY = [ev.pageX, ev.pageY];
						if(ev.type == MOUSEEVENTS[2]){//=mouseleave
							if(onDock){
								doMse = 2; //mouseleave
							}else{
								SET_IDLER(Dock);
							}
						}else{ //=mousemove or mouseenter...
							if(Dock.Opts.inactivity){
								CLEAR_TIMER(Dock, 'Inactive');
								Dock.Inactive = window.setTimeout(function(){ 
										DO_MOUSE(2, dockId, idx, true); //mouseleave
									}, Dock.Opts.inactivity);
							}
							if(ev.type == MOUSEEVENTS[1]){ //=mousemove
								if(idx < 0){
									if(onDock && Dock.Current >= 0){ //off of current
										doMse = 2; //mouseleave
									}
								}else if(!onDock || Dock.Current < 0){ //instant re-entry or no current
									doMse = 0; //mouseenter
								}else{ //change of current or moving within current
									doMse = 1; //mousemove
								}
							}else if(idx >= 0 && !onDock){ //mouseenter...
								doMse = 0; //mouseenter
							}
						}
						Dock.Sleeper = null;
						if(doMse >= 0){
							DO_MOUSE(doMse, dockId, idx);
						}
					}
				}
//v1.5 don't return false, otherwise handlers listening on docksleep and then, for example,
//     checking a mouseover on div.jqDock in order to 'bring back' a hidden menu, would
//     not receive notification of the mouseover because it would be blocked here
//				return false;
			}
/** handler for the docknudge and dockidle events
 * @private
 * @this {element} The original menu DOM element
 * @param {object} ev jQuery event object
 */
		, LISTENER = function(ev){
				var el = $('.jqDock', this).get(0)
					, dockId = DOCK_INDEX_FROM_ID(el)
					, Dock = DOCKS[ dockId ];
				if(Dock){
					// attempts to 'nudge' the dock awake...
					if(ev.type == 'docknudge'){
						//if Asleep, check for onWake returning a false - to stay asleep - and
						//trigger a dockwake event if not still asleep...
						if(Dock.Asleep && !(Dock.Asleep = (Dock.Opts.onWake.call(this) === false))){
							$(this).trigger('dockwake');
						}
						if(!Dock.Asleep){
							//start (or reset) idling now...
							SET_IDLER(Dock);
							//if we have buffered mouse event, run it...
							if(Dock.Sleeper){
								MOUSE_HANDLER.call(el, Dock.Sleeper);
							}
						}
					}else if(!Dock.Asleep){ //...must be 'dockidle' event type (which only affects a non-sleeping dock)...
						// attempts to send the dock to sleep...
						//NB: returning false from onSleep() prevents the dock going to sleep, but
						//it does NOT reset the idle timer!
						CLEAR_TIMER(Dock, 'Idler'); //needed if triggered by the calling program
						if((Dock.Asleep = (Dock.Opts.onSleep.call(Dock.Menu[0]) !== false))){
							Dock.Menu.trigger('docksleep');
							DO_MOUSE(2, dockId, 0, true); //fake a mouseleave as if it were due to inactivity
						}
					}
				}
			}
		;

/**
 * The main $.jqDock object
 * @private
 * @return {object}
 */
	$.jqDock = (function(){
		return {
				version : 1.5
			, defaults : { //can be set at runtime, per menu
					size : 48 //[px] maximum minor axis dimension of image (width or height depending on 'align' : vertical menu = width, horizontal = height)
				, distance : 72 //[px] attenuation distance from cursor
				, coefficient : 1.5 //attenuation coefficient
				, duration : 300 //[ms] duration of initial expansion and off-menu shrinkage
				, align : 'bottom' //[top/middle/bottom or left/center/right] fixes horizontal/vertical expansion axis
				, labels : 0 //enable/disable display of a label on the current image; (true) to use default position, or string to specify
				, source : 0 //function: given scope of relevant image element; passed index of image within menu; required to return image source path, or false to use original
				, loader : 0 //overrides useJqLoader if set to 'image' or 'jquery'
				, inactivity : 0 //[ms] duration of inactivity (no mouse movement) after which any expanded images will collapse; 0 (zero) disables the inactivity timeout
				, fadeIn : 0 //[ms] duration of the fade-in 'reveal' of the jqDocked menu; set to zero for instant 'show'
				, fadeLayer : '' //if fadeIn is set, this can change the element that is faded; the default is the entire original menu; alternatives are 'wrap' (.jqDockWrap element) or 'dock' (.jqDock element)
				, step : 50 //[ms] the timer interval between each step of shrinkage/expansion
//v1.5 : added setLabel, flow and idle options...
				, setLabel : 0  //function for transforming label text (ie. title) when initially building the label;
												//this is provided so that if the label requires HTML, the transform function can set 
												//it rather than having to put it in the title field and thereby make the markup invalid.
												//the called function will be given the scope (this) of the original menu element, and will be
												//passed 2 arguments: the text of the label, and the (zero-based) index of the option within the menu;
												//the function is required to return a string (HTML or plain text)
				, flow : 0  //alters the default dock behaviour such that the dock is NOT auto-centered and the wrap 
										//element (.jqDockWrap, which a relatively positioned) expands and collapses to precisely
										//contain the dock (.jqDock); this allows elements positioned around the docked menu to
										//adjust their own relative position according to the current state of the docked menu
				, idle : 0 //[ms] duration of idle time after the mouse has left the menu (without re-entering, obviously!) before the docksleep event is triggered (on the original menu element)
//v1.5 : added onReady, onSleep and onWake hooks...
				, onReady : 0 //function: called with scope of original menu element when dock has been initialised but not yet revealed (ie. before being shown)
											//NB: the onReady() function can return false to cancel the 'reveal' of the menu and put the dock to sleep
				, onSleep : 0 //function: called with scope of original menu element when dock has been idle for the defined idle period and has therefore gone to sleep
											//NB: the onSleep() function can return false to cancel the sleep
				, onWake : 0  //function: called with scope of original menu element when dock is 'nudged' awake, but only triggered if the dock was asleep prior to the' nudge'
											//NB: the onWake() function can return false to cancel the wake-up (dock stays asleep)
				}
			, useJqLoader : $.browser.opera || $.browser.safari //use jQuery method for loading images, rather than "new Image()" method

/**
 * initDock()
 * ==========
 * called by the image onload function, it stores and sets image height/width;
 * once all images have been loaded, it completes the setup of the dock menu
 * note: unless all images get loaded, the menu will stay hidden!
 * @this {$.jqDock}
 * @param {integer} id Dock index
 */
			, initDock : function(id){
			//========================================
					var Dock = DOCKS[id] //convenience
						, op = Dock.Opts //convenience
						, VH = VERTHORZ[op.vh] //convenience
						, invVH = VERTHORZ[VH.inv] //convenience
						, borders = Dock.Border //convenience
						, numItems = Dock.Elem.length
						, vanillaDiv = VANILLA.join('')
						, off = 0
						, i = 0
						, j, k, el, wh, acc, upad, wrap, callback
						, fadeLayer = op.fadeLayer //convenience
						;
					// things will screw up if we don't clear text nodes...
					REMOVE_TEXT(Dock.Menu[0]);
					//double wrap, and set some basic styles on the dock elements, otherwise it won't work
					Dock.Menu.children()
						.each(function(i, kid){
								var wrap = Dock.Elem[i].Wrap = $(kid).wrap(vanillaDiv + vanillaDiv + '</div></div>').parent(); 
								if(op.vh == 'h'){
									wrap.parent().css('float', 'left');
								}
							})
						.find('img').andSelf()
						.css({
								position: 'relative'
							, padding: 0
							, margin: 0
							, borderWidth: 0
							, borderStyle: 'none'
							, verticalAlign: 'top'
							, display: 'block'
							, width: '100%'
							, height: '100%'
							});
					//resize each image and store various settings wrt main axis...
					while(i < numItems){
						el = Dock.Elem[i++];
						//resize the image wrapper to make the minor axis dimension meet the specified 'Opts.size'...
						wh = KEEP_PROPORTION(el, op.size, VH.inv); //inverted!
						el.Major = el.Final = el.Initial = wh[VH.wh];
						el.Wrap.css(wh); //resize the image wrapper to its new shrunken setting
						//remove titles, alt text...
						el.Img.attr({alt:''}).parent('a').andSelf().removeAttr('title');
						//use inverts because we're after the minor axis dimension...
						Dock[invVH.wh] = Math.max(Dock[invVH.wh], op.size + el.Pad[invVH.lead] + el.Pad[invVH.trail]);

						el.Offset = off;
						el.Centre = off + el.Pad[VH.lead] + (el.Initial / 2);
						off += el.Initial + el.Pad[VH.lead] + el.Pad[VH.trail];
					}

					//'best guess' at calculating max 'spread' (main axis dimension - horizontal or vertical) of menu:
					//for each img element of the menu, call SET_SIZES() with a forced cursor position of the centre of the image;
					//SET_SIZES() will set each element's Final value, so tally them all, including user-applied padding, to give
					//an overall width/height for this cursor position; set dock width/height to be the largest width/height found;
					//repeat, with a forced cursor position of the leading edge of image
					i = 0;
					while(i < numItems){
						el = Dock.Elem[i++];
						upad = el.Pad[VH.lead] + el.Pad[VH.trail]; //user padding in main axis
						//tally the minimum widths...
						Dock.Spread += el.Initial + upad;

						//for override cursor positions of Centre and Offset...
						for(k in {Centre:1, Offset:1}){
							//set sizes with an overridden cursor position...
							SET_SIZES(id, el[k]);
							//tally image widths/heights (plus padding)...
							acc = 0; //accumulator for main axis image dimensions
							for(j = numItems; j--; ){
								//note that Final is an image dimension (in main axis) and does not include any user padding...
								acc += Dock.Elem[j].Final + upad;
							}
							//keep largest main axis dock dimension...
							if(acc > Dock[VH.wh]){ Dock[VH.wh] = acc; }
						}
					} //... i is now numItems
					//reset Final for each image...
					while(i){
						el = Dock.Elem[--i];
						el.Final = el.Initial;
					} //... i is now 0
					wrap = [
							VANILLA[0], VANILLA[2] //this will be div.jqDockWrap, but I don't want margin, border or background
						, '<div id="jqDock', id, '" class="jqDock" style="position:absolute;top:0;left:0;padding:0;margin:0;overflow:visible;'
						, 'height:', Dock.height, 'px;width:', Dock.width, 'px;"></div></div>'
						].join('');
					Dock.Yard = $('div.jqDock', Dock.Menu.wrapInner(wrap));
					//now that we have div.jqDock, let's see if the user has applied any css border styling to it...
					for(j = 4; j--; ){
						borders[j] = AS_INTEGER(Dock.Yard.css('border' + TRBL[j] + 'Width'));
					}
					Dock.Yard.parent().addClass('jqDockWrap')
						.width(Dock.width + borders[1] + borders[3]) //Right and Left
						.height(Dock.height + borders[0] + borders[2]); //Top and Bottom
					//shrink all images down to 'at rest' size, and add appropriate identifying class...
					for(; i < numItems; i++){
						el = Dock.Elem[i];
						//apply the image's user-applied padding to the outer element wrapper...
						upad = el.Wrap.parent();
						for(j = 4; j--; ){
							if(el.Pad[j]){
								upad.css('padding' + TRBL[j], el.Pad[j]);
							}
						}
						CHANGE_SIZE(id, i, el.Final, true); //force
						//give a mouse class to both the image and the outer element wrapper (to handle any user padding)...
						upad.add(el.Img).addClass('jqDockMouse'+i);
						//create and append the label
						SET_LABEL(Dock, el, i);
					}
					//bind a docknudge and dockidle event to the original menu element...
					el = Dock.Menu.bind('docknudge dockidle', LISTENER);
					//bind the mousehandler to the dock, and set filter:inherit on everthing below the dock (see below)...
					Dock.Yard.bind(MOUSEEVENTS.join(' '), MOUSE_HANDLER).find('*').css({filter:'inherit'});

/*v1.4 : bugfix : in IE8, non-statically positioned child elements do not inherit opacity; a way round this
                  is to set filter:inherit on child elements
  v1.5 : Further complications with IE's opacity handling :
         When animating opacity (as opposed to doing a fadeIn) the alpha filter of the animated element *must*
         be cleared (='' or ='inherit') on completion back to opacity 1. Otherwise, in IE7 the element will not allow
         children (in this case, the images) to be visible beyond its bounds (ie. expanding a menu item gets the image
         chopped off at the edge of jqDock); in IE8, the image does expand ok, but leaves 'shadows' when collapsing!
         Another complication is that jQuery does not recognise that filter can contain anything other than an
         'alpha(opacity=xxx)' value, so when the filter is set to 'inherit', jQuery animates opacity by *appending*
         the 'alpha(...)' value to the current 'inherit' value (eg. filter:'inheritalpha(...)' 
         So ...
            ... on the assumption that nothing outside of jDock is going to want to individually fade 
            anything below the .jqDock, I'm setting filter:inherit on all its children, for IE8's sake.
            this is just in case anyone uses docksleep to perform a fade on .jqDock; if they do a fade
            on either .jqDockWrap or the original menu element, then they may have to set (and probably
            clear) filter:inherit on .jqDock, or .jqDock and .jqDockWrap (respectively) themselves!
*/

					//show the menu now?...
					//if onReady returns false then the dock goes to sleep and will require a 'nudge' at some point to wake it up
					if(!(Dock.Asleep = (op.onReady.call(Dock.Menu[0]) === false))){
						callback = function(noFade){
								if(!noFade){
									$('.jqDockFilter', this).add(this).css({filter:''}).removeClass('jqDockFilter');
								}
								//clear the Sleep so that a docknudge won't do it's wake-up routine
								Dock.Sleep = false;
								Dock.Menu.trigger('dockshow').trigger('docknudge');
							};
						if(fadeLayer){
							//can only be 1 of menu/wrap/dock, and el is already set to Dock.Menu...
							if(fadeLayer != 'menu'){ //either dock or wrap...
								el = Dock.Yard;
								if(fadeLayer == 'wrap'){
									el = el.parent();
								}
							}
							//.jqDockFilter is used so that I can ensure that only elements *below* .jqDock
							//have filter:inherit set; this is so that if the calling program uses docksleep
							//to fade out .jqDock I can at least ensure that it will work for IE8 (regardless
							//of the other problems with animating IE's opacity!)
							//Unfortunately, because of IE (grrr), we have to put the dock to sleep while the
							//fade is taking place. This is because if the user were to mouse-over the menu 
							//while it was still fading in, the menu element expansion would either be cut off
							//at the jqDockWrap boundary (IE6/7) or would leave a 'shadow' trail effect beyond
							//the jqDockWrap boundary as it shrank (IE8) ... due to the filters not being reset
							//until the end of the animation.
							Dock.Asleep = !!$('.jqDock,.jqDockWrap', el).addClass('jqDockFilter').css({filter:'inherit'});
							el.css({opacity:0});
							REVEAL_MENU(Dock);
							el.animate({opacity:1}, op.fadeIn, callback);
						}else{
							REVEAL_MENU(Dock);
							callback(1);
						}
					}
				} //end function initDock()

			}; //end of return object
		})(); //run the function to set up $.jqDock

	/***************************************************************************************************
	*  jQuery.fn.jqDock()
	*  ==================
	* STANDARD
	* usage:      $(selector).jqDock(options);
	* options:    see $.jqDock.defaults
  * returns:    $(selector)
  *
  * ALTERNATE   ...a 'setter', providing a means for modifying image paths post-initialisation
  * usage:      $(image-selector).jqDock(options);
	* options:    object, with the following possible properties...
	*               src: {string|function} Path to 'at rest' image, or function returning a path
	*               altsrc: {string|function} Path to expanded image, or function returning a path
  * returns:    $(image-selector)
  * Note : image-selector *must* result in solely IMG element(s)
  * 
  * ALTERNATE2  ...a 'setter', providing a means for nudging a dock awake, or sending it to sleep
  * usage:      $(selector).jqDock('nudge'); //'nudges' dock awake
  *             $(selector).jqDock('idle'); //sends dock to sleep
  * returns:    $(selector)
  * Note : selector should be already initialised dock(s), ie. classed with 'jqDocked'
	*
	* undocumented, but used in example.js...
  * ALTERNATE3  ...a 'getter', providing a means for retrieving an image's object from the Elem array
  * usage:      $(image-selector).jqDock('get');
  * returns:    {object} The object which is the element of the Elem array corresponding to the
  *                      first 'img' DOM element in the $(image-selector) collection
	* 
	* note: the aim is to do as little processing as possible after setup, because everything is
	* driven from the mousemove/enter/leave events and I don't want to kill the browser if I can help it!
	* hence the code below, and in $.jqDock.initDock(), sets up and stores everything it possibly can
	* which will reduce processing at runtime, and hopefully give as smooth animation as possible.
	***************************************************************************************************/
	$.fn.jqDock = function(opts){
		if(this.length && !this.not('img').length){ //alternate usage...
			/***************************************************************************************************
			* ALTERNATE3:
			* undocumented, but used in example.js
			* 
			* Example:
			*   //to retrieve an item's original text used for the label (unmodified by setLabel option)...
			*   var labelText = $('#menu img:eq(2)').jqDock('get').Title;
			***************************************************************************************************/
			if(opts === 'get'){ //alternate2 usage ('getter')
				//since this is a getter, it does not support chaining and needs to cop out now
				var item = FIND_IMAGE(this.get(0));
				return item ? $.extend(true, {}, item, {Img:null}) : null;
			}

			/***************************************************************************************************
			* ALTERNATE:
			* If a function is provided, it will be called with scope of the image DOM element, and 2 parameters:
			* - current setting
			* - settingType, eg. 'src' or 'altsrc'
			*
			* Example (with strings):
			*   $('#menu img').eq(0).jqDock({src:'newpath.jpg', altsrc:'newexpanderpath.jpg'});
			* Example (with functions):
			*   fnChangePath = function(current, type){
			*       //always change altsrc, but only change src if image has a class of 'changeExpanded'...
			*       return type == 'altsrc' || $(this).hasClass('changeExpanded')
			*         ? current.replace(/old\.png$/, 'new.png')
			*         : current;
			*     };
			*   $('#menu img').jqDock({src:fnChangePath, altsrc:fnChangePath});
			***************************************************************************************************/
			this.each(function(n, el){
					var item = FIND_IMAGE(el)
						, src = 0
						, atRest, str, v
						;
					opts = opts || {};
					if(item){
						atRest = item.Major == item.Initial;
						for(v in {src:1, altsrc:1}){
							if(opts[v]){
								str = ($.isFunction(opts[v]) ? opts[v].call(el, item[v], v) : opts[v]).toString();
								if(item[v] !== str){
									item[v] = str;
									src = (v == 'src' ? atRest : !atRest) ? v : src;
								}
							}
						}
						if(src){
							$(el).attr('src', item[src]);
						}
					}
				});
			/***************************************************************************************************
			* ALTERNATE2:
			* Accepts 'nudge' or 'idle'. Chainable.
			* 
			* Example:
			*   $('#menu').jqDock('nudge'); //wake from sleep
			*   $('#menu').jqDock('idle'); //send to sleep
			***************************************************************************************************/
		}else if(opts === 'nudge' || opts === 'idle'){ //alternate usage 3 (nudge/idle)
			this.filter('.jqDocked').each(function(){ //only runs on an original menu element that has been docked
					LISTENER.call(this, {type:'dock'+opts});
				});
			/***************************************************************************************************
			* STANDARD:
			* Chainable.
			* 
			* Example:
			*   $('#menu').jqDock({align:'top'});
			***************************************************************************************************/
		}else{ //standard usage...
			this.not('.jqDocked').filter(function(){
					//check that no parents are already docked, and that all children are either images, or anchors containing only an image...
					return !$(this).parents('.jqDocked').length && !$(this).children().not('img').filter(function(){
							return $(this).filter('a').children('img').parent().children().length !== 1;
						}).length;
				}).addClass('jqDocked')
				.each(function(){
					var Self = $(this)
						, id = DOCKS.length
						, Dock, op, jqld, i;
					//add an object to the docks array for this new dock...
					DOCKS[id] = { 
							Elem : [] // an object per img menu option
						, Menu : Self //jQuery of original containing element
						, OnDock : 0 //indicates cursor over menu and initial sizes set
						, Xpand : 0 //indicates completion of initial menu element expansions
						, Stamp : 0 //set on mouseenter/leave and used (within opts.duration) to proportion the menu element sizes
						, width : 0 //width of div.jqDock container
						, height : 0 //height of div.jqDock container
						, Spread : 0 //main axis dimension (horizontal = width, vertical = height)
						, Border : [] //border widths on div.jqDock, indexed as per TRBL
						, Opts : $.extend({}, $.jqDock.defaults, opts||{}, $.metadata ? Self.metadata() : {}) //options; support metadata plugin
						, Current : -1 //current image index
						, Loaded : 0 //count of images loaded
/* these don't need to be explicitly set a this stage, either because their usage is by testing for [non]existence and
 * then assigning a value, or because they are explicitly set during initDock()...
						, Inactive : null //inactivity timer
						, Idler : null //idle timer
						, Asleep : false //set to true when dock is  following an idle period timeout
						, Sleeper : null //while Asleep, the most recent mouse event gets buffered for use on being nudged awake
						, Yard : 0 //jQuery of div.jqDock
*/
						};
					Dock = DOCKS[id]; //convenience
					op = Dock.Opts; //convenience
					//check some of the options...
					jqld = (!op.loader && $.jqDock.useJqLoader) || op.loader === 'jquery';
					for(i in {size:1, distance:1, duration:1, inactivity:1, fadeIn:1, step:1, idle:1}){
						op[i] = AS_INTEGER(op[i]);
					}
					i = op.coefficient * 1;
					op.coefficient = isNaN(i) ? 1.5 : i;
					op.labels = (/^[tmb][lcr]$/).test(op.labels.toString()) ? op.labels : ( op.labels ? {top:'br',left:'tr'}[op.align] || 'tl' : '' );
					op.setLabel = !!op.setLabel ? op.setLabel : TRANSFORM_LABEL;
					op.fadeLayer = op.fadeIn ? (({dock:1,wrap:1}[op.fadeLayer]) ? op.fadeLayer : 'menu') : '';
					for(i in {onSleep:1, onWake:1, onReady:1}){
						if(!op[i]){
							op[i] = EMPTYFUNC;
						}
					}
					//set up some extra Opts now, just to save some computing power later...
					op.attenuation = Math.pow(op.distance, op.coefficient); //straightforward, static calculation
					op.vh = ({left:1, center:1, right:1}[op.align]) ? 'v' : 'h'; //vertical/horizontal orientation based on 'align' option

					$('img', Self).each(function(n, el){
							//add an object to the dock's elements array for each image...
							var jself = $(el)
								, src = jself.attr('src') //'small' image source
								, linkParent = jself.parent('a')
								, i;
							Dock.Elem[n] = { 
									Img : jself //jQuery of img element
								, src : src  //image path, small
								, altsrc: (op.source ? op.source.call(el, n) : '') || ALT_IMAGE(el) || src //image path, large
								, Title : jself.attr('title') || linkParent.attr('title') || '' //label text? (pre setLabel())
								, Label : {
										mc: (/[mc]/).test(op.labels) //indicates the (possible) need for middle/centre label positioning information to be gathered
/* these don't need to be explicitly set at this stage: the first 2 are *always* set by SET_LABEL(); the other 2 are
 * only set (and used) by POSITION_LABEL() *if* the labels are being positioned middle and/or center
									, el: 0 //jqQuery of div.jqDockLabel
									, txt: '' //the label text (ie. Title) after being processed by Opts.setLabel()
									, v: 0 //the 'v' stands for vertical, so this is the label's overall height (ie. height + top/bottom padding)
									, h: 0 //the 'h' stands for horizontal, so this is the label's overall width (ie. width + left/right padding)
 */
									}
								, Pad : [] //user-applied padding, set up below and indexed as per TRBL
								, Link : linkParent.length ? 'Link' : 'Image' //image-within-link or not
/* these don't need to be explicitly set a this stage, either because their usage is by testing for [non]existence and
 * then assigning a value, or because they are explicitly set during IMAGE_ONLOAD() or initDock()...
								, width : 0 //original width of img element (the one that expands)
								, height : 0 //original height of img element (the one that expands)
								, Initial : 0 //width/height when fully shrunk; it's important to note that this is not necessarily the same as Opts.size!
								, Major : 0 //transitory width/height (main axis)
								, Minor : 0 //transitory width/height (minor axis)
								, Final : 0 //target width/height
								, Offset : 0 //offset of 'lead' edge of the image within div.jqDock (including user-padding)
								, Centre : 0 //'Offset' + 'lead' user-padding + half 'Initial' dimension
*/
								};
							for(i = 4; i--;){
								Dock.Elem[n].Pad[i] = AS_INTEGER(jself.css('padding' + TRBL[i]));
							}
						});
					//we have to run a 'loader' function for the images because the expanding image
					//may not be part of the current DOM. what this means though, is that if you
					//have a missing image in your dock, the entire dock will not be displayed!
					//however I've had a few problems with certain browsers: for instance, IE does
					//not like the jQuery method; and Opera was causing me problems with the native
					//method when reloading the page; I've also heard rumours that Safari 2 might cope better with
					//the jQuery method, but I cannot confirm since I no longer have Safari 2.
					//
					//anyway, I'm providing both methods. if anyone finds it doesn't work, try
					//overriding with option.loader, and/or changing $.jqDock.useJqLoader for the 
					//browser in question and let me know if that solves it.
					$.each(Dock.Elem, function(i, v){
							var pre, altsrc = v.altsrc;
							if(jqld){ //jQuery method...
								$('<img />').bind('load', {id:id, idx:i}, IMAGE_ONLOAD).attr({src:altsrc});
							}else{ //native 'new Image()' method...
								pre = new Image();
								pre.onload = function(){
										IMAGE_ONLOAD.call(this, {data:{id:id, idx:i}});
										pre.onload = ''; //wipe out this onload function
										pre = null;
									};
								pre.src = altsrc;
							}
						});
				});
		}
		return this;
	}; //end jQuery.fn.jqDock()
} //end of if()
})(jQuery, window);
