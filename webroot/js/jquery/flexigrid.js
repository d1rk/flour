/*
  * Flexigrid for jQuery - New Wave Grid
 *
 * Copyright (c) 2008 Paulo P. Marinas (flexigrid.info)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * $Date: 2010-03-25 00:09:43 +0800 (Thr, 25 Mar 2010) $
 */

(function(jQuery) {

	jQuery.addFlex = function(t, p) {

		if (t.grid)
			return false; // return if already exist

		// apply default properties
		p = jQuery.extend( {
			height : 200, // default height
			width : 'auto', // auto width
			striped : true, // apply odd even stripes
			novstripe : false, // don't apply the vertical stripe styling
			minwidth : 30, // min width of columns
			minheight : 80, // min height of columns
			resizable : true, // resizable table
			url : false, // ajax url
			method : 'POST', // data sending method
			dataType : 'xml', // type of data loaded
			errormsg : 'Connection Error',
			usepager : false, //
			nowrap : true, //
			page : 1, // current page
			total : 1, // total pages
			useRp : true, // use the results per page select box
			rp : 15, // results per page
			rpOptions : [ 10, 15, 20, 25, 40 ],
			title : false,
			pagestat : 'Displaying {from} to {to} of {total} items',
			procmsg : 'Processing, please wait ...',
			query : '',
			qtype : '',
			nomsg : 'No items',
			minColToggle : 1, // minimum allowed column to be hidden
			showToggleBtn : true, // show or hide column toggle popup
			hideOnSubmit : true,
			autoload : true,
			blockOpacity : 0.5,
			onToggleCol : false,
			onChangeSort : false,
			onSuccess : false,
			onSubmit : false
		// using a custom populate function
				}, p);

		jQuery(t).show().width('');

		// create grid class
		var g = {
			hset : {},
			rePosDrag : function() {

				var cdleft = 0 - jQuery(this.hDiv).scrollLeft();
				if (cdleft < 0)
					cdleft -= Math.floor(p.cgwidth / 2);
				jQuery(g.cDrag).css( {
					top : g.hDiv.offsetTop + 1
				});
				var cdpad = this.cdpad;

				jQuery('div', g.cDrag).hide();

				jQuery('thead tr:first th:visible', this.hDiv).each(function() {
					var n = jQuery('thead tr:first th:visible', g.hDiv).index(this);

					var cdpos = jQuery('div', this).width();
					if (cdleft == 0)
						cdleft -= Math.floor(p.cgwidth / 2);

					cdpos = cdpos + cdleft + cdpad;
					
					jQuery('div:eq(' + n + ')', g.cDrag).css( {
						'left' : cdpos
					}).show();

					cdleft = cdpos;
				});

			},
			fixHeight : function(newH) {
				newH = false;
				if (!newH)
					newH = jQuery(g.bDiv).height();
				var hdHeight = jQuery(this.hDiv).height();
				jQuery('div', this.cDrag).each(function() {
					jQuery(this).height(newH + hdHeight);
				});

				var nd = parseInt(jQuery(g.nDiv).height());

				if (nd > newH)
					jQuery(g.nDiv).height(newH).width(200);
				else
					jQuery(g.nDiv).height('auto').width('');

				jQuery(g.block).css( {
					height : newH,
					marginBottom : (newH * -1)
				});

				var hrH = g.bDiv.offsetTop + newH;
				if (p.height != 'auto' && p.resizable)
					hrH = g.vDiv.offsetTop;
				jQuery(g.rDiv).css( {
					height : hrH
				});

			},
			dragStart : function(dragtype, e, obj) { // default drag function start

				if (dragtype == 'colresize') // column resize
				{
					jQuery(g.nDiv).hide();
					jQuery(g.nBtn).hide();
					var n = jQuery('div', this.cDrag).index(obj);
					var ow = jQuery('th:visible div:eq(' + n + ')', this.hDiv)
							.width();
					jQuery(obj).addClass('dragging').siblings().hide();
					jQuery(obj).prev().addClass('dragging').show();

					this.colresize = {
						startX : e.pageX,
						ol : parseInt(obj.style.left),
						ow : ow,
						n : n
					};
					jQuery('body').css('cursor', 'col-resize');
				} else if (dragtype == 'vresize') // table resize
				{
					var hgo = false;
					if (obj) {
						hgo = true;
						jQuery('body').css('cursor', 'col-resize');
					} else {
						jQuery('body').css('cursor', 'row-resize');
					}
					this.vresize = {
						h : p.height,
						sy : e.pageY,
						w : p.width,
						sx : e.pageX,
						hgo : hgo
					};
				} else if (dragtype == 'colMove') // column header drag
				{
					jQuery(g.nDiv).hide();
					jQuery(g.nBtn).hide();
					this.hset = jQuery(this.hDiv).offset();
					this.hset.right = this.hset.left
							+ jQuery('table', this.hDiv).width();
					this.hset.bottom = this.hset.top
							+ jQuery('table', this.hDiv).height();
					this.dcol = obj;
					this.dcoln = jQuery('th', this.hDiv).index(obj);

					this.colCopy = jQuery('<div>').addClass("colCopy").html(obj.innerHTML).css( {
						position : 'absolute',
						float : 'left',
						textAlign : obj.align
					}).hide();
					if (jQuery.browser.msie) {
						this.colCopy.addClass("ie");
					}
					jQuery('body').append(this.colCopy);
					jQuery(this.cDrag).hide();

				}

				jQuery('body').noSelect();

			},
			dragMove : function(e) {

				if (this.colresize) // column resize
				{
					var n = this.colresize.n;
					var diff = e.pageX - this.colresize.startX;
					var nleft = this.colresize.ol + diff;
					var nw = this.colresize.ow + diff;
					if (nw > p.minwidth) {
						jQuery('div:eq(' + n + ')', this.cDrag).css('left', nleft);
						this.colresize.nw = nw;
					}
				} else if (this.vresize) // table resize
				{
					var v = this.vresize;
					var y = e.pageY;
					var diff = y - v.sy;

					if (!p.defwidth)
						p.defwidth = p.width;

					if (p.width != 'auto' && !p.nohresize && v.hgo) {
						var x = e.pageX;
						var xdiff = x - v.sx;
						var newW = v.w + xdiff;
						if (newW > p.defwidth) {
							this.gDiv.style.width = newW + 'px';
							p.width = newW;
						}
					}

					var newH = v.h + diff;
					if ((newH > p.minheight || p.height < p.minheight)
							&& !v.hgo) {
						this.bDiv.style.height = newH + 'px';
						p.height = newH;
						this.fixHeight(newH);
					}
					v = null;
				} else if (this.colCopy) {
					jQuery(this.dcol).addClass('thMove').removeClass('thOver');
					if (e.pageX > this.hset.right || e.pageX < this.hset.left
							|| e.pageY > this.hset.bottom
							|| e.pageY < this.hset.top) {
						jQuery('body').css('cursor', 'move');
					} else
						jQuery('body').css('cursor', 'pointer');
					this.colCopy.css( {
						top : e.pageY + 10,
						left : e.pageX + 20
					}).show();
				}

			},
			dragEnd : function() {

				if (this.colresize) {
					var n = this.colresize.n;
					var nw = this.colresize.nw;

					jQuery('th:visible div:eq(' + n + ')', this.hDiv).width(nw);
					jQuery('tr', this.bDiv).each(
							function() {
								jQuery('td:visible div:eq(' + n + ')', this).width(nw);
							});
					jQuery(this.hDiv).scrollLeft(jQuery(this.bDiv).scrollLeft());

					jQuery('div:eq(' + n + ')', this.cDrag).siblings().show();
					jQuery('.dragging', this.cDrag).removeClass('dragging');
					this.rePosDrag();
					this.fixHeight();
					this.colresize = false;
				} else if (this.vresize) {
					this.vresize = false;
				} else if (this.colCopy) {
					this.colCopy.remove();
					if (this.dcolt != null) {

						if (this.dcoln > this.dcolt)

							jQuery('th:eq(' + this.dcolt + ')', this.hDiv).before(
									this.dcol);
						else
							jQuery('th:eq(' + this.dcolt + ')', this.hDiv).after(
									this.dcol);

						this.switchCol(this.dcoln, this.dcolt);
						this.cdropleft.remove();
						this.cdropright.remove();
						this.rePosDrag();

					}

					this.dcol = null;
					this.hset = null;
					this.dcoln = null;
					this.dcolt = null;
					this.colCopy = null;

					jQuery('.thMove', this.hDiv).removeClass('thMove');
					jQuery(this.cDrag).show();
				}
				jQuery('body').css('cursor', 'default').noSelect(false);
			},
			toggleCol : function(cid, visible) {

				var ncol = jQuery("th[axis='col" + cid + "']", this.hDiv)[0];
				var n = jQuery('thead th', g.hDiv).index(ncol);
				var cb = jQuery('input[value=' + cid + ']', g.nDiv)[0];

				if (visible == null) {
					visible = ncol.hide;
				}

				if (jQuery('input:checked', g.nDiv).length < p.minColToggle
						&& !visible)
					return false;

				if (visible) {
					ncol.hide = false;
					jQuery(ncol).show();
					cb.checked = true;
				} else {
					ncol.hide = true;
					jQuery(ncol).hide();
					cb.checked = false;
				}

				jQuery('tbody tr', t).each(function() {
					if (visible)
						jQuery('td:eq(' + n + ')', this).show();
					else
						jQuery('td:eq(' + n + ')', this).hide();
				});

				this.rePosDrag();

				if (p.onToggleCol)
					p.onToggleCol(cid, visible);

				return visible;
			},
			switchCol : function(cdrag, cdrop) { // switch columns

				jQuery('tbody tr', t).each(
						function() {
							if (cdrag > cdrop)
								jQuery('td:eq(' + cdrop + ')', this).before(
										jQuery('td:eq(' + cdrag + ')', this));
							else
								jQuery('td:eq(' + cdrop + ')', this).after(
										jQuery('td:eq(' + cdrag + ')', this));
						});

				// switch order in nDiv
				if (cdrag > cdrop)
					jQuery('tr:eq(' + cdrop + ')', this.nDiv).before(
							jQuery('tr:eq(' + cdrag + ')', this.nDiv));
				else
					jQuery('tr:eq(' + cdrop + ')', this.nDiv).after(
							jQuery('tr:eq(' + cdrag + ')', this.nDiv));

				if (jQuery.browser.msie && jQuery.browser.version < 7.0)
					jQuery('tr:eq(' + cdrop + ') input', this.nDiv)[0].checked = true;

				jQuery(this.hDiv).scrollLeft(jQuery(this.bDiv).scrollLeft());
			},
			scroll : function() {
				jQuery(this.hDiv).scrollLeft(jQuery(this.bDiv).scrollLeft());
				this.rePosDrag();
			},
			addData : function(data) { // parse data
				if (p.preProcess)
					data = p.preProcess(data);

				jQuery('.pReload', this.pDiv).removeClass('loading');
				this.loading = false;

				if (!data) {
					jQuery('.pPageStat', this.pDiv).html(p.errormsg);
					return false;
				}

				if (p.dataType == 'xml')
					p.total = +jQuery('rows total', data).text();
				else
					p.total = data.total;

				if (p.total == 0) {
					jQuery('tr, a, td, div', t).unbind();
					jQuery(t).empty();
					p.pages = 1;
					p.page = 1;
					this.buildpager();
					jQuery('.pPageStat', this.pDiv).html(p.nomsg);
					return false;
				}

				p.pages = Math.ceil(p.total / p.rp);

				if (p.dataType == 'xml')
					p.page = jQuery('rows page', data).text();
				else
					p.page = data.page;

				this.buildpager();

			// build new body
			var tbody = document.createElement('tbody');
			var idx = 0;

			if (p.dataType == 'json') {
				jQuery.each(data.rows, function(i, row) {
					var tr = document.createElement('tr');
					if (i % 2 && p.striped)
						tr.className = 'erow';

					if (row.id)
						tr.id = 'row' + row.id;

					// add cell
					jQuery('thead tr:first th', g.hDiv).each(function() {
						var td = document.createElement('td');
						idx = jQuery(this).attr('axis').substr(3);
						td.align = this.align;
						//If the json elements aren't named (which is typical), use numeric order
						if(typeof row.cell[idx] != "undefined"){
							td.innerHTML = row.cell[idx];
						} else {
							td.innerHTML = row.cell[p.colModel[idx].name];
						}
						jQuery(tr).append(td);
						td = null;
					});

					// handle if grid has no headers
					if (jQuery('thead', this.gDiv).length < 1) {
						for (idx = 0; idx < cell.length; idx++) {
							var td = document.createElement('td');
							//If the json elements aren't named (which is typical), use numeric order
							if(typeof row.cell[idx] != "undefined"){
								td.innerHTML = row.cell[idx];
							} else {
								td.innerHTML = row.cell[p.colModel[idx].name];
							}
							jQuery(tr).append(td);
							td = null;
						}
					}

					jQuery(tbody).append(tr);
					tr = null;
				});

			} else if (p.dataType == 'xml') {
				i = 1;
				jQuery("rows row", data).each(function() {
					i++;
					var tr = document.createElement('tr');
					if (i % 2 && p.striped)
						tr.className = 'erow';

					var nid = jQuery(this).attr('id');
					if (nid)
						tr.id = 'row' + nid;

					nid = null;
					var robj = this;
					jQuery('thead tr:first th', g.hDiv).each(function() {
						var td = document.createElement('td');
						var idx = jQuery(this).attr('axis').substr(3);
						td.align = this.align;
						td.innerHTML = jQuery("cell:eq(" + idx + ")", robj).text();
						jQuery(tr).append(td);
						td = null;
					});
					
					//handle if grid has no headers
					if (jQuery('thead', this.gDiv).length < 1){
						jQuery('cell', this).each(function() {
							var td = document.createElement('td');
							td.innerHTML = jQuery(this).text();
							jQuery(tr).append(td);
							td = null;
						});
					}

					jQuery(tbody).append(tr);
					tr = null;
					robj = null;
				});

			}

			jQuery('tr', t).unbind();
			jQuery(t).empty();

			jQuery(t).append(tbody);
			this.addCellProp();
			this.addRowProp();

			this.rePosDrag();

			tbody = null;
			data = null;
			i = null;

			if (p.onSuccess)
				p.onSuccess();
			if (p.hideOnSubmit)
				jQuery(g.block).remove();

			jQuery(this.hDiv).scrollLeft(jQuery(this.bDiv).scrollLeft());
			if (jQuery.browser.opera)
				jQuery(t).css('visibility', 'visible');
		},
		changeSort : function(th) { // change sortorder

				if (this.loading)
					return true;

				jQuery(g.nDiv).hide();
				jQuery(g.nBtn).hide();

				if (p.sortname == jQuery(th).attr('abbr')) {
					if (p.sortorder == 'asc')
						p.sortorder = 'desc';
					else
						p.sortorder = 'asc';
				}

				jQuery(th).addClass('sorted').siblings().removeClass('sorted');
				jQuery('.sdesc', this.hDiv).removeClass('sdesc');
				jQuery('.sasc', this.hDiv).removeClass('sasc');
				jQuery('div', th).addClass('s' + p.sortorder);
				p.sortname = jQuery(th).attr('abbr');

				if (p.onChangeSort)
					p.onChangeSort(p.sortname, p.sortorder);
				else
					this.populate();

			},
			buildpager : function() { // rebuild pager based on new properties

				jQuery('.pcontrol input', this.pDiv).val(p.page);
				jQuery('.pcontrol span', this.pDiv).html(p.pages);

				var r1 = (p.page - 1) * p.rp + 1;
				var r2 = r1 + p.rp - 1;

				if (p.total < r2)
					r2 = p.total;

				var stat = p.pagestat;
				stat = stat.replace(/{from}/, r1).replace(/{to}/, r2).replace(/{total}/, p.total);

				jQuery('.pPageStat', this.pDiv).html(stat);

			},
			populate : function() { // get latest data

				if (this.loading)
					return true;

				if (p.onSubmit) {
					var gh = p.onSubmit();
					if (!gh)
						return false;
				}

				this.loading = true;
				if (!p.url)
					return false;

				jQuery('.pPageStat', this.pDiv).html(p.procmsg);

				jQuery('.pReload', this.pDiv).addClass('loading');

				jQuery(g.block).css( {
					top : g.bDiv.offsetTop
				});

				if (p.hideOnSubmit)
					jQuery(this.gDiv).prepend(g.block); // jQuery(t).hide();

				if (jQuery.browser.opera)
					jQuery(t).css('visibility', 'hidden');

				if (!p.newp)
					p.newp = 1;

				if (p.page > p.pages)
					p.page = p.pages;
				// var param = {page:p.newp, rp: p.rp, sortname: p.sortname,
				// sortorder: p.sortorder, query: p.query, qtype: p.qtype};
				var param = [ {
					name : 'page',
					value : p.newp
				}, {
					name : 'rp',
					value : p.rp
				}, {
					name : 'sortname',
					value : p.sortname
				}, {
					name : 'sortorder',
					value : p.sortorder
				}, {
					name : 'query',
					value : p.query
				}, {
					name : 'qtype',
					value : p.qtype
				} ];

				if (p.params) {
					for ( var pi = 0; pi < p.params.length; pi++)
						param[param.length] = p.params[pi];
				}

				jQuery.ajax( {
					type : p.method,
					url : p.url,
					data : param,
					dataType : p.dataType,
					success : function(data) {
						g.addData(data);
					},
					error : function(data) {
						try {
							if (p.onError)
								p.onError(data);
						} catch (e) {
						}
					}
				});
			},
			doSearch : function() {
				p.query = jQuery('input[name=q]', g.sDiv).val();
				p.qtype = jQuery('select[name=qtype]', g.sDiv).val();
				p.newp = 1;

				this.populate();
			},
			changePage : function(ctype) { // change page

				if (this.loading)
					return true;

				switch (ctype) {
				case 'first':
					p.newp = 1;
					break;
				case 'prev':
					if (p.page > 1)
						p.newp = parseInt(p.page) - 1;
					break;
				case 'next':
					if (p.page < p.pages)
						p.newp = parseInt(p.page) + 1;
					break;
				case 'last':
					p.newp = p.pages;
					break;
				case 'input':
					var nv = parseInt(jQuery('.pcontrol input', this.pDiv).val());
					if (isNaN(nv))
						nv = 1;
					if (nv < 1)
						nv = 1;
					else if (nv > p.pages)
						nv = p.pages;
					jQuery('.pcontrol input', this.pDiv).val(nv);
					p.newp = nv;
					break;
				}

				if (p.newp == p.page)
					return false;

				if (p.onChangePage)
					p.onChangePage(p.newp);
				else
					this.populate();

			},
			addCellProp : function() {

				jQuery('tbody tr td', g.bDiv).each(function() {
					var tdDiv = document.createElement('div');
					var n = jQuery('td', jQuery(this).parent()).index(this);
					var pth = jQuery('th:eq(' + n + ')', g.hDiv).get(0);

					if (pth != null) {
						if (p.sortname == jQuery(pth).attr('abbr') && p.sortname) {
							this.className = 'sorted';
						}
						jQuery(tdDiv).css( {
							textAlign : pth.align,
							width : jQuery('div:first', pth)[0].style.width
						});

						if (pth.hide)
							jQuery(this).hide();

					}

					if (p.nowrap == false)
						jQuery(tdDiv).css('white-space', 'normal');

					if (this.innerHTML == '')
						this.innerHTML = '&nbsp;';

					tdDiv.innerHTML = this.innerHTML;

					var prnt = jQuery(this).parent()[0];
					var pid = false;
					if (prnt.id)
						pid = prnt.id.substr(3);

					if (pth != null) {
						if (pth.process)
							pth.process(tdDiv, pid);
					}
					jQuery(this).empty().append(tdDiv); // wrap content
				});

			},
			getCellDim : function(obj) // get cell prop for editable event
			{
				return {
					ht : jQuery(obj).height(),
					wt : parseInt(obj.style.width),
					top : obj.offsetParent.offsetTop,
					left : obj.offsetParent.offsetLeft,
					pdl : jQuery(obj).css('paddingLeft'),
					pdt : jQuery(obj).css('paddingTop'),
					pht : jQuery(obj).parent().height(),
					pwt : jQuery(obj).parent().width()
				};
			},
			addRowProp : function() {
				jQuery('tbody tr', g.bDiv).each(function() {
					jQuery(this).click(function(e) {
						var obj = (e.target || e.srcElement);
						if (obj.href || obj.type)
							return true;
						jQuery(this).toggleClass('trSelected');
						if (p.singleSelect)
							jQuery(this).siblings().removeClass('trSelected');
					}).mousedown(function(e) {
						if (e.shiftKey) {
							jQuery(this).toggleClass('trSelected');
							g.multisel = true;
							this.focus();
							jQuery(g.gDiv).noSelect();
						}
					}).mouseup(function() {
						if (g.multisel) {
							g.multisel = false;
							jQuery(g.gDiv).noSelect(false);
						}
					}).hover(function(e) {
						if (g.multisel) {
							jQuery(this).toggleClass('trSelected');
						}
					}, function() {
					});

					if (jQuery.browser.msie && jQuery.browser.version < 7.0) {
						jQuery(this).hover(function() {
							jQuery(this).addClass('trOver');
						}, function() {
							jQuery(this).removeClass('trOver');
						});
					}
				});

			},
			pager : 0
		};

		// create model if any
		if (p.colModel) {
			var th;
			thead = document.createElement('thead');
			tr = document.createElement('tr');

			for (i = 0; i < p.colModel.length; i++) {
				var cm = p.colModel[i];
				th = document.createElement('th');
				th.innerHTML = cm.display;

				if (cm.name && cm.sortable){
					jQuery(th).attr('abbr', cm.name);
				}

				// th.idx = i;
				jQuery(th).attr('axis', 'col' + i);

				if (cm.align){
					th.align = cm.align;
				}

				if (cm.width) {
					jQuery(th).width(parseInt(cm.width));
				}

				if (cm.hide) {
					th.hide = true;
				}

				if (cm.process) {
					th.process = cm.process;
				}

				jQuery(tr).append(th);
			}
			jQuery(thead).append(tr);
			jQuery(t).prepend(thead);
		} // end if p.colmodel

		// init divs
		g.gDiv = document.createElement('div'); // create global container
		g.mDiv = document.createElement('div'); // create title container
		g.hDiv = document.createElement('div'); // create header container
		g.bDiv = document.createElement('div'); // create body container
		g.vDiv = document.createElement('div'); // create grip
		g.rDiv = document.createElement('div'); // create horizontal resizer
		g.cDrag = document.createElement('div'); // create column drag
		g.block = document.createElement('div'); // create blocker
		g.nDiv = document.createElement('div'); // create column show/hide popup
		g.nBtn = document.createElement('div'); // create column show/hide button
		g.iDiv = document.createElement('div'); // create editable layer
		g.tDiv = document.createElement('div'); // create toolbar
		g.sDiv = document.createElement('div');

		if (p.usepager)
			g.pDiv = document.createElement('div'); // create pager container
		g.hTable = jQuery('<table>');

		// set gDiv
		g.gDiv.className = 'flexigrid';
		if (p.width != 'auto')
			g.gDiv.style.width = p.width + 'px';

		// add conditional classes
		if (jQuery.browser.msie)
			jQuery(g.gDiv).addClass('ie');

		if (p.novstripe)
			jQuery(g.gDiv).addClass('novstripe');

		jQuery(t).before(g.gDiv);
		jQuery(g.gDiv).append(t);

		// set toolbar
		if (p.buttons) {
			g.tDiv.className = 'tDiv';
			var tDiv2 = jQuery('<div>').addClass('tDiv2');

			for (i = 0; i < p.buttons.length; i++) {
				var btn = p.buttons[i];
				if (!btn.separator) {
					var btnDiv = document.createElement('div');
					btnDiv.className = 'fbutton';
					btnDiv.innerHTML = "<div><span>" + btn.name
							+ "</span></div>";
					if (btn.bclass)
						jQuery('span', btnDiv).addClass(btn.bclass).css( {
							paddingLeft : 20
						});
					btnDiv.onpress = btn.onpress;
					btnDiv.name = btn.name;
					if (btn.onpress) {
						jQuery(btnDiv).click(function() {
							this.onpress(this.name, g.gDiv);
						});
					}
					tDiv2.append(btnDiv);
					if (jQuery.browser.msie && jQuery.browser.version < 7.0) {
						jQuery(btnDiv).hover(function() {
							jQuery(this).addClass('fbOver');
						}, function() {
							jQuery(this).removeClass('fbOver');
						});
					}

				} else {
					tDiv2.append("<div class='btnseparator'></div>");
				}
			}
			jQuery(g.tDiv).append(tDiv2);
			jQuery(g.tDiv).append("<div style='clear:both'></div>");
			jQuery(g.gDiv).prepend(g.tDiv);
		}

		// set hDiv
		g.hDiv.className = 'hDiv';

		jQuery(t).before(g.hDiv);

		// set hTable
		var thead = jQuery("thead:first", t).get(0);
		if (thead)
			g.hTable.append(thead);
		thead = null;
		jQuery(g.hDiv).append('<div class="hDivBox"></div>');
		jQuery('div', g.hDiv).append(g.hTable);

		if (!p.colmodel)
			var ci = 0;

		// setup thead
		jQuery('thead tr:first th', g.hDiv)
				.each(
						function() {
							var thdiv = document.createElement('div');

							if (jQuery(this).attr('abbr')) {
								jQuery(this).click(function(e) {

									if (!jQuery(this).hasClass('thOver'))
										return false;
									var obj = (e.target || e.srcElement);
									if (obj.href || obj.type)
										return true;
									g.changeSort(this);
								});

								if (jQuery(this).attr('abbr') == p.sortname) {
									this.className = 'sorted';
									thdiv.className = 's' + p.sortorder;
								}
							}

							if (this.hide)
								jQuery(this).hide();

							if (!p.colmodel) {
								jQuery(this).attr('axis', 'col' + ci++);
							}

							jQuery(thdiv).css( {
								textAlign : this.align,
								width : jQuery(this).width()
							});
							thdiv.innerHTML = this.innerHTML;

							jQuery(this)
									.empty()
									.append(thdiv)
									.width('')
									.mousedown(function(e) {
										g.dragStart('colMove', e, this);
									})
									.hover(
											function() {
												if (!g.colresize
														&& !jQuery(this).hasClass(
																'thMove')
														&& !g.colCopy)
													jQuery(this).addClass('thOver');

												if (jQuery(this).attr('abbr') != p.sortname
														&& !g.colCopy
														&& !g.colresize
														&& jQuery(this).attr('abbr'))
													jQuery('div', this).addClass(
															's' + p.sortorder);
												else if (jQuery(this).attr('abbr') == p.sortname
														&& !g.colCopy
														&& !g.colresize
														&& jQuery(this).attr('abbr')) {
													var no = '';
													if (p.sortorder == 'asc')
														no = 'desc';
													else
														no = 'asc';
													jQuery('div', this).removeClass(
															's' + p.sortorder)
															.addClass('s' + no);
												}

												if (g.colCopy) {
													var n = jQuery('th', g.hDiv)
															.index(this);

													if (n == g.dcoln)
														return false;

													if (n < g.dcoln)
														jQuery(this).append(
																g.cdropleft);
													else
														jQuery(this).append(
																g.cdropright);

													g.dcolt = n;

												} else if (!g.colresize) {

													var nv = jQuery('th:visible',
															g.hDiv).index(this);
													var onl = parseInt(jQuery(
															'div:eq(' + nv + ')',
															g.cDrag)
															.css('left'));
													var nw = jQuery(g.nBtn).outerWidth();
													nl = onl
															- nw
															+ Math.floor(p.cgwidth / 2);

													jQuery(g.nDiv).hide();
													jQuery(g.nBtn).hide();

													jQuery(g.nBtn).css( {
														'left' : nl,
														'top' : g.hDiv.offsetTop
													}).show();

													var ndw = jQuery(g.nDiv).width();

													jQuery(g.nDiv).css( {
														top : g.bDiv.offsetTop
													});

													if ((nl + ndw) > jQuery(g.gDiv).width()){
														jQuery(g.nDiv).css('left',
																onl - ndw + 1);
													} else {
														jQuery(g.nDiv).css('left',
																nl);
													}

													if (jQuery(this).hasClass(
															'sorted'))
														jQuery(g.nBtn).addClass(
																'srtd');
													else
														jQuery(g.nBtn).removeClass(
																'srtd');

												}

											},
											function() {
												jQuery(this).removeClass('thOver');
												if (jQuery(this).attr('abbr') != p.sortname)
													jQuery('div', this).removeClass(
															's' + p.sortorder);
												else if (jQuery(this).attr('abbr') == p.sortname) {
													var no = '';
													if (p.sortorder == 'asc')
														no = 'desc';
													else
														no = 'asc';

													jQuery('div', this).addClass(
															's' + p.sortorder)
															.removeClass(
																	's' + no);
												}
												if (g.colCopy) {
													g.cdropleft.remove();
													g.cdropright.remove();
													g.dcolt = null;
												}
											}); // wrap content
						});

		// set bDiv
		g.bDiv.className = 'bDiv';
		jQuery(t).before(g.bDiv);
		jQuery(g.bDiv).css( {
			height : (p.height == 'auto') ? 'auto' : p.height + "px"
		}).scroll(function(e) {
			g.scroll()
		}).append(t);

		if (p.height == 'auto') {
			jQuery('table', g.bDiv).addClass('autoht');
		}

		// add td properties
		g.addCellProp();

		// add row properties
		g.addRowProp();

		// set cDrag
		var cdcol = jQuery('thead tr:first th:first', g.hDiv).get(0);

		if (cdcol != null) {
			g.cDrag.className = 'cDrag';
			g.cdpad = 0;

			g.cdpad += (isNaN(parseInt(jQuery('div', cdcol).css('borderLeftWidth'))) ? 0
					: parseInt(jQuery('div', cdcol).css('borderLeftWidth')));
			g.cdpad += (isNaN(parseInt(jQuery('div', cdcol).css('borderRightWidth'))) ? 0
					: parseInt(jQuery('div', cdcol).css('borderRightWidth')));
			g.cdpad += (isNaN(parseInt(jQuery('div', cdcol).css('paddingLeft'))) ? 0
					: parseInt(jQuery('div', cdcol).css('paddingLeft')));
			g.cdpad += (isNaN(parseInt(jQuery('div', cdcol).css('paddingRight'))) ? 0
					: parseInt(jQuery('div', cdcol).css('paddingRight')));
			g.cdpad += (isNaN(parseInt(jQuery(cdcol).css('borderLeftWidth'))) ? 0
					: parseInt(jQuery(cdcol).css('borderLeftWidth')));
			g.cdpad += (isNaN(parseInt(jQuery(cdcol).css('borderRightWidth'))) ? 0
					: parseInt(jQuery(cdcol).css('borderRightWidth')));
			g.cdpad += (isNaN(parseInt(jQuery(cdcol).css('paddingLeft'))) ? 0
					: parseInt(jQuery(cdcol).css('paddingLeft')));
			g.cdpad += (isNaN(parseInt(jQuery(cdcol).css('paddingRight'))) ? 0
					: parseInt(jQuery(cdcol).css('paddingRight')));

			jQuery(g.bDiv).before(g.cDrag);

			var cdheight = jQuery(g.bDiv).height();
			var hdheight = jQuery(g.hDiv).height();

			jQuery(g.cDrag).css( {
				top : -hdheight
			});

			jQuery('thead tr:first th', g.hDiv).each(function() {
				var cgDiv = jQuery('<div>');
				if (!p.cgwidth){
					p.cgwidth = cgDiv.width();
				}
				cgDiv.css( {
					height : cdheight + hdheight
				}).mousedown(function(e) {
					g.dragStart('colresize', e, this);
				});
				if (jQuery.browser.msie && jQuery.browser.version < 7.0) {
					g.fixHeight(jQuery(g.gDiv).height());
					cgDiv.hover(function() {
						g.fixHeight();
						jQuery(this).addClass('dragging')
					}, function() {
						if (!g.colresize)
							jQuery(this).removeClass('dragging')
					});
				}
				jQuery(g.cDrag).append(cgDiv);
			});
		}

		// add stripe
		if (p.striped)
			jQuery('tbody tr:odd', g.bDiv).addClass('erow');

		if (p.resizable && p.height != 'auto') {
			g.vDiv.className = 'vGrip';
			jQuery(g.vDiv).mousedown(function(e) {
				g.dragStart('vresize', e)
			}).html('<span></span>');
			jQuery(g.bDiv).after(g.vDiv);
		}

		if (p.resizable && p.width != 'auto' && !p.nohresize) {
			g.rDiv.className = 'hGrip';
			jQuery(g.rDiv).mousedown(function(e) {
				g.dragStart('vresize', e, true);
			}).html('<span></span>').css('height', jQuery(g.gDiv).height());
			if (jQuery.browser.msie && jQuery.browser.version < 7.0) {
				jQuery(g.rDiv).hover(function() {
					jQuery(this).addClass('hgOver');
				}, function() {
					jQuery(this).removeClass('hgOver');
				});
			}
			jQuery(g.gDiv).append(g.rDiv);
		}

		// add pager
		if (p.usepager) {
			g.pDiv.className = 'pDiv';
			g.pDiv.innerHTML = '<div class="pDiv2"></div>';
			jQuery(g.bDiv).after(g.pDiv);
			var html = ' <div class="pGroup"> <div class="pFirst pButton"><span></span></div><div class="pPrev pButton"><span></span></div> </div> <div class="btnseparator"></div> <div class="pGroup"><span class="pcontrol">Page <input type="text" size="4" value="1" /> of <span> 1 </span></span></div> <div class="btnseparator"></div> <div class="pGroup"> <div class="pNext pButton"><span></span></div><div class="pLast pButton"><span></span></div> </div> <div class="btnseparator"></div> <div class="pGroup"> <div class="pReload pButton"><span></span></div> </div> <div class="btnseparator"></div> <div class="pGroup"><span class="pPageStat"></span></div>';
			jQuery('div', g.pDiv).html(html);

			jQuery('.pReload', g.pDiv).click(function() {
				g.populate()
			});
			jQuery('.pFirst', g.pDiv).click(function() {
				g.changePage('first')
			});
			jQuery('.pPrev', g.pDiv).click(function() {
				g.changePage('prev')
			});
			jQuery('.pNext', g.pDiv).click(function() {
				g.changePage('next')
			});
			jQuery('.pLast', g.pDiv).click(function() {
				g.changePage('last')
			});
			jQuery('.pcontrol input', g.pDiv).keydown(function(e) {
				if (e.keyCode == 13)
					g.changePage('input')
			});
			if (jQuery.browser.msie && jQuery.browser.version < 7)
				jQuery('.pButton', g.pDiv).hover(function() {
					jQuery(this).addClass('pBtnOver');
				}, function() {
					jQuery(this).removeClass('pBtnOver');
				});

			if (p.useRp) {
				var opt = "";
				for ( var nx = 0; nx < p.rpOptions.length; nx++) {
					if (p.rp == p.rpOptions[nx])
						sel = 'selected="selected"';
					else
						sel = '';
					opt += "<option value='" + p.rpOptions[nx] + "' " + sel
							+ " >" + p.rpOptions[nx] + "&nbsp;&nbsp;</option>";
				}
				;
				jQuery('.pDiv2', g.pDiv)
						.prepend(
								"<div class='pGroup'><select name='rp'>"
										+ opt
										+ "</select></div> <div class='btnseparator'></div>");
				jQuery('select', g.pDiv).change(function() {
					if (p.onRpChange)
						p.onRpChange(+this.value);
					else {
						p.newp = 1;
						p.rp = +this.value;
						g.populate();
					}
				});
			}

			// add search button
			if (p.searchitems) {
				jQuery('.pDiv2', g.pDiv)
						.prepend(
								"<div class='pGroup'> <div class='pSearch pButton'><span></span></div> </div>  <div class='btnseparator'></div>");
				jQuery('.pSearch', g.pDiv).click(
						function() {
							jQuery(g.sDiv).slideToggle(
									'fast',
									function() {
										jQuery('.sDiv:visible input:first', g.gDiv)
												.trigger('focus');
									});
						});
				// add search box
				g.sDiv.className = 'sDiv';

				sitems = p.searchitems;

				var sopt = "";
				for ( var s = 0; s < sitems.length; s++) {
					if (p.qtype == '' && sitems[s].isdefault == true) {
						p.qtype = sitems[s].name;
						sel = 'selected="selected"';
					} else
						sel = '';
					sopt += "<option value='" + sitems[s].name + "' " + sel
							+ " >" + sitems[s].display
							+ "&nbsp;&nbsp;</option>";
				}

				if (p.qtype == '')
					p.qtype = sitems[0].name;

				jQuery(g.sDiv)
						.append(
								"<div class='sDiv2'>Quick Search <input type='text' size='30' name='q' class='qsbox' /> <select name='qtype'>"
										+ sopt
										+ "</select> <input type='button' value='Clear' /></div>");

				jQuery('input[name=q],select[name=qtype]', g.sDiv).keydown(
						function(e) {
							if (e.keyCode == 13)
								g.doSearch()
						});
				jQuery('input[value=Clear]', g.sDiv).click(function() {
					jQuery('input[name=q]', g.sDiv).val('');
					p.query = '';
					g.doSearch();
				});
				jQuery(g.bDiv).after(g.sDiv);

			}

		}
		jQuery(g.pDiv, g.sDiv).append("<div style='clear:both'></div>");

		// add title
		if (p.title) {
			g.mDiv.className = 'mDiv';
			g.mDiv.innerHTML = '<div class="ftitle">' + p.title + '</div>';
			jQuery(g.gDiv).prepend(g.mDiv);
			if (p.showTableToggleBtn) {
				jQuery(g.mDiv)
						.append(
								'<div class="ptogtitle" title="Minimize/Maximize Table"><span></span></div>');
				jQuery('div.ptogtitle', g.mDiv).click(function() {
					jQuery(g.gDiv).toggleClass('hideBody');
					jQuery(this).toggleClass('vsble');
				});
			}
			// g.rePosDrag();
		}

		// setup cdrops
		g.cdropleft = jQuery('<span>').addClass('cdropleft');
		g.cdropright = jQuery('<span>').addClass('cdropright');

		// add block
		g.block.className = 'gBlock';
		var gh = jQuery(g.bDiv).height();
		var gtop = g.bDiv.offsetTop;
		jQuery(g.block).css( {
			width : g.bDiv.style.width,
			height : gh,
			background : 'white',
			position : 'relative',
			marginBottom : (gh * -1),
			zIndex : 1,
			top : gtop,
			left : '0px'
		});
		jQuery(g.block).fadeTo(0, p.blockOpacity);

		// add column control
		if (jQuery('th', g.hDiv).length) {

			g.nDiv.className = 'nDiv';
			g.nDiv.innerHTML = "<table cellpadding='0' cellspacing='0'><tbody></tbody></table>";
			jQuery(g.nDiv).css( {
				marginBottom : (gh * -1),
				top : gtop
			}).hide().noSelect();

			var cn = 0;

			jQuery('th div', g.hDiv).each(
					function() {
						var kcol = jQuery("th[axis='col" + cn + "']", g.hDiv).first();
						var chk = 'checked="checked"';
						if (!kcol.is(':visible'))
							chk = '';

						jQuery('tbody', g.nDiv).append(
								'<tr><td class="ndcol1"><input type="checkbox" '
										+ chk + ' class="togCol" value="' + cn
										+ '" /></td><td class="ndcol2">'
										+ jQuery(this).html() + '</td></tr>');
						cn++;
					});

			if (jQuery.browser.msie && jQuery.browser.version < 7.0)
				jQuery('tr', g.nDiv).hover(function() {
					jQuery(this).addClass('ndcolover');
				}, function() {
					jQuery(this).removeClass('ndcolover');
				});

			jQuery('td.ndcol2', g.nDiv).click(
					function() {
						if (jQuery('input:checked', g.nDiv).length <= p.minColToggle
								&& jQuery(this).prev().find('input')[0].checked)
							return false;
						return g.toggleCol(jQuery(this).prev().find('input').val());
					});

			jQuery('input.togCol', g.nDiv).click(
					function() {

						if (jQuery('input:checked', g.nDiv).length < p.minColToggle
								&& this.checked == false)
							return false;
						jQuery(this).parent().next().trigger('click');
						// return false;
					});

			jQuery(g.gDiv).prepend(g.nDiv);

			jQuery(g.nBtn).addClass('nBtn').html('<div></div>').attr('title',
					'Hide/Show Columns').click(function() {
				jQuery(g.nDiv).toggle();
				return true;
			});

			if (p.showToggleBtn)
				jQuery(g.gDiv).prepend(g.nBtn);

		}

		// add date edit layer
		jQuery(g.iDiv).addClass('iDiv').hide();
		jQuery(g.bDiv).append(g.iDiv);

		// add flexigrid events
		jQuery(g.bDiv).hover(function() {
			jQuery(g.nDiv).hide();
			jQuery(g.nBtn).hide();
		}, function() {
			if (g.multisel)
				g.multisel = false;
		});
		jQuery(g.gDiv).hover(function() {
		}, function() {
			jQuery(g.nDiv).hide();
			jQuery(g.nBtn).hide();
		});

		// add document events
		jQuery(document).mousemove(function(e) {
			g.dragMove(e)
		}).mouseup(function(e) {
			g.dragEnd()
		}).hover(function() {
		}, function() {
			g.dragEnd()
		});

		// browser adjustments
		if (jQuery.browser.msie && jQuery.browser.version < 7.0) {
			jQuery('.hDiv,.bDiv,.mDiv,.pDiv,.vGrip,.tDiv, .sDiv', g.gDiv).css( {
				width : '100%'
			});
			jQuery(g.gDiv).addClass('ie6');
			if (p.width != 'auto')
				jQuery(g.gDiv).addClass('ie6fullwidthbug');
		}

		g.rePosDrag();
		g.fixHeight();

		// make grid functions accessible
		t.p = p;
		t.grid = g;

		// load data
		if (p.url && p.autoload) {
			g.populate();
		}

		return t;

	};

	var docloaded = false;

	jQuery(document).ready(function() {
		docloaded = true
	});

	jQuery.fn.flexigrid = function(p) {

		return this.each(function() {
			if (!docloaded) {
				jQuery(this).hide();
				var t = this;
				jQuery(document).ready(function() {
					jQuery.addFlex(t, p);
				});
			} else {
				jQuery.addFlex(this, p);
			}
		});

	}; // end flexigrid

	jQuery.fn.flexReload = function(p) { // function to reload grid

		return this.each(function() {
			if (this.grid && this.p.url)
				this.grid.populate();
		});

	}; // end flexReload

	jQuery.fn.flexOptions = function(p) { // function to update general options

		return this.each(function() {
			if (this.grid)
				jQuery.extend(this.p, p);
		});

	}; // end flexOptions

	jQuery.fn.flexToggleCol = function(cid, visible) { // function to reload grid

		return this.each(function() {
			if (this.grid)
				this.grid.toggleCol(cid, visible);
		});

	}; // end flexToggleCol

	jQuery.fn.flexAddData = function(data) { // function to add data to grid

		return this.each(function() {
			if (this.grid)
				this.grid.addData(data);
		});

	};

	jQuery.fn.noSelect = function(p) { // no select plugin Paulo P. Marinas

		if (p == null)
			prevent = true;
		else
			prevent = p;

		if (prevent) {

			return this.each(function() {
				if (jQuery.browser.msie || jQuery.browser.safari)
					jQuery(this).bind('selectstart', function() {
						return false;
					});
				else if (jQuery.browser.mozilla) {
					jQuery(this).css('MozUserSelect', 'none');
					jQuery('body').trigger('focus');
				} else if (jQuery.browser.opera)
					jQuery(this).bind('mousedown', function() {
						return false;
					});
				else
					jQuery(this).attr('unselectable', 'on');
			});

		} else {

			return this.each(function() {
				if (jQuery.browser.msie || jQuery.browser.safari)
					jQuery(this).unbind('selectstart');
				else if (jQuery.browser.mozilla)
					jQuery(this).css('MozUserSelect', 'inherit');
				else if (jQuery.browser.opera)
					jQuery(this).unbind('mousedown');
				else
					jQuery(this).removeAttr('unselectable', 'on');
			});

		}

	}; //end noSelect

})(jQuery);