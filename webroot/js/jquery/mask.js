/* $Id: jquery.meiomask.js 52 2008-12-08 02:09:39Z fabiomcosta $ */
/**
 * @version 1.0.4 $Revision: 52 $
 * The MIT License
 * Copyright (c) 2008 Fabio M. Costa http://www.meiocodigo.com
 */
/**
 * jquery.meiomask.js
 * $URL: http://svn.assembla.com/svn/meiomask/jquery.meiomask.js $
 * @author: $Author: fabiomcosta $
 * @version 1.0.4 $Revision: 52 $
 * @lastchange: $Date: 2008-12-08 00:09:39 -0200 (Mon, 08 Dec 2008) $
 *
 * Created by Fabio M. Costa on 2008-09-16. Please report any bug at http://www.meiocodigo.com
 *
 * Copyright (c) 2008 Fabio M. Costa http://www.meiocodigo.com
 *
 * The MIT License
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

(function($){

	var isIphone = (window.orientation!=undefined);

	$.extend({
		mask : {

			// the mask rules. You may add yours!
			// number rules will be overwritten
			rules : {
				'z': /[a-z]/,
				'Z': /[A-Z]/,
				'a': /[a-zA-Z]/,
				'*': /[0-9a-zA-Z]/,
				'@': /[0-9a-zA-ZÃ§Ã‡Ã¡Ã Ã£Ã©Ã¨Ã­Ã¬Ã³Ã²ÃµÃºÃ¹Ã¼]/
			},

			// fixed chars to be used on the masks. You may change it for your needs!
			fixedChars : '[(),.:/ -]',

			// these keys will be ignored by the mask.
			// all these numbers where obtained on the keydown event
			keyRepresentation : {
				8	: 'backspace',
				9	: 'tab',
				13	: 'enter',
				16	: 'shift',
				17	: 'control',
				18	: 'alt',
				27	: 'esc',
				33	: 'page up',
				34	: 'page down',
				35	: 'end',
				36	: 'home',
				37	: 'left',
				38	: 'up',
				39	: 'right',
				40	: 'down',
				45	: 'insert',
				46	: 'delete',
				116	: 'f5',
				224	: 'command'
			},

			iphoneKeyRepresentation : {
				10	: 'go',
				127	: 'delete'
			},

			signals : {
				'+' : '',
				'-' : '-'
			},

			// default settings for the plugin
			options : {
				attr: 'alt', // an attr to look for the mask name or the mask itself
				mask : null, // the mask to be used on the input
				type : 'fixed', // the mask of this mask
				defaultValue : '', // the default value for this input
				signal : false,  // this should not be set, to use signal at masks put the signal you want ('-' or '+') at the default value of this mask.
								// See the defined masks for a better understanding.
				onInvalid : function(){},
				onValid : function(){},
				onOverflow : function(){}
			},

			// masks. You may add yours!
			// Ex: $.fn.setMask.masks.msk = {mask: '999'}
			// and then if the 'attr' options value is 'alt', your input should look like:
			// <input type="text" name="some_name" id="some_name" alt="msk" />
			masks : {

				//added by Dirk Bruensicke
				'transupper'		: { mask : 'a',type:'infinite',onValid:function(c,nKey){ $(this).attr('value', $(this).unmaskedVal().toUpperCase());} },
				'translower'		: { mask : 'a',type:'infinite',onValid:function(c,nKey){ $(this).attr('value', $(this).unmaskedVal().toLowerCase());} },
				'numtranslower'		: { mask : '*',type:'infinite',onValid:function(c,nKey){ $(this).attr('value', $(this).unmaskedVal().toLowerCase());} },
				'upper'				: { mask : 'Z',type:'infinite' },
				'lower'				: { mask : 'z',type:'infinite' },
				'plz'				: { mask : '99999' },

				//default ones
				'phone'				: { mask : '(99) 9999-9999' },
				'phone-us'			: { mask : '(999) 9999-9999' },
				'cpf'				: { mask : '999.999.999-99' }, // cadastro nacional de pessoa fisica
				'cnpj'				: { mask : '99.999.999/9999-99' },
				'date'				: { mask : '39/19/9999' }, //uk date
				'date-us'			: { mask : '19/39/9999' },
				'cep'				: { mask : '99999-999' },
				'time'				: { mask : '29:59' },
				'cc'				: { mask : '9999 9999 9999 9999' }, //credit card mask
				'integer'			: { mask : '999.999.999.999', type : 'reverse' },
				'decimal'			: { mask : '99,999.999.999.999', type : 'reverse', defaultValue : '000' },
				'decimal-us'		: { mask : '99.999,999,999,999', type : 'reverse', defaultValue : '000' },
				'signed-decimal'	: { mask : '99,999.999.999.999', type : 'reverse', defaultValue : '+000' },
				'signed-decimal-us' : { mask : '99,999.999.999.999', type : 'reverse', defaultValue : '+000' }
			},

			init : function(){
				// if has not inited...
				if( !this.hasInit ){

					var self = this, i,
						keyRep = ( isIphone ) ? this.iphoneKeyRepresentation : this.keyRepresentation;

					this.ignore = false;
					this.fixedCharsReg = new RegExp(this.fixedChars);
					this.fixedCharsRegG = new RegExp(this.fixedChars,'g');

					// constructs number rules
					for(i=0; i<=9; i++) this.rules[i] = new RegExp('[0-'+i+']');

					this.keyRep = keyRep;
					// ignore keys array creation for iphone or the normal ones
					this.ignoreKeys = [];
					$.each(keyRep,function(key){
						self.ignoreKeys.push( parseInt(key) );
					});

					this.hasInit = true;
				}
			},

			set: function(el,options){

				var maskObj = this,
					$el = $(el),
					mlStr = 'maxLength';

				this.init();

				return $el.each(function(){

					var $this = $(this),
						o = $.extend({},maskObj.options),
						attrValue = $this.attr(o.attr),
						tmpMask = '',
						// 'input' event fires on every keyboard event on the input
						pasteEvent = maskObj.__getPasteEvent();

					// then we look for the 'attr' option
					tmpMask = ( typeof options == 'string' )?options:( attrValue != '' )?attrValue:null;
					if(tmpMask) o.mask = tmpMask;

					// then we see if it's a defined mask
					if(maskObj.masks[tmpMask]) o = $.extend(o,maskObj.masks[tmpMask]);

					// then it looks if the options is an object, if it is we will overwrite the actual options
					if( typeof options == 'object' ) o = $.extend(o,options);

					//then we look for some metadata on the input
					if($.metadata) o = $.extend(o,$this.metadata());

					if( o.mask != null ){

						if($this.data('mask')) maskObj.unset($this);

						var defaultValue = o.defaultValue,
							mlValue = $this.attr(mlStr),
							reverse = (o.type=='reverse');

						o = $.extend({},o,{
							maxlength: mlValue,
							maskArray : o.mask.split(''),
							maskNonFixedCharsArray : o.mask.replace(maskObj.fixedCharsRegG,'').split('')
						});

						//sets text-align right for reverse masks
						if(reverse) $this.css('text-align','right');

						// apply mask to the current value of the input
						if($this.val()!='') $this.val( maskObj.string($this.val(),o) );

						// apply the default value of the mask to the input
						else if(defaultValue!='') $this.val( maskObj.string(defaultValue,o) );

						$this.data('mask',o);

						// removes the maxlength attribute (it will be set again if you use the unset method)
						$this.removeAttr(mlStr);

						// setting the input events
						$this.bind('keydown',{func:maskObj._keyDown,thisObj:maskObj},maskObj._onMask)
							.bind('keyup',{func:maskObj._keyUp,thisObj:maskObj},maskObj._onMask)
							.bind('keypress',{func:maskObj._keyPress,thisObj:maskObj},maskObj._onMask)
							.bind(pasteEvent,{func:maskObj._paste,thisObj:maskObj},maskObj._delayedOnMask);
					}
				});
			},

			//unsets the mask from el
			unset : function(el){
				var $el = $(el),
					_this = this;
				return $el.each(function(){
					var $this = $(this);
					if( $this.data('mask') ){
						var maxLength = $this.data('mask').maxlength,
							pasteEvent = _this.__getPasteEvent();

						if(maxLength != -1) $this.attr('maxLength',maxLength);

						$this.unbind('keydown',_this._onMask)
							.unbind('keypress',_this._onMask)
							.unbind('keyup',_this._onMask)
							.unbind(pasteEvent,_this._delayedOnMask)
							.removeData('mask');
					}
				});
			},

			//masks a string
			string : function(str,options){
				this.init();
				var o={};
				if(typeof str != 'string') str = String(str);
				switch(typeof options){
					case 'string':
						// then we see if it's a defined mask
						if(this.masks[options]) o = $.extend(o,this.masks[options]);
						else o.mask = options;
						break;
					case 'object':
						o = options;
				}

				// insert signal if any
				if( (o.type=='reverse') && o.defaultValue ){
					if( typeof this.signals[o.defaultValue.charAt(0)] != 'undefined' ){
						var maybeASignal = str.charAt(0);
						o.signal = (typeof this.signals[maybeASignal] != 'undefined') ? this.signals[maybeASignal] : this.signals[o.defaultValue.charAt(0)];
						o.defaultValue = o.defaultValue.substring(1);
					}
				}

				return this.__maskArray(str.split(''),
							o.mask.replace(this.fixedCharsRegG,'').split(''),
							o.mask.split(''),
							o.type,
							o.defaultValue,
							o.signal);
			},

			unmaskedVal : function(el){
				return $(el).val().replace($.mask.fixedCharsRegG,'');
			},

			_onMask : function(e){
				var thisObj = e.data.thisObj,
					o = {};
				o._this = e.target;
				o.$this = $(o._this);
				// if the input is readonly it does nothing
				if( o.$this.attr('readonly') ) return true;
				o.value = o.$this.val();
				o.nKey = thisObj.__getKeyNumber(e);
				o.range = thisObj.__getRange(o._this);
				o.valueArray = o.value.split('');
				o.data = o.$this.data('mask');
				o[o.data.type] = true;
				return e.data.func.call(thisObj,e,o);
			},

			// the timeout is set because on ie we can't get the value from the input without it
			_delayedOnMask : function(e){
				e.type='paste';
				setTimeout(function(){ e.data.thisObj._onMask(e); },1);
			},

			_keyDown : function(e,o){
				// lets say keypress at desktop == keydown at iphone (theres no keypress at iphone)
				this.ignore = ( $.inArray(o.nKey,this.ignoreKeys) > -1 );
				if( this.ignore ){
					var rep = this.keyRep[o.nKey];
					o.data.onValid.call(o._this,rep?rep:'',o.nKey);
				}
				return isIphone ? this._keyPress(e,o) : true;
			},

			_keyUp : function(e,o){
				//9=TAB_KEY
				//this is a little bug, when you go to an input with tab key
				//it would remove the range selected by default, and that's not a desired behavior
				if(o.nKey==9 && ($.browser.safari || $.browser.msie)) return true;
				return (!o.infinite)?this._paste(e,o):true;
			},

			_paste : function(e,o){
				// changes the signal at the data obj from the input
				if(o.reverse) this.__changeSignal(e.type,o);

				var $thisVal = this.__maskArray(
					o.valueArray,
					o.data.maskNonFixedCharsArray,
					o.data.maskArray,
					o.data.type,
					o.data.defaultValue,
					o.data.signal
				);

				o.$this.val( $thisVal );
				// this makes the caret stay at first position when
				// the user removes all values in an input and the plugin adds the default value to it (if it haves one).
				if( !o.reverse && o.data.defaultValue.length && (o.range.start==o.range.end) )
					this.__setRange(o._this,o.range.start,o.range.end);

				//fix so ie's and safari's caret won't go to the end of the input value.
				if( ($.browser.msie || $.browser.safari) && !o.reverse) this.__setRange(o._this,o.range.start,o.range.end);

				return true;

			},

			_keyPress: function(e,o){

				if( this.ignore || e.ctrlKey || e.metaKey || e.altKey ) return true;

				// changes the signal at the data obj from the input
				if(o.reverse) this.__changeSignal(e.type,o);

				var c = String.fromCharCode(o.nKey),
					rangeStart = o.range.start,
					rawValue = o.value,
					maskArray = o.data.maskArray;

				if(o.reverse){
					 	// the input value from the range start to the value start
					var valueStart = rawValue.substr(0,rangeStart),
						// the input value from the range end to the value end
						valueEnd = rawValue.substr(o.range.end,rawValue.length);

					rawValue = (valueStart+c+valueEnd);
					//necessary, if not decremented you will be able to input just the mask.length-1 if signal!=''
					//ex: mask:99,999.999.999 you will be able to input 99,999.999.99
					if( o.data.signal && (rangeStart-o.data.signal.length > 0 ) ) rangeStart-=o.data.signal.length;
				}

				var valueArray = rawValue.replace(this.fixedCharsRegG,'').split(''),
					// searches for fixed chars begining from the range start position, till it finds a non fixed
					extraPos = this.__extraPositionsTill(rangeStart,maskArray);

				o.rsEp = rangeStart+extraPos;

				if( o.infinite ) o.rsEp = 0;

				// if the rule for this character doesnt exist (value.length is bigger than mask.length)
				if( !this.rules[maskArray[o.rsEp]] ){
					o.data.onOverflow.call(o._this,c,o.nKey);
					return false;
				}
				// if the new character is not obeying the law... :P
				else if( !this.rules[maskArray[o.rsEp]].test( c ) ){
					o.data.onInvalid.call(o._this,c,o.nKey);
					return false;
				}
				else o.data.onValid.call(o._this,c,o.nKey);

				var $thisVal = this.__maskArray(
					valueArray,
					o.data.maskNonFixedCharsArray,
					maskArray,
					o.data.type,
					o.data.defaultValue,
					o.data.signal,
					extraPos
				);

				o.$this.val( $thisVal );

				return (o.reverse)?this._keyPressReverse(e,o):(o.fixed)?this._keyPressFixed(e,o):true;
			},

			_keyPressFixed : function(e,o){

				if(o.range.start==o.range.end){
					// the 0 thing is cause theres a particular behavior i wasnt liking when you put a default
					// value on a fixed mask and you select the value from the input the range would go to the
					// end of the string when you enter a char. with this it will overwrite the first char wich is a better behavior.
					// opera fix, cant have range value bigger than value length, i think it loops thought the input value...
					if( (o.rsEp==0 && o.value.length==0) || o.rsEp < o.value.length )
						this.__setRange(o._this,o.rsEp,o.rsEp+1);
				}
				else
					this.__setRange(o._this,o.range.start,o.range.end);

				return true;
			},

			_keyPressReverse : function(e,o){
				//fix for ie
				//this bug was pointed by Pedro Martins
				//it fixes a strange behavior that ie was having after a char was inputted in a text input that
				//had its content selected by any range
				if($.browser.msie && ( (o.rangeStart==0 && o.range.end==0) || o.rangeStart != o.range.end ) )
					this.__setRange(o._this,o.value.length);
				return false;
			},

			// changes the signal at the data obj from the input
			__changeSignal : function(eventType,o){
				if(o.data.signal!==false){
					var inputChar = (eventType=='paste')?o.value.charAt(0):String.fromCharCode(o.nKey);
					if( this.signals && (typeof this.signals[inputChar] != 'undefined') ){
						o.data.signal = this.signals[inputChar];
					}
				}
			},

			// browsers like firefox2 and before and opera doenst have the onPaste event, but the paste feature can be done with the onInput event.
			__getPasteEvent : function(){
				return ( $.browser.opera || ( $.browser.mozilla && parseFloat($.browser.version.substr(0,3)) < 1.9 ))?'input':'paste';
			},

			__getKeyNumber : function(e){
				return (e.charCode||e.keyCode||e.which);
			},

			// this function is totaly specific to be used with this plugin, youll never need it
			// it gets the array representing an unmasked string and masks it depending on the type of the mask
			__maskArray : function(valueArray,maskNonFixedCharsArray,maskArray,type,defaultValue,signal,extraPos){
				if(type == 'reverse') valueArray.reverse();
				valueArray = this.__removeInvalidChars(valueArray,maskNonFixedCharsArray);
				if(defaultValue) valueArray = this.__applyDefaultValue.call(valueArray,defaultValue);
				valueArray = this.__applyMask(valueArray,maskArray,extraPos);
				switch(type){
					case 'reverse':
						valueArray.reverse();
						return (signal || '')+valueArray.join('').substring(valueArray.length-maskArray.length);
					case 'infinite':
						return valueArray.join('');
					default:
						return valueArray.join('').substring(0,maskArray.length);
				}
				return '';
			},

			// applyes the default value to the result string
			__applyDefaultValue : function(defaultValue){
				var defLen = defaultValue.length,thisLen = this.length,i;
				//removes the leading chars
				for(i=thisLen-1;i>=0;i--){
					if(this[i]==defaultValue.charAt(0)) this.pop();
					else break;
				}
				// apply the default value
				for(i=0;i<defLen;i++) if(!this[i])
					this[i] = defaultValue.charAt(i);

				return this;
			},

			// Removes values that doesnt match the mask from the valueArray
			// Returns the array without the invalid chars.
			__removeInvalidChars : function(valueArray,maskNonFixedCharsArray){
				// removes invalid chars
				for(var i=0; i<valueArray.length; i++ ){
					if( maskNonFixedCharsArray[i] &&
						this.rules[maskNonFixedCharsArray[i]] &&
						!this.rules[maskNonFixedCharsArray[i]].test(valueArray[i]) ){
							valueArray.splice(i,1);
							i--;
					}
				}
				return valueArray;
			},

			// Apply the current input mask to the valueArray and returns it.
			__applyMask : function(valueArray,maskArray,plus){
				if( typeof plus == 'undefined' ) plus = 0;
				// apply the current mask to the array of chars
				for(var i=0; i<valueArray.length+plus; i++ ){
					if( maskArray[i] && this.fixedCharsReg.test(maskArray[i]) )
						valueArray.splice(i,0,maskArray[i]);
				}
				return valueArray;
			},

			// searches for fixed chars begining from the range start position, till it finds a non fixed
			__extraPositionsTill : function(rangeStart,maskArray){
				var extraPos = 0;
				while( this.fixedCharsReg.test(maskArray[rangeStart]) ){
					rangeStart++;
					extraPos++;
				}
				return extraPos;
			},

			// http://www.bazon.net/mishoo/articles.epl?art_id=1292
			__setRange : function(input,start,end) {
				if(typeof end == 'undefined') end = start;
				if (input.setSelectionRange) {
					input.setSelectionRange(start, end);
				} else {
					// assumed IE
					var range = input.createTextRange();
					range.collapse();
					range.moveStart('character', start);
					range.moveEnd('character', end - start);
					range.select();
				}
			},

			// adaptation from http://digitarald.de/project/autocompleter/
			__getRange : function(input){
				if (!$.browser.msie) return {start: input.selectionStart, end: input.selectionEnd};
				var pos = {start: 0, end: 0},
					range = document.selection.createRange();
				pos.start = 0 - range.duplicate().moveStart('character', -100000);
				pos.end = pos.start + range.text.length;
				return pos;
			}

		}
	});

	$.fn.extend({
		setMask : function(options){
			return $.mask.set(this,options);
		},
		unsetMask : function(){
			return $.mask.unset(this);
		},
		unmaskedVal : function(){
			return $.mask.unmaskedVal(this[0]);
		}
	});
})(jQuery);
