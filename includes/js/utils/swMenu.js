//
// Requires MooTools
// Author SUTIJA-WEB, http://www.sutija-web.rs
//
/*
 var dsMenuClass=new Class({initialize:function(){this.menuClass='menumeni';this.parentClass='parent';this.parentMenus=$$('li[class~='+this.parentClass+']');this.childUL=$$('li[class~='+this.parentClass+'] ul');this.childUL.each(function(item){$(item).setStyles({'position':'absolute','visibility':'hidden','width':220,'z-index.php':999})})this.parentMenus.each(function(item){this.ulChildren=$(item).getChildren('ul')});this.events()},events:function(){$('meni').addEvent('mouseover',function(event){this.parent=event.target.getParents('li[class~=parent]');try{$(this.childUL[this.parentMenus.indexOf(this.parent[0])]).fade(0.96)}catch(error){}}.bind(this));$('meni').addEvent('mouseout',function(event){this.parent=$(event.target).getParents('li[class~=parent]');try{$(this.childUL[this.parentMenus.indexOf(this.parent[0])]).fade(0)}catch(error){}}.bind(this))}});var dsMenu=new dsMenuClass();
 */
var menus_visina = new Array();
var menuClass = new Class({
    initialize: function () {
        this.menuClass = 'menumeni';
        this.parentClass = 'parent';
        this.parentMenus = $$('#menu ul li[class~=' + this.parentClass + ']');
        this.bottomMenus = $$('#menu ul li ul');
        this.sirina = 0;
        this.i = 0;
        this.podmenu = new Array();
        this.podmenu_visina = new Array();
        this.parentMenus.each(function (item) {
            try {
                $(item).setStyle('overflow', 'hidden').y;
                this.podmenu[this.i] = $(item).getChildren('ul')[0];
                this.podmenu_visina[this.i] = $(item).getChildren('ul').getSize()[0].y;
                if ($(item).getChildren('ul').getSize()[0].x > this.sirina) {
                    this.sirina = $(item).getChildren('ul').getSize()[0].x;
                }
                this.i++;
                $(item).addEvent('mouseenter', function () {
                    var visina = this.podmenu_visina[this.podmenu.indexOf($(item).getChildren('ul')[0])];
                    $(item).getChildren('ul').morph({'height': visina, 'opacity': 0.98});
                }.bind(this));
                $(item).addEvent('mouseleave', function () {
                    $(item).getChildren('ul').morph({'height': 0, 'opacity': 0});
                });
            }
            catch (error) {
            }
        }.bind(this));
        this.parentMenus.each(function (item) {
            $(item).getChildren('ul').setStyles({'width': this.sirina});
        }.bind(this));
    }
});