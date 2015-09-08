(function() {
  var bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  if (!this.Maslosoft) {
    this.Maslosoft = {};
  }

  if (!this.Maslosoft.Gallery) {
    this.Maslosoft.Gallery = {};
  }

  this.Maslosoft.Gallery.Presenter = (function() {
    Presenter.prototype.id = '';

    Presenter.prototype.element = null;

    Presenter.prototype.overlay = null;

    Presenter.prototype.view = null;

    Presenter.prototype.row = null;

    Presenter.prototype.controls = null;

    Presenter.prototype.play = null;

    Presenter.prototype.stop = null;

    Presenter.prototype.moves = 100;

    function Presenter(gallery) {
      this.resize = bind(this.resize, this);
      this.showImage = bind(this.showImage, this);
      this.zoomOut = bind(this.zoomOut, this);
      this.urlMouseMove = bind(this.urlMouseMove, this);
      this.mouseMove = bind(this.mouseMove, this);
      this._fadeOutControls = bind(this._fadeOutControls, this);
      this.fadeControls = bind(this.fadeControls, this);
      this.vm = gallery.vm;
      this.element = jQuery("#" + gallery.id);
      this.overlay = this.element.find('.maslosoft-gallery-overlay');
      this.view = this.overlay.find('.maslosoft-gallery-group-view');
      this.row = this.view.find('.maslosoft-gallery-image-row');
      this.controls = this.overlay.find('.maslosoft-gallery-controls');
      this.thumbs = this.controls.find('.maslosoft-gallery-controls-thumbs');
      this.thumbsSelector = this.controls.find('.maslosoft-gallery-controls-thumbs-selector');
      this.play = this.element.find('.maslosoft-gallery-play');
      this.pause = this.element.find('.maslosoft-gallery-pause');
      this.viewable = {
        width: 0,
        height: 0
      };
      if (gallery.options.fadeControls) {
        jQuery(document).on('mousemove', this.mouseMove);
        this.element.on("mousemove", "a[rel='tooltip']", this.urlMouseMove);
        jQuery(document).on('mousedown', this.fadeControls);
        jQuery(document).on('keydown', this.fadeControls);
        this.fadeControls();
      }
    }

    Presenter.prototype.timer = null;

    Presenter.prototype.fadeControls = function(e, data) {
      this.controls.stop(true, true);
      this.controls.fadeIn();
      clearTimeout(this.timer);
      return this.timer = setTimeout(this._fadeOutControls, 5000);
    };

    Presenter.prototype._fadeOutControls = function() {
      return this.controls.fadeOut('slow');
    };

    Presenter.prototype.mouseMove = function(e, data) {
      this.moves++;
      if (this.moves > 20) {
        console.log('mousemoves ' + this.moves);
        this.moves = 0;
        return this.fadeControls(e, data);
      }
    };

    Presenter.prototype.urlMouseMove = function(e) {
      console.log('url mouse move');
      return e.stopPropagation();
    };

    Presenter.prototype.zoomOut = function(e, data) {
      e.stopPropagation();
      e.preventDefault();
      return this.showGroup();
    };

    Presenter.prototype.showImage = function() {
      var animation, image, selector, view;
      this._calculateHeight();
      view = this.view;
      selector = "#mid-image-" + this.vm.selectedImage.id + " img";
      image = jQuery(selector);
      animation = {
        height: 0,
        width: 0
      };
      animation = {};
      jQuery('.maslosoft-gallery-groups img').not(selector).animate(animation, 200, function() {
        var elem, img, l, len, parents, results, val;
        img = jQuery(this);
        img.parent().hide();
        parents = img.parent().parents('.maslosoft-gallery-image-row');
        results = [];
        for (l = 0, len = parents.length; l < len; l++) {
          val = parents[l];
          elem = jQuery(val);
          if (!elem.find(selector).length) {
            results.push(elem.hide());
          } else {
            results.push(void 0);
          }
        }
        return results;
      });
      animation = {
        height: this.viewable.height,
        width: 'auto'
      };
      animation = {};
      image.animate(animation, 500);
      image.addClass('maslosoft-gallery-zoom-out');
      return this.showOverlay();
    };

    Presenter.prototype.showGroup = function() {
      var groups, images;
      groups = jQuery('.maslosoft-gallery-groups');
      groups.find('.maslosoft-gallery-image-row').show();
      groups.find('.maslosoft-gallery-image-url').show();
      images = groups.find('img');
      images.removeClass('maslosoft-gallery-zoom-out');
      images.height(0);
      images.show();
      return this.showOverlay();
    };

    Presenter.prototype.showOverlay = function() {
      console.log("scrollHeight: " + document.body.scrollHeight);
      console.log("clientHeight: " + document.body.clientHeight);
      if (document.body.scrollHeight === document.body.clientHeight) {
        jQuery('body').addClass('maslosoft-gallery-body-scrolls');
      }
      this.overlay.show();
      return this._calculateHeight();
    };

    Presenter.prototype.hideOverlay = function() {
      this.overlay.hide();
      return jQuery('body').removeClass('maslosoft-gallery-body-scrolls');
    };

    Presenter.prototype.resize = function() {
      return this._calculateHeight();
    };

    Presenter.prototype._calculate = function() {
      var ar, image, images, index, l, len, len1, oHeight, oImageHeight, oImageWidth, oWidth, p, part, partitions, ref, rows, totalWidth, weights;
      this.viewable = {
        width: jQuery(window).width(),
        height: jQuery(window).height()
      };
      this.viewable.ar = this.viewable.width / this.viewable.height;
      oWidth = this._getOutlineWidth(this.view);
      oHeight = this._getOutlineHeight(this.view);
      this.view.width(this.viewable.width - oWidth);
      this.view.height(this.viewable.height - oHeight);
      image = this.view.find('img').first();
      oImageWidth = this._getOutlineWidth(image);
      oImageHeight = this._getOutlineHeight(image);
      totalWidth = 0;
      weights = [];
      if (this.vm.selectedGroup) {
        ref = this.vm.selectedGroup.items;
        for (l = 0, len = ref.length; l < len; l++) {
          image = ref[l];
          ar = (image.file.width + oImageWidth) / (image.file.height + oImageHeight);
          weights.push(parseInt(ar * 100));
          totalWidth += ar * this.view.height();
        }
        this.imgAr = totalWidth / this.view.height();
        rows = Math.ceil(this.imgAr / this.viewable.ar);
        images = this.view.find('img');
        console.log(images);
        images.height(Math.ceil((this.view.height()) / rows) - oImageHeight);
        console.log("Total width: " + totalWidth + " (of images)");
        console.log("View width: " + (this.view.width()));
        console.log(weights);
        console.log("Rows: " + rows);
        partitions = this.linearPartition(weights, rows);
        index = 0;
        for (p = 0, len1 = partitions.length; p < len1; p++) {
          part = partitions[p];
          index += part.length;
          console.log(index);
          jQuery(images[index - 1]).parent().after('<div />');
        }
        return this.selectThumbs();
      }
    };

    Presenter.prototype.selectThumbs = function() {
      var left, leftClip, offset, width;
      console.log('selectThumbs');
      this.vm.selectedGroupIndex;
      this.thumbs.removeClass('maslosoft-gallery-controls-thumbs-active');
      this.active = jQuery(this.thumbs.find('a')[this.vm.selectedGroupIndex]);
      if (!this.active) {
        return;
      }
      console.log(this.active);
      this.active.addClass('maslosoft-gallery-controls-thumbs-active');
      offset = this.active.offset();
      width = this.active.width();
      console.log(offset);
      console.log("Left position: " + (this.active.position().left));
      console.log("Right thumbs offset: " + (this.viewable.width - (this.active.offset().left + this.active.outerWidth())));
      if ((this.viewable.width / 2) < (this.active.position().left + (width / 2))) {
        console.log('Should scroll');
        left = (this.viewable.width / 2) - (width / 2);
        this.thumbsSelector.css({
          left: left,
          width: width
        });
        leftClip = left - offset.left;
        console.log((this.active.position().left) + " - " + leftClip);
        this.thumbs.css({
          left: this.thumbs.offset().left + leftClip
        });
      } else {
        this.thumbs.css({
          left: 0
        });
        offset = this.active.offset();
        width = this.active.width();
        this.thumbsSelector.css({
          left: offset.left,
          width: width
        });
        console.log('Should not scroll');
        console.log(this.viewable.width + " / 2 - (" + (this.active.position().left + (width / 2)) + ")");
      }
      return this.thumbsSelector.animate({
        boxShadow: '0px 0px 1000px 0px white'
      }, 2000, null, (function(_this) {
        return function() {
          return _this.thumbsSelector.animate({
            boxShadow: '0px 0px 0px 0px white'
          }, 2000);
        };
      })(this));
    };

    Presenter.prototype._calculateHeight = function() {
      var ctrl, image, images, img, imgHeight, outersHeight, row, rows, total, view, viewHeight, viewableOutersHeight;
      this._calculate();
      return;
      total = this.overlay.height();
      rows = this.view.find('.maslosoft-gallery-image-row').length;
      console.log(rows);
      image = this.row.find('img').first();
      images = this.row.find('img');
      view = this._getOutlineHeight(this.view);
      row = this._getOutlineHeight(this.row);
      img = this._getOutlineHeight(image);
      ctrl = this.controls.outerHeight();
      outersHeight = (row * rows) + view + (img * rows) + ctrl;
      viewableOutersHeight = row + view + img + ctrl;
      this.viewable.height = total - viewableOutersHeight;
      imgHeight = (total - outersHeight) / rows;
      console.log("Viewable height: " + this.viewable.height);
      viewHeight = total - view;
      console.log("imgHeight: " + imgHeight);
      if (jQuery('.maslosoft-gallery-groups').find('img:visible').length === 1) {
        images.height(this.viewable.height);
        return images.width('auto');
      } else {
        images.height(imgHeight);
        return images.width('auto');
      }
    };

    Presenter.prototype._getOutlineHeight = function(elem) {
      var border, margin, padding;
      padding = parseInt(elem.css('padding-top')) + parseInt(elem.css('padding-bottom'));
      margin = parseInt(elem.css('margin-top')) + parseInt(elem.css('margin-bottom'));
      border = parseInt(elem.css('border-top-width')) + parseInt(elem.css('border-bottom-width'));
      return padding + margin + border;
    };

    Presenter.prototype._getOutlineWidth = function(elem) {
      var border, margin, padding;
      padding = parseInt(elem.css('padding-left')) + parseInt(elem.css('padding-right'));
      margin = parseInt(elem.css('margin-left')) + parseInt(elem.css('margin-right'));
      border = parseInt(elem.css('border-left-width')) + parseInt(elem.css('border-right-width'));
      return padding + margin + border;
    };

    Presenter.prototype.linearPartition = function(seq, k) {
      var ans, i, j, l, m, n, p, q, r, ref, ref1, ref2, ref3, solution, table, x, y;
      n = seq.length;
      if (k <= 0) {
        return [];
      }
      if (k > n) {
        return seq.map(function(x) {
          return [x];
        });
      }
      table = (function() {
        var l, ref, results;
        results = [];
        for (y = l = 0, ref = n; 0 <= ref ? l < ref : l > ref; y = 0 <= ref ? ++l : --l) {
          results.push((function() {
            var p, ref1, results1;
            results1 = [];
            for (x = p = 0, ref1 = k; 0 <= ref1 ? p < ref1 : p > ref1; x = 0 <= ref1 ? ++p : --p) {
              results1.push(0);
            }
            return results1;
          })());
        }
        return results;
      })();
      solution = (function() {
        var l, ref, results;
        results = [];
        for (y = l = 0, ref = n - 1; 0 <= ref ? l < ref : l > ref; y = 0 <= ref ? ++l : --l) {
          results.push((function() {
            var p, ref1, results1;
            results1 = [];
            for (x = p = 0, ref1 = k - 1; 0 <= ref1 ? p < ref1 : p > ref1; x = 0 <= ref1 ? ++p : --p) {
              results1.push(0);
            }
            return results1;
          })());
        }
        return results;
      })();
      for (i = l = 0, ref = n; 0 <= ref ? l < ref : l > ref; i = 0 <= ref ? ++l : --l) {
        table[i][0] = seq[i] + (i ? table[i - 1][0] : 0);
      }
      for (j = p = 0, ref1 = k; 0 <= ref1 ? p < ref1 : p > ref1; j = 0 <= ref1 ? ++p : --p) {
        table[0][j] = seq[0];
      }
      for (i = q = 1, ref2 = n; 1 <= ref2 ? q < ref2 : q > ref2; i = 1 <= ref2 ? ++q : --q) {
        for (j = r = 1, ref3 = k; 1 <= ref3 ? r < ref3 : r > ref3; j = 1 <= ref3 ? ++r : --r) {
          m = _.min((function() {
            var ref4, results, s;
            results = [];
            for (x = s = 0, ref4 = i; 0 <= ref4 ? s < ref4 : s > ref4; x = 0 <= ref4 ? ++s : --s) {
              results.push([_.max([table[x][j - 1], table[i][0] - table[x][0]]), x]);
            }
            return results;
          })(), function(o) {
            return o[0];
          });
          table[i][j] = m[0];
          solution[i - 1][j - 1] = m[1];
        }
      }
      n = n - 1;
      k = k - 2;
      ans = [];
      while (k >= 0) {
        ans = [
          (function() {
            var ref4, ref5, results, s;
            results = [];
            for (i = s = ref4 = solution[n - 1][k] + 1, ref5 = n + 1; ref4 <= ref5 ? s < ref5 : s > ref5; i = ref4 <= ref5 ? ++s : --s) {
              results.push(seq[i]);
            }
            return results;
          })()
        ].concat(ans);
        n = solution[n - 1][k];
        k = k - 1;
      }
      return [
        (function() {
          var ref4, results, s;
          results = [];
          for (i = s = 0, ref4 = n + 1; 0 <= ref4 ? s < ref4 : s > ref4; i = 0 <= ref4 ? ++s : --s) {
            results.push(seq[i]);
          }
          return results;
        })()
      ].concat(ans);
    };

    return Presenter;

  })();

  this.Maslosoft.Gallery.Actions = (function() {
    Actions.prototype.vm = null;

    Actions.prototype.presenter = null;

    Actions.prototype.dm = null;

    function Actions(gallery) {
      this.vm = gallery.vm;
      this.presenter = gallery.presenter;
      this.dm = new Maslosoft.Gallery.DataManager(gallery);
    }

    Actions.prototype.page = function(page) {};

    Actions.prototype.group = function(id) {
      var group;
      if (id == null) {
        id = null;
      }
      if (id) {
        group = this.dm.findGroup(id);
        console.log("Viewing group " + group.id);
        this.vm.selectedGroup = group;
      } else {
        group = this.vm.selectedGroup;
      }
      this.vm.selectedGroupIndex = this.dm.groupIndex(group);
      this.vm.nextGroup = this.dm.nextGroup(group);
      this.vm.prevGroup = this.dm.prevGroup(group);
      return this.presenter.showGroup();
    };

    Actions.prototype.image = function(id) {
      var image;
      image = this.dm.findImage(id);
      console.log("Viewing image " + image.id);
      this.vm.selectedImage = image;
      this.presenter.showImage();
      return this.vm.selectedImage = image;
    };

    Actions.prototype.close = function(e) {
      if (e == null) {
        e = null;
      }
      console.log('Forced close');
      History.pushState(null, null, purl(window.location).attr('path'));
      return this.presenter.hideOverlay();
    };

    return Actions;

  })();

  this.Maslosoft.Gallery.Activities = (function() {
    Activities.prototype.vm = null;

    Activities.prototype.dm = null;

    Activities.prototype.action = null;

    Activities.prototype.presenter = null;

    function Activities(gallery) {
      this.slider = bind(this.slider, this);
      this.vm = gallery.vm;
      this.dm = new Maslosoft.Gallery.DataManager(gallery);
      this.presenter = gallery.presenter;
      this.action = gallery.action;
    }

    Activities.prototype.slide = function(play) {
      this.vm.slide = !this.vm.slide;
      return this.slider();
    };

    Activities.prototype.slider = function() {
      if (this.vm.slide) {
        if (this.vm.nextGroup) {
          this.action.group(this.dm.nextGroup(this.vm.selectedGroup));
          return setTimeout(this.slider, 5000);
        } else {
          this.action.group(this.vm.firstGroup);
          this.vm.slide = false;
          return this.presenter.fadeControls();
        }
      }
    };

    Activities.prototype.fullscreen = function(active) {
      if (active === 'true') {
        this.vm.fullscreen = true;
        return screenfull.request();
      } else {
        this.vm.fullscreen = false;
        return screenfull.exit();
      }
    };

    return Activities;

  })();

  this.Maslosoft.Gallery.DataManager = (function() {
    DataManager.prototype.vm = null;

    function DataManager(gallery) {
      this.vm = gallery.vm;
      this.vm.firstGroup = this.vm.dp.data[0];
      this.vm.lastGroup = this.vm.dp.data[this.vm.dp.data.length - 1];
    }

    DataManager.prototype.findGroup = function(id) {
      var group, l, len, ref;
      if (id.id) {
        id = id.id;
      }
      ref = this.vm.dp.data;
      for (l = 0, len = ref.length; l < len; l++) {
        group = ref[l];
        if (group.id === id) {
          return group;
        }
      }
      return false;
    };

    DataManager.prototype.groupIndex = function(group) {
      return this.vm.dp.data.indexOf(group);
    };

    DataManager.prototype.nextGroup = function(group) {
      var index;
      index = this.vm.dp.data.indexOf(group);
      console.log;
      if (index >= 0 && index < this.vm.dp.data.length - 1) {
        return this.vm.dp.data[index + 1];
      }
      return false;
    };

    DataManager.prototype.prevGroup = function(group) {
      var index;
      index = this.vm.dp.data.indexOf(group);
      if (index > 0 && index - 1 <= this.vm.dp.data.length) {
        return this.vm.dp.data[index - 1];
      }
      return false;
    };

    DataManager.prototype.findImage = function(id) {
      var group, item, l, len, len1, p, ref, ref1;
      if (id.id) {
        id = id.id;
      }
      ref = this.vm.dp.data;
      for (l = 0, len = ref.length; l < len; l++) {
        group = ref[l];
        ref1 = group.items;
        for (p = 0, len1 = ref1.length; p < len1; p++) {
          item = ref1[p];
          if (item.id === id) {
            return item;
          }
        }
      }
      return false;
    };

    return DataManager;

  })();

  this.Maslosoft.Gallery.Grid = (function() {
    Grid.prototype.vm = null;

    function Grid(gallery) {
      this.vm = gallery.vm;
    }

    return Grid;

  })();

  this.Maslosoft.Gallery.GalleryWidget = (function() {
    GalleryWidget.prototype.id = '';

    GalleryWidget.prototype.options = {};

    GalleryWidget.prototype.vm = null;

    GalleryWidget.prototype.presenter = null;

    GalleryWidget.prototype.action = null;

    GalleryWidget.prototype.activity = null;

    function GalleryWidget(id1, vm, options) {
      this.id = id1;
      this.vm = vm != null ? vm : {};
      this.options = options != null ? options : {};
      this.overlayClick = bind(this.overlayClick, this);
      this.urlClick = bind(this.urlClick, this);
      this.hashChange = bind(this.hashChange, this);
      this.historyChange = bind(this.historyChange, this);
      this.presenter = new Maslosoft.Gallery.Presenter(this);
      this.action = new Maslosoft.Gallery.Actions(this);
      this.activity = new Maslosoft.Gallery.Activities(this);
      this.presenter.element.on("click", ".maslosoft-gallery-image-url", this.urlClick);
      this.presenter.overlay.on('click', '.maslosoft-gallery-image-row', this.overlayClick);
      jQuery(window).resize(this.presenter.resize);
      History.Adapter.bind(window, 'statechange', this.historyChange);
      this.historyChange();
    }

    GalleryWidget.prototype.historyChange = function() {
      var name, ref, regex, state, url, value;
      state = History.getState();
      url = purl(state.url);
      console.log("Processing url " + state.url);
      ref = url.param();
      for (name in ref) {
        value = ref[name];
        console.log(name + ": " + value);
        break;
      }
      regex = new RegExp(this.id + "\:");
      console.log(regex);
      if (name && name.match(regex)) {
        name = name.replace(regex, '');
        if (typeof this.action[name] === 'function') {
          console.log("Calling @action." + name + "()");
          this.action[name](value);
          return this;
        }
      }
      return this.action.close();
    };

    GalleryWidget.prototype.hashChange = function(url) {
      var fragment, name, parts, value;
      fragment = purl(url).attr('fragment');
      parts = fragment.split('=');
      name = parts.shift();
      value = parts.shift();
      if (name) {
        if (typeof this.activity[name] === 'function') {
          console.log("Calling @activity." + name + "()");
          this.activity[name](value);
          return this;
        }
      }
      return this;
    };

    GalleryWidget.prototype.urlClick = function(e) {
      var element, id, title;
      e.preventDefault();
      e.stopPropagation();
      element = jQuery(e.currentTarget);
      id = element.data('id');
      title = element.data('title');
      console.log("Clicked link " + e.currentTarget.href);
      History.pushState(null, title, e.currentTarget.href);
      this.hashChange(e.currentTarget.href);
      return false;
    };

    GalleryWidget.prototype.overlayClick = function(e) {
      console.log('Maybe clicked overlay...');
      console.log(e);
      if (jQuery(e.target).is('.maslosoft-gallery-image-row')) {
        console.log('Ok, closing on overlay click');
        History.pushState(null, null, purl(window.location).attr('path'));
        return this.presenter.hideOverlay();
      }
    };

    return GalleryWidget;

  })();

}).call(this);

//# sourceMappingURL=GalleryWidget.js.map
